<?php

namespace App\Livewire;

use App\Models\NewsPost;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MySubmissionsList extends Component
{
    public function delete($postId)
    {
        $post = NewsPost::findOrFail($postId);

        if ($post->user_id !== Auth::id() && ! Auth::user()->isEditor()) {
            abort(403);
        }

        $post->delete();
        session()->flash('message', 'Post deleted successfully!');
    }

    public function render()
    {
        $posts = NewsPost::where('user_id', Auth::id())
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('livewire.my-submissions-list', compact('posts'));
    }
}
