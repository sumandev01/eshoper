<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\TeamMember;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $positions = [
            'Chief Executive Officer',
            'Chief Operating Officer',
            'Chief Technology Officer',
            'Marketing Manager',
            'Lead Developer',
            'Customer Support Manager'
        ];

        foreach ($positions as $index => $position) {
            
            // Reusing an existing media or generating one could be done, 
            // but picking a random existing media is safer for performance.
            $mediaId = Media::inRandomOrder()->first()?->id;

            TeamMember::create([
                'name'      => $faker->name(),
                'position'  => $position,
                'media_id'  => $mediaId,
                'order'     => $index + 1,
                'is_active' => 1,
            ]);
        }
    }
}
