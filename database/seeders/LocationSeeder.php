<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Insert or Get Divisions
        $dhakaId = $this->getDivisionId('Dhaka');
        $rangpurId = $this->getDivisionId('Rangpur');
        $ctgId = $this->getDivisionId('Chittagong');

        // 2. Insert or Get Districts
        $dhakaCityId = $this->getDistrictId($dhakaId, 'Dhaka');
        $gazipurId = $this->getDistrictId($dhakaId, 'Gazipur');
        
        $rangpurCityId = $this->getDistrictId($rangpurId, 'Rangpur');
        $dinajpurId = $this->getDistrictId($rangpurId, 'Dinajpur');
        $thakurgaonId = $this->getDistrictId($rangpurId, 'Thakurgaon');
        
        $ctgCityId = $this->getDistrictId($ctgId, 'Chittagong');

        // 3. Update or Insert Thanas
        $thanas = [
            ['district_id' => $dhakaCityId, 'name' => 'Mirpur'],
            ['district_id' => $dhakaCityId, 'name' => 'Uttara'],
            ['district_id' => $dhakaCityId, 'name' => 'Dhanmondi'],
            ['district_id' => $dhakaCityId, 'name' => 'Mohammadpur'],
            ['district_id' => $dhakaCityId, 'name' => 'Gulshan'],
            ['district_id' => $dhakaCityId, 'name' => 'Banani'],
            ['district_id' => $dhakaCityId, 'name' => 'Dakshinkhan'],
            ['district_id' => $gazipurId, 'name' => 'Tongi'],
            ['district_id' => $gazipurId, 'name' => 'Sreepur'],
            ['district_id' => $gazipurId, 'name' => 'Gazipur'],
            ['district_id' => $rangpurCityId, 'name' => 'Rangpur Sadar'],
            ['district_id' => $rangpurCityId, 'name' => 'Kaunia'],
            ['district_id' => $dinajpurId, 'name' => 'Dinajpur Sadar'],
            ['district_id' => $dinajpurId, 'name' => 'Birampur'],
            ['district_id' => $thakurgaonId, 'name' => 'Thakurgaon Sadar'],
            ['district_id' => $thakurgaonId, 'name' => 'Pirganj'],
            ['district_id' => $ctgCityId, 'name' => 'Anwara'],
            ['district_id' => $ctgCityId, 'name' => 'Pohela'],
        ];

        foreach ($thanas as $thana) {
            DB::table('thanas')->updateOrInsert(
                [
                    'district_id' => $thana['district_id'], 
                    'name' => $thana['name']
                ],
                [
                    'created_at' => now(), 
                    'updated_at' => now()
                ]
            );
        }
    }

    private function getDivisionId(string $name): int
    {
        DB::table('divisions')->updateOrInsert(
            ['name' => $name],
            ['created_at' => now(), 'updated_at' => now()]
        );

        return DB::table('divisions')->where('name', $name)->value('id');
    }

    private function getDistrictId(int $divisionId, string $name): int
    {
        DB::table('districts')->updateOrInsert(
            ['division_id' => $divisionId, 'name' => $name],
            ['created_at' => now(), 'updated_at' => now()]
        );

        return DB::table('districts')
            ->where('division_id', $divisionId)
            ->where('name', $name)
            ->value('id');
    }
}