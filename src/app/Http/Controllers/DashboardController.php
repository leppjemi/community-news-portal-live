<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\NewsPost;
use App\Models\SocialShareClick;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // If user is admin, show admin dashboard
        if ($user->isAdmin()) {
            try {
                $stats = [
                    'totalUsers' => User::count(),
                    'adminUsers' => User::where('role', 'admin')->count(),
                    'editorUsers' => User::where('role', 'editor')->count(),
                    'regularUsers' => User::where('role', 'user')->count(),
                    'totalCategories' => Category::count(),
                    'totalPosts' => NewsPost::count(),
                    'publishedPosts' => NewsPost::where('status', 'published')->count(),
                    'approvedPosts' => NewsPost::where('status', 'published')->count(), // Same as published since approval publishes
                    'pendingPosts' => NewsPost::where('status', 'pending')->count(),
                    'totalViews' => NewsPost::sum('views_count'),
                    'totalShares' => SocialShareClick::count(),
                ];

                return view('admin.dashboard', compact('stats'));
            } catch (\Exception $e) {
                \Log::error('Error loading admin dashboard: '.$e->getMessage());

                return view('admin.dashboard', ['stats' => []])->with('error', 'Unable to load dashboard statistics.');
            }
        }

        // If user is editor, show editor dashboard
        if ($user->isEditor()) {
            return redirect()->route('editor.dashboard');
        }

        // Regular user - redirect to user dashboard
        return redirect()->route('user.dashboard');
    }
}
