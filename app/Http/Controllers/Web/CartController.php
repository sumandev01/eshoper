<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Repositories\CartRepository;
use Illuminate\Http\Request;

class CartController extends Controller
{

    protected $cartRepository;


    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function cart()
    {
        $userId = auth('web')->user()->id;
        $carts = Cart::where('user_id', $userId)->latest('id')->get();
        $subTotalPrice = $carts->map(function ($cartItem) {
            return $cartItem->cart_price * $cartItem->quantity;
        })->sum();
        return view('web.cart', compact('carts', 'subTotalPrice'));
    }
    
    public function addToCart(Request $request)
    {
        $inventoryId = $this->cartRepository->checkInventory($request);
        
        if ($inventoryId === false) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 400);
        }

        $userId = auth('web')->id();

        $result = $this->cartRepository->storeByRequest($request, $inventoryId, $userId);

        if ($result['status'] === 'error') {
            return response()->json($result, 400);
        }

        return response()->json($result);
    }

    public function updateCart(Request $request)
    {
        $result = $this->cartRepository->updateByRequest($request);

        return response()->json($result);
    }

    public function removeFromCart(Cart $cart)
    {
        $cartItem = Cart::find($cart->id);
        $cartItem->delete();

        return redirect()->route('cart')->with('success', 'Product removed from cart successfully.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'cartSubTotal' => 'required|numeric|min:0',
            'couponCode' => 'required|string',
        ]);

        $discountPrice = 0;

        $couponCode = $request->couponCode;

        $coupon = Coupon::where('code', $couponCode)->where('status', 1)->first();

        if (!$coupon) {
            return response()->json(['status' => 'error', 'message' => 'Coupon not found'], 400);
        }

        $isValid = $coupon->start_date <= now() && $coupon->expire_date >= now();

        if (!$isValid) {
            return response()->json(['status' => 'error', 'message' => 'Coupon is not valid'], 400);
        }

        $hasLimit = ($coupon->usage_limit - $coupon->used_count) > 0;

        if (!$hasLimit) {
            return response()->json(['status' => 'error', 'message' => 'Coupon usage limit exceeded'], 400);
        }

        $minAmount = $coupon->min_order_amount;

        if ($request->cartSubTotal < $minAmount) {
            return response()->json(['status' => 'error', 'message' => 'Minimum order amount not met'], 400);
        }

        $couponDiscount = 0;

        if ($coupon->type == 'fixed') {
            $couponDiscount = $coupon->amount;
        } elseif ($coupon->type == 'percentage') {
            $couponDiscount = ($request->cartSubTotal * $coupon->amount) / 100;
        }

        $discountPrice = $request->cartSubTotal - $couponDiscount;


        return response()->json([
            'status' => 'success',
            'message' => 'Coupon applied successfully',
            'discountPrice' => $discountPrice,
            'couponId' => $coupon->id
        ]);
    }
}
