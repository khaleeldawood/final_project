<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\University;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'university_id' => University::factory(),
            'author_id' => User::factory(),
            'title' => fake()->sentence(),
            'content' => fake()->paragraph(),
            'category' => fake()->word(),
            'status' => 'PENDING',
            'is_global' => false,
        ];
    }
}
