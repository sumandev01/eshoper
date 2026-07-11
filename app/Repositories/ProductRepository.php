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
                'buy_price' => $request->buy_price ?? 0,
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

            return $product;
        });
    }

    public function getOutOfStockProducts()
    {
        return Product::with(['inventories' => function ($query) {
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