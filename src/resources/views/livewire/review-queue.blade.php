<div>
    <!-- Success/Error Messages -->
    @if(session('message'))
        <div class="alert alert-success mb-6 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error mb-6 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Stats Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="stats shadow bg-base-100 border border-base-200">
            <div class="stat">
                <div class="stat-figure text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div class="stat-title">Pending Review</div>
                <div class="stat-value text-primary">{{ $this->stats['total_pending'] }}</div>
                <div class="stat-desc">Articles waiting for approval</div>
            </div>
        </div>
        
        <div class="stats shadow bg-base-100 border border-base-200 md:col-span-2">
            <div class="stat">
                <div class="stat-title mb-2">Pending by Category</div>
                <div class="flex flex-wrap gap-2">
                    @foreach($this->stats['categories_count'] as $stat)
                        <div class="badge badge-lg gap-2 {{ $categoryFilter == $stat->category_id ? 'badge-primary' : 'badge-ghost' }} cursor-pointer hover:scale-105 transition-transform"
                             wire:click="$set('categoryFilter', '{{ $categoryFilter == $stat->category_id ? '' : $stat->category_id }}')">
                            {{ $stat->category->name }}
                            <span class="badge badge-sm badge-outline">{{ $stat->count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @if($posts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($posts as $post)
                <div class="card bg-base-100 shadow-md hover:shadow-2xl transition-all duration-300 group flex flex-col h-full border border-base-200 hover:border-primary">
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
                            <span class="badge badge-warning badge-lg shadow-md font-bold text-white gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pending
                            </span>
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

                        <div class="text-sm text-base-content/60 mb-4 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>{{ $post->user->name }}</span>
                        </div>
                        
                        <div class="mt-auto pt-4 border-t border-base-200 flex items-center justify-between gap-2" onclick="event.stopPropagation()">
                            <!-- Preview Button -->
                            <button wire:click="openPreview({{ $post->id }})" class="btn btn-ghost btn-sm flex-1 gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Preview
                            </button>

                            <!-- Approve Button -->
                            <button wire:click="confirmApprove({{ $post->id }})" class="btn btn-success btn-sm btn-square text-white tooltip" data-tip="Approve">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>

                            <!-- Reject Button -->
                            <button wire:click="confirmReject({{ $post->id }})" class="btn btn-error btn-sm btn-square text-white tooltip" data-tip="Reject">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-2">All caught up!</h3>
                <p class="text-base-content/60 mb-8 max-w-md mx-auto">There are no pending articles to review at the moment.</p>
            </div>
        </div>
    @endif

    <!-- Preview Modal -->
    @if($showPreviewModal && $selectedPost)
        <div class="modal modal-open">
            <div class="modal-box w-11/12 max-w-4xl p-0 overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-base-200 px-6 py-4 flex justify-between items-center border-b border-base-300">
                    <h3 class="font-bold text-lg">Review Article</h3>
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
                                {{ $selectedPost->user->name }}
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

    <!-- Approve Confirmation Modal -->
    @if($showApproveModal)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg text-success flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Approve Article?
                </h3>
                <p class="py-4">Are you sure you want to approve this article? It will be published immediately and visible to all users.</p>
                <div class="modal-action">
                    <button wire:click="cancelApprove" class="btn">Cancel</button>
                    <button wire:click="approve" class="btn btn-success text-white">Yes, Approve & Publish</button>
                </div>
            </div>
            <div class="modal-backdrop" wire:click="cancelApprove"></div>
        </div>
    @endif

    <!-- Reject Confirmation Modal -->
    @if($showRejectModal)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg text-error flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Reject Article?
                </h3>
                <p class="py-4">Are you sure you want to reject this article? The author will be notified and the article will be moved to their rejected list.</p>
                <div class="modal-action">
                    <button wire:click="cancelReject" class="btn">Cancel</button>
                    <button wire:click="reject" class="btn btn-error text-white">Yes, Reject Article</button>
                </div>
            </div>
            <div class="modal-backdrop" wire:click="cancelReject"></div>
        </div>
    @endif
</div>