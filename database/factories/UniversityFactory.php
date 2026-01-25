<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UniversityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' University',
            'description' => fake()->paragraph(),
            'logo_url' => fake()->imageUrl(),
            'email_domain' => fake()->domainName(),
        ];
    }
}
