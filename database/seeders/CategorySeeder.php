<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\SeedsDummyImages;

class CategorySeeder extends Seeder
{
    use SeedsDummyImages;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Men\'s Fashion',
                'slug' => 'mens-fashion'
            ],
            [
                'name' => 'Women\'s Fashion',
                'slug' => 'womens-fashion'
            ],
            [
                'name' => 'Kids Fashion',
                'slug' => 'kids-fashion'
            ],
            [
                'name' => 'Footwear',
                'slug' => 'footwear'
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories'
            ],
            [
                'name' => 'Watches',
                'slug' => 'watches'
            ],
            [
                'name' => 'Bags & Purses',
                'slug' => 'bags-purses'
            ],
            [
                'name' => 'Jewelry',
                'slug' => 'jewelry'
            ],
            [
                'name' => 'Winterwear',
                'slug' => 'winterwear'
            ],
            [
                'name' => 'Summer Collection',
                'slug' => 'summer-collection'
            ],
            [
                'name' => 'Sportswear',
                'slug' => 'sportswear'
            ],
            [
                'name' => 'Sleepwear',
                'slug' => 'sleepwear'
            ]
        ];
        
        foreach ($data as $category) {
            $mediaId = $this->seedImage(300, 300, 'image', 'category', 3);
            $category['media_id'] = $mediaId;
            
            Category::UpdateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
