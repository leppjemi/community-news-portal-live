<?php

namespace App\Livewire;

use App\Models\NewsPost;
use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewQueue extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $selectedPost = null;
    public $showPreviewModal = false;
    public $categoryFilter = '';

    public $showApproveModal = false;
    public $showRejectModal = false;
    public $postToApproveId = null;
    public $postToRejectId = null;

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function getStatsProperty()
    {
        return [
            'total_pending' => NewsPost::where('status', 'pending')->count(),
            'categories_count' => NewsPost::where('status', 'pending')
                ->selectRaw('category_id, count(*) as count')
                ->groupBy('category_id')
                ->with('category')
                ->get()
        ];
    }

    public function openPreview($postId)
    {
        $this->selectedPost = NewsPost::with('category', 'user')->find($postId);
        $this->showPreviewModal = true;
    }

    public function closePreview()
    {
        $this->showPreviewModal = false;
        $this->selectedPost = null;
    }

    public function confirmApprove($postId)
    {
        $this->postToApproveId = $postId;
        $this->showApproveModal = true;
    }

    public function cancelApprove()
    {
        $this->showApproveModal = false;
        $this->postToApproveId = null;
    }

    public function confirmReject($postId)
    {
        $this->postToRejectId = $postId;
        $this->showRejectModal = true;
    }

    public function cancelReject()
    {
        $this->showRejectModal = false;
        $this->postToRejectId = null;
    }

    public function approve()
    {
        if (!$this->postToApproveId) {
            return;
        }

        try {
            $post = NewsPost::findOrFail($this->postToApproveId);
            Gate::authorize('approve', $post);

            $post->status = 'published';
            $post->published_at = now();
            $post->save();

            session()->flash('message', 'Post approved and published successfully!');
            $this->cancelApprove();
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            session()->flash('error', 'You do not have permission to approve this post.');
            $this->cancelApprove();
        } catch (\Exception $e) {
            \Log::error('Error approving post: ' . $e->getMessage());
            session()->flash('error', 'Failed to approve post. Please try again.');
            $this->cancelApprove();
        }
    }

    public function reject()
    {
        if (!$this->postToRejectId) {
            return;
        }

        try {
            $post = NewsPost::findOrFail($this->postToRejectId);
            Gate::authorize('reject', $post);

            $post->status = 'rejected';
            $post->save();

            session()->flash('message', 'Post rejected.');
            $this->cancelReject();
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            session()->flash('error', 'You do not have permission to reject this post.');
            $this->cancelReject();
        } catch (\Exception $e) {
            \Log::error('Error rejecting post: ' . $e->getMessage());
            session()->flash('error', 'Failed to reject post. Please try again.');
            $this->cancelReject();
        }
    }

    public function render()
    {
        try {
            $query = NewsPost::where('status', 'pending')
                ->with(['user', 'category'])
                ->latest();

            if ($this->categoryFilter) {
                $query->where('category_id', $this->categoryFilter);
            }

            $posts = $query->paginate(10);
            $categories = Category::orderBy('name')->get();

            return view('livewire.review-queue', compact('posts', 'categories'));
        } catch (\Exception $e) {
            \Log::error('Error loading review queue: ' . $e->getMessage());
            $posts = collect();
            $categories = collect();
            return view('livewire.review-queue', compact('posts', 'categories'));
        }
    }
}
