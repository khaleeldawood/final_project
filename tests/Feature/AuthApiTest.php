<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\EmailVerificationToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register_user()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'role' => 'STUDENT',
            'universityId' => null,
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(200)
            ->assertJsonStructure(['userId', 'name', 'email']);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'email_verified' => false,
        ]);
    }

    public function test_cannot_login_without_verification()
    {
        $user = User::factory()->create([
            'password' => Hash::make('Password123!'),
            'email_verified' => false,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Please verify your email before logging in']);
    }

    public function test_can_verify_email()
    {
        $user = User::factory()->create([
            'email_verified' => false,
        ]);

        $token = Str::uuid()->toString();
        EmailVerificationToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addHours(24),
        ]);

        $response = $this->getJson('/api/auth/verify-email?token=' . $token);

        $response->assertStatus(200)
            ->assertJson(['email' => $user->email]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verified' => true,
        ]);
    }

    public function test_can_login_verified_user()
    {
        $user = User::factory()->create([
            'password' => Hash::make('Password123!'),
            'email_verified' => true,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $response->assertStatus(200)
            ->assertJson(['userId' => $user->id]);

        $response->assertSessionHas('user_id', $user->id);
    }

    public function test_can_get_current_session()
    {
        $user = User::factory()->create([
            'email_verified' => true,
        ]);

        $response = $this->withSession(['user_id' => $user->id])
            ->getJson('/api/auth/session');

        $response->assertStatus(200)
            ->assertJson(['userId' => $user->id]);
    }

    public function test_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->withSession(['user_id' => $user->id])
            ->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);

        $response->assertSessionMissing('user_id');
    }
}
