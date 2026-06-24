<?php

namespace App\Repositories;

use App\Enums\OrderStatusEnums;
use App\Enums\PaymentStatusEnums;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\Setting;
use App\Models\ShippingCost;
use App\Services\CouponService;
use Illuminate\Support\Facades\DB;
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
            'order_status' => OrderStatusEnums::PENDING->value,
            'transaction_id' => $request->transaction_id ?? null,
            'note' => $request->note ?? null,
        ]);

        BillingRepository::storeByRequest($request, $order);

        ShippingRepository::storeByRequest($request, $order);

        OrderProductRepository::storeByRequest($request, $user, $order, $cartItems);

        return $order;
    }

    public function finalizeOrder($order, $transactionId, $paymentData)
    {
        return DB::transaction(function () use ($order, $transactionId, $paymentData) {
            // Row-level lock to prevent race conditions during Webhook/Success callback concurrency
            $order = Order::where('id', $order->id)->lockForUpdate()->first();

            if ($order->payment_status === PaymentStatusEnums::PAID) {
                return $order;
            }

            // 1. Update Order Status
            $order->update([
                'order_status' => OrderStatusEnums::CONFIRMED->value,
                'payment_status' => PaymentStatusEnums::PAID->value,
                'transaction_id' => $transactionId,
                'payment_data' => is_array($paymentData) ? json_encode($paymentData) : $paymentData,
            ]);

            // 2. Process Stock Deduction
            foreach ($order->orderProducts as $item) {
                // Find specific inventory by matching size and color names
                $inventory = ProductInventory::where('product_id', $item->product_id)
                    ->when($item->size_name, fn($q) => $q->whereHas('size', fn($sq) => $sq->where('name', $item->size_name)))
                    ->when($item->color_name, fn($q) => $q->whereHas('color', fn($cq) => $cq->where('name', $item->color_name)))
                    ->first();

                if ($inventory) {
                    $inventory->decrement('stock', $item->quantity);
                }
                Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
            }

            // 3. Increment Coupon usage
            if ($order->coupon_code) {
                Coupon::where('code', $order->coupon_code)->increment('used_count');
            }

            // 4. Clear Cart for this order's products
            Cart::where('user_id', $order->user_id)
                ->whereIn('product_id', $order->orderProducts->pluck('product_id'))
                ->delete();

            return $order;
        });
    }

    public function failOrder($order)
    {
        $orderId = Order::findOrFail($order->id);
        // if($orderId){
        //     $orderId->delete();
        // }
        if ($order->order_status === OrderStatusEnums::PENDING) {
            $order->update([
                'order_status' => OrderStatusEnums::FAILED->value,
                'payment_status' => PaymentStatusEnums::FAILED->value,
            ]);
        }
    }

    public function processSSLCommerzPayment($order, $request)
    {
        $isSandbox = config('services.sslcommerz.is_sandbox');
        $baseUrl = $isSandbox ? 'https://sandbox.sslcommerz.com' : 'https://securepay.sslcommerz.com';

        $siteSettings = Setting::where('key_name', 'currency_code')->first();
        $rawCurrencyCode = $siteSettings ? strtolower(trim($siteSettings->key_value)) : 'bdt';

        if ($rawCurrencyCode === 'bd' || $rawCurrencyCode === 'bdt') {
            $sslCurrency = 'BDT';
        } else {
            $sslCurrency = strtoupper($rawCurrencyCode);
        }

        $post_data = [
            'store_id' => config('services.sslcommerz.store_id'),
            'store_passwd' => config('services.sslcommerz.store_password'),
            'total_amount' => $order->grand_total,
            'currency' => $sslCurrency,
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
                'success_url' => route('stripe.success').'?session_id={CHECKOUT_SESSION_ID}&order_number='.urlencode($order->order_number),
                'cancel_url' => route('stripe.cancel').'?order_number='.urlencode($order->order_number),
            ]);

            return redirect()->away($session->url);

        } catch (\Exception $e) {
            throw new \Exception('Stripe Error: '.$e->getMessage());
        }
    }
}
