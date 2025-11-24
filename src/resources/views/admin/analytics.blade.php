@extends('layouts.admin')

@section('title', 'Analytics')

@section('breadcrumbs')
    <li>Analytics</li>
@endsection

@section('content')
    <div class="space-y-8">
        <!-- Header & Filters -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold">Social Share Analytics</h2>
                        <p class="text-base-content/70">Track how your content is being shared across platforms.</p>
                    </div>
                    <div class="stats shadow bg-base-200">
                        <div class="stat">
                            <div class="stat-title">Total Shares</div>
                            <div class="stat-value text-primary">{{ number_format($totalShares) }}</div>
                            <div class="stat-desc">For selected period</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Date Filters -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="text-sm font-medium text-base-content/70 self-center">Quick filters:</span>
                    <button type="button" onclick="setDateRange('last_7_days')" class="btn btn-xs btn-outline">Last 7
                        Days</button>
                    <button type="button" onclick="setDateRange('this_month')" class="btn btn-xs btn-outline">This
                        Month</button>
                    <button type="button" onclick="setDateRange('last_month')" class="btn btn-xs btn-outline">Last
                        Month</button>
                    <button type="button" onclick="setDateRange('this_year')" class="btn btn-xs btn-outline">This
                        Year</button>
                    <button type="button" onclick="setDateRange('last_year')" class="btn btn-xs btn-outline">Last
                        Year</button>
                </div>

                <form action="{{ route('admin.analytics') }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4" id="analyticsFilterForm">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Start Date</span></label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">End Date</span></label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Platform</span></label>
                        <select name="platform" class="select select-bordered w-full">
                            <option value="all">All Platforms</option>
                            @foreach(['facebook', 'twitter', 'whatsapp', 'telegram', 'email'] as $p)
                                <option value="{{ $p }}" {{ $platform == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Category</span></label>
                        <select name="category_id" class="select select-bordered w-full">
                            <option value="all">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control flex-row items-end gap-2 mt-auto">
                        <button type="submit" class="btn btn-primary flex-1">Filter</button>
                        <a href="{{ route('admin.analytics') }}" class="btn btn-ghost">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Shares Over Time -->
            <div class="card bg-base-100 shadow-xl lg:col-span-2">
                <div class="card-body">
                    <h3 class="card-title text-sm uppercase text-base-content/60 mb-4">Shares Over Time</h3>
                    <div class="h-80">
                        <canvas id="sharesOverTimeChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Shares by Platform -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h3 class="card-title text-sm uppercase text-base-content/60 mb-4">Platform Distribution</h3>
                    <div class="h-64">
                        <canvas id="platformChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Shares by Category -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h3 class="card-title text-sm uppercase text-base-content/60 mb-4">Category Performance</h3>
                    <div class="h-64">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Content Table -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h3 class="card-title mb-6">Top Shared Content</h3>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Title / URL</th>
                                <th>Category</th>
                                <th class="text-right">Share Count</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topPages as $page)
                                <tr class="hover">
                                    <td>
                                        <div class="font-bold">{{ Str::limit($page['title'], 60) }}</div>
                                        <div class="text-xs text-base-content/60 truncate max-w-md">{{ $page['page_url'] }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($page['category'])
                                            <div class="badge badge-ghost">{{ $page['category'] }}</div>
                                        @else
                                            <span class="text-base-content/40">-</span>
                                        @endif
                                    </td>
                                    <td class="text-right font-mono font-bold">{{ number_format($page['share_count']) }}</td>
                                    <td class="text-right">
                                        <a href="{{ $page['page_url'] }}" target="_blank" class="btn btn-xs btn-ghost">
                                            Visit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-base-content/60">
                                        No data available for the selected period.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Quick date range filter function
            function setDateRange(range) {
                const today = new Date();
                let startDate, endDate;

                switch (range) {
                    case 'last_7_days':
                        startDate = new Date(today);
                        startDate.setDate(today.getDate() - 7);
                        endDate = today;
                        break;
                    case 'this_month':
                        startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                        endDate = today;
                        break;
                    case 'last_month':
                        startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                        endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                        break;
                    case 'this_year':
                        startDate = new Date(today.getFullYear(), 0, 1);
                        endDate = today;
                        break;
                    case 'last_year':
                        startDate = new Date(today.getFullYear() - 1, 0, 1);
                        endDate = new Date(today.getFullYear() - 1, 11, 31);
                        break;
                }

                // Format dates as YYYY-MM-DD
                const formatDate = (date) => {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                };

                // Set form values
                document.querySelector('input[name="start_date"]').value = formatDate(startDate);
                document.querySelector('input[name="end_date"]').value = formatDate(endDate);

                // Submit form
                document.getElementById('analyticsFilterForm').submit();
            }

            document.addEventListener('DOMContentLoaded', function () {
                // Common Chart Options
                const commonOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { usePointStyle: true, padding: 20 }
                        }
                    }
                };

                // Shares Over Time Chart
                const timeCtx = document.getElementById('sharesOverTimeChart').getContext('2d');
                new Chart(timeCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode(array_keys($sharesOverTime)) !!},
                        datasets: [{
                            label: 'Shares',
                            data: {!! json_encode(array_values($sharesOverTime)) !!},
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                            x: { grid: { display: false } }
                        }
                    }
                });

                // Platform Chart
                const platformCtx = document.getElementById('platformChart').getContext('2d');
                new Chart(platformCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode(array_map('ucfirst', array_keys($sharesByPlatform))) !!},
                        datasets: [{
                            data: {!! json_encode(array_values($sharesByPlatform)) !!},
                            backgroundColor: [
                                '#1877F2', // Facebook Blue
                                '#1DA1F2', // Twitter Blue
                                '#25D366', // WhatsApp Green
                                '#0088cc', // Telegram Blue
                                '#EA4335', // Email Red
                                '#A0AEC0'  // Other Gray
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: commonOptions
                });

                // Category Chart
                const categoryCtx = document.getElementById('categoryChart').getContext('2d');
                new Chart(categoryCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(array_keys($sharesByCategory)) !!},
                        datasets: [{
                            label: 'Shares',
                            data: {!! json_encode(array_values($sharesByCategory)) !!},
                            backgroundColor: '#5a67d8',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection