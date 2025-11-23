@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="min-h-[60vh] flex items-center justify-center">
        <div class="card bg-base-100 shadow-2xl w-full max-w-md">
            <div class="card-body p-8">
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-bold mb-2">Create Account</h2>
                    <p class="text-base-content/70">Join our community and start sharing news</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Full Name</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name"
                            class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-primary @error('name') input-error @enderror"
                            required>
                        @error('name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Email Address</span>
                        </label>
                        <input type="email" id="email-input" name="email" value="{{ old('email') }}"
                            placeholder="Enter your email"
                            class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-primary @error('email') input-error @enderror"
                            required>

                        <!-- Email Availability Indicator -->
                        <div id="email-availability-indicator" class="mt-2" style="display:none;">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-xs font-medium"></span>
                            </div>
                        </div>

                        @error('email')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Password</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password-input" name="password" placeholder="Create a password"
                                class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-primary @error('password') input-error @enderror"
                                required>
                            <button type="button" id="toggle-password"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-base-content/50 hover:text-base-content cursor-pointer">
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
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>

                        <!-- Password Strength Progress Bar -->
                        <div class="mt-2" id="password-strength-container" style="display:none;">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="flex-1 h-2 bg-base-200 rounded-full overflow-hidden">
                                    <div id="password-strength-bar" class="h-full transition-all duration-300"
                                        style="width: 0%"></div>
                                </div>
                                <span id="password-strength-text" class="text-xs font-medium"></span>
                            </div>
                        </div>

                        <!-- Password Requirements -->
                        <div class="mt-3 space-y-1" id="password-requirements">
                            <p class="text-xs font-medium text-base-content/70">Password must contain:</p>
                            <div class="grid grid-cols-1 gap-1 text-xs">
                                <div class="flex items-center gap-2" id="req-length">
                                    <svg class="h-4 w-4 text-base-content/30" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                                    </svg>
                                    <span class="text-base-content/50">At least 8 characters</span>
                                </div>
                                <div class="flex items-center gap-2" id="req-uppercase">
                                    <svg class="h-4 w-4 text-base-content/30" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                                    </svg>
                                    <span class="text-base-content/50">One uppercase letter (A-Z)</span>
                                </div>
                                <div class="flex items-center gap-2" id="req-lowercase">
                                    <svg class="h-4 w-4 text-base-content/30" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                                    </svg>
                                    <span class="text-base-content/50">One lowercase letter (a-z)</span>
                                </div>
                                <div class="flex items-center gap-2" id="req-number">
                                    <svg class="h-4 w-4 text-base-content/30" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                                    </svg>
                                    <span class="text-base-content/50">One number (0-9)</span>
                                </div>
                                <div class="flex items-center gap-2" id="req-special">
                                    <svg class="h-4 w-4 text-base-content/30" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                                    </svg>
                                    <span class="text-base-content/50">One special character (!@#$%^&*)</span>
                                </div>
                            </div>
                        </div>

                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Confirm Password</span>
                        </label>
                        <input type="password" id="password-confirmation-input" name="password_confirmation"
                            placeholder="Confirm your password"
                            class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-primary" required>

                        <!-- Password Match Indicator -->
                        <div id="password-match-indicator" class="mt-2" style="display:none;">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-xs font-medium"></span>
                            </div>
                        </div>
                    </div>



                    <div class="form-control mt-6">
                        <button type="submit" id="register-submit-btn" class="btn btn-primary btn-block">
                            <!-- Normal Icon -->
                            <svg id="register-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>

                            <!-- Loading Spinner (hidden by default) -->
                            <svg id="register-spinner" class="animate-spin h-5 w-5 mr-2 hidden"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>

                            <span id="register-btn-text">Create Account</span>
                        </button>
                    </div>
                </form>

                <div class="divider my-6">OR</div>

                <div class="text-center">
                    <p class="text-sm text-base-content/70 mb-2">
                        Already have an account?
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-outline btn-block">
                        Sign In Instead
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection