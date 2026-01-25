<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventParticipationRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventParticipationRequestSeeder extends Seeder
{
    public function run(): void
    {
        $event = Event::where('title', 'Tech Mentorship Meetup')->firstOrFail();
        $student = User::where('email', 'student5@citytech.edu')->firstOrFail();
        $supervisor = User::where('email', 'supervisor2@citytech.edu')->firstOrFail();

        EventParticipationRequest::create([
            'event_id' => $event->id,
            'user_id' => $student->id,
            'requested_role' => 'ATTENDEE',
            'status' => 'PENDING',
            'requested_at' => now()->subDays(1),
            'responded_at' => null,
            'responded_by' => null,
        ]);

        EventParticipationRequest::create([
            'event_id' => $event->id,
            'user_id' => $supervisor->id,
            'requested_role' => 'ORGANIZER',
            'status' => 'APPROVED',
            'requested_at' => now()->subDays(3),
            'responded_at' => now()->subDays(2),
            'responded_by' => $supervisor->id,
        ]);
    }
}
