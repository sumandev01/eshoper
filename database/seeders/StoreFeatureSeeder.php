<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoreFeature;

class StoreFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            ['title' => 'Quality Product', 'icon' => 'fa-check', 'order' => 1],
            ['title' => 'Free Shipping', 'icon' => 'fa-shipping-fast', 'order' => 2],
            ['title' => '14-Day Return', 'icon' => 'fa-exchange-alt', 'order' => 3],
            ['title' => '24/7 Support', 'icon' => 'fa-phone-volume', 'order' => 4],
        ];

        foreach ($features as $feature) {
            StoreFeature::create($feature);
        }
    }
}
