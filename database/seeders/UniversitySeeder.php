<?php

namespace Database\Seeders;

use App\Models\University;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    public function run(): void
    {
        University::create([
            'name' => 'North Valley University',
            'description' => 'A public university focused on community engagement and service learning.',
            'logo_url' => 'https://example.com/logos/north-valley.png',
            'email_domain' => 'north.edu',
        ]);

        University::create([
            'name' => 'City Tech Institute',
            'description' => 'A tech-forward institute with strong industry partnerships.',
            'logo_url' => 'https://example.com/logos/city-tech.png',
            'email_domain' => 'citytech.edu',
        ]);
    }
}
