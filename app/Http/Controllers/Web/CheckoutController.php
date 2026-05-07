<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Division;
use App\Models\Product;
use App\Models\ProductInventory;
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

        $validItems = $cartItems->filter(function ($item) {
            if ($item->product_inventory_id) {
                $inventory = ProductInventory::find($item->product_inventory_id);
                return $inventory && $inventory->stock > 0;
            }

            $product = Product::find($item->product_id);
            return $product && $product->stock > 0;
        });

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
        $divisions = Division::all();

        $shippingCost = [
            (object)[
                'id' => 1,
                'location' => 'Inside Dhaka',
                'price' => 60,
            ],
            (object)[
                'id' => 2,
                'location' => 'Outside Dhaka',
                'price' => 100,
            ]
        ];

        return view('web.checkout', compact('coupon', 'userId', 'validItems', 'subTotalPrice', 'couponDiscount', 'totalPrice', 'billingAddress', 'shippingAddress', 'divisions', 'shippingCost'));
    }
}
