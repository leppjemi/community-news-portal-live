<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Editor Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-base-200">
    <div class="drawer lg:drawer-open">
        <input id="editor-drawer" type="checkbox" class="drawer-toggle" />

        <!-- Page Content -->
        <div class="drawer-content">
            <!-- Editor Navbar -->
            <div class="navbar bg-base-100 shadow-md sticky top-0 z-30">
                <!-- Mobile Menu Button -->
                <div class="flex-none lg:hidden">
                    <label for="editor-drawer" class="btn btn-ghost btn-square">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </label>
                </div>

                <!-- Sidebar Toggle (Desktop) -->
                <div class="flex-none hidden lg:flex">
                    <button id="sidebar-toggle" class="btn btn-ghost btn-square">
                        <svg id="sidebar-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>
                </div>

                <!-- Logo/Title -->
                <div class="flex-1">
                    <a href="{{ route('editor.dashboard') }}" class="btn btn-ghost normal-case text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-warning" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="hidden sm:inline">Editor Dashboard</span>
                    </a>
                </div>

                <!-- User Dropdown -->
                <div class="flex-none gap-2">
                    <!-- Theme Toggle -->
                    <label class="swap swap-rotate">
                        <input type="checkbox" class="theme-controller" />
                        <svg class="swap-on fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24">
                            <path
                                d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z" />
                        </svg>
                        <svg class="swap-off fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24">
                            <path
                                d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z" />
                        </svg>
                    </label>

                    <!-- User Menu Dropdown -->
                    <div class="dropdown dropdown-end">
                        <label tabindex="0" class="btn btn-ghost normal-case">
                            <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </label>
                        <ul tabindex="0"
                            class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
                            <li class="menu-title">
                                <span class="font-semibold">{{ Auth::user()->name }}</span>
                                <span class="text-xs opacity-60">{{ Auth::user()->email }}</span>
                            </li>
                            <div class="divider my-0"></div>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="p-4 lg:p-8">
                @if(session('message'))
                    <div class="alert alert-success mb-6 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('message') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error mb-6 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error mb-6 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h3 class="font-bold">Please fix the following errors:</h3>
                            <ul class="list-disc list-inside mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

        <!-- Editor Sidebar -->
        <div class="drawer-side z-40">
            <label for="editor-drawer" class="drawer-overlay"></label>
            <aside id="editor-sidebar" class="bg-base-100 min-h-full shadow-xl transition-all duration-300"
                style="width: 280px;">
                <!-- Sidebar Header -->
                <div class="p-4 border-b border-base-300">
                    <div class="sidebar-content">
                        <div class="sidebar-text">
                            <h2 class="font-bold text-lg">{{ Auth::user()->name }}</h2>
                            <p class="text-xs text-base-content/70">
                                <span class="badge badge-warning badge-sm">{{ ucfirst(Auth::user()->role) }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Navigation -->
                <ul class="menu p-4 space-y-2">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('editor.dashboard') }}"
                            class="{{ request()->routeIs('editor.dashboard') ? 'active' : '' }}" title="Dashboard">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span class="sidebar-text">Dashboard</span>
                        </a>
                    </li>

                    <div class="divider my-2 sidebar-text">Content</div>

                    <!-- Submit News -->
                    <li>
                        <a href="{{ route('editor.submit-news') }}"
                            class="{{ request()->routeIs('editor.submit-news') ? 'active' : '' }}" title="Submit News">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="sidebar-text">Submit News</span>
                        </a>
                    </li>

                    <!-- My Submissions -->
                    <li>
                        <a href="{{ route('editor.submissions') }}"
                            class="{{ request()->routeIs('editor.submissions') ? 'active' : '' }}"
                            title="My Submissions">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="sidebar-text">My Submissions</span>
                        </a>
                    </li>

                    <div class="divider my-2 sidebar-text">Editor Tools</div>

                    <!-- Review Queue -->
                    <li>
                        <a href="{{ route('editor.review-queue') }}"
                            class="{{ request()->routeIs('editor.review-queue') ? 'active' : '' }}"
                            title="Review Queue">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span class="sidebar-text">Review Queue</span>
                        </a>
                    </li>

                    <!-- Approved Articles -->
                    <li>
                        <a href="{{ route('editor.approved-articles') }}"
                            class="{{ request()->routeIs('editor.approved-articles') ? 'active' : '' }}"
                            title="Approved Articles">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="sidebar-text">Approved Articles</span>
                        </a>
                    </li>

                    <!-- Rejected Articles -->
                    <li>
                        <a href="{{ route('editor.rejected-articles') }}"
                            class="{{ request()->routeIs('editor.rejected-articles') ? 'active' : '' }}"
                            title="Rejected Articles">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="sidebar-text">Rejected Articles</span>
                        </a>
                    </li>

                    <div class="divider my-2 sidebar-text">Account</div>

                    <!-- Settings -->
                    <li>
                        <a href="{{ route('editor.settings') }}"
                            class="{{ request()->routeIs('editor.settings') ? 'active' : '' }}" title="Settings">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="sidebar-text">Settings</span>
                        </a>
                    </li>

                    <div class="divider my-2"></div>

                    <!-- Logout -->
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-2" title="Logout">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="sidebar-text">Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </aside>
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
        if (themeController) {
            themeController.checked = currentTheme === 'dark';

            themeController.addEventListener('change', (e) => {
                const theme = e.target.checked ? 'dark' : 'light';
                html.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
            });
        }

        // Sidebar collapse/expand toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('editor-sidebar');
        const sidebarIcon = document.getElementById('sidebar-icon');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');

        // Check for saved sidebar state
        const sidebarCollapsed = localStorage.getItem('editorSidebarCollapsed') === 'true';

        // Apply saved state on load
        if (sidebarCollapsed && window.innerWidth >= 1024) {
            collapseSidebar();
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                if (sidebar.style.width === '80px') {
                    expandSidebar();
                } else {
                    collapseSidebar();
                }
            });
        }

        function collapseSidebar() {
            sidebar.style.width = '80px';
            sidebarTexts.forEach(el => el.style.display = 'none');
            if (sidebarIcon) {
                sidebarIcon.style.transform = 'rotate(180deg)';
            }
            localStorage.setItem('editorSidebarCollapsed', 'true');
        }

        function expandSidebar() {
            sidebar.style.width = '280px';
            sidebarTexts.forEach(el => el.style.display = '');
            if (sidebarIcon) {
                sidebarIcon.style.transform = 'rotate(0deg)';
            }
            localStorage.setItem('editorSidebarCollapsed', 'false');
        }

        // Auto-close drawer on navigation (mobile)
        document.querySelectorAll('.drawer-side a').forEach(link => {
            link.addEventListener('click', () => {
                const drawer = document.getElementById('editor-drawer');
                if (drawer) drawer.checked = false;
            });
        });
    </script>
</body>

</html>