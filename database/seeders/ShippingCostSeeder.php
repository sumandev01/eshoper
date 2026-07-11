<?php

namespace Database\Seeders;

use App\Models\ShippingCost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingCostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Inside Dhaka',
                'price' => 60,
            ],
            [
                'name' => 'Outside Dhaka',
                'price' => 100,
            ]
        ];

        foreach ($data as $shippingCost) {
            ShippingCost::updateOrCreate(
                ['name' => $shippingCost['name']],
                $shippingCost
            );
        }
    }
}
