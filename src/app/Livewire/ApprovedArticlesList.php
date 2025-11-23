<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\NewsPost;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ApprovedArticlesList extends Component
{
    use WithPagination;

    public $selectedPost = null;

    public $showPreviewModal = false;

    public $showEditModal = false;

    public $showDeleteModal = false;

    public $postToEditId = null;

    public $postToDeleteId = null;

    // Search and filter
    public $search = '';

    public $categoryFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->categoryFilter = '';
        $this->resetPage();
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
        if (! $this->postToEditId) {
            return;
        }

        return redirect()->route('editor.edit-approved-article', $this->postToEditId);
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
        if (! $this->postToDeleteId) {
            return;
        }

        $post = NewsPost::with('author')->findOrFail($this->postToDeleteId);

        // Check if the post author is an editor - if so, deny deletion
        if ($post->author->isEditor()) {
            session()->flash('error', 'You cannot delete articles published by other editors.');
            $this->cancelDelete();

            return;
        }

        // Editors can delete published posts from regular users
        $post->delete();
        session()->flash('message', 'Article deleted successfully!');
        $this->cancelDelete();
    }

    public function render()
    {
        // Show all published posts from other users (not the current editor)
        // Exclude posts from other editors - only show regular users' posts
        $query = NewsPost::where('user_id', '!=', Auth::id())
            ->where('status', 'published')
            ->with('category', 'author')
            ->whereHas('author', function ($q) {
                $q->where('role', '!=', 'editor');
            });

        // Apply search filter
        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('content', 'like', '%'.$this->search.'%')
                    ->orWhereHas('author', function ($authorQuery) {
                        $authorQuery->where('name', 'like', '%'.$this->search.'%');
                    });
            });
        }

        // Apply category filter
        if (! empty($this->categoryFilter)) {
            $query->where('category_id', $this->categoryFilter);
        }

        $posts = $query->latest('published_at')->paginate(12);

        // Get categories for filter dropdown
        $categories = Category::orderBy('name')->get();

        return view('livewire.approved-articles-list', compact('posts', 'categories'));
    }
}
