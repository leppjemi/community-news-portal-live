@extends('layouts.editor')

@section('title', 'My Submissions')

@section('content')
    <div class="mb-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-black mb-2 tracking-tight">My Submissions</h1>
                <p class="text-base-content/70 text-lg">Manage your published articles</p>
            </div>
            <a href="{{ route('editor.submit-news') }}" class="btn btn-warning shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Publish New
            </a>
        </div>
    </div>

    @livewire('editor-submissions-list')
@endsection