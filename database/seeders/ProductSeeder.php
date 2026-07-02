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
use Database\Seeders\Traits\SeedsDummyImages;

class ProductSeeder extends Seeder
{
    use SeedsDummyImages;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('en_US');

        $productNames = [
            'Premium Cotton Crewneck T-Shirt',
            'Men\'s Classic Vintage Leather Jacket',
            'Women\'s High-Waisted Skinny Jeans',
            'Air Max 270 Lifestyle Sneakers',
            'Ultraboost Performance Running Shoes',
            'Casual Oxford Button-Down Shirt',
            'Elegant Evening Chiffon Maxi Dress',
            'Classic Blue Denim Trucker Jacket',
            'Seamless High-Waist Yoga Leggings',
            'Polarized Classic Aviator Sunglasses',
            'Genuine Leather Crossbody Messenger Bag',
            'Heavyweight Waterproof Winter Parka',
            'Men\'s Slim Fit Stretch Chino Pants',
            'Cozy Oversized Knitted Winter Sweater',
            'Formal Genuine Leather Oxford Shoes',
            'Sporty Fleece Lined Pullover Hoodie',
            'Summer Floral Wrap Sundress',
            'Athletic Moisture-Wicking Performance Shorts',
            'Minimalist Quartz Stainless Steel Watch',
            'Heavy-Duty Canvas Weekend Duffle Bag',
        ];

        // Creating 20 sample products with details, galleries, tags, and inventory
        for ($i = 1; $i <= 20; $i++) {
            
            $name = $productNames[$i - 1] ?? ucwords($faker->words(3, true));
            $price = $faker->randomElement([49.99, 89.99, 129.50, 150.00, 200.00, 25.99, 39.95, 45.00, 59.99]);
            
            // 60% chance to have a discount (10% to 30% off)
            $discount = $price;
            if ($faker->boolean(60)) {
                $discountPercentage = $faker->randomElement([10, 15, 20, 25, 30]);
                $discount = $price - ($price * ($discountPercentage / 100));
            }

            // 1. Create product
            $product = Product::create([
                'name'     => $name,
                'slug'     => Str::slug($name) . '-' . rand(100, 999),
                'sku'      => 'SKU-' . strtoupper(Str::random(6)),
                'price'    => $price,
                'buy_price'=> $price * 0.7,
                'discount' => $discount,
                'tax'      => 5,
                'stock'    => $faker->numberBetween(10, 100),
                'status'   => 1, // active
                'media_id' => $this->seedImage(800, 800, 'image', 'product', 10), 
            ]);

            // 2. Create product details
            ProductDetails::create([
                'product_id'        => $product->id,
                'category_id'       => Category::inRandomOrder()->first()?->id,
                'sub_category_id'   => SubCategory::inRandomOrder()->first()?->id,
                'brand_id'          => Brand::inRandomOrder()->first()?->id,
                'shortDescription'  => 'Experience the perfect blend of style, comfort, and durability with this premium ' . strtolower($name) . '.',
                'description'       => '<p>Upgrade your lifestyle with our <strong>' . $name . '</strong>. Meticulously designed for the modern trendsetter, this product offers unparalleled comfort and exceptional quality.</p><p>Features include premium materials, precise stitching, and a design that never goes out of style. Whether you are dressing up for a special occasion or keeping it casual, this is the perfect addition to your collection.</p><ul><li>High-quality materials</li><li>Durable and long-lasting</li><li>Modern and stylish design</li><li>Easy to clean and maintain</li></ul>',
                'information'       => 'Care Instructions: Machine wash cold with like colors. Tumble dry low. Do not bleach. Material: 100% Premium Quality Fabric.',
                'meta_title'        => $name . ' | Buy Online',
                'meta_description'  => 'Shop the best ' . strtolower($name) . ' online. Enjoy premium quality, fast shipping, and secure checkout.',
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
                        'media_id'   => $this->seedImage(800, 800, 'image', 'product_variant', 5),
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