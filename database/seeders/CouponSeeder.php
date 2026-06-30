<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code'             => 'WELCOME20',
                'type'             => 'percentage',
                'amount'           => 20,
                'min_order_amount' => 1000,
                'max_discount'     => 500,
                'usage_limit'      => 100,
                'used_count'       => 0,
                'start_date'       => Carbon::now(),
                'expire_date'      => Carbon::now()->addMonths(2),
                'status'           => 1,
            ],
            [
                'code'             => 'FLAT500',
                'type'             => 'fixed',
                'amount'           => 500,
                'min_order_amount' => 3000,
                'max_discount'     => 500,
                'usage_limit'      => 50,
                'used_count'       => 0,
                'start_date'       => Carbon::now(),
                'expire_date'      => Carbon::now()->addMonths(1),
                'status'           => 1,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }
    }
}
