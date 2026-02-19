<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductInventory;

class ProductCartService
{
    /**
     * Product, Size এবং Color ID দিয়ে চূড়ান্ত বিক্রয়মূল্য বের করার ফাংশন
     */
    public function getResolvedPrice($request)
    {
        $inventory = ProductInventory::where('product_id', $request->productId)
            ->where('size_id', $request->sizeId)
            ->where('color_id', $request->colorId)
            ->with('product')
            ->first();

        if (!$inventory) {
            return 0;
        }

        $basePrice = ($inventory->use_main_price == 1 || $inventory->price === null)
            ? (float)$inventory->product->price
            : (float)$inventory->price;

        $finalDiscount = 0;
        if ($inventory->use_main_discount == 1) {
            $finalDiscount = (float)($inventory->product->discount ?? 0);
        } else {
            $finalDiscount = (float)($inventory->discount ?? 0);
        }

        $sellingPrice = ($finalDiscount > 0 && $finalDiscount < $basePrice)
            ? $finalDiscount
            : $basePrice;

        return $sellingPrice;
    }
}
