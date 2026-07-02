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
        $faker = Faker::create('en_US');
        
        $products = Product::all();
        $users = User::all();

        $realisticReviews = [
            "Absolutely love this product! The quality is amazing and it fits perfectly.",
            "Great value for the price. I've been using it every day since it arrived.",
            "Fast shipping and the product looks exactly like the pictures. Highly recommended!",
            "It's good, but the sizing runs a bit small. Still, very comfortable.",
            "One of the best purchases I've made this year. The material feels premium.",
            "Exceeded my expectations. Five stars all the way!",
            "Nice design and very durable. I'll definitely buy more from this brand.",
            "Decent product. Does what it says, but nothing extraordinary.",
            "I get compliments every time I wear this! So stylish and chic.",
            "Customer service was great and the product itself is fantastic.",
            "I was hesitant at first, but I'm so glad I bought this.",
            "Perfect fit and excellent quality. Will buy another one in a different color."
        ];

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
                        'review_text'=> $faker->randomElement($realisticReviews),
                        'status'     => 1, // Approved
                    ]);
                }
            }
        }
    }
}
