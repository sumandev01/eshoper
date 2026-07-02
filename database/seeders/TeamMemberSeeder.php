<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\TeamMember;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\Traits\SeedsDummyImages;

class TeamMemberSeeder extends Seeder
{
    use SeedsDummyImages;

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
            
            $mediaId = $this->seedImage(400, 400, 'image', 'team', 3);

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
