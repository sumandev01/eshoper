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
            //Shoes, Jackets, Blazers, Jumpsuits, Sportswear, Sleepwear, Swimwear, 	Jeans, Shirts, 	Dresses - categories
            [
                'name' => 'Shoes',
                'slug' => 'shoes'
            ],
            [
                'name' => 'Jackets',
                'slug' => 'jackets'
            ],
            [
                'name' => 'Blazers',
                'slug' => 'blazers'
            ],
            [
                'name' => 'Jumpsuits',
                'slug' => 'jumpsuits'
            ],
            [
                'name' => 'Sportswear',
                'slug' => 'sportswear'
            ],
            [
                'name' => 'Sleepwear',
                'slug' => 'sleepwear'
            ],
            [
                'name' => 'Swimwear',
                'slug' => 'swimwear'
            ],
            [
                'name' => 'Jeans',
                'slug' => 'jeans'
            ],
            [
                'name' => 'Shirts',
                'slug' => 'shirts'
            ],
            [
                'name' => 'Dresses',
                'slug' => 'dresses'
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
