@extends('layouts.app')

@section('title', 'Home - Community News Portal')

@section('content')
<div x-data="{ 
    scrollProgress: 0,
    updateScrollProgress() {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        this.scrollProgress = (winScroll / height) * 100;
    }
}" 
x-init="window.addEventListener('scroll', () => updateScrollProgress())"
class="min-h-screen bg-base-100">

    <!-- Full Width Hero Section -->
    <div class="relative h-screen w-full overflow-hidden flex items-center justify-center">
        <!-- Background Parallax -->
        <div class="absolute inset-0 z-0">
            @if($posts->count() > 0 && $posts->first()->cover_image)
                <img src="{{ $posts->first()->cover_image_url }}" 
                     alt="Hero Background" 
                     class="w-full h-full object-cover"
                     style="transform: scale(1.1);"
                     x-bind:style="`transform: scale(${1.1 + (scrollProgress/500)}) translateY(${scrollProgress * 0.5}px)`">
            @else
                <div class="w-full h-full bg-gradient-to-br from-primary to-secondary animate-gradient-xy"></div>
            @endif
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-base-100 via-transparent to-black/60"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 container mx-auto px-4 text-center text-white mt-20">
            <h1 class="text-5xl lg:text-7xl font-black mb-6 tracking-tight drop-shadow-lg"
                x-data x-intersect="$el.classList.add('opacity-100', 'translate-y-0')"
                class="opacity-0 translate-y-10 transition-all duration-1000 ease-out">
                Community <span class="text-primary">Pulse</span>
            </h1>
            <p class="text-xl lg:text-2xl opacity-90 mb-10 max-w-2xl mx-auto drop-shadow-md"
               x-data x-intersect="$el.classList.add('opacity-100', 'translate-y-0')"
               class="opacity-0 translate-y-10 transition-all duration-1000 delay-200 ease-out">
                Your daily source for local stories, events, and updates.
            </p>
            
            <!-- Floating Search Bar -->
            <div class="max-w-3xl mx-auto transform hover:scale-[1.02] transition-transform duration-300 px-4"
                 x-data x-intersect="$el.classList.add('opacity-100', 'translate-y-0')"
                 class="opacity-0 translate-y-10 transition-all duration-1000 delay-400 ease-out">
                <div class="bg-base-100/10 backdrop-blur-md p-2 rounded-3xl md:rounded-full border border-white/20 shadow-2xl">
                    <form method="GET" action="{{ route('home') }}" class="flex flex-col md:flex-row gap-2">
                        <div class="relative flex-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 absolute left-4 top-1/2 -translate-y-1/2 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}" 
                                placeholder="Search stories, events, and more..." 
                                class="input input-ghost w-full pl-12 text-white placeholder-white/70 focus:bg-white/10 rounded-full transition-colors">
                        </div>
                        <div class="hidden md:block w-px bg-white/20 my-2"></div>
                        <select name="category" class="select select-ghost text-white focus:bg-white/10 rounded-full w-full md:w-48" onchange="this.form.submit()">
                            <option value="" class="text-base-content">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }} class="text-base-content">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary rounded-full w-full md:w-auto px-8 border-none shadow-lg hover:shadow-primary/50">
                            Explore
                        </button>
                    </form>
                </div>
            </div>

            <!-- Category Pills (Quick Links) -->
            <div class="mt-8 flex flex-wrap justify-center gap-3 px-4"
                 x-data x-intersect="$el.classList.add('opacity-100', 'translate-y-0')"
                 class="opacity-0 translate-y-10 transition-all duration-1000 delay-500 ease-out">
                <a href="{{ route('home', ['search' => request('search')]) }}" 
                   class="btn btn-sm rounded-full {{ !request('category') ? 'btn-primary border-none' : 'btn-outline text-white border-white/30 hover:bg-white/20 hover:border-white' }} backdrop-blur-sm">
                    All
                </a>
                @foreach($categories->take(5) as $category)
                    <a href="{{ route('home', ['category' => $category->id, 'search' => request('search')]) }}" 
                       class="btn btn-sm rounded-full {{ request('category') == $category->id ? 'btn-primary border-none' : 'btn-outline text-white border-white/30 hover:bg-white/20 hover:border-white' }} backdrop-blur-sm">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce text-white/50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="relative z-20 bg-base-100 -mt-8 rounded-t-[3rem] shadow-[0_-20px_60px_-15px_rgba(0,0,0,0.3)] pt-16 pb-20">
        <div class="container mx-auto px-4">
            
            <!-- Section Header -->
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="text-3xl font-bold mb-2">
                        @if(request('search'))
                            Search Results
                        @elseif(request('category'))
                            {{ $categories->find(request('category'))->name }}
                        @else
                            Trending Stories
                        @endif
                    </h2>
                    <div class="h-1 w-20 bg-primary rounded-full"></div>
                </div>
                
                @if(request('search') || request('category'))
                    <a href="{{ route('home') }}" class="btn btn-ghost btn-sm">Clear Filters</a>
                @endif
            </div>

            <!-- News Grid -->
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
                    @foreach($posts as $post)
                        <article class="group cursor-pointer"
                                 x-data
                                 x-intersect="$el.classList.add('opacity-100', 'translate-y-0')"
                                 class="opacity-0 translate-y-12 transition-all duration-700 ease-out hover:-translate-y-2">
                            
                            <a href="{{ route('news.show', $post->slug) }}" class="block">
                                <div class="relative h-64 mb-6 overflow-hidden rounded-2xl shadow-lg">
                                    @if($post->cover_image)
                                        <img src="{{ $post->cover_image_url }}" 
                                             alt="{{ $post->title }}" 
                                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <div class="absolute top-4 left-4">
                                        <div class="badge badge-primary border-none shadow-lg text-white font-medium px-3 py-3">
                                            {{ $post->category->name }}
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3 px-2">
                                    <div class="flex items-center gap-3 text-sm text-base-content/60">
                                        <div class="flex items-center gap-1">
                                            <div class="avatar placeholder w-5 h-5 rounded-full bg-base-300 text-base-content">
                                                <span class="text-[10px] w-full text-center">{{ substr($post->user->name, 0, 1) }}</span>
                                            </div>
                                            <span>{{ $post->user->name }}</span>
                                        </div>
                                        <span>•</span>
                                        <span>{{ $post->published_at?->diffForHumans() }}</span>
                                    </div>

                                    <h3 class="text-xl font-bold leading-tight group-hover:text-primary transition-colors">
                                        {{ $post->title }}
                                    </h3>

                                    <p class="text-base-content/70 line-clamp-2 leading-relaxed">
                                        {{ Str::limit(strip_tags($post->content), 120) }}
                                    </p>
                                    
                                    <div class="pt-2 flex items-center text-primary font-medium text-sm group-hover:gap-2 transition-all">
                                        Read Article 
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                    <div class="mt-20 flex justify-center">
                        {{ $posts->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-20">
                    <div class="bg-base-200 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">No stories found</h3>
                    <p class="text-base-content/60 mb-8">Try adjusting your search or filter criteria</p>
                    <a href="{{ route('home') }}" class="btn btn-primary btn-wide rounded-full">Clear Filters</a>
                </div>
            @endif

        </div>
    </div>



</div>
@endsection

