@extends('layouts.app')

@section('title', $post->title)

@push('meta')
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($post->content), 200) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    @if($post->cover_image)
        <meta property="og:image" content="{{ str_starts_with($post->cover_image, 'http') ? $post->cover_image : asset('storage/' . $post->cover_image) }}">
    @endif
@endpush

@section('content')
<div x-data="{ 
    scrollProgress: 0, 
    readingTime: 0,
    calculateReadingTime() {
        const text = document.getElementById('article-content').innerText;
        const wpm = 225;
        const words = text.trim().split(/\s+/).length;
        this.readingTime = Math.ceil(words / wpm);
    },
    updateScrollProgress() {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        this.scrollProgress = (winScroll / height) * 100;
    }
}" 
x-init="calculateReadingTime(); window.addEventListener('scroll', () => updateScrollProgress())"
class="relative min-h-screen">

    <!-- Reading Progress Bar -->
    <div class="fixed top-0 left-0 h-1 bg-primary z-[60] transition-all duration-300 ease-out"
         :style="`width: ${scrollProgress}%`"></div>

    <!-- Hero Section with Parallax -->
    <div class="relative h-[70vh] w-full overflow-hidden flex items-end">
        <div class="absolute inset-0 z-0">
            @if($post->cover_image)
                <img src="{{ $post->cover_image_url }}" 
                     alt="{{ $post->title }}" 
                     class="w-full h-full object-cover transition-transform duration-700 hover:scale-105"
                     style="transform: scale(1.1);"
                     x-bind:style="`transform: scale(${1 + (scrollProgress/500)}) translateY(${scrollProgress * 0.5}px)`"
                     onerror="this.src='https://via.placeholder.com/1920x1080?text=News+Portal'">
            @else
                <div class="w-full h-full bg-gradient-to-br from-primary/80 to-secondary/80"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-base-100 via-base-100/50 to-transparent"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10 pb-12 lg:pb-20">
            <div class="max-w-4xl mx-auto" 
                 x-data 
                 x-intersect="$el.classList.add('opacity-100', 'translate-y-0')"
                 class="opacity-0 translate-y-10 transition-all duration-1000 ease-out">
                
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="badge badge-primary badge-lg font-bold uppercase tracking-wider">{{ $post->category->name }}</span>
                    <span class="text-base-content/80 font-medium flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span x-text="readingTime + ' min read'"></span>
                    </span>
                </div>

                <h1 class="text-4xl lg:text-6xl font-black leading-tight mb-6 drop-shadow-sm">
                    {{ $post->title }}
                </h1>

                <div class="flex items-center gap-4">
                    <div class="avatar placeholder">
                        <div class="bg-neutral text-neutral-content rounded-full w-12">
                            <span class="text-xl">{{ substr($post->user->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="font-bold text-lg">{{ $post->user->name }}</div>
                        <div class="text-sm text-base-content/70">{{ $post->published_at?->format('F d, Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="container mx-auto px-4 py-12 lg:py-20">
        <div class="flex flex-col lg:flex-row gap-12 max-w-7xl mx-auto">
            
            <!-- Sidebar (Share & Actions) -->
            <div class="lg:w-24 flex-shrink-0">
                <div class="sticky top-24 flex lg:flex-col gap-4 items-center justify-center lg:justify-start p-4 bg-base-200/50 backdrop-blur-sm rounded-2xl border border-base-300/50">
                    <div class="tooltip tooltip-right" data-tip="Like this article">
                        @livewire('news-like-button', ['post' => $post])
                    </div>
                    
                    <div class="w-full h-px bg-base-300 hidden lg:block"></div>
                    
                    <div class="flex lg:flex-col gap-2">
                        @livewire('social-share-buttons', [
                            'pageUrl' => request()->fullUrl(),
                            'pageTitle' => $post->title,
                            'pageType' => 'news',
                            'newsPostId' => $post->id
                        ])
                    </div>
                </div>
            </div>

            <!-- Article Body -->
            <article class="flex-1 min-w-0">
                <div id="article-content" 
                     class="prose prose-lg lg:prose-xl max-w-none 
                            prose-headings:font-bold prose-headings:tracking-tight 
                            prose-p:leading-relaxed prose-p:text-base-content/90
                            prose-a:text-primary prose-a:no-underline hover:prose-a:underline
                            prose-img:rounded-2xl prose-img:shadow-xl
                            prose-blockquote:border-l-4 prose-blockquote:border-primary prose-blockquote:bg-base-200/50 prose-blockquote:p-6 prose-blockquote:rounded-r-lg prose-blockquote:italic">
                    
                    <div class="first-letter:text-7xl first-letter:font-bold first-letter:text-primary first-letter:mr-3 first-letter:float-left">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                </div>

                <!-- Tags / Footer Meta -->
                <div class="mt-16 pt-8 border-t border-base-300">
                    <div class="flex flex-wrap gap-2">
                        <span class="text-base-content/60 font-medium mr-2">Tags:</span>
                        <a href="#" class="badge badge-outline hover:badge-primary transition-colors">#{{ Str::slug($post->category->name) }}</a>
                        <a href="#" class="badge badge-outline hover:badge-primary transition-colors">#News</a>
                        <a href="#" class="badge badge-outline hover:badge-primary transition-colors">#Community</a>
                    </div>
                </div>

                <!-- Author Bio Box -->
                <div class="mt-12 p-8 bg-base-200 rounded-2xl flex flex-col sm:flex-row gap-6 items-center sm:items-start text-center sm:text-left">
                    <div class="avatar placeholder">
                        <div class="bg-neutral text-neutral-content rounded-full w-20">
                            <span class="text-3xl">{{ substr($post->user->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl mb-2">About {{ $post->user->name }}</h3>
                        <p class="text-base-content/80">
                            Contributing author at Community News Portal. Passionate about bringing local stories to life.
                        </p>
                    </div>
                </div>

            </article>

        </div>
    </div>

    <!-- Related Articles (Placeholder for future implementation) -->
    <div class="bg-base-200 py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8 text-center">More from {{ $post->category->name }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- We can add a related posts loop here later -->
            </div>
        </div>
    </div>

</div>

<style>
    /* Custom scrollbar for article content if needed */
    html {
        scroll-behavior: smooth;
    }
</style>
@endsection

