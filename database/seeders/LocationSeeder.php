<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $dhakaId = DB::table('divisions')->insertGetId(['name' => 'Dhaka', 'created_at' => now(), 'updated_at' => now()]);
        $rangpurId = DB::table('divisions')->insertGetId(['name' => 'Rangpur', 'created_at' => now(), 'updated_at' => now()]);
        $ctgId = DB::table('divisions')->insertGetId(['name' => 'Chittagong', 'created_at' => now(), 'updated_at' => now()]);

        $dhakaCityId = DB::table('districts')->insertGetId([
            'division_id' => $dhakaId, 
            'name' => 'Dhaka',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $rangpurCityId = DB::table('districts')->insertGetId([
            'division_id' => $rangpurId, 
            'name' => 'Rangpur',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $dinajpurId = DB::table('districts')->insertGetId([
            'division_id' => $rangpurId, 
            'name' => 'Dinajpur',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $thakurgaonId = DB::table('districts')->insertGetId([
            'division_id' => $rangpurId, 
            'name' => 'Thakurgaon',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $gazipurId = DB::table('districts')->insertGetId([
            'division_id' => $dhakaId, 
            'name' => 'Gazipur',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('thanas')->insert([
            ['district_id' => $dhakaCityId, 'name' => 'Mirpur', 'created_at' => now(), 'updated_at' => now()],
            ['district_id' => $dhakaCityId, 'name' => 'Uttara', 'created_at' => now(), 'updated_at' => now()],
            ['district_id' => $dhakaCityId, 'name' => 'Dhanmondi', 'created_at' => now(), 'updated_at' => now()],
            ['district_id' => $dhakaCityId, 'name' => 'Mohammadpur', 'created_at' => now(), 'updated_at' => now()],
            ['district_id' => $dhakaCityId, 'name' => 'Gulshan', 'created_at' => now(), 'updated_at' => now()],
            ['district_id' => $dhakaCityId, 'name' => 'Banani', 'created_at' => now(), 'updated_at' => now()],
            ['district_id' => $dhakaCityId, 'name' => 'Dakshinkhan', 'created_at' => now(), 'updated_at' => now()],
            ['district_id' => $dhakaCityId, 'name' => 'Mirpur', 'created_at' => now(), 'updated_at' => now()],
            ['district_id' => $gazipurId, 'name' => 'Tongi', 'created_at' => now(), 'updated_at' => now()],
            ['district_id' => $gazipurId, 'name' => 'Sreepur', 'created_at' => now(), 'updated_at' => now()],
            ['district_id' => $gazipurId, 'name' => 'Gazipur', 'created_at' => now(), 'updated_at' => now()],
            ['district_id' => $rangpurCityId, 'name' => 'Rangpur Sadar', 'created_at' => now(), 'updated_at' => now()],
            ['district_id' => $rangpurCityId, 'name' => 'Kaunia', 'created_at' => now(), 'updated_at' => now()],
            ['district_id' => $dinajpurId, 'name' => 'Dinajpur Sadar', 'created_at' => now(), 'updated_at' => now()],
            ['district_id' => $dinajpurId, 'name' => 'Birampur', 'created_at' => now(), 'updated_at' => now()],
            ['district_id' => $thakurgaonId, 'name' => 'Thakurgaon Sadar', 'created_at' => now(), 'updated_at' => now()],
            ['district_id' => $thakurgaonId, 'name' => 'Pirganj', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}