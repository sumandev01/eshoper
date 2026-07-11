<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\SeedsDummyImages;
use App\Models\Media;

class SettingSeeder extends Seeder
{
    use SeedsDummyImages;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // Download dummy logos (1 variation)
        $logoId = $this->seedImage(200, 80, 'image', 'logo', 1);
        $mobileLogoId = $this->seedImage(100, 40, 'image', 'mobile_logo', 1);
        $faviconId = $this->seedImage(32, 32, 'image', 'favicon', 1);
        

        $data = [
            ['key_name' => 'site_title', 'key_value' => 'MartX'],
            ['key_name' => 'seo_title', 'key_value' => 'Quality Products and Trending Fashion'],
            ['key_name' => 'site_logo', 'key_value' => $logoId],
            ['key_name' => 'site_mobile_logo', 'key_value' => $mobileLogoId],
            ['key_name' => 'site_favicon', 'key_value' => $faviconId],
            ['key_name' => 'site_description', 'key_value' => 'Welcome to our website!'],
            ['key_name' => 'site_keywords', 'key_value' => 'Online shopping, fashion, electronics, beauty, home decor, accessories, clothing, footwear'],
            
            // Home Page Text Settings
            ['key_name' => 'home_trending_title', 'key_value' => 'Trendy Products'],
            ['key_name' => 'home_latest_title', 'key_value' => 'Just Arrived'],
            ['key_name' => 'subscribe_heading', 'key_value' => 'Stay Updated'],
            ['key_name' => 'subscribe_text', 'key_value' => 'Subscribe to our newsletter to get the latest updates and offers.'],
            ['key_name' => 'contact_email', 'key_value' => 'superadmin@example.com'],
            ['key_name' => 'contact_phone', 'key_value' => '+8801234567890'],
            ['key_name' => 'contact_address', 'key_value' => '123 Street, City, Country'],
            ['key_name' => 'footer_text', 'key_value' => '© {year} {site_title}. All rights reserved.'],
            ['key_name' => 'google_analytics', 'key_value' => 'UA-123456789-1'],
            ['key_name' => 'facebook_pixel', 'key_value' => '123456789'],
            ['key_name' => 'google_tag_manager', 'key_value' => 'GTM-123456789'],
            ['key_name' => 'currency_symbol', 'key_value' => '৳'],
            ['key_name' => 'currency_code', 'key_value' => 'BDT'],
            ['key_name' => 'google_map', 'key_value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1683.4716418868045!2d91.50980138952988!3d24.294198585414446!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbd!4v1780639829558!5m2!1sen!2sbd" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>'],
            
            // Social Media Links
            ['key_name' => 'social_facebook', 'key_value' => 'https://www.facebook.com/'],
            ['key_name' => 'social_twitter', 'key_value' => 'https://x.com/'],
            ['key_name' => 'social_linkedin', 'key_value' => 'https://www.linkedin.com/'],
            ['key_name' => 'social_instagram', 'key_value' => 'https://www.instagram.com/'],
            ['key_name' => 'social_youtube', 'key_value' => 'https://www.youtube.com/'],
            ['key_name' => 'terms_conditions', 'key_value' => '<h3>Terms and Conditions</h3><p>' . implode('</p><p>', $faker->paragraphs(5)) . '</p>'],
            ['key_name' => 'privacy_policy', 'key_value' => '<h3>Privacy Policy</h3><p>' . implode('</p><p>', $faker->paragraphs(5)) . '</p>'],
            
            // Theme Colors
            ['key_name' => 'theme_color_primary', 'key_value' => '#D19C97'],
            ['key_name' => 'theme_color_dark', 'key_value' => '#1C1C1C'],
            
            // Button Colors
            ['key_name' => 'theme_button_bg', 'key_value' => '#D19C97'],
            ['key_name' => 'theme_button_text', 'key_value' => '#212529'],
        ];

        foreach ($data as $datum) {
            Setting::updateOrCreate(['key_name' => $datum['key_name']], ['key_value' => $datum['key_value']]);
        }
    }
}

