<?php

namespace App\Repositories;

use App\Enums\PaymentStatusEnums;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\ShippingCost;
use App\Services\CouponService;

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
        $shippingCost = ShippingCost::where('price', $request->shipping_charge)->first();
        if (! $shippingCost) {
            return redirect()->back()->with('error', 'Shipping cost not found');
        } else {
            $shippingCostId = $shippingCost->id;
            $shippingLocation = $shippingCost->location;
            $shippingPrice = $shippingCost->price;
        }

        $cartItems = Cart::whereIn('id', $request->cart_ids)->get();
        $subTotalPrice = $cartItems->map(function ($item) {
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
}
