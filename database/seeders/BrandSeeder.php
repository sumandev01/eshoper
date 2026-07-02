<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\SeedsDummyImages;

class BrandSeeder extends Seeder
{
    use SeedsDummyImages;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Adidas',
                'slug' => 'adidas'
            ],
            [
                'name' => 'Nike',
                'slug' => 'nike'
            ],
            [
                'name' => 'Puma',
                'slug' => 'puma'
            ],
            [
                'name' => 'Reebok',
                'slug' => 'reebok'
            ],
            [
                'name' => 'Vans',
                'slug' => 'vans'
            ],
            [
                'name' => 'Converse',
                'slug' => 'converse'
            ],
            [
                'name' => 'New Balance',
                'slug' => 'new-balance'
            ],
            [
                'name' => 'Under Armour',
                'slug' => 'under-armour'
            ],
            [
                'name' => 'Asics',
                'slug' => 'asics'
            ],
            [
                'name' => 'Fila',
                'slug' => 'fila'
            ],
        ];

        foreach ($data as $brand) {
            $mediaId = $this->seedImage(200, 200, 'image', 'brand', 4);
            $brand['media_id'] = $mediaId;

            Brand::updateOrCreate(
                ['name' => $brand['name']],
                $brand
            );
        }
    }
}
