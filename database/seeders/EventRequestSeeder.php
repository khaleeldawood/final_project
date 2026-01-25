<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventRequestSeeder extends Seeder
{
    public function run(): void
    {
        $event = Event::where('title', 'Tech Mentorship Meetup')->firstOrFail();
        $student = User::where('email', 'student4@citytech.edu')->firstOrFail();

        EventRequest::create([
            'event_id' => $event->id,
            'user_id' => $student->id,
            'requested_role' => 'VOLUNTEER',
            'status' => 'PENDING',
        ]);
    }
}
