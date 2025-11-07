<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\NewsPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_home_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Latest Community News');
    }

    public function test_guest_can_view_published_post(): void
    {
        $category = Category::factory()->create();
        $post = NewsPost::factory()->create([
            'status' => 'published',
            'category_id' => $category->id,
            'published_at' => now(),
        ]);

        $response = $this->get("/news/{$post->slug}");

        $response->assertStatus(200);
        $response->assertSee($post->title);
    }

    public function test_guest_cannot_view_pending_post(): void
    {
        $category = Category::factory()->create();
        $post = NewsPost::factory()->create([
            'status' => 'pending',
            'category_id' => $category->id,
        ]);

        $response = $this->get("/news/{$post->slug}");

        $response->assertStatus(404);
    }

    public function test_home_page_shows_only_published_posts(): void
    {
        $category = Category::factory()->create();
        $publishedPost = NewsPost::factory()->published()->create([
            'category_id' => $category->id,
        ]);
        $pendingPost = NewsPost::factory()->create([
            'status' => 'pending',
            'category_id' => $category->id,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($publishedPost->title);
        $response->assertDontSee($pendingPost->title);
    }
}
