@extends('layouts.admin')

@section('title', 'Users')

@section('breadcrumbs')
    <li>Users</li>
@endsection

@section('content')
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="card-title">User Management</h2>
                    <p class="text-base-content/70 text-sm">Manage platform users and roles</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="hover">
                                <td>
                                    <div class="font-bold">{{ $user->name }}</div>
                                </td>
                                <td class="text-sm">
                                    {{ $user->email }}
                                </td>
                                <td>
                                    @if($user->role === 'admin')
                                        <div class="badge badge-error gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                            Admin
                                        </div>
                                    @elseif($user->role === 'editor')
                                        <div class="badge badge-warning gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Editor
                                        </div>
                                    @else
                                        <div class="badge badge-info gap-1">
                                            User
                                        </div>
                                    @endif
                                </td>
                                <td class="text-sm">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td>
                                    <div class="flex gap-2 justify-end">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-ghost btn-xs">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="p-4 rounded-full bg-base-200 text-base-content/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold">No users found</p>
                                            <p class="text-sm text-base-content/70">No users are currently registered</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection