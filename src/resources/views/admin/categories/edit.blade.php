@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl font-bold">Edit Category</h1>
    </div>

    <div class="card bg-base-100 shadow-xl max-w-2xl">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                @csrf
                @method('PUT')

                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Name</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" class="input input-bordered"
                        required>
                </div>

                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Description</span>
                    </label>
                    <textarea name="description" class="textarea textarea-bordered w-full h-32 resize-y" rows="4"
                        placeholder="Enter category description (optional)">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="form-control">
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
@endsection