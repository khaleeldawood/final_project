<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\University;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $north = University::where('email_domain', 'north.edu')->firstOrFail();
        $city = University::where('email_domain', 'citytech.edu')->firstOrFail();
        $supervisor = User::where('email', 'supervisor1@north.edu')->firstOrFail();
        $student = User::where('email', 'student3@citytech.edu')->firstOrFail();

        Blog::create([
            'university_id' => $north->id,
            'author_id' => $supervisor->id,
            'title' => 'Planning Effective Volunteer Events',
            'content' => 'A guide to structuring volunteer events that are safe, impactful, and engaging.',
            'category' => 'Guides',
            'status' => 'APPROVED',
            'is_global' => true,
        ]);

        Blog::create([
            'university_id' => $city->id,
            'author_id' => $student->id,
            'title' => 'My First Mentorship Session',
            'content' => 'Sharing what I learned from connecting with mentors and peers.',
            'category' => 'Experience',
            'status' => 'PENDING',
            'is_global' => false,
        ]);
    }
}
