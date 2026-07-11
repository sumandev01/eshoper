<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\ShippingCost;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $couponId = $request->couponId;
        $coupon = Coupon::find($couponId);

        $userId = auth('web')->id();

        $cartItems = auth('web')->user()->cartItems;

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is completely empty! Please add some products to checkout.');
        }

        $validItems = $cartItems->filter(function ($item) {
            if ($item->product_inventory_id) {
                $inventory = ProductInventory::find($item->product_inventory_id);
                return $inventory && $inventory->stock > 0;
            }

            $product = Product::find($item->product_id);
            return $product && $product->stock > 0;
        });

        if ($validItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'The items in your cart are currently out of stock or invalid.');
        }

        $subTotalPrice = $validItems->map(function ($item) {
            return $item->cart_price * $item->quantity;
        })->sum();

        $couponDiscount = 0;
        if ($coupon?->type == 'fixed') {
            $couponDiscount = $coupon?->amount;
        } elseif ($coupon?->type == 'percentage') {
            $couponDiscount = ($subTotalPrice * $coupon?->amount) / 100;
        }

        $totalPrice = $subTotalPrice - $couponDiscount;

        $billingAddress = UserAddress::where('user_id', $userId)->where('type', 'billing')->first();
        $shippingAddress = UserAddress::where('user_id', $userId)->where('type', 'shipping')->first();
        $countries = \App\Models\Country::all();

        $shippingCost = \App\Models\ShippingCost::orderBy('id', 'asc')->get();

        return view('web.shop.checkout', compact('coupon', 'userId', 'validItems', 'subTotalPrice', 'couponDiscount', 'totalPrice', 'billingAddress', 'shippingAddress', 'countries', 'shippingCost'));
    }
}
