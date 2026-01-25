<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Badge;
use App\Models\PointsLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamificationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_leaderboard()
    {
        $user1 = User::factory()->create(['points' => 100, 'name' => 'Alice']);
        $user2 = User::factory()->create(['points' => 200, 'name' => 'Bob']);

        $response = $this->getJson('/api/gamification/leaderboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'scope',
                'type',
                'rankings' => [
                    '*' => ['userId', 'name', 'points', 'currentBadge']
                ]
            ]);

        // Check order
        $data = $response->json();
        $this->assertEquals('Bob', $data['rankings'][0]['name']);
        $this->assertEquals('Alice', $data['rankings'][1]['name']);
    }

    public function test_can_view_my_rank()
    {
        $user = User::factory()->create(['points' => 150]);
        // Create other users to establish rank
        User::factory()->create(['points' => 200]);
        User::factory()->create(['points' => 100]);

        $response = $this->withSession(['user_id' => $user->id])
            ->getJson('/api/gamification/my-rank');

        $response->assertStatus(200)
            ->assertJson([
                'rank' => 2,
                'points' => 150
            ]);
    }

    public function test_can_view_badges()
    {
        Badge::factory()->create(['name' => 'Gold Star']);

        $response = $this->getJson('/api/gamification/badges');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['badgeId', 'name', 'description', 'pointsThreshold']
            ]);
    }
}
