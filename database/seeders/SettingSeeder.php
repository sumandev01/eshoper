<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['key_name' => 'site_title', 'key_value' => 'E-Shopper'],
            ['key_name' => 'site_logo', 'key_value' => ''],
            ['key_name' => 'site_favicon', 'key_value' => ''],
            ['key_name' => 'site_description', 'key_value' => 'Welcome to our E-Shopper website!'],
            ['key_name' => 'site_keywords', 'key_value' => 'E-Shopper, online shopping, fashion, electronics, beauty, home decor, accessories, clothing, footwear'],
            ['key_name' => 'contact_email', 'key_value' => '2lUjI@example.com'],
            ['key_name' => 'contact_phone', 'key_value' => '+8801234567890'],
            ['key_name' => 'contact_address', 'key_value' => '123 Street, City, Country'],
            ['key_name' => 'footer_text', 'key_value' => '© 2024 E-Shopper. All rights reserved.'],
            ['key_name' => 'google_analytics', 'key_value' => 'UA-123456789-1'],
            ['key_name' => 'facebook_pixel', 'key_value' => '123456789'],
            ['key_name' => 'google_tag_manager', 'key_value' => 'GTM-123456789'],
            ['key_name' => 'currency_symbol', 'key_value' => '৳'],
            ['key_name' => 'currency_code', 'key_value' => 'BDT'],
        ];

        foreach ($data as $datum) {
            Setting::updateOrCreate(['key_name' => $datum['key_name']], ['key_value' => $datum['key_value']]);
        }
    }
}
