<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NewsSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_news(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create();

        // Use a publicly accessible test image URL
        $testImageUrl = 'https://via.placeholder.com/400x300.jpg';

        Livewire::actingAs($user)
            ->test(\App\Livewire\NewsSubmissionForm::class)
            ->set('title', 'Test News Title')
            ->set('content', 'This is a test news content that is long enough to pass validation.')
            ->set('category_id', $category->id)
            ->set('cover_image', $testImageUrl)
            ->call('save')
            ->assertRedirect(route('user.submissions'));

        $this->assertDatabaseHas('news_posts', [
            'title' => 'Test News Title',
            'user_id' => $user->id,
            'status' => 'pending',
            'cover_image' => $testImageUrl,
        ]);
    }

    public function test_user_can_edit_own_post(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create();
        $post = NewsPost::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\NewsSubmissionForm::class, ['postId' => $post->id])
            ->set('title', 'Updated Title')
            ->call('save')
            ->assertRedirect(route('user.submissions'));

        $this->assertDatabaseHas('news_posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_user_cannot_edit_others_post(): void
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);
        $category = Category::factory()->create();
        $post = NewsPost::factory()->create([
            'user_id' => $user1->id,
            'category_id' => $category->id,
        ]);

        Livewire::actingAs($user2)
            ->test(\App\Livewire\NewsSubmissionForm::class, ['postId' => $post->id])
            ->assertForbidden();
    }
}
