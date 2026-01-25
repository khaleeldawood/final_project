<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Database\Seeder;

class UserBadgeSeeder extends Seeder
{
    public function run(): void
    {
        $starter = Badge::where('name', 'Starter')->firstOrFail();
        $contributor = Badge::where('name', 'Contributor')->firstOrFail();
        $leader = Badge::where('name', 'Leader')->firstOrFail();

        $admin = User::where('email', 'admin@north.edu')->firstOrFail();
        $student1 = User::where('email', 'student1@north.edu')->firstOrFail();
        $student3 = User::where('email', 'student3@citytech.edu')->firstOrFail();

        UserBadge::create([
            'user_id' => $admin->id,
            'badge_id' => $leader->id,
            'earned_at' => now()->subDays(30),
        ]);

        UserBadge::create([
            'user_id' => $student1->id,
            'badge_id' => $contributor->id,
            'earned_at' => now()->subDays(7),
        ]);

        UserBadge::create([
            'user_id' => $student3->id,
            'badge_id' => $starter->id,
            'earned_at' => now()->subDays(2),
        ]);
    }
}
