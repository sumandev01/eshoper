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
use Faker\Factory as Faker;
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
        $faker = Faker::create();

        // Creating 20 sample products with details, galleries, tags, and inventory
        for ($i = 1; $i <= 20; $i++) {
            
            $name = ucwords($faker->words(rand(2, 4), true));
            $price = $faker->randomElement([500, 800, 1200, 1500, 2000, 2500, 3000, 4500, 5000]);
            
            // 60% chance to have a discount (10% to 30% off)
            $discount = $price;
            if ($faker->boolean(60)) {
                $discountPercentage = $faker->randomElement([10, 15, 20, 25, 30]);
                $discount = $price - ($price * ($discountPercentage / 100));
            }

            // 1. Create product
            $product = Product::create([
                'name'     => $name,
                'slug'     => Str::slug($name) . '-' . time() . rand(10, 99),
                'sku'      => 'SKU-' . strtoupper(Str::random(6)),
                'price'    => $price,
                'buy_price'=> $price * 0.7,
                'discount' => $discount,
                'tax'      => 5,
                'stock'    => $faker->numberBetween(10, 100),
                'status'   => 1, // active
                'media_id' => Media::inRandomOrder()->first()?->id, 
            ]);

            // 2. Create product details
            ProductDetails::create([
                'product_id'        => $product->id,
                'category_id'       => Category::inRandomOrder()->first()?->id,
                'sub_category_id'   => SubCategory::inRandomOrder()->first()?->id,
                'brand_id'          => Brand::inRandomOrder()->first()?->id,
                'shortDescription'  => $faker->text(150),
                'description'       => '<p>' . $faker->paragraphs(3, true) . '</p>',
                'information'       => $faker->text(200),
                'meta_title'        => $name . ' | Premium Quality',
                'meta_description'  => $faker->text(100),
            ]);

            // 3. Product Tags (Many-to-Many)
            $tags = Tag::inRandomOrder()->limit(rand(2, 4))->get();
            $product->tags()->attach($tags);

            // 4. Product Inventory (Size and Color Variants)
            $variantCount = rand(2, 4);
            $sizes = Size::inRandomOrder()->limit($variantCount)->get();
            $colors = Color::inRandomOrder()->limit($variantCount)->get();
            
            for ($v = 0; $v < $variantCount; $v++) {
                $size = $sizes[$v] ?? null;
                $color = $colors[$v] ?? null;
                
                if ($size && $color) {
                    ProductInventory::create([
                        'product_id' => $product->id,
                        'size_id'    => $size->id,
                        'color_id'   => $color->id,
                        'media_id'   => Media::inRandomOrder()->first()?->id,
                        'price'      => $price,
                        'discount'   => $discount,
                        'stock'      => $faker->numberBetween(5, 30),
                        'use_main_price' => 1,
                        'use_main_discount' => 1,
                    ]);
                }
            }
        }
    }
}