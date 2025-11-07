<?php

namespace App\Livewire;

use App\Models\NewsPost;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NewsLikeButton extends Component
{
    public $post;

    public $isLiked = false;

    public $likesCount = 0;

    public function mount(NewsPost $post)
    {
        $this->post = $post;
        $this->likesCount = $post->likes_count;

        if (Auth::check()) {
            $this->isLiked = $post->likers()->where('user_id', Auth::id())->exists();
        }
    }

    public function toggleLike()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($this->isLiked) {
            $this->post->likers()->detach($user->id);
            $this->isLiked = false;
            $this->likesCount--;
        } else {
            $this->post->likers()->attach($user->id);
            $this->isLiked = true;
            $this->likesCount++;
        }

        $this->post->update(['likes_count' => $this->likesCount]);
    }

    public function render()
    {
        return view('livewire.news-like-button');
    }
}
