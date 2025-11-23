@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="text-4xl lg:text-5xl font-bold mb-2">Welcome back, {{ $user->name }}!</h1>
                <p class="text-base-content/70 text-lg">Here's what's happening with your account</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="stat bg-base-100 shadow-xl rounded-2xl border border-base-300">
            <div class="stat-figure text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="stat-title">Your Role</div>
            <div class="stat-value text-2xl">
                <span
                    class="badge badge-lg badge-{{ $user->role === 'admin' ? 'error' : ($user->role === 'editor' ? 'warning' : 'info') }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>

        <div class="stat bg-base-100 shadow-xl rounded-2xl border border-base-300">
            <div class="stat-figure text-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="stat-title">Your Submissions</div>
            <div class="stat-value text-3xl">{{ $user->newsPosts()->count() }}</div>
            <div class="stat-desc">Total news articles</div>
        </div>

        <div class="stat bg-base-100 shadow-xl rounded-2xl border border-base-300">
            <div class="stat-figure text-accent">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-title">Published</div>
            <div class="stat-value text-3xl">{{ $user->newsPosts()->where('status', 'published')->count() }}</div>
            <div class="stat-desc">Approved articles</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="card bg-base-100 shadow-xl border border-base-300 hover:shadow-2xl transition-shadow">
            <div class="card-body">
                <h2 class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Quick Actions
                </h2>
                <p class="text-sm text-base-content/70 mb-4">Common tasks and shortcuts</p>
                <div class="card-actions flex-col gap-2">
                    <a href="{{ route('user.submit-news') }}" class="btn btn-primary btn-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Submit News
                    </a>
                    <a href="{{ route('user.submissions') }}" class="btn btn-outline btn-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        My Submissions
                    </a>
                </div>
            </div>
        </div>

        @if($user->isEditor())
            <div class="card bg-base-100 shadow-xl border border-base-300 hover:shadow-2xl transition-shadow">
                <div class="card-body">
                    <h2 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-warning" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editor Tools
                    </h2>
                    <p class="text-sm text-base-content/70 mb-4">Review and manage submissions</p>
                    <div class="card-actions">
                        <a href="{{ route('editor.review-queue') }}" class="btn btn-warning btn-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Review Queue
                        </a>
                    </div>
                </div>
            </div>
        @endif

        @if($user->isAdmin())
            <div class="card bg-base-100 shadow-xl border border-base-300 hover:shadow-2xl transition-shadow">
                <div class="card-body">
                    <h2 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-error" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Admin Panel
                    </h2>
                    <p class="text-sm text-base-content/70 mb-4">Manage the platform</p>
                    <div class="card-actions flex-col gap-2">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-error btn-block btn-sm">
                            Manage Categories
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-error btn-block btn-sm">
                            Manage Users
                        </a>
                        <a href="{{ route('admin.analytics') }}" class="btn btn-error btn-block btn-sm">
                            View Analytics
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection