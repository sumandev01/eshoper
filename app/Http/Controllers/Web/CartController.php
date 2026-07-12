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
        $carts = Cart::whereUserId($userId)->latest('id')->get();
        $subTotalPrice = $carts->map(function ($cartItem) {
            return $cartItem->cart_price * $cartItem->quantity;
        })->sum();
        
        $cartProductIds = $carts->pluck('product_id')->toArray();
        $recentProducts = $recentlyViewedService->get($cartProductIds);
        
        $productReviews = ProductReview::whereStatus(1)->get();
        return view('web.shop.cart', compact('carts', 'subTotalPrice', 'productReviews', 'recentProducts'));
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
        $cartItem = Cart::findOrFail($cart->id);
        $cartItem->delete();

        return redirect()->route('cart')->with('success', 'Product removed from cart successfully.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'cartSubTotal' => 'required|numeric|min:0',
            'couponCode' => 'required|string',
        ]);

        $couponCode = $request->couponCode;
        $cartSubTotal = $request->cartSubTotal;

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
