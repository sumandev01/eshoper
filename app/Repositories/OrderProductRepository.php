<?php

namespace App\Repositories;

use App\Models\OrderProduct;

class OrderProductRepository
{
    public static function storeByRequest($request, $order, $cartItems)
    {
        foreach ($cartItems as $cart) {
            $productId = $cart->product_id;
            $name = $cart->product->name ?? 'Product Not Found';
            $sku = $cart->product->sku;
            $size = $cart->size->name ?? 'N/A';
            $color = $cart->color->name ?? 'N/A';
            $quantity = $cart->quantity;
            $price = $cart->cart_price;

            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'product_name' => $name,
                'product_sku' => $sku,
                'size_name' => $size,
                'color_name' => $color,
                'quantity' => $quantity,
                'price' => $price
            ]);
        }
    }
}
