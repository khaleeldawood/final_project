<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventReport;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventReportSeeder extends Seeder
{
    public function run(): void
    {
        $event = Event::where('title', 'River Cleanup Drive')->firstOrFail();
        $reporter = User::where('email', 'student2@north.edu')->firstOrFail();

        EventReport::create([
            'event_id' => $event->id,
            'reported_by' => $reporter->id,
            'reason' => 'Event details need clarification about safety measures.',
            'status' => 'REVIEWED',
        ]);
    }
}
