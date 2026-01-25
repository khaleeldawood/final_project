<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        Badge::create([
            'name' => 'Starter',
            'description' => 'Completed the first community activity.',
            'points_threshold' => 50,
        ]);

        Badge::create([
            'name' => 'Contributor',
            'description' => 'Reached 250 points through participation.',
            'points_threshold' => 250,
        ]);

        Badge::create([
            'name' => 'Leader',
            'description' => 'Reached 800 points and led events.',
            'points_threshold' => 800,
        ]);
    }
}
