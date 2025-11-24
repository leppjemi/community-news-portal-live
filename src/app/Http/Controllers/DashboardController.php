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

    public function index(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();

        // If user is admin, show admin dashboard
        if ($user->isAdmin()) {
            try {
                $range = $request->get('range', 'last_7_days');
                $dates = $this->getDateRange($range);

                $stats = [
                    'totalUsers' => User::whereBetween('created_at', $dates)->count(),
                    'totalPosts' => NewsPost::whereBetween('created_at', $dates)->count(),
                    'totalViews' => NewsPost::whereBetween('updated_at', $dates)->sum('views_count'),
                    'totalShares' => SocialShareClick::whereBetween('created_at', $dates)->count(),
                    'pendingPosts' => NewsPost::where('status', 'pending')->count(),
                    'publishedPosts' => NewsPost::where('status', 'published')->count(),
                    'rejectedPosts' => NewsPost::where('status', 'rejected')->count(),
                    'adminUsers' => User::where('role', 'admin')->count(),
                    'editorUsers' => User::where('role', 'editor')->count(),
                    'regularUsers' => User::where('role', 'user')->count(),
                    'totalCategories' => Category::count(),
                ];

                $charts = [
                    'contentTrend' => $this->getChartData(NewsPost::class, $dates, 'published_at'),
                ];

                return view('admin.dashboard', compact('stats', 'charts', 'range'));
            } catch (\Exception $e) {
                \Log::error('Error loading admin dashboard: '.$e->getMessage());

                return view('admin.dashboard', ['stats' => [], 'charts' => [], 'range' => 'last_7_days'])->with('error', 'Unable to load dashboard statistics.');
            }
        }

        // If user is editor, show editor dashboard
        if ($user->isEditor()) {
            return redirect()->route('editor.dashboard');
        }

        // Regular user - redirect to user dashboard
        return redirect()->route('user.dashboard');
    }

    private function getDateRange($range)
    {
        $now = \Carbon\Carbon::now();
        switch ($range) {
            case 'this_month':
                return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
            case 'last_month':
                return [$now->copy()->subMonth()->startOfMonth(), $now->copy()->subMonth()->endOfMonth()];
            case 'last_6_months':
                return [$now->copy()->subMonths(6), $now];
            case 'this_year':
                return [$now->copy()->startOfYear(), $now->copy()->endOfYear()];
            case 'last_7_days':
            default:
                return [$now->copy()->subDays(7), $now];
        }
    }

    private function getChartData($model, $range, $dateColumn, $valueColumn = null, $aggregate = 'count')
    {
        $data = $model::whereBetween($dateColumn, $range)
            ->selectRaw("DATE($dateColumn) as date, ".($valueColumn ? "$aggregate($valueColumn)" : 'COUNT(*)').' as value')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $values = [];

        // Fill in missing dates
        $period = \Carbon\CarbonPeriod::create($range[0], $range[1]);
        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->format('M d');
            $record = $data->firstWhere('date', $dateString);
            $values[] = $record ? $record->value : 0;
        }

        return ['labels' => $labels, 'data' => $values];
    }
}
