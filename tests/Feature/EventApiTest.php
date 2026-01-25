<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use App\Models\University;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class EventApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_events()
    {
        Event::factory()->count(3)->create();

        $response = $this->getJson('/api/events');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_event()
    {
        $university = University::factory()->create();
        $user = User::factory()->create(['university_id' => $university->id]);

        $eventData = [
            'title' => 'Test Event',
            'description' => 'This is a test event',
            'location' => 'Main Hall',
            'startDate' => Carbon::now()->addDay()->toIso8601String(),
            'endDate' => Carbon::now()->addDays(2)->toIso8601String(),
            'type' => 'WORKSHOP',
            'universityId' => null,
        ];


        $response = $this->withSession(['user_id' => $user->id])
            ->postJson('/api/events', $eventData);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Test Event',
                'location' => 'Main Hall',
            ]);

        $this->assertDatabaseHas('events', [
            'title' => 'Test Event',
            'created_by' => $user->id,
        ]);
    }

    public function test_can_view_event()
    {
        $event = Event::factory()->create();

        $response = $this->getJson('/api/events/' . $event->id);

        $response->assertStatus(200)
            ->assertJson([
                'eventId' => $event->id,
                'title' => $event->title,
            ]);
    }

    public function test_can_join_event_as_attendee()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'status' => 'APPROVED', // Assume only approved events can be joined? Or maybe PENDING ok?
        ]);

        $response = $this->withSession(['user_id' => $user->id])
            ->postJson('/api/events/' . $event->id . '/join', [
                'role' => 'ATTENDEE',
            ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Joined event']);

        $this->assertDatabaseHas('event_participants', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'role' => 'ATTENDEE',
        ]);
    }

    public function test_cannot_create_event_without_auth()
    {
        $eventData = [
            'title' => 'Test Event',
            'description' => 'Test',
            'location' => 'Test',
            'startDate' => now(),
            'endDate' => now(),
            'type' => 'TEST',
        ];

        $response = $this->postJson('/api/events', $eventData);

        $response->assertStatus(401);
    }
}
