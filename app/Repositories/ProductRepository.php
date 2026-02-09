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
        $product = Product::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'sku' => $request->sku,
            'price' => $request->sale_price,
            'buy_price' => $request->buy_price ?? 0,
            'discount' => $request->discount ?? 0,
            'tax' => $request->tax ?? 0,
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
            'shortDescription' => $request->short_description,
            'description' => $request->description,
            'information' => $request->specifications,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        if ($request->has('product_galleries')) {
            foreach ($request->product_galleries as $gallery) {
                DB::table('product_galleries')->insert([
                    'product_id' => $product->id,
                    'media_id' => $gallery,
                ]);
            }
        }

        if ($request->has('tag_id')) {
            foreach ($request->tag_id as $tagId) {
                DB::table('product_tags')->insert([
                    'product_id' => $product->id,
                    'tag_id' => $tagId,
                ]);
            }
        }

        return $product;
    }

    public function updateByRequest($request, Product $product)
    {
        $product->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'sku' => $request->sku,
            'price' => $request->sale_price,
            'buy_price' => $request->buy_price ?? 0,
            'discount' => $request->discount ?? 0,
            'tax' => $request->tax ?? 0,
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
                'shortDescription' => $request->short_description,
                'description' => $request->description,
                'information' => $request->specifications,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
            ]);
        }

        // Gallery update logic
        if ($request->filled('product_galleries')) {
            // 1. First, delete all existing gallery records for this product
            DB::table('product_galleries')->where('product_id', $product->id)->delete();

            // 2. Prepare the new IDs into an array for bulk insertion
            $galleryData = [];
            foreach ($request->product_galleries as $mediaId) {
                if (!empty($mediaId)) {
                    $galleryData[] = [
                        'product_id' => $product->id,
                        'media_id'   => $mediaId,
                    ];
                }
            }

            // 3. Perform bulk insert (Done outside the loop for better performance)
            if (!empty($galleryData)) {
                DB::table('product_galleries')->insert($galleryData);
            }
        } else {
            // If no gallery images are present in the request (e.g., user removed all images),
            // then clean up all existing gallery records for this product from the database
            DB::table('product_galleries')->where('product_id', $product->id)->delete();
        }

        // Tags
        if ($request->has('tag_id')) {
            // 1. First, detach all existing tags for this product
            DB::table('product_tags')->where('product_id', $product->id)->delete();

            // 2. Prepare the data for bulk insertion
            $tagData = [];
            foreach ($request->tag_id as $tagId) {
                if (!empty($tagId)) {
                    $tagData[] = [
                        'product_id' => $product->id,
                        'tag_id'     => $tagId,
                    ];
                }
            }

            // 3. Insert all tags at once
            if (!empty($tagData)) {
                DB::table('product_tags')->insert($tagData);
            }
        } else {
            // If no tags in request, remove all existing tags from database
            DB::table('product_tags')->where('product_id', $product->id)->delete();
        }

        return $product;
    }
}