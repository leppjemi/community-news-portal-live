<?php

namespace App\Livewire;

use App\Models\NewsPost;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use Livewire\WithPagination;

class EditorSubmissionsList extends Component
{
    use WithPagination;

    public $selectedPost = null;
    public $showPreviewModal = false;
    public $showDeleteModal = false;
    public $showEditModal = false;
    public $postToDeleteId = null;
    public $postToEditId = null;

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

    public function confirmEdit($postId)
    {
        $this->postToEditId = $postId;
        $this->showEditModal = true;
    }

    public function cancelEdit()
    {
        $this->showEditModal = false;
        $this->postToEditId = null;
    }

    public function proceedToEdit()
    {
        if (!$this->postToEditId) {
            return;
        }

        return redirect()->route('editor.edit-news', $this->postToEditId);
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

        // Ensure editor can only delete their own posts
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        // Editors can delete published posts
        $post->delete();
        session()->flash('message', 'Article deleted successfully!');
        $this->cancelDelete();
    }

    public function render()
    {
        // Show only published posts for editors
        $posts = NewsPost::where('user_id', Auth::id())
            ->where('status', 'published')
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('livewire.editor-submissions-list', compact('posts'));
    }
}
