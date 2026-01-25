<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            UniversitySeeder::class,
            UserSeeder::class,
            BadgeSeeder::class,
            EventSeeder::class,
            BlogSeeder::class,
            EventParticipantSeeder::class,
            UserBadgeSeeder::class,
            PointsLogSeeder::class,
            NotificationSeeder::class,
            EventRequestSeeder::class,
            EventParticipationRequestSeeder::class,
            BlogReportSeeder::class,
            EventReportSeeder::class,
        ]);
    }
}
