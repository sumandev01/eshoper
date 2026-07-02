<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\Slider;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\SeedsDummyImages;

class SliderSeeder extends Seeder
{
    use SeedsDummyImages;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'title'       => 'Summer Collection 2026',
                'subtitle'    => 'Get up to 50% off on all summer wear!',
                'button_text' => 'Shop Now',
                'button_link' => '/shop',
                'order'       => 1,
                'is_active'   => 1,
            ],
            [
                'title'       => 'Exclusive New Arrivals',
                'subtitle'    => 'Discover the latest trends in fashion.',
                'button_text' => 'Discover',
                'button_link' => '/shop',
                'order'       => 2,
                'is_active'   => 1,
            ],
            [
                'title'       => 'Premium Accessories',
                'subtitle'    => 'Elevate your style with our premium accessories.',
                'button_text' => 'Explore',
                'button_link' => '/shop',
                'order'       => 3,
                'is_active'   => 1,
            ]
        ];

        foreach ($sliders as $sliderData) {
            $sliderData['media_id'] = $this->seedImage(1920, 800, 'image', 'slider', 3);
            Slider::create($sliderData);
        }
    }
}
