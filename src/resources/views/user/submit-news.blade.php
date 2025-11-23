@extends('layouts.user')

@section('title', isset($postId) ? 'Edit News' : 'Submit News')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl lg:text-5xl font-bold mb-2">{{ isset($postId) ? 'Edit News' : 'Submit News' }}</h1>
        <p class="text-base-content/70 text-lg">
            {{ isset($postId) ? 'Update your news article' : 'Share your news with the community' }}</p>
    </div>

    @livewire('news-submission-form', ['postId' => $postId ?? null])
@endsection