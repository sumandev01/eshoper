<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductInventory;
use App\Services\ProductCartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(ProductCartService $cartService)
    {
        $this->cartService = $cartService;
    }
    public function cart()
    {    
        $carts = Cart::where('user_id', auth('web')->user()->id)->latest('id')->get();
        $subTotalPrice = $carts->map(function ($cartItem) {
            return $cartItem->cart_price * $cartItem->quantity;
        })->sum();
        return view('web.cart', compact('carts', 'subTotalPrice'));
    }

    public function addToCart(Request $request)
    {
        $inventoryId = $this->cartService->checkInventory($request);

        if ($inventoryId === false) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 400);
        }

        $userId = auth('web')->user()->id;

        $cartItem = Cart::where([
            'user_id' => $userId,
            'product_id' => $request->productId,
            'product_inventory_id' => $inventoryId,
            'size_id' => $request->sizeId,
            'color_id' => $request->colorId
        ])->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $request->productId,
                'product_inventory_id' => $inventoryId,
                'size_id' => $request->sizeId,
                'color_id' => $request->colorId,
                'quantity' => $request->quantity
            ]);
        }

        $cartCount = Cart::where('user_id', $userId)->count();

        return response()->json([
            'status' => 'success',
            'message' => 'Add to cart',
            'cartCount' => $cartCount
        ]);
    }

    public function checkout()
    {
        return view('web.checkout');
    }
}
