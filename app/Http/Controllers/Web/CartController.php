<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\ProductReview;
use App\Repositories\CartRepository;
use App\Services\CouponService;
use Illuminate\Http\Request;

class CartController extends Controller
{

    protected $cartRepository;
    protected $couponService;


    public function __construct(CartRepository $cartRepository, CouponService $couponService)
    {
        $this->cartRepository = $cartRepository;
        $this->couponService = $couponService;
    }

    public function cart(\App\Services\RecentlyViewedService $recentlyViewedService)
    {
        $userId = auth('web')->user()->id;
        $carts = Cart::whereUserId($userId)->with(['product' => function ($query) {
            $query->withAvg('reviews', 'rating')->withCount('reviews');
        }])->latest('id')->get();
        $subTotalPrice = $carts->map(function ($cartItem) {
            return $cartItem->cart_price * $cartItem->quantity;
        })->sum();
        
        $cartProductIds = $carts->pluck('product_id')->toArray();
        $recentProducts = $recentlyViewedService->get($cartProductIds);
        
        return view('web.shop.cart', compact('carts', 'subTotalPrice', 'recentProducts'));
    }
    
    public function minicart()
    {
        $user = auth('web')->user();
        $cartProducts = $user ? $user->cartItems : collect();
        
        return view('web.layouts.headers.header_1_partials.minicart_dropdown', compact('cartProducts'))->render();
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
        $this->authorize('delete', $cart);
        $cart->delete();

        return redirect()->route('cart')->with('success', 'Product removed from cart successfully.');
    }

    public function applyCoupon(\App\Http\Requests\ApplyCouponRequest $request)
    {
        $couponCode = $request->couponCode;
        
        $userId = auth('web')->id();
        $carts = Cart::whereUserId($userId)->get();
        $cartSubTotal = $carts->map(function ($cartItem) {
            return $cartItem->cart_price * $cartItem->quantity;
        })->sum();

        $couponResult = $this->couponService->getAjaxCouponPrice($couponCode, $cartSubTotal);

        if ($couponResult['status'] === 'error') {
            return response()->json($couponResult);
        }

        return response()->json([
            'status' => 'success',
            'message' => $couponResult['message'],
            'discountPrice' => $couponResult['finalDiscountPrice'],
            'couponId' => $couponResult['couponId'],
        ]);
    }
}
