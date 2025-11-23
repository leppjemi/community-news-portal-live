<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        try {
            $categories = Category::latest()->paginate(15);
            return view('admin.categories.index', compact('categories'));
        } catch (\Exception $e) {
            \Log::error('Error loading categories: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Unable to load categories. Please try again.');
        }
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            Category::create($request->validated());
            Cache::forget('categories.all');
            return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            \Log::error('Error creating category: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create category. Please try again.');
        }
    }

    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(StoreCategoryRequest $request, Category $category)
    {
        try {
            $category->update($request->validated());
            Cache::forget('categories.all');
            return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Error updating category: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update category. Please try again.');
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            Cache::forget('categories.all');
            return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Error deleting category: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete category. It may be in use by existing posts.');
        }
    }
}
