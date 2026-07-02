<?php

namespace Database\Seeders;

use App\Models\AboutUs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\SeedsDummyImages;
use App\Models\Media;

class AboutUsSeeder extends Seeder
{
    use SeedsDummyImages;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mediaId = $this->seedImage(600, 400, 'image', 'about', 1);
        $imageUrl = $mediaId ? Media::find($mediaId)?->src : '';

        $data = [
            ['key_name' => 'top_header', 'key_value' => 'About Us'],
            ['key_name' => 'top_sub_header', 'key_value' => 'Delivering quality products to your doorstep since 2020.'],
            ['key_name' => 'heading', 'key_value' => 'Our Story'],
            ['key_name' => 'description', 'key_value' => 'We started with a simple idea: to make high-quality products accessible to everyone. What began in a small garage has now grown into a full-scale e-commerce platform serving thousands of happy customers daily. <br> Our dedicated team works around the clock to source the best materials, ensure top-notch quality control, and provide customer service that makes you feel like family.'],
            ['key_name' => 'image', 'key_value' => $imageUrl],
            ['key_name' => 'button_text', 'key_value' => 'Shop Now'],
            ['key_name' => 'button_link', 'key_value' => '#'],
            ['key_name' => 'our_mission', 'key_value' => 'To provide exceptional value and a seamless shopping experience for every customer, every time.'],
            ['key_name' => 'our_vision', 'key_value' => 'To become the most trusted and preferred online shopping destination globally.'],
            ['key_name' => 'our_values', 'key_value' => 'Integrity, customer-first approach, continuous innovation, and sustainable practices.'],
        ];

        foreach ($data as $datum) {
            AboutUs::updateOrCreate(['key_name' => $datum['key_name']], ['key_value' => $datum['key_value']]);
        }
    }
}
