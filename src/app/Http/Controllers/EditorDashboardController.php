<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class EditorDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:editor']);
    }

    /**
     * Show editor dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Get editor statistics
        $totalSubmissions = $user->newsPosts()->count();
        $publishedCount = $user->newsPosts()->where('status', 'published')->count();
        $pendingCount = $user->newsPosts()->where('status', 'pending')->count();
        $totalViews = $user->newsPosts()->sum('views_count');

        // Get pending reviews from other users (for review queue)
        $pendingReviews = NewsPost::where('status', 'pending')
            ->where('user_id', '!=', $user->id)
            ->count();

        // Get recent submissions
        $recentSubmissions = $user->newsPosts()
            ->where('status', 'published')
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('editor.dashboard', compact(
            'user',
            'totalSubmissions',
            'publishedCount',
            'pendingCount',
            'totalViews',
            'pendingReviews',
            'recentSubmissions'
        ));
    }

    /**
     * Update editor profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user->update([
            'name' => $request->name,
        ]);

        return redirect()->route('editor.settings')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show editor settings page
     */
    public function settings()
    {
        $user = Auth::user();

        return view('editor.settings', compact('user'));
    }

    /**
     * Update editor password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $user = Auth::user();

        // Verify current password
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('editor.settings')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Show editor submissions
     */
    public function submissions()
    {
        return view('editor.submissions');
    }

    /**
     * Show submit news form
     */
    public function submitNews()
    {
        return view('editor.submit-news');
    }

    /**
     * Show edit news form (for editor's own posts)
     */
    public function editNews($id)
    {
        $user = Auth::user();

        // Ensure editor can only edit their own posts
        $post = NewsPost::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        return view('editor.edit-news', ['postId' => $id]);
    }

    /**
     * Show edit form for approved articles from other users
     */
    public function editApprovedArticle($id)
    {
        $user = Auth::user();

        // Ensure the post is NOT owned by the current editor
        // and the author is NOT another editor (only regular users' posts can be edited)
        $post = NewsPost::where('id', $id)
            ->where('user_id', '!=', $user->id)
            ->where('status', 'published')
            ->with('author')
            ->firstOrFail();

        // Check if the post author is an editor - if so, deny access
        if ($post->author->isEditor()) {
            abort(403, 'You cannot edit articles published by other editors.');
        }

        return view('editor.edit-approved-article', ['postId' => $id]);
    }

    /**
     * Show all approved articles from other users
     */
    public function approvedArticles()
    {
        return view('editor.approved-articles');
    }

    public function rejectedArticles()
    {
        return view('editor.rejected-articles');
    }

    /**
     * Check if the provided password matches the current user's password
     */
    public function checkPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = Auth::user();

        if (Hash::check($request->password, $user->password)) {
            return response()->json(['valid' => true]);
        }

        return response()->json(['valid' => false]);
    }
}
