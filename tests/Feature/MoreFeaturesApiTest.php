<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use App\Models\Blog;
use App\Models\Notification;
use App\Models\University;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoreFeaturesApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_manage_universities()
    {
        // Admin creates university
        $admin = User::factory()->create(['role' => 'ADMIN']);
        $uniData = [
            'name' => 'Tech University',
            'emailDomain' => 'tech.edu',
            'description' => 'A great place.',
            'logoUrl' => 'http://example.com/logo.png',
        ];

        $response = $this->withSession(['user_id' => $admin->id])
            ->postJson('/api/admin/universities', $uniData);

        $response->assertStatus(201)
            ->assertJson(['name' => 'Tech University']);

        $this->assertDatabaseHas('universities', ['email_domain' => 'tech.edu']);
    }

    public function test_can_report_blog()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create();

        $response = $this->withSession(['user_id' => $user->id])
            ->postJson("/api/reports/blogs/{$blog->id}", [
                'reason' => 'Spam content',
                'description' => 'This blog is spam.',
            ]);

        $response->assertStatus(201)
            ->assertJson(['reason' => 'Spam content']);

        $this->assertDatabaseHas('blog_reports', [
            'blog_id' => $blog->id,
            'reported_by' => $user->id,
            'reason' => 'Spam content',
        ]);
    }

    public function test_can_get_notifications()
    {
        $user = User::factory()->create();
        Notification::create([
            'user_id' => $user->id,
            'message' => 'Welcome!',
            'type' => 'WELCOME',
            'is_read' => false,
        ]);

        $response = $this->withSession(['user_id' => $user->id])
            ->getJson('/api/notifications');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'message', 'is_read']
            ]);
    }

    public function test_can_mark_notification_as_read()
    {
        $user = User::factory()->create();
        $notification = Notification::create([
            'user_id' => $user->id,
            'message' => 'Welcome!',
            'type' => 'WELCOME',
            'is_read' => false,
        ]);

        $response = $this->withSession(['user_id' => $user->id])
            ->putJson("/api/notifications/{$notification->id}/read");

        $response->assertStatus(200);

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'is_read' => true,
        ]);
    }
}
