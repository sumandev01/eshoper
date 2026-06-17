<?php

namespace App\Repositories;

use App\Enums\PaymentStatusEnums;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Setting;
use App\Models\ShippingCost;
use App\Services\CouponService;
use Illuminate\Support\Facades\Http;

class OrderRepository
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function OrderByStore($user, $request)
    {
        $userName = $user->name;
        $userEmail = $user->email;

        $shippingCost = ShippingCost::where('id', $request->shipping_charge)->first();
        if (! $shippingCost) {
            throw new \Exception('Invalid shipping method selected.');
        } else {
            $shippingCostId = $shippingCost->id;
            $shippingLocation = $shippingCost->location;
            $shippingPrice = $shippingCost->price;
        }

        $cartItems = Cart::whereIn('id', $request->cart_ids)
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            throw new \Exception('Your cart is empty or items are invalid.');
        }

        $subTotalPrice = $cartItems->map(function ($item) {
            // Note: $item->cart_price accesses the model's accessor which securely calculates current price
            return $item->cart_price * $item->quantity;
        })->sum();

        if ($request->coupon_code) {
            $couponId = $request->coupon_code;
            $couponResult = $this->couponService->getCouponPrice($couponId, $subTotalPrice);
            $couponDiscount = $couponResult['couponDiscount'];
            $finalDiscountPrice = $couponResult['finalDiscountPrice'];
            $grandTotalPrice = $finalDiscountPrice + $shippingCost->price;
            $couponCode = $couponResult['couponCode'];
        } else {
            $couponCode = null;
            $couponDiscount = 0;
            $grandTotalPrice = $subTotalPrice + $shippingPrice;
        }

        $orderNumber = '#ORD-'.date('Y').date('m').date('d').rand(1000, 9999);
        if (Order::where('order_number', $orderNumber)->exists()) {
            $orderNumber = '#ORD-'.date('Y').date('m').date('d').rand(1000, 9999);
        }

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $user->id,
            'user_name' => $userName,
            'user_email' => $userEmail,
            'sub_total' => $subTotalPrice,
            'coupon_id' => $couponId ?? null,
            'coupon_code' => $couponCode,
            'coupon_discount' => $couponDiscount,
            'shipping_cost_id' => $shippingCostId,
            'shipping_location' => $shippingLocation,
            'shipping_charge' => $shippingPrice,
            'grand_total' => $grandTotalPrice,
            'payment_method' => $request->payment,
            'payment_status' => PaymentStatusEnums::UNPAID->value,
            'order_status' => 'pending',
            'transaction_id' => $request->transaction_id ?? null,
            'note' => $request->note ?? null,
        ]);

        if (! empty($couponCode)) {
            Coupon::where('code', $couponCode)->increment('used_count');
        }

        BillingRepository::storeByRequest($request, $order);

        ShippingRepository::storeByRequest($request, $order);

        OrderProductRepository::storeByRequest($request, $user, $order, $cartItems);

        foreach ($cartItems as $cart) {
            if ($cart->product_inventory_id) {
                $cart->productInventory()->decrement('stock', $cart->quantity);
                $cart->product()->decrement('stock', $cart->quantity);
            } elseif ($cart->product_id) {
                $cart->product()->decrement('stock', $cart->quantity);
            }
        }
        $cartItems->each->delete();

        return $order;
    }

    public function processSSLCommerzPayment($order, $request)
    {
        $isSandbox = config('services.sslcommerz.is_sandbox');
        $baseUrl = $isSandbox ? 'https://sandbox.sslcommerz.com' : 'https://securepay.sslcommerz.com';

        $post_data = [
            'store_id' => config('services.sslcommerz.store_id'),
            'store_passwd' => config('services.sslcommerz.store_password'),
            'total_amount' => $order->grand_total,
            'currency' => 'BDT',
            'tran_id' => $order->order_number,

            // Callbacks URL
            'success_url' => route('ssl.success'),
            'fail_url' => route('ssl.fail'),
            'cancel_url' => route('ssl.cancel'),
            'ipn_url' => route('ssl.ipn'),

            // Customer Information
            'cus_name' => $request->billing_name,
            'cus_email' => $request->billing_email,
            'cus_phone' => $request->billing_mobile,
            'cus_add1' => $request->billing_address,
            'cus_city' => $request->billing_city ?? 'Dhaka',
            'cus_country' => 'Bangladesh',

            // Shipping Information
            'shipping_method' => 'NO',
            'product_name' => 'Ecommerce Order',
            'product_category' => 'General',
            'product_profile' => 'general',
        ];

        // API call to SSLCommerz
        $response = Http::asForm()->post($baseUrl.'/gwprocess/v4/api.php', $post_data);
        $result = $response->json();

        if (isset($result['status']) && $result['status'] == 'SUCCESS') {
            return redirect($result['GatewayPageURL']);
        }

        throw new \Exception('Payment initialization failed! Check Credentials.');
    }

    public function processStripePayment($order, $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $siteSettings = Setting::where('key_name', 'currency_code')->first();
            $rawCurrencyCode = $siteSettings ? strtolower(trim($siteSettings->key_value)) : 'bdt';

            if ($rawCurrencyCode === 'bd' || $rawCurrencyCode === 'bdt') {
                $stripeCurrency = 'bdt';
            } elseif ($rawCurrencyCode === 'usd') {
                $stripeCurrency = 'usd';
            } else {
                $stripeCurrency = 'bdt';
            }

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $stripeCurrency,
                        'product_data' => [
                            'name' => 'Order Number: '.$order->order_number,
                            'description' => 'Payment for order at '.config('app.name'),
                        ],
                        'unit_amount' => round($order->grand_total * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                // পেমেন্ট সফল হলে Stripe এই URL এ session_id এবং order_number ফেরত পাঠাবে
                'success_url' => route('stripe.success').'?session_id={CHECKOUT_SESSION_ID}&order_number='.urlencode($order->order_number),
                'cancel_url' => route('stripe.cancel').'?order_number='.urlencode($order->order_number),
            ]);

            return redirect()->away($session->url);

        } catch (\Exception $e) {
            throw new \Exception('Stripe Error: '.$e->getMessage());
        }
    }
}
