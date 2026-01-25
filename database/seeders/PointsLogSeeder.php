<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\Event;
use App\Models\PointsLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class PointsLogSeeder extends Seeder
{
    public function run(): void
    {
        $cleanup = Event::where('title', 'River Cleanup Drive')->firstOrFail();
        $foodBank = Event::where('title', 'Food Bank Sorting')->firstOrFail();
        $contributor = Badge::where('name', 'Contributor')->firstOrFail();

        $student1 = User::where('email', 'student1@north.edu')->firstOrFail();
        $student2 = User::where('email', 'student2@north.edu')->firstOrFail();
        $admin = User::where('email', 'admin@north.edu')->firstOrFail();

        PointsLog::create([
            'user_id' => $student1->id,
            'source_type' => 'event',
            'source_id' => $cleanup->id,
            'points' => 60,
            'description' => 'Volunteered at River Cleanup Drive.',
        ]);

        PointsLog::create([
            'user_id' => $student2->id,
            'source_type' => 'event',
            'source_id' => $foodBank->id,
            'points' => 15,
            'description' => 'Attended Food Bank Sorting.',
        ]);

        PointsLog::create([
            'user_id' => $admin->id,
            'source_type' => 'badge',
            'source_id' => $contributor->id,
            'points' => 250,
            'description' => 'Earned Contributor badge.',
        ]);
    }
}
