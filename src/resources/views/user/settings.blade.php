@extends('layouts.user')

@section('title', 'Account Settings')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold mb-2">Account Settings</h1>
            <p class="text-base-content/70">Manage your account preferences and security</p>
        </div>

        <!-- Profile Settings Card -->
        <div class="card bg-base-100 shadow-xl border border-base-300 mb-6">
            <div class="card-body">
                <h2 class="card-title mb-4">Profile Information</h2>

                <form method="POST" action="{{ route('user.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-medium">Full Name</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="input input-bordered w-full @error('name') input-error @enderror" required>
                        @error('name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="card-actions justify-end">
                        <button type="submit" class="btn btn-primary">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password Card -->
        <div class="card bg-base-100 shadow-xl border border-base-300 mb-6">
            <div class="card-body">
                <h2 class="card-title mb-4">Change Password</h2>

                <form method="POST" action="{{ route('user.settings.password') }}">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-medium">Current Password</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="current_password" id="current-password-input"
                                class="input input-bordered w-full @error('current_password') input-error @enderror"
                                required>
                            <div id="current-password-feedback" class="absolute right-3 top-3 flex items-center hidden">
                                <!-- Feedback icon will be injected here -->
                            </div>
                        </div>
                        @error('current_password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-medium">New Password</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="password-input"
                                class="input input-bordered w-full @error('password') input-error @enderror" required>
                            <button type="button" id="toggle-password"
                                class="absolute right-3 top-3 text-base-content/50 hover:text-base-content focus:outline-none">
                                <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.575-3.107m-2.413 8.508L19.071 4.929" />
                                </svg>
                            </button>
                        </div>

                        <!-- Password Strength Meter -->
                        <div id="password-strength-container" class="mt-3 hidden">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs text-base-content/70">Password Strength</span>
                                <span id="password-strength-text" class="text-xs font-medium">Weak</span>
                            </div>
                            <div class="w-full bg-base-200 rounded-full h-2 mb-3">
                                <div id="password-strength-bar"
                                    class="h-full rounded-full transition-all duration-300 bg-error" style="width: 0%">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
                                <div id="req-length" class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-base-content/30" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                                    </svg>
                                    <span class="text-base-content/50">At least 8 characters</span>
                                </div>
                                <div id="req-uppercase" class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-base-content/30" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                                    </svg>
                                    <span class="text-base-content/50">One uppercase letter</span>
                                </div>
                                <div id="req-lowercase" class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-base-content/30" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                                    </svg>
                                    <span class="text-base-content/50">One lowercase letter</span>
                                </div>
                                <div id="req-number" class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-base-content/30" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                                    </svg>
                                    <span class="text-base-content/50">One number</span>
                                </div>
                                <div id="req-special" class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-base-content/30" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                                    </svg>
                                    <span class="text-base-content/50">One special character</span>
                                </div>
                            </div>
                        </div>

                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div class="form-control mb-6">
                        <label class="label">
                            <span class="label-text font-medium">Confirm New Password</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password-confirmation-input"
                            class="input input-bordered w-full" required>

                        <!-- Password Match Indicator -->
                        <div id="password-match-indicator" class="mt-2 hidden">
                            <div class="flex items-center gap-2 text-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <!-- Icon injected by JS -->
                                </svg>
                                <span><!-- Text injected by JS --></span>
                            </div>
                        </div>
                    </div>

                    <div class="card-actions justify-end">
                        <button type="submit" class="btn btn-primary">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Information -->
        <div class="card bg-base-100 shadow-xl border border-base-300">
            <div class="card-body">
                <h2 class="card-title mb-4">Account Information</h2>

                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-base-300">
                        <div>
                            <div class="font-medium">Account Email</div>
                            <div class="text-sm text-base-content/70">{{ $user->email }}</div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center py-3 border-b border-base-300">
                        <div>
                            <div class="font-medium">Account Role</div>
                            <div class="text-sm text-base-content/70">
                                <span class="badge badge-info">{{ ucfirst($user->role) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center py-3">
                        <div>
                            <div class="font-medium">Member Since</div>
                            <div class="text-sm text-base-content/70">{{ $user->created_at->format('F d, Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
    </div>
@endsection