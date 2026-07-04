<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Payment Methods
        PaymentMethod::create([
            'name' => 'Default Payment Logo',
            'order' => 1
        ]);
    }
}
