@extends('layouts.editor')

@section('title', 'Review Queue')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl lg:text-5xl font-bold mb-2">Review Queue</h1>
        <p class="text-base-content/70 text-lg">Review and approve pending news submissions</p>
    </div>

    @livewire('review-queue')
@endsection