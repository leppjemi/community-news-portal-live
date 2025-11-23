<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Community News Portal')</title>
    @stack('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-base-100 ">
    @php
        $currentRoute = request()->route()?->getName() ?? '';
        $isPublic = in_array($currentRoute, ['home', 'news.show']);
        $isDashboard = $currentRoute === 'dashboard';
        $isUserModule = in_array($currentRoute, ['user.submissions', 'user.submit-news', 'user.edit-news']);
        $isEditorModule = $currentRoute && str_starts_with($currentRoute, 'editor.');
        $isAdminModule = $currentRoute && str_starts_with($currentRoute, 'admin.');
    @endphp

    <div class="drawer">
        <input id="mobile-drawer" type="checkbox" class="drawer-toggle" />

        <!-- Page content -->
        <div class="drawer-content flex flex-col">
            <!-- Navbar -->
            <div x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)"
                :class="{ 'bg-base-100/95 backdrop-blur-md shadow-lg': scrolled || !{{ json_encode($isPublic) }}, 'bg-transparent text-white': !scrolled && {{ json_encode($isPublic) }} }"
                class="navbar fixed top-0 w-full z-50 transition-all duration-300 border-b border-transparent"
                :class="{ 'border-base-300': scrolled || !{{ json_encode($isPublic) }} }">

                <div class="flex-none lg:hidden">
                    <label for="mobile-drawer" class="btn btn-ghost btn-square">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </label>
                </div>

                <div class="flex-none flex items-center gap-2">
                    <x-navbar.theme-toggle />
                    <x-navbar.logo />
                </div>

                <!-- Center: Module-specific Menu (Desktop) -->
                <div class="flex-1 hidden lg:flex justify-center">
                    <div class="flex items-center gap-2">
                        @if($isPublic)
                            <x-navbar.guest-menu :currentRoute="$currentRoute" />
                        @elseif($isDashboard)
                            <x-navbar.dashboard-menu :currentRoute="$currentRoute" />
                        @elseif($isUserModule)
                            <x-navbar.user-menu :currentRoute="$currentRoute" />
                        @elseif($isEditorModule)
                            <x-navbar.editor-menu :currentRoute="$currentRoute" />
                        @elseif($isAdminModule)
                            <x-navbar.admin-menu :currentRoute="$currentRoute" />
                        @endif
                    </div>
                </div>

                <!-- Right: User/Login Menu (Desktop) -->
                <div class="flex-none hidden lg:flex items-center gap-2 ml-auto">
                    <x-navbar.user-dropdown />
                </div>
            </div>

            <!-- Main Content -->
            <main class="flex-1 {{ $isPublic ? 'w-full' : 'container mx-auto px-4 py-6 lg:py-8 pt-20' }}">
                @if(!$isPublic)
                    @if(session('message'))
                        <div class="alert alert-success mb-4 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5 lg:h-6 lg:w-6"
                                fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm lg:text-base break-words">{{ session('message') }}</span>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success mb-4 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5 lg:h-6 lg:w-6"
                                fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm lg:text-base break-words">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-error mb-4 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5 lg:h-6 lg:w-6"
                                fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="flex-1">
                                <h3 class="font-bold text-sm lg:text-base">Please fix the following errors:</h3>
                                <ul class="list-disc list-inside mt-2 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li class="text-xs lg:text-sm break-words">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-neutral text-neutral-content mt-auto">
                <div class="container mx-auto px-4 py-16">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <!-- Brand Column -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 text-2xl font-black tracking-tight">
                                <div class="w-8 h-8 rounded bg-primary flex items-center justify-center text-primary-content">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                </div>
                                <span>Community <span class="text-primary">Pulse</span></span>
                            </div>
                            <p class="opacity-70 leading-relaxed max-w-md">
                                Empowering our community with timely, accurate, and engaging local news. Your voice, your stories, your portal.
                            </p>
                            <div class="flex gap-4 pt-2">
                                <a href="#" class="btn btn-square btn-sm btn-ghost hover:bg-white/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                                </a>
                                <a href="#" class="btn btn-square btn-sm btn-ghost hover:bg-white/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.85-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </a>
                                <a href="#" class="btn btn-square btn-sm btn-ghost hover:bg-white/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                </a>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div>
                            <h3 class="font-bold text-lg mb-6 text-white">Quick Links</h3>
                            <ul class="space-y-3">
                                <li><a href="{{ route('home') }}" class="link link-hover hover:text-primary transition-colors">Home</a></li>
                                <li><a href="{{ route('login') }}" class="link link-hover hover:text-primary transition-colors">Log In</a></li>
                                <li><a href="{{ route('register') }}" class="link link-hover hover:text-primary transition-colors">Sign Up</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="divider divider-neutral my-10"></div>

                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm opacity-60">
                        <p>Copyright © {{ date('Y') }} Community News Portal. All rights reserved.</p>
                        <div class="flex items-center gap-6">
                            <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="flex items-center gap-2 hover:text-white transition-colors cursor-pointer">
                                Back to Top
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Sidebar (Mobile) -->
        <div class="drawer-side z-50">
            <label for="mobile-drawer" class="drawer-overlay"></label>
            <div class="menu p-4 w-80 min-h-full bg-base-100 text-base-content">
                <!-- Sidebar Header -->
                <div class="flex items-center gap-3 px-4 py-3 mb-4 border-b border-base-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <div>
                        <h2 class="text-lg font-bold">News Portal</h2>
                        @auth
                            <p class="text-xs text-base-content/70">{{ auth()->user()->name }}</p>
                        @endauth
                    </div>
                </div>

                <!-- Sidebar Navigation -->
                @auth
                    <ul class="menu-compact">
                        <li class="menu-title">
                            <span>Navigation</span>
                        </li>
                        <li>
                            <a href="{{ route('home') }}" class="gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard') }}" class="gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        @if(auth()->user()->hasRole('user') || auth()->user()->isEditor())
                            <li>
                                <a href="{{ route('user.submissions') }}" class="gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    My Submissions
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.submit-news') }}" class="gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Submit News
                                </a>
                            </li>
                        @endif
                        @if(auth()->user()->isEditor() && !auth()->user()->isAdmin())
                            <li class="menu-title mt-4">
                                <span>Editor</span>
                            </li>
                            <li>
                                <a href="{{ route('editor.review-queue') }}" class="gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Review Queue
                                </a>
                            </li>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <li class="menu-title mt-4">
                                <span>Editor</span>
                            </li>
                            <li>
                                <a href="{{ route('editor.review-queue') }}" class="gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Review Queue
                                </a>
                            </li>
                            <li class="menu-title mt-4">
                                <span>Admin</span>
                            </li>
                            <li>
                                <a href="{{ route('admin.categories.index') }}" class="gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    Categories
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.index') }}" class="gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Users
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.analytics') }}" class="gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Analytics
                                </a>
                            </li>
                        @endif

                        <li class="mt-4">
                            <div class="divider my-1"></div>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full text-left gap-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                @else
                    <ul class="menu-compact">
                        <li>
                            <a href="{{ route('home') }}" class="gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('login') }}" class="gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Login
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                Sign Up
                            </a>
                        </li>
                    </ul>
                @endauth
            </div>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')

    <script>
        // Theme switcher
        const themeController = document.querySelector('.theme-controller');
        const html = document.documentElement;

        // Check for saved theme preference or default to light
        const currentTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', currentTheme);
        themeController.checked = currentTheme === 'dark';

        themeController.addEventListener('change', (e) => {
            const theme = e.target.checked ? 'dark' : 'light';
            html.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
        });

        // Auto-close drawer on navigation (mobile)
        document.querySelectorAll('.drawer-side a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobile-drawer').checked = false;
            });
        });
    </script>
</body>

</html>