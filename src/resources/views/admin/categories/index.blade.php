@extends('layouts.admin')

@section('title', 'Categories')

@section('breadcrumbs')
    <li>Categories</li>
@endsection

@section('content')
    <div class="card bg-base-100 shadow-xl" x-data="{ showDeleteModal: false, deleteUrl: '' }">
        <div class="card-body">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="card-title">Category Management</h2>
                    <p class="text-base-content/70 text-sm">Manage news categories and organization</p>
                </div>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Category
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Posts</th>
                            <th>Created</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr class="hover">
                                <td class="font-bold">
                                    {{ $category->name }}
                                </td>
                                <td>
                                    <div class="badge badge-ghost font-mono">{{ $category->slug }}</div>
                                </td>
                                <td class="max-w-xs truncate text-base-content/70">
                                    {{ Str::limit($category->description, 50) }}
                                </td>
                                <td>
                                    <div
                                        class="badge {{ $category->news_posts_count > 0 ? 'badge-secondary' : 'badge-ghost' }}">
                                        {{ $category->news_posts_count }}
                                    </div>
                                </td>
                                <td class="text-sm">
                                    {{ $category->created_at->format('M d, Y') }}
                                </td>
                                <td>
                                    <div class="flex gap-2 justify-end">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-ghost btn-xs">
                                            Edit
                                        </a>
                                        @if($category->news_posts_count > 0)
                                            <div class="tooltip tooltip-left"
                                                data-tip="{{ $category->news_posts_count }} linked articles">
                                                <button class="btn btn-ghost btn-xs text-base-content/30 cursor-not-allowed"
                                                    disabled>
                                                    Delete
                                                </button>
                                            </div>
                                        @else
                                            <button
                                                @click="showDeleteModal = true; deleteUrl = '{{ route('admin.categories.destroy', $category) }}'"
                                                class="btn btn-ghost btn-xs text-error hover:bg-error/10">
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="p-4 rounded-full bg-base-200 text-base-content/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold">No categories found</p>
                                            <p class="text-sm text-base-content/70 mb-4">Get started by creating your first
                                                category</p>
                                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
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

            @if($categories->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        <dialog class="modal modal-bottom sm:modal-middle" :class="{ 'modal-open': showDeleteModal }">
            <div class="modal-box">
                <h3 class="font-bold text-lg text-error">Delete Category</h3>
                <p class="py-4">Are you sure you want to delete this category? This action cannot be undone.</p>
                <div class="modal-action">
                    <button class="btn btn-ghost" @click="showDeleteModal = false">Cancel</button>
                    <form :action="deleteUrl" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-error">Delete</button>
                    </form>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button @click="showDeleteModal = false">close</button>
            </form>
        </dialog>
    </div>
@endsection