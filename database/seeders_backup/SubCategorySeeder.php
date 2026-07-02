<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        set_time_limit(300);

        $data = [
            'Shoes' => ['Sneakers', 'Formal Shoes', 'Boots', 'Sandals', 'Loafers', 'Sports Shoes'],
            'Jackets' => ['Leather Jackets', 'Denim Jackets', 'Bomber Jackets', 'Puffer Jackets', 'Windbreakers'],
            'Blazers' => ['Formal Blazers', 'Casual Blazers', 'Tuxedos', 'Slim Fit Blazers'],
            'Jumpsuits' => ['Full-Length Jumpsuits', 'Short Jumpsuits', 'Rompers', 'Denim Jumpsuits'],
            'Sportswear' => ['Gym T-shirts', 'Tracksuits', 'Gym Leggings', 'Sports Bras', 'Training Shorts'],
            'Sleepwear' => ['Pajama Sets', 'Nightgowns', 'Robes', 'Sleep Shirts'],
            'Swimwear' => ['Bikinis', 'One-Piece Suits', 'Swim Trunks', 'Board Shorts'],
            'Jeans' => ['Skinny Jeans', 'Slim Fit Jeans', 'Bootcut Jeans', 'Relaxed Fit Jeans', 'Mom Jeans', 'Cargo Jeans'],
            'Shirts' => ['Casual Shirts', 'Formal Shirts', 'T-shirts', 'Polo Shirts', 'Flannel Shirts'],
            'Dresses' => ['Maxi Dresses', 'Party Dresses', 'Casual Dresses', 'Evening Gowns', 'Cocktail Dresses'],
        ];

        foreach ($data as $categoryName => $subCategories) {
            $category = Category::updateOrCreate(
                ['name' => $categoryName],
                ['slug' => Str::slug($categoryName)]
            );

            foreach ($subCategories as $subCategoryName) {
                SubCategory::updateOrCreate(
                    ['name' => $subCategoryName],
                    [
                        'category_id' => $category->id,
                        'slug'        => Str::slug($subCategoryName),
                        'media_id'    => null,
                    ]
                );
            }
            
            $this->command->info("Completed seeding for Category: " . $categoryName);
        }
    }
}