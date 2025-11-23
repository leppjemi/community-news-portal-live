<div>
    <!-- Filter Tabs -->
    <div class="tabs tabs-boxed bg-base-200 p-1 mb-8 inline-flex">
        <button wire:click="setFilter('all')" class="tab {{ $filter === 'all' ? 'tab-active bg-primary text-primary-content' : '' }}">All Posts</button>
        <button wire:click="setFilter('published')" class="tab {{ $filter === 'published' ? 'tab-active bg-success text-white' : '' }}">Published</button>
        <button wire:click="setFilter('pending')" class="tab {{ $filter === 'pending' ? 'tab-active bg-warning text-white' : '' }}">Pending Review</button>
        <button wire:click="setFilter('rejected')" class="tab {{ $filter === 'rejected' ? 'tab-active bg-error text-white' : '' }}">Rejected</button>
    </div>

    @if($posts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($posts as $post)
                <div class="card bg-base-100 shadow-md hover:shadow-2xl transition-all duration-300 group flex flex-col h-full">
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
                            @if($post->status === 'published')
                                <span class="badge badge-success badge-lg shadow-md font-bold text-white gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Published
                                </span>
                            @elseif($post->status === 'pending')
                                <span class="badge badge-warning badge-lg shadow-md font-bold text-white gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Pending
                                </span>
                            @elseif($post->status === 'approved')
                                <span class="badge badge-info badge-lg shadow-md font-bold text-white gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Approved
                                </span>
                            @elseif($post->status === 'rejected')
                                <span class="badge badge-error badge-lg shadow-md font-bold text-white gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Rejected
                                </span>
                            @else
                                <span class="badge badge-ghost badge-lg shadow-md font-bold">{{ ucfirst($post->status) }}</span>
                            @endif
                        </div>
                    </figure>

                    <!-- Card Body -->
                    <div class="card-body p-5 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50 mb-2">
                            <span class="text-primary">{{ $post->category->name }}</span>
                            <span>•</span>
                            <span>{{ $post->created_at->format('M d, Y') }}</span>
                        </div>
                        
                        <h3 class="card-title text-lg font-bold leading-tight mb-2 group-hover:text-primary transition-colors">
                            {{ $post->title }}
                        </h3>
                        
                        <div class="mt-auto pt-4 border-t border-base-200 flex items-center justify-between">
                            <div class="text-sm text-base-content/60 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ number_format($post->views_count) }}
                            </div>

                            <div class="flex items-center gap-2">
                                @if($post->status === 'pending' || $post->status === 'rejected')
                                    <button wire:click="openPreview({{ $post->id }})" class="btn btn-circle btn-ghost btn-sm tooltip" data-tip="Preview">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                @else
                                    <a href="{{ route('news.show', $post->slug) }}" class="btn btn-circle btn-ghost btn-sm tooltip" data-tip="View Live" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @endif

                                @if($post->status === 'pending')
                                    <a href="{{ route('user.edit-news', $post->id) }}" class="btn btn-circle btn-ghost btn-sm tooltip" data-tip="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                @endif

                                @if($post->status === 'pending' || $post->status === 'rejected')
                                    <button wire:click="confirmDelete({{ $post->id }})" 
                                            class="btn btn-circle btn-ghost btn-sm text-error tooltip" data-tip="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif

                                @if($post->status === 'rejected')
                                    <a href="{{ route('user.edit-news', $post->id) }}" class="btn btn-circle btn-ghost btn-sm text-warning tooltip" data-tip="Edit & Resubmit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                @endif
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
                <h3 class="text-2xl font-bold mb-2">No submissions yet</h3>
                <p class="text-base-content/60 mb-8 max-w-md mx-auto">You haven't submitted any news articles yet. Share your first story with the community today!</p>
                <a href="{{ route('user.submit-news') }}" class="btn btn-primary btn-lg shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Submit Your First News
                </a>
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
                            <span class="badge badge-primary badge-lg">{{ $selectedPost->category->name }}</span>
                            <span class="text-base-content/60 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $selectedPost->created_at->format('F j, Y') }}
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
                    @if($selectedPost->status === 'pending')
                        <a href="{{ route('user.edit-news', $selectedPost->id) }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Article
                        </a>
                    @endif
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
                    Confirm Deletion
                </h3>
                <p class="py-4">Are you sure you want to delete this post? This action cannot be undone.</p>
                <div class="modal-action">
                    <button wire:click="cancelDelete" class="btn">Cancel</button>
                    <button wire:click="deletePost" class="btn btn-error text-white">Delete</button>
                </div>
            </div>
            <div class="modal-backdrop" wire:click="cancelDelete"></div>
        </div>
    @endif

</div>