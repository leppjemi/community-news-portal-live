<div>
    <!-- Success/Error Messages -->
    @if(session('message'))
        <div class="alert alert-success mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Search and Filter -->
    <div class="card bg-base-100 shadow-md mb-6">
        <div class="card-body p-4">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search Input with Join -->
                <div class="join flex-1">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Search by title, content, or author..." 
                           class="input input-bordered join-item w-full">
                    <button class="btn btn-error join-item">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>

                <!-- Category Filter -->
                <div class="form-control w-full md:w-64">
                    <select wire:model.live="categoryFilter" class="select select-bordered w-full">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Clear Filters -->
                @if($search || $categoryFilter)
                    <button wire:click="clearFilters" class="btn btn-ghost btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear Filters
                    </button>
                @endif
            </div>
        </div>
    </div>

    @if($posts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($posts as $post)
                <div class="card bg-base-100 shadow-md hover:shadow-2xl transition-all duration-300 group flex flex-col h-full border-t-4 border-error">
                    <!-- Card Image -->
                    <figure class="relative h-48 overflow-hidden bg-base-200 flex-shrink-0">
                        @if($post->cover_image)
                            <img src="{{ $post->cover_image_url }}" 
                                 alt="{{ $post->title }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                            <div class="hidden w-full h-full items-center justify-center bg-base-200 text-base-content/20 group-hover:bg-base-300 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-base-200 text-base-content/20 group-hover:bg-base-300 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="badge badge-error badge-lg shadow-md font-bold text-white gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Rejected
                            </span>
                        </div>
                    </figure>

                    <!-- Card Body -->
                    <div class="card-body p-5 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50 mb-2">
                            <span class="text-error">{{ $post->category->name }}</span>
                            <span>•</span>
                            <span>{{ $post->updated_at->format('M d, Y') }}</span>
                        </div>
                        
                        <h3 class="card-title text-lg font-bold leading-tight mb-2 group-hover:text-error transition-colors">
                            {{ $post->title }}
                        </h3>

                        <div class="text-sm text-base-content/60 mb-3 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>{{ $post->author->name }}</span>
                        </div>
                        
                        <div class="mt-auto pt-4 border-t border-base-200 flex items-center justify-between">
                            <div class="text-sm text-base-content/60 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ number_format($post->views_count) }}
                            </div>

                            <div class="flex items-center gap-2">
                                <!-- Preview Button -->
                                <button wire:click="openPreview({{ $post->id }})" class="btn btn-circle btn-ghost btn-sm tooltip" data-tip="Preview">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <button wire:click="confirmDelete({{ $post->id }})" class="btn btn-circle btn-ghost btn-sm text-error tooltip" data-tip="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @endif
    @else
        <div class="card bg-base-100 shadow-xl border-2 border-dashed border-base-300">
            <div class="card-body text-center py-16">
                <div class="w-24 h-24 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-2">No rejected articles</h3>
                <p class="text-base-content/60 mb-8 max-w-md mx-auto">There are no rejected articles at the moment.</p>
            </div>
        </div>
    @endif

    <!-- Preview Modal -->
    @if($showPreviewModal && $selectedPost)
        <div class="modal modal-open">
            <div class="modal-box w-11/12 max-w-4xl p-0 overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-base-200 px-6 py-4 flex justify-between items-center border-b border-base-300">
                    <h3 class="font-bold text-lg">Preview Article</h3>
                    <button wire:click="closePreview" class="btn btn-sm btn-circle btn-ghost">✕</button>
                </div>

                <!-- Modal Content -->
                <div class="overflow-y-auto max-h-[70vh] p-6 md:p-10">
                    <div class="max-w-3xl mx-auto">
                        @if($selectedPost->cover_image)
                            <img src="{{ $selectedPost->cover_image_url }}" alt="{{ $selectedPost->title }}"
                                class="w-full h-64 md:h-96 object-cover rounded-2xl shadow-lg mb-8">
                        @endif

                        <div class="flex flex-wrap items-center gap-4 mb-6 text-sm">
                            <span class="badge badge-error badge-lg">{{ $selectedPost->category->name }}</span>
                            <span class="text-base-content/60 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $selectedPost->updated_at->format('F j, Y') }}
                            </span>
                            <span class="text-base-content/60 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $selectedPost->author->name }}
                            </span>
                        </div>

                        <h1 class="text-3xl md:text-4xl font-black mb-8 leading-tight">{{ $selectedPost->title }}</h1>

                        <div class="prose prose-lg max-w-none">
                            {!! nl2br(e($selectedPost->content)) !!}
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-base-200 px-6 py-4 flex justify-end gap-2 border-t border-base-300">
                    <button wire:click="closePreview" class="btn">Close Preview</button>
                </div>
            </div>
            <div class="modal-backdrop" wire:click="closePreview"></div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg text-error flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Delete Rejected Article?
                </h3>
                <p class="py-4">You are about to permanently delete this rejected article. This action cannot be undone. Are you sure?</p>
                <div class="modal-action">
                    <button wire:click="cancelDelete" class="btn">Cancel</button>
                    <button wire:click="deletePost" class="btn btn-error text-white">Yes, Delete Article</button>
                </div>
            </div>
            <div class="modal-backdrop" wire:click="cancelDelete"></div>
        </div>
    @endif
</div>
