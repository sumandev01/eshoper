<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductInventory;
use Illuminate\Support\Facades\Storage;

class ProductWebService
{
    /**
     * Get colors by size.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function getColorsBySize($request)
    {
        $inventories = ProductInventory::where('product_id', $request->productId)
            ->where('size_id', $request->sizeId)
            ->with(['color', 'media', 'product'])
            ->get();

        return $inventories->map(function ($inventory) {
            $mediaUrl = $inventory->media && $inventory->media->src ? Storage::url($inventory->media->src) : '';

            // ১. প্রাইস সেট করা (ভ্যারিয়েন্ট না থাকলে মেইন প্রাইস)
            $basePrice = ($inventory->use_main_price == 1 || $inventory->price === null)
                ? (float)$inventory->product->price
                : (float)$inventory->price;

            // ২. ডিসকাউন্ট লজিক (আপনার রিকোয়ারমেন্ট অনুযায়ী)
            $finalDiscount = 0;
            if ($inventory->use_main_discount == 1) {
                // মেইন প্রোডাক্টের ডিসকাউন্ট নিবে
                $finalDiscount = (float)($inventory->product->discount ?? 0);
            } else {
                // ইনভেন্টরির নিজস্ব ডিসকাউন্ট নিবে (যদি থাকে)
                $finalDiscount = (float)($inventory->discount ?? 0);
            }

            return [
                'id'             => $inventory->color_id,
                'name'           => $inventory->color->name ?? 'N/A',
                'image'          => $mediaUrl,
                'price'          => $basePrice,
                'discount_price' => $finalDiscount, // এটি ০ হতে পারে যদি ভ্যালু না থাকে
                'stock'          => $inventory->stock,
            ];
        })->unique('id')->values();
    }



    /**
     * Get available colors for a single product based on the selected size.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function singleProductGetColorBySize($request)
    {
        $availableColors = ProductInventory::query()
            ->where('product_id', $request->product_id)
            ->where('size_id', $request->size_id)
            ->with('color')
            ->get()
            ->map(function ($inventory) {
                return [
                    'id' => $inventory->color_id,
                    'name' => $inventory->color ? $inventory->color->name : 'Unknown',
                ];
            })
            ->unique('id')
            ->values();

        return response()->json([
            'availableColors' => $availableColors
        ]);
    }

    /**
     * Get variant details by product id, size id, and color id
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function singleProductGetVariantDetails($request)
    {
        $inventory = ProductInventory::with('product')
            ->where('product_id', $request->product_id)
            ->where('size_id', $request->size_id)
            ->where('color_id', $request->color_id)
            ->first();

        if (!$inventory) {
            return response()->json([
                'inventory_id' => null,
                'stock' => 0,
                'price' => null,
                'discount' => null,
                'use_main_price' => 0,
                'use_main_discount' => 0,
                'product_price' => null,
                'product_discount' => null,
                'image' => null,
            ]);
        }

        $product = $inventory->product;

        return response()->json([
            'inventory_id' => $inventory->id,
            'stock' => $inventory->stock,
            'price' => $inventory->price,
            'discount' => $inventory->discount,
            'use_main_price' => $inventory->use_main_price,
            'use_main_discount' => $inventory->use_main_discount,
            'product_price' => $product->price,
            'product_discount' => $product->discount,
            'image' => $inventory->image ?? null,
        ]);
    }


    /**
     * Get related products by product id
     * 
     * @param Product $product
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getRelatedProductsSinglePage($product, $limit = 8)
    {
        $categoryId = $product->details->category_id ?? null;

        $query = Product::where('id', '!=', $product->id)
            ->where('status', 1);

        $related = $query->when($categoryId, function ($q) use ($categoryId) {
            return $q->whereHas('details', function ($detailsQuery) use ($categoryId) {
                $detailsQuery->where('category_id', $categoryId);
            });
        })
            ->inRandomOrder()
            ->limit($limit)
            ->get();

        // if no related products found, get random products
        if ($related->isEmpty()) {
            $related = Product::where('id', '!=', $product->id)
                ->where('status', 1)
                ->inRandomOrder()
                ->limit($limit)
                ->get();
        }

        return $related;
    }
}
