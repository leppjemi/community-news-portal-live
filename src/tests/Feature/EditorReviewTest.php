<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EditorReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_editor_can_view_review_queue(): void
    {
        $editor = User::factory()->create(['role' => 'editor']);
        $category = Category::factory()->create();
        NewsPost::factory()->create([
            'status' => 'pending',
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($editor)->get('/editor/review-queue');

        $response->assertStatus(200);
        $response->assertSee('Review Queue');
    }

    public function test_editor_can_approve_post(): void
    {
        $editor = User::factory()->create(['role' => 'editor']);
        $category = Category::factory()->create();
        $post = NewsPost::factory()->create([
            'status' => 'pending',
            'category_id' => $category->id,
        ]);

        Livewire::actingAs($editor)
            ->test(\App\Livewire\ReviewQueue::class)
            ->call('confirmApprove', $post->id)
            ->call('approve');

        $this->assertDatabaseHas('news_posts', [
            'id' => $post->id,
            'status' => 'published',
        ]);
    }

    public function test_regular_user_cannot_access_review_queue(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/editor/review-queue');

        $response->assertStatus(403);
    }
}
