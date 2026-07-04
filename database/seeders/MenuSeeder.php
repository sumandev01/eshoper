<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Header Top Menu
        $headerTop = Menu::firstOrCreate(
            ['location' => 'header_top'],
            ['name' => 'Header Top Menu', 'status' => true]
        );
        $headerTop->items()->createMany([
            ['title' => 'FAQs', 'type' => 'system', 'reference_id' => 'faq', 'order' => 1],
            ['title' => 'Help', 'type' => 'system', 'reference_id' => 'contact', 'order' => 2],
            ['title' => 'Support', 'type' => 'system', 'reference_id' => 'contact', 'order' => 3],
        ]);

        // 2. Main Header Menu
        $headerMain = Menu::firstOrCreate(
            ['location' => 'header_main'],
            ['name' => 'Main Navigation Menu', 'status' => true]
        );
        $headerMain->items()->createMany([
            ['title' => 'Home', 'type' => 'system', 'reference_id' => 'root', 'order' => 1],
            ['title' => 'Shop', 'type' => 'system', 'reference_id' => 'shop', 'order' => 2],
            ['title' => 'About Us', 'type' => 'system', 'reference_id' => 'about', 'order' => 3],
            ['title' => 'Contact Us', 'type' => 'system', 'reference_id' => 'contact', 'order' => 4],
        ]);

        // 3. Footer Company Menu
        $footerCompany = Menu::firstOrCreate(
            ['location' => 'footer_company'],
            ['name' => 'Footer Company Menu', 'status' => true]
        );
        $footerCompany->items()->createMany([
            ['title' => 'Home', 'type' => 'system', 'reference_id' => 'root', 'order' => 1],
            ['title' => 'Shop', 'type' => 'system', 'reference_id' => 'shop', 'order' => 2],
            ['title' => 'About Us', 'type' => 'system', 'reference_id' => 'about', 'order' => 3],
            ['title' => 'Contact Us', 'type' => 'system', 'reference_id' => 'contact', 'order' => 4],
        ]);

        // 4. Footer Support Menu
        $footerSupport = Menu::firstOrCreate(
            ['location' => 'footer_support'],
            ['name' => 'Footer Support Menu', 'status' => true]
        );
        $footerSupport->items()->createMany([
            ['title' => 'FAQs', 'type' => 'system', 'reference_id' => 'faq', 'order' => 1],
            ['title' => 'Order Tracking', 'type' => 'system', 'reference_id' => 'orderTracking', 'order' => 2],
        ]);
    }
}
