<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = NewsPost::where('status', 'published')
                ->with(['user', 'category'])
                ->latest('published_at');

            // Search
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            }

            // Category filter
            if ($request->filled('category')) {
                $query->where('category_id', $request->get('category'));
            }

            $posts = $query->paginate(12);

            // Cache categories for 1 hour (3600 seconds) - they rarely change
            $categories = Cache::remember('categories.all', 3600, function () {
                return Category::orderBy('name')->get();
            });

            return view('home', compact('posts', 'categories'));
        } catch (\Exception $e) {
            \Log::error('Error loading home page: '.$e->getMessage());
            $posts = collect();
            $categories = collect();

            return view('home', compact('posts', 'categories'))->with('error', 'Unable to load news posts. Please try again later.');
        }
    }

    public function show($slug)
    {
        try {
            $post = NewsPost::where('slug', $slug)
                ->where('status', 'published')
                ->with(['user', 'category'])
                ->firstOrFail();

            $post->incrementViews();

            return view('news.show', compact('post'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'News article not found.');
        } catch (\Exception $e) {
            \Log::error('Error showing news post: '.$e->getMessage());
            abort(500, 'Unable to load this news article.');
        }
    }
}
