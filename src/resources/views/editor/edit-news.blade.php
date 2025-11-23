@extends('layouts.editor')

@section('title', 'Edit News')

@section('content')
    <div class="mb-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-black mb-2 tracking-tight">Edit News</h1>
                <p class="text-base-content/70 text-lg">Update your published article</p>
            </div>
        </div>
    </div>

    @livewire('editor-news-submission-form', ['postId' => $postId])
@endsection