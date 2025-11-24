<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrackSocialShareRequest;
use App\Models\Category;
use App\Models\SocialShareClick;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SocialShareController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin'])->only('analytics');
    }

    /**
     * Track a social share click.
     */
    public function track(TrackSocialShareRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $click = SocialShareClick::create([
                'platform' => $validated['platform'],
                'page_url' => $validated['page_url'],
                'page_type' => $validated['page_type'] ?? null,
                'news_post_id' => $validated['news_post_id'] ?? null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Share tracked successfully',
                'data' => $click,
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error tracking social share: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to track share',
            ], 500);
        }
    }

    /**
     * Display analytics dashboard for admin.
     */
    public function analytics(Request $request)
    {
        try {
            // Get filter parameters
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $platform = $request->input('platform');
            $pageType = $request->input('page_type');
            $categoryId = $request->input('category_id');

            // Build base query with filters (reused for all queries)
            $baseQuery = function ($query) use ($startDate, $endDate, $platform, $pageType, $categoryId) {
                if ($startDate) {
                    $query->byDateRange($startDate, null);
                }
                if ($endDate) {
                    $query->byDateRange(null, $endDate);
                }
                if ($platform && $platform !== 'all') {
                    $query->byPlatform($platform);
                }
                if ($pageType && $pageType !== 'all') {
                    $query->byPageType($pageType);
                }
                if ($categoryId && $categoryId !== 'all') {
                    $query->whereHas('newsPost', function ($q) use ($categoryId) {
                        $q->where('category_id', $categoryId);
                    });
                }
            };

            // Get total shares
            $totalSharesQuery = SocialShareClick::query();
            $baseQuery($totalSharesQuery);
            $totalShares = $totalSharesQuery->count();

            // Get shares by platform
            $sharesByPlatformQuery = SocialShareClick::query();
            $baseQuery($sharesByPlatformQuery);
            $sharesByPlatform = $sharesByPlatformQuery
                ->select('platform', DB::raw('count(*) as count'))
                ->groupBy('platform')
                ->orderByDesc('count')
                ->get()
                ->pluck('count', 'platform')
                ->toArray();

            // Get shares over time (grouped by date)
            $sharesOverTimeQuery = SocialShareClick::query();
            $baseQuery($sharesOverTimeQuery);
            $sharesOverTime = $sharesOverTimeQuery
                ->select(DB::raw('DATE(social_share_clicks.created_at) as date'), DB::raw('count(*) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->date => $item->count];
                })
                ->toArray();

            // Get top shared pages with news post details
            $topPagesQuery = SocialShareClick::query();
            $baseQuery($topPagesQuery);
            $topPages = $topPagesQuery
                ->select('page_url', 'page_type', 'news_post_id', DB::raw('count(*) as share_count'))
                ->with(['newsPost:id,slug,title,category_id', 'newsPost.category:id,name'])
                ->groupBy('page_url', 'page_type', 'news_post_id')
                ->orderByDesc('share_count')
                ->limit(20)
                ->get()
                ->map(function ($item) {
                    // Generate dynamic URL based on content type
                    $url = $item->page_url; // Fallback
    
                    if ($item->newsPost) {
                        $url = route('news.show', $item->newsPost->slug);
                    } elseif ($item->page_type === 'home') {
                        $url = route('home');
                    }

                    return [
                        'page_url' => $url, // Use the dynamic URL
                        'original_url' => $item->page_url, // Keep original for reference if needed
                        'share_count' => $item->share_count,
                        'title' => $item->newsPost ? $item->newsPost->title : 'Home Page',
                        'category' => $item->newsPost && $item->newsPost->category ? $item->newsPost->category->name : null,
                    ];
                });

            // Get shares by page type
            $sharesByPageTypeQuery = SocialShareClick::query();
            $baseQuery($sharesByPageTypeQuery);
            $sharesByPageType = $sharesByPageTypeQuery
                ->select('page_type', DB::raw('count(*) as count'))
                ->groupBy('page_type')
                ->get()
                ->pluck('count', 'page_type')
                ->toArray();

            // Get shares by category
            $sharesByCategoryQuery = SocialShareClick::query();
            // Apply date filters manually with table prefix before joins
            if ($startDate) {
                $sharesByCategoryQuery->whereDate('social_share_clicks.created_at', '>=', $startDate);
            }
            if ($endDate) {
                $sharesByCategoryQuery->whereDate('social_share_clicks.created_at', '<=', $endDate);
            }
            if ($platform && $platform !== 'all') {
                $sharesByCategoryQuery->where('social_share_clicks.platform', $platform);
            }
            if ($pageType && $pageType !== 'all') {
                $sharesByCategoryQuery->where('social_share_clicks.page_type', $pageType);
            }
            if ($categoryId && $categoryId !== 'all') {
                $sharesByCategoryQuery->whereHas('newsPost', function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            }
            $sharesByCategory = $sharesByCategoryQuery
                ->whereNotNull('news_post_id')
                ->join('news_posts', 'social_share_clicks.news_post_id', '=', 'news_posts.id')
                ->join('categories', 'news_posts.category_id', '=', 'categories.id')
                ->select('categories.name as category_name', DB::raw('count(*) as count'))
                ->groupBy('categories.id', 'categories.name')
                ->orderByDesc('count')
                ->get()
                ->pluck('count', 'category_name')
                ->toArray();

            // Pivot: Platform vs Category
            $platformCategoryPivotQuery = SocialShareClick::query();
            // Apply date filters manually with table prefix before joins
            if ($startDate) {
                $platformCategoryPivotQuery->whereDate('social_share_clicks.created_at', '>=', $startDate);
            }
            if ($endDate) {
                $platformCategoryPivotQuery->whereDate('social_share_clicks.created_at', '<=', $endDate);
            }
            if ($platform && $platform !== 'all') {
                $platformCategoryPivotQuery->where('social_share_clicks.platform', $platform);
            }
            if ($pageType && $pageType !== 'all') {
                $platformCategoryPivotQuery->where('social_share_clicks.page_type', $pageType);
            }
            if ($categoryId && $categoryId !== 'all') {
                $platformCategoryPivotQuery->whereHas('newsPost', function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            }
            $platformCategoryPivot = $platformCategoryPivotQuery
                ->whereNotNull('news_post_id')
                ->join('news_posts', 'social_share_clicks.news_post_id', '=', 'news_posts.id')
                ->join('categories', 'news_posts.category_id', '=', 'categories.id')
                ->select('social_share_clicks.platform', 'categories.name as category_name', DB::raw('count(*) as count'))
                ->groupBy('social_share_clicks.platform', 'categories.id', 'categories.name')
                ->get()
                ->groupBy('platform')
                ->map(function ($platformGroup) {
                    return $platformGroup->pluck('count', 'category_name')->toArray();
                })
                ->toArray();

            // Pivot: Platform vs Page Type
            $platformPageTypePivotQuery = SocialShareClick::query();
            $baseQuery($platformPageTypePivotQuery);
            $platformPageTypePivot = $platformPageTypePivotQuery
                ->select('platform', 'page_type', DB::raw('count(*) as count'))
                ->groupBy('platform', 'page_type')
                ->get()
                ->groupBy('platform')
                ->map(function ($platformGroup) {
                    return $platformGroup->pluck('count', 'page_type')->toArray();
                })
                ->toArray();

            // Pivot: Category vs Platform (reverse view)
            $categoryPlatformPivotQuery = SocialShareClick::query();
            // Apply date filters manually with table prefix before joins
            if ($startDate) {
                $categoryPlatformPivotQuery->whereDate('social_share_clicks.created_at', '>=', $startDate);
            }
            if ($endDate) {
                $categoryPlatformPivotQuery->whereDate('social_share_clicks.created_at', '<=', $endDate);
            }
            if ($platform && $platform !== 'all') {
                $categoryPlatformPivotQuery->where('social_share_clicks.platform', $platform);
            }
            if ($pageType && $pageType !== 'all') {
                $categoryPlatformPivotQuery->where('social_share_clicks.page_type', $pageType);
            }
            if ($categoryId && $categoryId !== 'all') {
                $categoryPlatformPivotQuery->whereHas('newsPost', function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            }
            $categoryPlatformPivot = $categoryPlatformPivotQuery
                ->whereNotNull('news_post_id')
                ->join('news_posts', 'social_share_clicks.news_post_id', '=', 'news_posts.id')
                ->join('categories', 'news_posts.category_id', '=', 'categories.id')
                ->select('categories.name as category_name', 'social_share_clicks.platform', DB::raw('count(*) as count'))
                ->groupBy('categories.id', 'categories.name', 'social_share_clicks.platform')
                ->get()
                ->groupBy('category_name')
                ->map(function ($categoryGroup) {
                    return $categoryGroup->pluck('count', 'platform')->toArray();
                })
                ->toArray();

            // Get all categories for filter dropdown
            $categories = Category::orderBy('name')->get();

            return view('admin.analytics', compact(
                'totalShares',
                'sharesByPlatform',
                'sharesOverTime',
                'topPages',
                'sharesByPageType',
                'sharesByCategory',
                'platformCategoryPivot',
                'platformPageTypePivot',
                'categoryPlatformPivot',
                'categories',
                'startDate',
                'endDate',
                'platform',
                'pageType',
                'categoryId'
            ));
        } catch (\Exception $e) {
            \Log::error('Error loading analytics: ' . $e->getMessage());

            return back()->with('error', 'Failed to load analytics data. Please try again.');
        }
    }
}
