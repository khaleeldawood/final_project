<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JordanianUniversitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $universities = [
            // Public Universities (Government)
            [
                'id' => 1,
                'name' => 'University of Jordan',
                'description' => 'The largest and oldest university in Jordan, established in 1962',
                'logo_url' => null,
                'email_domain' => 'ju.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Jordan University of Science and Technology',
                'description' => 'Leading technological university in Jordan',
                'logo_url' => null,
                'email_domain' => 'just.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'Yarmouk University',
                'description' => 'Major public university located in Irbid',
                'logo_url' => null,
                'email_domain' => 'yu.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'Hashemite University',
                'description' => 'Public university in Zarqa Governorate',
                'logo_url' => null,
                'email_domain' => 'hu.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'name' => 'Al-Balqa Applied University',
                'description' => 'Applied sciences and technical education university',
                'logo_url' => null,
                'email_domain' => 'bau.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'name' => 'Mutah University',
                'description' => 'Public university in Karak Governorate',
                'logo_url' => null,
                'email_domain' => 'mutah.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7,
                'name' => 'Tafila Technical University',
                'description' => 'Technical university in Tafila',
                'logo_url' => null,
                'email_domain' => 'ttu.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 8,
                'name' => 'Al al-Bayt University',
                'description' => 'Public university in Mafraq',
                'logo_url' => null,
                'email_domain' => 'aabu.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 9,
                'name' => 'Al-Hussein Bin Talal University',
                'description' => 'Public university in Ma\'an',
                'logo_url' => null,
                'email_domain' => 'ahu.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 10,
                'name' => 'German Jordanian University',
                'description' => 'German-Jordanian collaboration university',
                'logo_url' => null,
                'email_domain' => 'gju.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 11,
                'name' => 'Al-Hussein Technical University',
                'description' => 'Technical university focused on engineering',
                'logo_url' => null,
                'email_domain' => 'htu.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Private Universities
            [
                'id' => 12,
                'name' => 'Princess Sumaya University for Technology',
                'description' => 'Private non-profit IT university',
                'logo_url' => null,
                'email_domain' => 'psut.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 13,
                'name' => 'Amman Arab University',
                'description' => 'Private university for graduate studies',
                'logo_url' => null,
                'email_domain' => 'aau.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 14,
                'name' => 'Applied Science Private University',
                'description' => 'Leading private university in Jordan',
                'logo_url' => null,
                'email_domain' => 'asu.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 15,
                'name' => 'Middle East University',
                'description' => 'Private university in Amman',
                'logo_url' => null,
                'email_domain' => 'meu.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 16,
                'name' => 'Isra University',
                'description' => 'Private university in Amman',
                'logo_url' => null,
                'email_domain' => 'iu.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 17,
                'name' => 'Zarqa University',
                'description' => 'Private university in Zarqa',
                'logo_url' => null,
                'email_domain' => 'zu.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 18,
                'name' => 'Philadelphia University',
                'description' => 'Private university known for engineering',
                'logo_url' => null,
                'email_domain' => 'philadelphia.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 19,
                'name' => 'Jerash Private University',
                'description' => 'Private university in Jerash',
                'logo_url' => null,
                'email_domain' => 'jpu.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 20,
                'name' => 'Irbid National University',
                'description' => 'Private university in Irbid',
                'logo_url' => null,
                'email_domain' => 'inu.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 21,
                'name' => 'Al-Ahliyya Amman University',
                'description' => 'One of the oldest private universities',
                'logo_url' => null,
                'email_domain' => 'ammanu.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 22,
                'name' => 'Al-Zaytoonah University of Jordan',
                'description' => 'Private university in Amman',
                'logo_url' => null,
                'email_domain' => 'zuj.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 23,
                'name' => 'Arab Open University - Jordan',
                'description' => 'Branch of Arab Open University',
                'logo_url' => null,
                'email_domain' => 'aou.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 24,
                'name' => 'Jadara University',
                'description' => 'Private university in Irbid Governorate',
                'logo_url' => null,
                'email_domain' => 'jadara.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 25,
                'name' => 'World Islamic Sciences and Education University',
                'description' => 'Islamic sciences university',
                'logo_url' => null,
                'email_domain' => 'wise.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 26,
                'name' => 'Ajloun National University',
                'description' => 'Private university in Ajloun',
                'logo_url' => null,
                'email_domain' => 'anu.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 27,
                'name' => 'Petra University',
                'description' => 'Private university in Amman',
                'logo_url' => null,
                'email_domain' => 'uop.edu.jo',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Use upsert to insert or update - avoids foreign key constraint violations
        DB::table('universities')->upsert(
            $universities,
            ['id'], // Unique key column
            ['name', 'description', 'logo_url', 'email_domain', 'updated_at'] // Columns to update
        );

        $this->command->info('✅ Successfully seeded ' . count($universities) . ' Jordanian universities!');
    }
}
