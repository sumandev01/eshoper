<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'New'
            ],
            [
                'name' => 'Best Seller'
            ],
            [
                'name' => 'Trending'
            ],
            [
                'name' => 'Sale'
            ],
            [
                'name' => 'Hot'
            ],
            [
                'name' => 'Latest'
            ],
            [
                'name' => 'Special'
            ],
        ];
        foreach ($data as $tag) {
            Tag::updateOrCreate(
                ['name' => $tag['name']],
                $tag
            );
        };
    }
}
