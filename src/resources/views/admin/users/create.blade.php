@extends('layouts.admin')

@section('title', 'Create User')

@section('breadcrumbs')
    <li><a href="{{ route('admin.users.index') }}">Users</a></li>
    <li>Create</li>
@endsection

@section('content')
    <div class="card bg-base-100 shadow-xl max-w-2xl mx-auto">
        <div class="card-body">
            <h2 class="card-title mb-6">Create New User</h2>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Full Name</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="input input-bordered w-full @error('name') input-error @enderror" placeholder="e.g. John Doe"
                        required />
                    @error('name')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Email Address</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="input input-bordered w-full @error('email') input-error @enderror"
                        placeholder="e.g. john@example.com" required />
                    @error('email')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Password</span>
                    </label>
                    <input type="password" name="password"
                        class="input input-bordered w-full @error('password') input-error @enderror" required />
                    @error('password')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control w-full mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Role</span>
                    </label>
                    <select name="role" class="select select-bordered w-full @error('role') select-error @enderror">
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="editor" {{ old('role') == 'editor' ? 'selected' : '' }}>Editor</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('role')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="card-actions justify-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
@endsection