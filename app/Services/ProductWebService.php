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
            $mediaUrl = $inventory->media ? $inventory->media->medium_url : '';

            $basePrice = ($inventory->use_main_price == 1 || $inventory->price === null)
                ? (float)$inventory->product->price
                : (float)$inventory->price;

            $finalDiscount = 0;
            if ($inventory->use_main_discount == 1) {
                $finalDiscount = (float)($inventory->product->discount ?? 0);
            } else {
                $finalDiscount = (float)($inventory->discount ?? 0);
            }

            return [
                'id'             => $inventory->color_id,
                'name'           => $inventory->color->name ?? 'N/A',
                'image'          => $mediaUrl,
                'price'          => $basePrice,
                'discount_price' => $finalDiscount,
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
            'image' => $inventory->media ? Storage::url($inventory->media->src) : null,
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

        $related = collect();

        if ($categoryId) {
            $related = Product::active()
                ->withListingDefaults()
                ->where('id', '!=', $product->id)
                ->whereHas('details', function ($detailsQuery) use ($categoryId) {
                    $detailsQuery->where('category_id', $categoryId);
                })
                ->inRandomOrder()
                ->limit($limit)
                ->get();
        }

        if ($related->count() < $limit) {
            $remaining = $limit - $related->count();

            $excludedIds = $related->pluck('id')->push($product->id);

            $randomProducts = Product::active()
                ->withListingDefaults()
                ->whereNotIn('id', $excludedIds)
                ->inRandomOrder()
                ->limit($remaining)
                ->get();

            $related = $related->merge($randomProducts);
        }

        return $related;
    }

    public function getTrendingProducts()
    {
        $trendyProducts = Product::active()
            ->withListingDefaults()
            ->where('is_trending', 1)
            ->take(8)
            ->get();

        $remaining = 8 - $trendyProducts->count();

        if ($remaining > 0) {
            $trendyIds = $trendyProducts->pluck('id');
            $moreTrendy = Product::active()
                ->withListingDefaults()
                ->whereNotIn('id', $trendyIds)
                ->inRandomOrder()
                ->take($remaining)
                ->get();

            $trendyProducts = $trendyProducts->merge($moreTrendy);
        }

        return $trendyProducts;
    }
}
