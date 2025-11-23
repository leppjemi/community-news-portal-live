@extends('layouts.admin')

@section('title', 'Manage Users')

@section('breadcrumbs')
    <li class="text-base-content/70">Users</li>
@endsection

@section('content')
<div class="mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
    <div>
        <h1 class="text-3xl lg:text-4xl font-bold mb-2">Users</h1>
        <p class="text-base-content/70">Manage platform users and their roles</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </svg>
        Create User
    </a>
</div>

<div class="card bg-base-100 shadow-lg border border-base-300">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table table-zebra table-hover">
                <thead>
                    <tr class="bg-base-200">
                        <th class="font-semibold">User</th>
                        <th class="font-semibold">Email</th>
                        <th class="font-semibold">Role</th>
                        <th class="font-semibold">Created</th>
                        <th class="text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="hover">
                            <td>
                                <div class="font-semibold">{{ $user->name }}</div>
                            </td>
                            <td>
                                <span class="text-sm">{{ $user->email }}</span>
                            </td>
                            <td>
                                <span class="badge badge-lg badge-{{ $user->role === 'admin' ? 'error' : ($user->role === 'editor' ? 'warning' : 'info') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="text-sm">{{ $user->created_at->format('M d, Y') }}</span>
                            </td>
                            <td>
                                <div class="flex gap-2 justify-end">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="btn btn-sm btn-error gap-2" 
                                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-16">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="p-4 rounded-full bg-base-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-base-content/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-lg font-semibold mb-2">No users found</p>
                                        <p class="text-base-content/70 mb-4">Get started by creating your first user</p>
                                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                            </svg>
                                            Create User
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
@if($users->hasPages())
    <div class="mt-8">
        {{ $users->links() }}
    </div>
@endif
@endsection

