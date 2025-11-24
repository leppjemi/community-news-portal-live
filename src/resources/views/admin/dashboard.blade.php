@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumbs')
    <li>Dashboard</li>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Header & Date Filter -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold">Overview</h2>
                <p class="text-base-content/70">Platform performance for
                    <span class="font-semibold text-primary">
                        {{ ucwords(str_replace('_', ' ', $range)) }}
                    </span>
                </p>
            </div>
            <form action="{{ route('admin.dashboard') }}" method="GET" class="join">
                <select name="range" class="select select-bordered join-item" onchange="this.form.submit()">
                    <option value="last_7_days" {{ $range == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="this_month" {{ $range == 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="last_month" {{ $range == 'last_month' ? 'selected' : '' }}>Last Month</option>
                    <option value="last_6_months" {{ $range == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                    <option value="this_year" {{ $range == 'this_year' ? 'selected' : '' }}>This Year</option>
                </select>
            </form>
        </div>

        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Users -->
            <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300">
                <div class="card-body p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-base-content/60">Total Users</p>
                            <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['totalUsers']) }}</h3>
                        </div>
                        <div class="p-2 bg-primary/10 rounded-lg text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Posts -->
            <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300">
                <div class="card-body p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-base-content/60">New Posts</p>
                            <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['totalPosts']) }}</h3>
                        </div>
                        <div class="p-2 bg-secondary/10 rounded-lg text-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Views -->
            <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300">
                <div class="card-body p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-base-content/60">Total Views</p>
                            <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['totalViews']) }}</h3>
                        </div>
                        <div class="p-2 bg-accent/10 rounded-lg text-accent">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Shares -->
            <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300">
                <div class="card-body p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-base-content/60">Social Shares</p>
                            <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['totalShares']) }}</h3>
                        </div>
                        <div class="p-2 bg-info/10 rounded-lg text-info">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section (Spanning full width on large screens) -->
            <div class="card bg-base-100 shadow-xl col-span-1 md:col-span-2 lg:col-span-4">
                <div class="card-body">
                    <h3 class="card-title text-lg mb-4">Content Publishing Trend</h3>
                    <div class="h-64">
                        <canvas id="contentChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Quick Stats / Status -->
            <div class="card bg-base-100 shadow-xl col-span-1 md:col-span-2 lg:col-span-4">
                <div class="card-body">
                    <h3 class="card-title text-lg mb-4">Current Status</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-center justify-between p-4 bg-base-200 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="badge badge-warning badge-lg p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold">Pending Review</p>
                                    <p class="text-xs text-base-content/60">Requires attention</p>
                                </div>
                            </div>
                            <span class="text-2xl font-bold">{{ $stats['pendingPosts'] }}</span>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-base-200 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="badge badge-success badge-lg p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold">Published</p>
                                    <p class="text-xs text-base-content/60">Live content</p>
                                </div>
                            </div>
                            <span class="text-2xl font-bold">{{ $stats['publishedPosts'] }}</span>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-base-200 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="badge badge-primary badge-lg p-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold">Active Categories</p>
                                    <p class="text-xs text-base-content/60">Content types</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-xs btn-ghost">Manage</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overview Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 col-span-1 md:col-span-2 lg:col-span-4">
                <!-- User Roles -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title mb-4">
                            <div class="badge badge-primary badge-lg p-3 mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            User Roles
                        </h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="badge badge-error">Admin</div>
                                    <span class="font-semibold">Administrators</span>
                                </div>
                                <span class="font-bold text-lg">{{ $stats['adminUsers'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="badge badge-warning">Editor</div>
                                    <span class="font-semibold">Editors</span>
                                </div>
                                <span class="font-bold text-lg">{{ $stats['editorUsers'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="badge badge-info">User</div>
                                    <span class="font-semibold">Regular Users</span>
                                </div>
                                <span class="font-bold text-lg">{{ $stats['regularUsers'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Post Status -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title mb-4">
                            <div class="badge badge-secondary badge-lg p-3 mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            Post Status
                        </h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="badge badge-success">Published</div>
                                    <span class="font-semibold">Live Posts</span>
                                </div>
                                <span class="font-bold text-lg">{{ $stats['publishedPosts'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="badge badge-warning">Pending</div>
                                    <span class="font-semibold">Awaiting Review</span>
                                </div>
                                <span class="font-bold text-lg">{{ $stats['pendingPosts'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="badge badge-error">Rejected</div>
                                    <span class="font-semibold">Rejected</span>
                                </div>
                                <span class="font-bold text-lg">{{ $stats['rejectedPosts'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Content Trend Chart
                const contentCtx = document.getElementById('contentChart').getContext('2d');
                new Chart(contentCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($charts['contentTrend']['labels']) !!},
                        datasets: [{
                            label: 'New Posts',
                            data: {!! json_encode($charts['contentTrend']['data']) !!},
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    display: false
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection