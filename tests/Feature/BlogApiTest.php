<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_blogs()
    {
        Blog::factory()->count(3)->create();

        $response = $this->getJson('/api/blogs');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_blog()
    {
        $user = User::factory()->create();

        $blogData = [
            'title' => 'My First Blog',
            'content' => 'This is the content.',
            'category' => 'Technology',
            'isGlobal' => true,
        ];


        $response = $this->withSession(['user_id' => $user->id])
            ->postJson('/api/blogs', $blogData);

        $response->assertStatus(201)
            ->assertJson([
                'title' => 'My First Blog',
                'category' => 'Technology',
                'status' => 'PENDING',
            ]);

        $this->assertDatabaseHas('blogs', [
            'title' => 'My First Blog',
            'author_id' => $user->id,
        ]);
    }

    public function test_can_view_blog()
    {
        $blog = Blog::factory()->create();

        $response = $this->getJson('/api/blogs/' . $blog->id);

        $response->assertStatus(200)
            ->assertJson([
                'blogId' => $blog->id,
                'title' => $blog->title,
            ]);
    }

    public function test_author_can_edit_blog()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['author_id' => $user->id, 'status' => 'PENDING']);

        $updateData = [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'category' => 'Life',
            'isGlobal' => false,
        ];

        $response = $this->withSession(['user_id' => $user->id])
            ->putJson('/api/blogs/' . $blog->id, $updateData);

        $response->assertStatus(200)
            ->assertJson(['title' => 'Updated Title']);

        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_cannot_edit_others_blog()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $blog = Blog::factory()->create(['author_id' => $otherUser->id]);

        $updateData = [
            'title' => 'Hacked Title',
            'content' => 'Hacked content',
            'category' => 'Life',
        ];

        $response = $this->withSession(['user_id' => $user->id])
            ->putJson('/api/blogs/' . $blog->id, $updateData);

        $response->assertStatus(400) // Controller returns 400 for errors, usually 403 Forbidden is better but sticking to implementation
            ->assertJson(['message' => 'You do not have permission to edit this blog']);
    }

    public function test_admin_can_approve_blog()
    {
        // Assuming admin middleware or check isn't strictly enforced on this route in the provided code, 
        // or we simulate admin user if implemented. 
        // Based on route `Route::put('{id}/approve', [BlogController::class, 'approve']);` in `api.php` under `blogs` prefix.
        // It seems there is no middleware on this group.

        $blog = Blog::factory()->create(['status' => 'PENDING']);

        // Simulating admin usually involves role check, but let's check if the controller enforces it.
        // BlogController::approve does NOT check for user role in the provided snippet! 
        // It just approves. This might be a security hole in the project, but for testing "As Is":

        $response = $this->putJson('/api/blogs/' . $blog->id . '/approve');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Blog approved successfully']);

        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'status' => 'APPROVED',
        ]);
    }
}
