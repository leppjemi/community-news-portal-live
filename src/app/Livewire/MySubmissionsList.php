<?php

namespace App\Livewire;

use App\Models\NewsPost;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use Livewire\WithPagination;

class MySubmissionsList extends Component
{
    use WithPagination;

    public $selectedPost = null;
    public $showPreviewModal = false;
    public $showDeleteModal = false;
    public $postToDeleteId = null;
    public $filter = 'all';

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function openPreview($postId)
    {
        $this->selectedPost = NewsPost::with('category', 'author')->find($postId);
        $this->showPreviewModal = true;
    }

    public function closePreview()
    {
        $this->showPreviewModal = false;
        $this->selectedPost = null;
    }

    public function confirmDelete($postId)
    {
        $this->postToDeleteId = $postId;
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->postToDeleteId = null;
    }

    public function deletePost()
    {
        if (!$this->postToDeleteId) {
            return;
        }

        $post = NewsPost::findOrFail($this->postToDeleteId);

        if ($post->user_id !== Auth::id() && !Auth::user()->isEditor()) {
            abort(403);
        }

        if (!in_array($post->status, ['pending', 'rejected'])) {
            session()->flash('error', 'You cannot delete a published or approved post.');
            $this->cancelDelete();
            return;
        }

        $post->delete();
        session()->flash('message', 'Post deleted successfully!');
        $this->cancelDelete();
    }



    public function render()
    {
        $query = NewsPost::where('user_id', Auth::id())
            ->with('category');

        if ($this->filter === 'published') {
            $query->where('status', 'published');
        } elseif ($this->filter === 'pending') {
            $query->where('status', 'pending');
        } elseif ($this->filter === 'rejected') {
            $query->where('status', 'rejected');
        }

        $posts = $query->latest()->paginate(10);

        return view('livewire.my-submissions-list', compact('posts'));
    }
}
