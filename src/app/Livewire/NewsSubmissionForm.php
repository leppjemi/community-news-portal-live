<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\NewsPost;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class NewsSubmissionForm extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $postId = null;

    public $title = '';

    public $content = '';

    public $category_id = '';

    public $cover_image;

    public $existing_image = null;

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string|min:50',
        'category_id' => 'required|exists:categories,id',
        'cover_image' => 'nullable|image|max:2048',
    ];

    public function mount($postId = null)
    {
        if ($postId) {
            $post = NewsPost::findOrFail($postId);
            Gate::authorize('update', $post);

            $this->postId = $post->id;
            $this->title = $post->title;
            $this->content = $post->content;
            $this->category_id = $post->category_id;
            $this->existing_image = $post->cover_image;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->postId) {
            $post = NewsPost::findOrFail($this->postId);
            Gate::authorize('update', $post);
        } else {
            Gate::authorize('create', NewsPost::class);
            $post = new NewsPost;
            $post->user_id = Auth::id();
            $post->status = 'pending';
        }

        $post->title = $this->title;
        $post->content = $this->content;
        $post->category_id = $this->category_id;

        if ($this->cover_image) {
            // Delete old image if exists
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }

            $path = $this->cover_image->store('news', 'public');
            $post->cover_image = $path;
        }

        $post->save();

        session()->flash('message', $this->postId ? 'Post updated successfully!' : 'Post submitted for review!');

        return redirect()->route('my-submissions');
    }

    public function render()
    {
        // Cache categories for 1 hour (3600 seconds) - they rarely change
        $categories = Cache::remember('categories.all', 3600, function () {
            return Category::orderBy('name')->get();
        });

        return view('livewire.news-submission-form', compact('categories'));
    }
}
