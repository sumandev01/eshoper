<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductInventory;

class CartRepository
{
    /**
     * Fetch all data.
     */
    public function all()
    {
        // Logic goes here
    }

    /**
     * Find data by ID.
     */
    public function find($id)
    {
        // Logic goes here
    }

    public function checkInventory($request)
    {
        $hasInventory = ProductInventory::where('product_id', $request->productId)->exists();

        if ($hasInventory) {
            if (!$request->sizeId || !$request->colorId) {
                return false;
            }

            $inventory = ProductInventory::where('product_id', $request->productId)
                ->where('size_id', $request->sizeId)
                ->where('color_id', $request->colorId)
                ->first();

            return $inventory ? $inventory->id : false;
        }

        $productExists = Product::where('id', $request->productId)->exists();

        if ($productExists) {
            return null;
        }

        return false;
    }

    public function storeByRequest($request, $inventoryId, $userId)
    {
        $pId = $request->productId ?? $request->product_id;
        $qty = $request->quantity ?? 1;
        $sId = $request->sizeId ?? $request->size_id;
        $cId = $request->colorId ?? $request->color_id;

        if ($inventoryId) {
            $inventory = ProductInventory::with('product')->findOrFail($inventoryId);
            $availableStock = $inventory->stock;
            
            // Server-side authoritative price calculation
            $basePrice = ($inventory->use_main_price == 1 || $inventory->price === null) ? (float)$inventory->product->price : (float)$inventory->price;
            $discountPrice = ($inventory->use_main_discount == 1) ? (float)($inventory->product->discount ?? 0) : (float)($inventory->discount ?? 0);
            $price = ($discountPrice > 0 && $discountPrice < $basePrice) ? $discountPrice : $basePrice;
        } else {
            $product = Product::findOrFail($pId);
            $availableStock = $product->stock;
            
            // Server-side authoritative price calculation
            $price = ($product->discount > 0 && $product->discount < $product->price) ? (float)$product->discount : (float)$product->price;
        }

        if ($availableStock <= 0) {
            return ['status' => 'error', 'message' => 'Out of stock!'];
        }

        $cartItem = Cart::where([
            'user_id' => $userId,
            'product_id' => $pId,
            'product_inventory_id' => $inventoryId,
            'size_id' => $sId,
            'color_id' => $cId
        ])->first();

        $currentInCart = $cartItem ? $cartItem->quantity : 0;
        $totalNeeded = $currentInCart + $qty;

        if ($totalNeeded > $availableStock) {
            $finalQty = $availableStock;
            $message = "Only {$availableStock} items available. Updated to max.";
        } else {
            $finalQty = $totalNeeded;
            $message = "Added to cart successfully.";
        }

        if ($cartItem) {
            $cartItem->update(['quantity' => $finalQty]);
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $pId,
                'product_inventory_id' => $inventoryId,
                'size_id' => $sId,
                'color_id' => $cId,
                'cart_price' => $price,
                'quantity' => $finalQty
            ]);
        }

        return [
            'status' => 'success',
            'message' => $message,
            'cartCount' => Cart::where('user_id', $userId)->count()
        ];
    }

    public function updateByRequest($request)
    {
        $cartItem = Cart::where('id', $request->cartId)
            ->where('user_id', auth('web')->id())
            ->firstOrFail();
            
        $cartItem->update(['quantity' => $request->quantity]);
        return $cartItem;
    }
}
