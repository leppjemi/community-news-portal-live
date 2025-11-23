@extends('layouts.admin')

@section('title', 'Manage Categories')

@section('breadcrumbs')
    <li class="text-base-content/70">Categories</li>
@endsection

@section('content')
    <div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h1 class="text-3xl lg:text-4xl font-bold mb-2">Categories</h1>
            <p class="text-base-content/70">Manage news categories and organization</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create Category
        </a>
    </div>

    <div class="card bg-base-100 shadow-lg border border-base-300">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table table-zebra table-hover">
                    <thead>
                        <tr class="bg-base-200">
                            <th class="font-semibold">Name</th>
                            <th class="font-semibold">Slug</th>
                            <th class="font-semibold">Description</th>
                            <th class="font-semibold">Created</th>
                            <th class="text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr class="hover">
                                <td>
                                    <div class="font-semibold">{{ $category->name }}</div>
                                </td>
                                <td>
                                    <code class="text-xs bg-base-200 px-2 py-1 rounded">{{ $category->slug }}</code>
                                </td>
                                <td>
                                    <span
                                        class="text-sm text-base-content/70">{{ Str::limit($category->description, 50) }}</span>
                                </td>
                                <td>
                                    <span class="text-sm">{{ $category->created_at->format('M d, Y') }}</span>
                                </td>
                                <td>
                                    <div class="flex gap-2 justify-end">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                            class="btn btn-sm btn-outline gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this category?')"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-error gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-16">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="p-4 rounded-full bg-base-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-base-content/50"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-lg font-semibold mb-2">No categories yet</p>
                                            <p class="text-base-content/70 mb-4">Get started by creating your first category</p>
                                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4" />
                                                </svg>
                                                Create Category
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
        <div class="mt-8">
            {{ $categories->links() }}
        </div>
    @endif
@endsection