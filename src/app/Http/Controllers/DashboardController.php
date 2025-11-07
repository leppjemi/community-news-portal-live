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
            $stats = [
                'totalUsers' => User::count(),
                'adminUsers' => User::where('role', 'admin')->count(),
                'editorUsers' => User::where('role', 'editor')->count(),
                'regularUsers' => User::where('role', 'user')->count(),
                'totalCategories' => Category::count(),
                'totalPosts' => NewsPost::count(),
                'publishedPosts' => NewsPost::where('status', 'published')->count(),
                'approvedPosts' => NewsPost::where('status', 'approved')->count(),
                'pendingPosts' => NewsPost::where('status', 'pending')->count(),
                'totalViews' => NewsPost::sum('views_count'),
                'totalShares' => SocialShareClick::count(),
            ];

            return view('admin.dashboard', compact('stats'));
        }

        // Regular user dashboard
        return view('dashboard', compact('user'));
    }
}
