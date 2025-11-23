@extends('layouts.editor')

@section('title', 'Rejected Articles')
@section('header', 'Rejected Articles')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-base-content mb-2">Rejected Articles</h1>
            <p class="text-base-content/60">Manage articles that have been rejected by the editorial team.</p>
        </div>

        <livewire:rejected-articles-list />
    </div>
@endsection