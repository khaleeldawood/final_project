<?php

namespace Tests\Feature;

use App\Models\University;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    private function createUniversity(): University
    {
        return University::create([
            'name' => 'Test University',
            'description' => 'Test',
            'logo_url' => null,
            'email_domain' => 'test.edu',
        ]);
    }

    private function createUser(array $overrides = []): User
    {
        $university = $overrides['university'] ?? $this->createUniversity();

        return User::create(array_merge([
            'university_id' => $university->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('oldpassword'),
            'email_verified' => true,
            'role' => 'STUDENT',
            'points' => 0,
            'current_badge_id' => null,
        ], $overrides));
    }

    public function test_get_current_user_requires_auth(): void
    {
        $this->getJson('/api/users/me')->assertStatus(401);
    }

    public function test_update_profile(): void
    {
        $user = $this->createUser();

        $response = $this->withSession(['user_id' => $user->id])
            ->putJson('/api/users/me', ['name' => 'Updated Name']);

        $response->assertOk();
        $response->assertJson(['name' => 'Updated Name']);
    }

    public function test_change_password(): void
    {
        $user = $this->createUser();

        $response = $this->withSession(['user_id' => $user->id])
            ->putJson('/api/users/change-password', [
                'oldPassword' => 'oldpassword',
                'newPassword' => 'newpassword',
            ]);

        $response->assertOk();
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }
}