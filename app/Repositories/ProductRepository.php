<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductDetails;
use App\Models\ProductGallery;
use App\Models\ProductInventory;
use App\Models\ProductTag;
use Illuminate\Support\Facades\DB;

class ProductRepository
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

    public function storeByRequest($request)
    {
        return DB::transaction(function () use ($request) {
            $product = Product::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'sku' => $request->sku,
                'price' => $request->sale_price,
                'buy_price' => $request->buy_price ?? null,
                'discount' => $request->discount ?? 0,
                'stock' => $request->quantity,
                'rating' => $request->rating ?? 0,
                'views' => $request->views ?? 0,
                'status' => $request->status ?? 0,
                'media_id' => $request->media_id,
            ]);

            ProductDetails::create([
                'product_id' => $product->id,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'brand_id' => $request->brand_id,
                'shortDescription' => clean_html($request->short_description),
                'description' => clean_html($request->description),
                'information' => clean_html($request->specifications),
                'meta_title' => $request->meta_title,
                'meta_keyword' => $request->meta_keyword,
                'meta_description' => $request->meta_description,
            ]);

            if ($request->has('product_galleries')) {
                $product->galleries()->sync($request->product_galleries);
            }

            if ($request->has('tag_id')) {
                $product->tags()->sync($request->tag_id);
            }

            if ($request->has('variants') && is_array($request->variants)) {
                $totalStock = 0;
                foreach ($request->variants as $variant) {
                    $useMainPrice = isset($variant['use_main_price']) && $variant['use_main_price'] == '1' ? 1 : 0;
                    $useMainDiscount = isset($variant['use_main_discount']) && $variant['use_main_discount'] == '1' ? 1 : 0;

                    $finalPrice = ($useMainPrice === 1) ? null : ($variant['price'] ?? null);
                    $finalDiscount = ($useMainDiscount === 1) ? null : ($variant['discount'] ?? null);
                    $stock = $variant['stock'] ?? 0;
                    $totalStock += $stock;

                    ProductInventory::create([
                        'product_id' => $product->id,
                        'size_id' => $variant['size_id'] ?? null,
                        'color_id' => $variant['color_id'] ?? null,
                        'price' => $finalPrice,
                        'discount' => $finalDiscount,
                        'use_main_price' => $useMainPrice,
                        'use_main_discount' => $useMainDiscount,
                        'stock' => $stock,
                        'media_id' => $variant['media_id'] ?? null,
                    ]);
                }
                
                // Update main product stock with the total stock from variants
                $product->update(['stock' => $totalStock]);
            }

            return $product;
        });
    }

    public function updateByRequest($request, Product $product)
    {
        return DB::transaction(function () use ($request, $product) {
            $product->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'sku' => $request->sku,
                'price' => $request->sale_price,
                'buy_price' => $request->buy_price ?? 0,
                'discount' => $request->discount ?? 0,
                'stock' => $request->quantity,
                'rating' => $request->rating ?? 0,
                'views' => $request->views ?? 0,
                'status' => $request->status ?? 0,
                'media_id' => $request->media_id,
            ]);

            $productDetails = ProductDetails::where('product_id', $product->id)->first();
            if ($productDetails) {
                $productDetails->update([
                    'category_id' => $request->category_id,
                    'sub_category_id' => $request->sub_category_id,
                    'brand_id' => $request->brand_id,
                    'shortDescription' => clean_html($request->short_description),
                    'description' => clean_html($request->description),
                    'information' => clean_html($request->specifications),
                    'meta_title' => $request->meta_title,
                    'meta_keyword' => $request->meta_keyword,
                    'meta_description' => $request->meta_description,
                ]);
            } else {
                ProductDetails::create([
                    'product_id' => $product->id,
                    'category_id' => $request->category_id,
                    'sub_category_id' => $request->sub_category_id,
                    'brand_id' => $request->brand_id,
                    'shortDescription' => clean_html($request->short_description),
                    'description' => clean_html($request->description),
                    'information' => clean_html($request->specifications),
                    'meta_title' => $request->meta_title,
                    'meta_keyword' => $request->meta_keyword,
                    'meta_description' => $request->meta_description,
                ]);
            }

            // Gallery update logic
            $product->galleries()->sync($request->product_galleries ?? []);

            // Tags update logic
            $product->tags()->sync($request->tag_id ?? []);

            // Variants update logic
            if ($request->has('variants') && is_array($request->variants) && count($request->variants) > 0) {
                $submittedVariantCombos = [];
                $totalStock = 0;
                
                foreach ($request->variants as $variant) {
                    $submittedVariantCombos[] = [
                        'size_id' => $variant['size_id'] ?? null,
                        'color_id' => $variant['color_id'] ?? null,
                    ];
                    
                    $stock = intval($variant['stock'] ?? 0);
                    $totalStock += $stock;
                    
                    $useMainPrice = isset($variant['use_main_price']) && $variant['use_main_price'] == 1 ? 1 : 0;
                    $useMainDiscount = isset($variant['use_main_discount']) && $variant['use_main_discount'] == 1 ? 1 : 0;
                    
                    $finalPrice = $useMainPrice ? $request->sale_price : ($variant['price'] ?? 0);
                    $finalDiscount = $useMainDiscount ? $request->discount : ($variant['discount'] ?? 0);
                    
                    ProductInventory::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'size_id' => $variant['size_id'] ?? null,
                            'color_id' => $variant['color_id'] ?? null,
                        ],
                        [
                            'price' => $finalPrice,
                            'discount' => $finalDiscount,
                            'use_main_price' => $useMainPrice,
                            'use_main_discount' => $useMainDiscount,
                            'stock' => $stock,
                            'media_id' => $variant['media_id'] ?? null,
                        ]
                    );
                }
                
                // Delete missing variants
                $existingInventories = ProductInventory::where('product_id', $product->id)->get();
                foreach ($existingInventories as $existing) {
                    $found = false;
                    foreach ($submittedVariantCombos as $combo) {
                        if ($existing->size_id == $combo['size_id'] && $existing->color_id == $combo['color_id']) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $existing->delete();
                    }
                }
                
                // Update main product stock with the total stock from variants
                $product->update(['stock' => $totalStock]);
            } else {
                // If no variants submitted, delete all variants for this product
                ProductInventory::where('product_id', $product->id)->delete();
                // Ensure stock matches the manual quantity
                $product->update(['stock' => $request->quantity]);
            }

            return $product;
        });
    }

    public function getOutOfStockProducts()
    {
        return Product::with(['media', 'inventories' => function ($query) {
            $query->where('stock', 0)->with(['color', 'size', 'media']);
        }])
        ->where(function ($query) {
            $query->where('stock', 0)
                ->orWhereHas('inventories', function ($subQuery) {
                    $subQuery->where('stock', 0);
                });
        })
        ->get();
    }
}