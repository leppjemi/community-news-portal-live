@extends('layouts.editor')

@section('title', 'Edit Community Article')

@section('content')
    <div class="mb-10">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('editor.approved-articles') }}" class="btn btn-ghost btn-circle">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl md:text-4xl font-black tracking-tight">Edit Community Article</h1>
                <p class="text-base-content/70 text-lg">Editing article published by another community member</p>
            </div>
        </div>

        <div class="alert alert-warning mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <h3 class="font-bold">Editing Community Content</h3>
                <div class="text-sm">You are editing an article published by another community member. Changes will be
                    visible immediately on the live website.</div>
            </div>
        </div>
    </div>

    @livewire('editor-news-submission-form', ['postId' => $postId, 'redirectRoute' => 'editor.approved-articles'])
@endsection