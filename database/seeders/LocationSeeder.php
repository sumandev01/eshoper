<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Insert or Get Country
        $bangladeshId = $this->getCountryId('Bangladesh');

        // 2. Insert States for Bangladesh
        $states = [
            ['country_id' => $bangladeshId, 'name' => 'Dhaka'],
            ['country_id' => $bangladeshId, 'name' => 'Chittagong'],
            ['country_id' => $bangladeshId, 'name' => 'Sylhet'],
            ['country_id' => $bangladeshId, 'name' => 'Khulna'],
            ['country_id' => $bangladeshId, 'name' => 'Rajshahi'],
            ['country_id' => $bangladeshId, 'name' => 'Rangpur'],
            ['country_id' => $bangladeshId, 'name' => 'Barisal'],
            ['country_id' => $bangladeshId, 'name' => 'Mymensingh'],
        ];

        foreach ($states as $state) {
            DB::table('states')->updateOrInsert(
                [
                    'country_id' => $state['country_id'], 
                    'name' => $state['name']
                ],
                [
                    'created_at' => now(), 
                    'updated_at' => now()
                ]
            );
        }
    }

    private function getCountryId(string $name): int
    {
        DB::table('countries')->updateOrInsert(
            ['name' => $name],
            ['created_at' => now(), 'updated_at' => now()]
        );

        return DB::table('countries')->where('name', $name)->value('id');
    }
}