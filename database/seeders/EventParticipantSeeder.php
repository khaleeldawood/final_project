<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventParticipantSeeder extends Seeder
{
    public function run(): void
    {
        $cleanup = Event::where('title', 'River Cleanup Drive')->firstOrFail();
        $foodBank = Event::where('title', 'Food Bank Sorting')->firstOrFail();

        $organizer = User::where('email', 'supervisor1@north.edu')->firstOrFail();
        $volunteer = User::where('email', 'student1@north.edu')->firstOrFail();
        $attendee = User::where('email', 'student2@north.edu')->firstOrFail();
        $cityStudent = User::where('email', 'student3@citytech.edu')->firstOrFail();

        EventParticipant::create([
            'event_id' => $cleanup->id,
            'user_id' => $organizer->id,
            'role' => 'ORGANIZER',
            'points_awarded' => 120,
            'joined_at' => now()->subDays(2),
        ]);

        EventParticipant::create([
            'event_id' => $cleanup->id,
            'user_id' => $volunteer->id,
            'role' => 'VOLUNTEER',
            'points_awarded' => 60,
            'joined_at' => now()->subDays(1),
        ]);

        EventParticipant::create([
            'event_id' => $foodBank->id,
            'user_id' => $attendee->id,
            'role' => 'ATTENDEE',
            'points_awarded' => 15,
            'joined_at' => now()->subDays(3),
        ]);

        EventParticipant::create([
            'event_id' => $foodBank->id,
            'user_id' => $cityStudent->id,
            'role' => 'VOLUNTEER',
            'points_awarded' => 45,
            'joined_at' => now()->subDays(2),
        ]);
    }
}
