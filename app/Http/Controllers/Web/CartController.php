<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Repositories\CartRepository;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    protected $cartRepository;


    public function __construct(CartRepository $cartService, CartRepository $cartRepository)
    {
        $this->cartService = $cartService;
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
        $inventoryId = $this->cartService->checkInventory($request);
        
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

    public function checkout()
    {
        return view('web.checkout');
    }

    public function removeFromCart(Cart $cart)
    {
        $cartItem = Cart::find($cart->id);
        $cartItem->delete();

        return redirect()->route('cart')->with('success', 'Product removed from cart successfully.');
    }

    public function applyCoupon(Request $request)
    {
        dd($request);
        $userId = auth('web')->id();
        $result = $this->cartService->applyCoupon($request, $userId);
        return response()->json($result);
    }
}
