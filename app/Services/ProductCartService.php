<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductInventory;

class ProductCartService
{

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
            return 0; // যদি কোনো কারণে ইনভেন্টরি না পাওয়া যায়
        }

        // ১. বেস প্রাইস নির্ধারণ (ভ্যারিয়েন্ট বনাম মেইন)
        $basePrice = ($inventory->use_main_price == 1 || $inventory->price === null)
            ? (float)$inventory->product->price
            : (float)$inventory->price;

        // ২. ডিসকাউন্ট লজিক
        $finalDiscount = 0;
        if ($inventory->use_main_discount == 1) {
            $finalDiscount = (float)($inventory->product->discount ?? 0);
        } else {
            $finalDiscount = (float)($inventory->discount ?? 0);
        }

        // ৩. ফাইনাল সেলিং প্রাইস (যদি ডিসকাউন্ট থাকে তবে সেটা, না হলে বেস প্রাইস)
        $sellingPrice = ($finalDiscount > 0 && $finalDiscount < $basePrice)
            ? $finalDiscount
            : $basePrice;

        return $sellingPrice;
    }
}
