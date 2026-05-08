<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebOrderRequest;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\ShippingCost;
use App\Services\CouponService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $couponService;
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }
    public function index()
    {
        // return view('web.orders.index');
    }

    public function store(WebOrderRequest $request)
    {
        $user = auth('web')->user();
        $userName = $user->name;
        $userEmail = $user->email;

        $shippingCost = ShippingCost::where('price', $request->shipping_charge)->first();
        if (!$shippingCost) {
            return redirect()->back()->with('error', 'Shipping cost not found');
        } else {
            $shippingCostId = $shippingCost->id;
            $shippingLocation = $shippingCost->location;
            $shippingPrice = $shippingCost->price;
        }

        dd($shippingCostId, $shippingLocation, $shippingPrice);

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
            $finalDiscountPrice = $subTotalPrice;
            $grandTotalPrice = $subTotalPrice + $shippingPrice;
        }

        dd($userName, $userEmail, $shippingPrice, $subTotalPrice, $couponDiscount, $finalDiscountPrice, $grandTotalPrice, $couponCode, $couponId);

        foreach ($cartItems as $cart) {
            $productId = $cart->product_id;
            $name = $cart->product->name;
            $sku = $cart->product->sku;
            $size = $cart->size->name;
            $color = $cart->color->name;
            $quantity = $cart->quantity;
            $price = $cart->cart_price;

            // dd($productId, $name, $sku, $size, $color, $quantity, $price);
        }

        dd($request->all());
    }
}
