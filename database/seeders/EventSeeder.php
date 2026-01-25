<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\University;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $north = University::where('email_domain', 'north.edu')->firstOrFail();
        $city = University::where('email_domain', 'citytech.edu')->firstOrFail();
        $admin = User::where('email', 'admin@north.edu')->firstOrFail();
        $supervisor = User::where('email', 'supervisor2@citytech.edu')->firstOrFail();

        Event::create([
            'university_id' => $north->id,
            'title' => 'River Cleanup Drive',
            'description' => 'Community-led cleanup of the North River shoreline.',
            'location' => 'North River Park',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(10)->addHours(3),
            'type' => 'ENVIRONMENT',
            'status' => 'APPROVED',
            'max_organizers' => 3,
            'max_volunteers' => 20,
            'max_attendees' => 50,
            'organizer_points' => 120,
            'volunteer_points' => 60,
            'attendee_points' => 20,
            'created_by' => $admin->id,
        ]);

        Event::create([
            'university_id' => $city->id,
            'title' => 'Tech Mentorship Meetup',
            'description' => 'Mentorship and resume reviews for junior students.',
            'location' => 'City Tech Hub',
            'start_date' => now()->addDays(18),
            'end_date' => now()->addDays(18)->addHours(2),
            'type' => 'EDUCATION',
            'status' => 'PENDING',
            'max_organizers' => 2,
            'max_volunteers' => 10,
            'max_attendees' => 40,
            'organizer_points' => 100,
            'volunteer_points' => 50,
            'attendee_points' => 15,
            'created_by' => $supervisor->id,
        ]);

        Event::create([
            'university_id' => $north->id,
            'title' => 'Food Bank Sorting',
            'description' => 'Sorting and packaging donations for local food banks.',
            'location' => 'North Community Center',
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(5)->addHours(4),
            'type' => 'COMMUNITY',
            'status' => 'APPROVED',
            'max_organizers' => 2,
            'max_volunteers' => 15,
            'max_attendees' => 30,
            'organizer_points' => 90,
            'volunteer_points' => 45,
            'attendee_points' => 15,
            'created_by' => $admin->id,
        ]);
    }
}
