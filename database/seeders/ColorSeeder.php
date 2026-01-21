<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Black',
                'color_code' => '#000000'
            ],
            [
                'name' => 'White',
                'color_code' => '#FFFFFF'
            ],
            [
                'name' => 'Red',
                'color_code' => '#FF0000'
            ],
            [
                'name' => 'Green',
                'color_code' => '#00FF00'
            ],
            [
                'name' => 'Blue',
                'color_code' => '#0000FF'
            ],
            [
                'name' => 'Yellow',
                'color_code' => '#FFFF00'
            ],
            [
                'name' => 'Orange',
                'color_code' => '#FFA500'
            ],
            [
                'name' => 'Purple',
                'color_code' => '#800080'
            ],
            [
                'name' => 'Pink',
                'color_code' => '#FFC0CB'
            ],
            [
                'name' => 'Brown',
                'color_code' => '#A52A2A'
            ]
        ];
        foreach ($data as $color) {
            Color::updateOrCreate(
                ['name' => $color['name']],
                $color
            );
        }
    }
}
