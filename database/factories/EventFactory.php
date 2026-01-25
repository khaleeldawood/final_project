<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\University;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'university_id' => University::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'location' => fake()->address(),
            'start_date' => Carbon::parse(fake()->dateTimeBetween('now', '+1 month')),
            'end_date' => Carbon::parse(fake()->dateTimeBetween('+1 month', '+2 months')),
            'type' => fake()->randomElement(['WORKSHOP', 'SEMINAR', 'COMPETITION']),
            'status' => 'APPROVED',
            'max_organizers' => 5,
            'max_volunteers' => 10,
            'max_attendees' => 50,
            'organizer_points' => 50,
            'volunteer_points' => 20,
            'attendee_points' => 10,
            'created_by' => User::factory(),
        ];
    }
}
