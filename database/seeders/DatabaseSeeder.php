<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clean up media directory to prevent orphaned files during migrate:fresh --seed
        $mediaPath = storage_path('app/public/media');
        if (\Illuminate\Support\Facades\File::exists($mediaPath)) {
            \Illuminate\Support\Facades\File::cleanDirectory($mediaPath);
        }

        // User::factory(10)->create();
        $this->call([
            // Core Settings and Access Control
            SettingSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,

            // E-commerce Settings
            LocationSeeder::class,
            ShippingCostSeeder::class,
            CouponSeeder::class,

            // Product Attributes & Categories
            CategorySeeder::class,
            SubCategorySeeder::class,
            ColorSeeder::class,
            SizeSeeder::class,
            BrandSeeder::class,
            TagSeeder::class,
            
            // Products
            ProductSeeder::class,
            ProductReviewSeeder::class,

            // CMS Pages & Info
            SliderSeeder::class,
            AboutUsSeeder::class,
            FaqSeeder::class,
            TeamMemberSeeder::class,
        ]);
    }
}
