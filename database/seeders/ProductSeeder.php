<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Size;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Media;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use App\Models\ProductDetails;
use App\Models\ProductInventory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating 3 sample products with details, galleries, tags, and inventory
        for ($i = 1; $i <= 3; $i++) {
            
            $name = "Premium Product " . $i;

            // 1. Create product
            $product = Product::create([
                'name'     => $name,
                'slug'     => Str::slug($name) . '-' . time(),
                'sku'      => 'SKU-' . strtoupper(Str::random(6)),
                'price'    => 1200.50,
                'buy_price'=> 800.00,
                'discount' => 10,
                'tax'      => 5,
                'stock'    => 100,
                'status'   => 1, // active
                'media_id' => Media::first()?->id, // first media file
            ]);

            // 2. Create product details
            ProductDetails::create([
                'product_id'        => $product->id,
                'category_id'       => Category::first()?->id,
                'sub_category_id'   => SubCategory::first()?->id,
                'brand_id'          => Brand::first()?->id,
                'shortDescription'  => 'This is a short description for ' . $name,
                'description'       => '<p>This is a <b>long description</b> for ' . $name . '</p>',
                'information'       => 'Additional product information goes here.',
                'meta_title'        => $name . ' SEO Title',
                'meta_description'  => 'Meta description for ' . $name,
            ]);

            // 3. Create product galleries
            

            // 4. Product Tags (Many-to-Many)
            $tags = Tag::limit(2)->get();
            $product->tags()->attach($tags); // This will insert data into the product_tags table

            // 5. Product Inventory (Size and Color Variants)
            $size = Size::first();
            $color = Color::first();
            
            if ($size && $color) {
                ProductInventory::create([
                    'product_id' => $product->id,
                    'size_id'    => $size->id,
                    'color_id'   => $color->id,
                    'media_id'   => Media::first()?->id,
                    'price'      => 1150.00,
                    'stock'      => 50,
                ]);
            }
        }
    }
}