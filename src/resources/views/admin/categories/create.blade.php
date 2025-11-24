@extends('layouts.admin')

@section('title', 'Create Category')

@section('breadcrumbs')
    <li><a href="{{ route('admin.categories.index') }}">Categories</a></li>
    <li>Create</li>
@endsection

@section('content')
    <div class="card bg-base-100 shadow-xl max-w-2xl mx-auto">
        <div class="card-body">
            <h2 class="card-title mb-6">Create New Category</h2>

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                <div class="form-control w-full mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Category Name</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="input input-bordered w-full @error('name') input-error @enderror"
                        placeholder="e.g. Technology" required />
                    @error('name')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control w-full mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Description</span>
                    </label>
                    <textarea name="description"
                        class="textarea textarea-bordered h-24 w-full @error('description') textarea-error @enderror"
                        placeholder="Brief description of the category...">{{ old('description') }}</textarea>
                    @error('description')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="card-actions justify-end gap-2">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Category</button>
                </div>
            </form>
        </div>
    </div>
@endsection