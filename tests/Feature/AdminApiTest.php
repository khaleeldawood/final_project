<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_users()
    {
        $admin = User::factory()->create(['role' => 'ADMIN']);
        User::factory()->count(3)->create();

        // Assuming no strict middleware in provided code, but good to test accessibility
        $response = $this->withSession(['user_id' => $admin->id])
            ->getJson('/api/admin/users');

        $response->assertStatus(200)
            ->assertJsonCount(4); // Admin + 3 users
    }

    public function test_admin_can_create_supervisor()
    {
        $admin = User::factory()->create(['role' => 'ADMIN']);
        $userData = [
            'name' => 'Supervisor User',
            'email' => 'supervisor@example.com',
            'password' => Hash::make('password'),
            'role' => 'SUPERVISOR',
            'universityId' => null,
        ];

        $response = $this->withSession(['user_id' => $admin->id])
            ->postJson('/api/admin/users', $userData);

        $response->assertStatus(201)
            ->assertJson(['role' => 'SUPERVISOR', 'name' => 'Supervisor User']);

        $this->assertDatabaseHas('users', ['email' => 'supervisor@example.com']);
    }

    public function test_admin_can_delete_user()
    {
        $admin = User::factory()->create(['role' => 'ADMIN']);
        $user = User::factory()->create();

        $response = $this->withSession(['user_id' => $admin->id])
            ->deleteJson('/api/admin/users/' . $user->id);

        $response->assertStatus(200)
            ->assertJson(['message' => 'User deactivated']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
