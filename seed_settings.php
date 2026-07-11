<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$defaults = [
    'site_title' => 'MartX',
    'site_logo' => null,
    'site_mobile_logo' => null,
    'site_favicon' => null,
    'site_description' => 'Best e-commerce platform',
    'site_keywords' => 'ecommerce, shop, online store',
    'social_facebook' => '',
    'social_twitter' => '',
    'social_linkedin' => '',
    'social_instagram' => '',
    'social_youtube' => '',
    'theme_color_primary' => '#D19C97',
    'theme_color_dark' => '#1C1C1C',
    'theme_button_bg' => '#D19C97',
    'theme_button_text' => '#111111'
];

foreach ($defaults as $key => $default) {
    \App\Models\Setting::firstOrCreate(
        ['key_name' => $key],
        ['key_value' => $default]
    );
}

echo "Settings seeded successfully.";
