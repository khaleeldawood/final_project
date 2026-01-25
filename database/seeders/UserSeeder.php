<?php

namespace Database\Seeders;

use App\Models\University;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $north = University::where('email_domain', 'north.edu')->firstOrFail();
        $city = University::where('email_domain', 'citytech.edu')->firstOrFail();

        User::create([
            'university_id' => $north->id,
            'name' => 'Amina Hassan',
            'email' => 'admin@north.edu',
            'password' => bcrypt('password'),
            'email_verified' => true,
            'role' => 'ADMIN',
            'points' => 1200,
            'current_badge_id' => null,
        ]);

        User::create([
            'university_id' => $north->id,
            'name' => 'Khaled Farouk',
            'email' => 'supervisor1@north.edu',
            'password' => bcrypt('password'),
            'email_verified' => true,
            'role' => 'SUPERVISOR',
            'points' => 450,
            'current_badge_id' => null,
        ]);

        User::create([
            'university_id' => $city->id,
            'name' => 'Mira Chen',
            'email' => 'supervisor2@citytech.edu',
            'password' => bcrypt('password'),
            'email_verified' => true,
            'role' => 'SUPERVISOR',
            'points' => 520,
            'current_badge_id' => null,
        ]);

        User::create([
            'university_id' => $north->id,
            'name' => 'Yousef Ibrahim',
            'email' => 'student1@north.edu',
            'password' => bcrypt('password'),
            'email_verified' => true,
            'role' => 'STUDENT',
            'points' => 220,
            'current_badge_id' => null,
        ]);

        User::create([
            'university_id' => $north->id,
            'name' => 'Laila Mansour',
            'email' => 'student2@north.edu',
            'password' => bcrypt('password'),
            'email_verified' => true,
            'role' => 'STUDENT',
            'points' => 180,
            'current_badge_id' => null,
        ]);

        User::create([
            'university_id' => $city->id,
            'name' => 'Omar Salem',
            'email' => 'student3@citytech.edu',
            'password' => bcrypt('password'),
            'email_verified' => true,
            'role' => 'STUDENT',
            'points' => 90,
            'current_badge_id' => null,
        ]);

        User::create([
            'university_id' => $city->id,
            'name' => 'Sara Nassar',
            'email' => 'student4@citytech.edu',
            'password' => bcrypt('password'),
            'email_verified' => true,
            'role' => 'STUDENT',
            'points' => 140,
            'current_badge_id' => null,
        ]);

        User::create([
            'university_id' => $city->id,
            'name' => 'Zaid Rahman',
            'email' => 'student5@citytech.edu',
            'password' => bcrypt('password'),
            'email_verified' => true,
            'role' => 'STUDENT',
            'points' => 60,
            'current_badge_id' => null,
        ]);
    }
}
