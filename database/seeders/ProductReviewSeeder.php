<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Faker\Factory as Faker;
use App\Models\ProductReview;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        $products = Product::all();
        $users = User::all();

        if ($products->count() > 0 && $users->count() > 0) {
            foreach ($products as $product) {
                // Generate 2 to 5 reviews for each product
                $reviewCount = rand(2, 5);
                for ($i = 0; $i < $reviewCount; $i++) {
                    $user = $users->random();
                    ProductReview::create([
                        'product_id' => $product->id,
                        'user_id'    => $user->id,
                        'name'       => $user->name,
                        'rating'     => $faker->numberBetween(3, 5), // mostly positive reviews
                        'review_text'=> $faker->paragraph(),
                        'status'     => 1, // Approved
                    ]);
                }
            }
        }
    }
}
