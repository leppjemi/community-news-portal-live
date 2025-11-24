<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:user']);
    }

    /**
     * Show user dashboard
     */
    public function index()
    {
        try {
            $user = Auth::user();

            // Get user statistics
            $totalSubmissions = $user->newsPosts()->count();
            $publishedCount = $user->newsPosts()->where('status', 'published')->count();
            $pendingCount = $user->newsPosts()->where('status', 'pending')->count();
            $totalViews = $user->newsPosts()->sum('views_count');

            // Get recent submissions
            $recentSubmissions = $user->newsPosts()
                ->where('status', 'published')
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();

            return view('user.dashboard', compact(
                'user',
                'totalSubmissions',
                'publishedCount',
                'pendingCount',
                'totalViews',
                'recentSubmissions'
            ));
        } catch (\Exception $e) {
            \Log::error('Error loading user dashboard: '.$e->getMessage());

            return back()->with('error', 'Failed to load dashboard. Please try again.');
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $user->update([
                'name' => $request->name,
            ]);

            return redirect()->route('user.settings')
                ->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Error updating profile: '.$e->getMessage());

            return back()->withInput()->with('error', 'Failed to update profile. Please try again.');
        }
    }

    /**
     * Show user settings page
     */
    public function settings()
    {
        $user = Auth::user();

        return view('user.settings', compact('user'));
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        try {
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

            return redirect()->route('user.settings')
                ->with('success', 'Password updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Error updating password: '.$e->getMessage());

            return back()->with('error', 'Failed to update password. Please try again.');
        }
    }

    /**
     * Show user submissions
     */
    public function submissions()
    {
        return view('user.submissions');
    }

    /**
     * Show submit news form
     */
    public function submitNews()
    {
        return view('user.submit-news');
    }

    /**
     * Show edit news form
     */
    public function editNews($id)
    {
        $user = Auth::user();

        // Ensure user can only edit their own posts
        $post = NewsPost::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        // Check if post is pending or rejected
        if (! in_array($post->status, ['pending', 'rejected'])) {
            return redirect()->route('user.submissions')
                ->with('error', 'You can only edit posts that are pending review or rejected.');
        }

        return view('user.submit-news', ['postId' => $id]);
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
