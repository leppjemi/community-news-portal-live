@extends('layouts.user')

@section('title', 'My Dashboard')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Hero Section -->
        <div class="relative overflow-hidden rounded-3xl bg-primary text-primary-content shadow-2xl mb-12">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-64 h-64 bg-black opacity-10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 p-8 md:p-12">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-black mb-4 tracking-tight">
                            Welcome back,<br/>{{ $user->name }}! 👋
                        </h1>
                        <p class="text-lg opacity-90 max-w-xl font-medium">
                            Ready to share your next big story? Your dashboard is ready for you.
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <a href="{{ route('user.submit-news') }}" class="btn btn-lg btn-surface text-primary border-0 shadow-lg hover:scale-105 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create New Post
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Stats & Submissions (8 cols) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Stats Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Total Submissions -->
                    <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-all duration-300 border-b-4 border-primary">
                        <div class="card-body p-4 items-center text-center">
                            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mb-2 text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                            <div class="text-3xl font-bold">{{ $totalSubmissions }}</div>
                            <div class="text-xs uppercase tracking-wider font-semibold opacity-60">Total Posts</div>
                        </div>
                    </div>

                    <!-- Published -->
                    <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-all duration-300 border-b-4 border-success">
                        <div class="card-body p-4 items-center text-center">
                            <div class="w-12 h-12 rounded-full bg-success/10 flex items-center justify-center mb-2 text-success">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="text-3xl font-bold">{{ $publishedCount }}</div>
                            <div class="text-xs uppercase tracking-wider font-semibold opacity-60">Published</div>
                        </div>
                    </div>

                    <!-- Pending -->
                    <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-all duration-300 border-b-4 border-warning">
                        <div class="card-body p-4 items-center text-center">
                            <div class="w-12 h-12 rounded-full bg-warning/10 flex items-center justify-center mb-2 text-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="text-3xl font-bold">{{ $pendingCount }}</div>
                            <div class="text-xs uppercase tracking-wider font-semibold opacity-60">Pending</div>
                        </div>
                    </div>

                    <!-- Views -->
                    <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-all duration-300 border-b-4 border-info">
                        <div class="card-body p-4 items-center text-center">
                            <div class="w-12 h-12 rounded-full bg-info/10 flex items-center justify-center mb-2 text-info">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <div class="text-3xl font-bold">{{ number_format($totalViews) }}</div>
                            <div class="text-xs uppercase tracking-wider font-semibold opacity-60">Total Views</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Submissions -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold flex items-center gap-2">
                            <span class="w-2 h-8 bg-secondary rounded-full"></span>
                            Recent Articles
                        </h2>
                        <a href="{{ route('user.submissions') }}" class="btn btn-ghost btn-sm group">
                            View All
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>

                    @if($recentSubmissions->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($recentSubmissions as $post)
                                <div class="card bg-base-100 shadow-md hover:shadow-2xl transition-all duration-300 group">
                                    <figure class="relative h-48 overflow-hidden bg-base-200">
                                        @if($post->cover_image)
                                            <img src="{{ str_starts_with($post->cover_image, 'http') ? $post->cover_image : Storage::url($post->cover_image) }}" 
                                                 alt="{{ $post->title }}" 
                                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-base-200 text-base-content/20 group-hover:bg-base-300 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <div class="absolute top-3 right-3">
                                            @if($post->status === 'published')
                                                <span class="badge badge-success badge-lg shadow-md font-bold text-white">Published</span>
                                            @elseif($post->status === 'pending')
                                                <span class="badge badge-warning badge-lg shadow-md font-bold text-white">Pending</span>
                                            @else
                                                <span class="badge badge-ghost badge-lg shadow-md font-bold">{{ ucfirst($post->status) }}</span>
                                            @endif
                                        </div>
                                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                                            <div class="flex items-center text-white/90 text-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $post->created_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </figure>
                                    <div class="card-body p-6">
                                        <h3 class="card-title text-lg font-bold line-clamp-2 group-hover:text-primary transition-colors">
                                            {{ $post->title }}
                                        </h3>
                                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-base-200">
                                            <div class="flex items-center text-base-content/60 text-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                {{ number_format($post->views_count) }} views
                                            </div>
                                            @if($post->status === 'pending')
                                                <a href="{{ route('user.edit-news', $post->id) }}" class="btn btn-sm btn-ghost text-primary">Edit</a>
                                            @else
                                                <a href="{{ route('news.show', $post->slug) }}" class="btn btn-sm btn-ghost text-primary">View</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="card bg-base-100 shadow-xl border-2 border-dashed border-base-300">
                            <div class="card-body text-center py-12">
                                <div class="w-20 h-20 bg-base-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold mb-2">No articles yet</h3>
                                <p class="text-base-content/60 mb-6 max-w-md mx-auto">You haven't submitted any news articles yet. Share your first story with the community today!</p>
                                <a href="{{ route('user.submit-news') }}" class="btn btn-primary">
                                    Start Writing
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Quick Actions & Profile (4 cols) -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Quick Actions -->
                <div class="card bg-base-100 shadow-xl overflow-hidden">
                    <div class="bg-base-200/50 p-4 border-b border-base-200">
                        <h2 class="font-bold text-lg flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Command Center
                        </h2>
                    </div>
                    <div class="card-body p-0">
                        <div class="grid grid-cols-1 divide-y divide-base-200">
                            <a href="{{ route('user.submit-news') }}" class="flex items-center gap-4 p-4 hover:bg-base-200 transition-colors group">
                                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-bold">Submit New Article</div>
                                    <div class="text-xs text-base-content/60">Share a story with the community</div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>

                            <a href="{{ route('user.submissions') }}" class="flex items-center gap-4 p-4 hover:bg-base-200 transition-colors group">
                                <div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-bold">Manage Submissions</div>
                                    <div class="text-xs text-base-content/60">View and edit your posts</div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>

                            <a href="{{ route('user.settings') }}" class="flex items-center gap-4 p-4 hover:bg-base-200 transition-colors group">
                                <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center text-accent group-hover:bg-accent group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-bold">Edit Profile</div>
                                    <div class="text-xs text-base-content/60">Update your personal info</div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-base-content/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="card bg-base-100 shadow-xl border border-base-300">
                    <div class="card-body">
                        <h3 class="font-bold text-lg mb-2">Writing Tips 💡</h3>
                        <ul class="space-y-3 text-sm text-base-content/80">
                            <li class="flex gap-2">
                                <span class="text-success">✓</span>
                                <span>Use a catchy headline to grab attention.</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="text-success">✓</span>
                                <span>Include high-quality images with your posts.</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="text-success">✓</span>
                                <span>Keep your paragraphs short and readable.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection