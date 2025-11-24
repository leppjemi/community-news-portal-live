{{-- User Dropdown Menu (Right Side) --}}
@auth
    <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="btn btn-ghost btn-sm gap-2">
            <span class="hidden xl:inline max-w-[120px] truncate">{{ auth()->user()->name }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <ul tabindex="0"
            class="dropdown-content menu bg-base-100 rounded-box z-1 w-56 p-2 shadow-xl border border-base-300 mt-2">
            <li class="menu-title">
                <span>{{ auth()->user()->name }}</span>
                <span
                    class="badge badge-sm badge-{{ auth()->user()->role === 'admin' ? 'error' : (auth()->user()->role === 'editor' ? 'warning' : 'info') }}">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </li>
            <li>
                <a href="{{ route('dashboard') }}" class="gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="divider my-1"></div>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left gap-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
@else
    <div class="flex items-center gap-3">
        <a href="{{ route('login') }}" class="btn btn-ghost btn-sm font-medium hover:bg-base-content/10">
            Log In
        </a>
        <a href="{{ route('register') }}"
            class="btn btn-primary btn-sm rounded-full px-6 shadow-lg hover:shadow-primary/50 border-none">
            Sign Up
        </a>
    </div>
@endauth