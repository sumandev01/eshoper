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
            ->with('color', 'media')
            ->get();

        $colors = $inventories->map(function ($inventory) {
            $mediaUrl = '';
            if ($inventory->first() && $inventory->first()->media && $inventory->first()->media->src) {
                $mediaUrl = Storage::url($inventory->first()->media->src);
            };
            return [
                'id' => $inventory->color_id,
                'name' => $inventory->color->name ?? 'N/A',
                'image' => $mediaUrl,
                'price' => $inventory->price,
            ];
        })->unique('id')->values();

        return $colors;
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
            ->where('stock', '>', 0)
            ->pluck('color_id') // Color IDs of available colors
            ->toArray();

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
        $variant = ProductInventory::where('product_id', $request->product_id)
            ->where('size_id', $request->size_id)
            ->where('color_id', $request->color_id)
            ->first();

        $mediaUrl = '';
        if ($variant && $variant->media && $variant->media->src) {
            $mediaUrl = Storage::url($variant->media->src);
        }

        if ($variant) {
            return response()->json([
                'stock' => $variant->stock,
                'price' => $variant->price,
                'image' => $mediaUrl,
                'inventory_id' => $variant->id
            ]);
        }
        return response()->json([
            'stock' => 0,
            'price' => 0,
            'image' => '',
            'inventory_id' => ''
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
