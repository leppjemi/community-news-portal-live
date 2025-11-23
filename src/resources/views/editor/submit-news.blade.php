@extends('layouts.editor')

@section('title', 'Publish News')

@section('content')
    <div class="mb-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-black mb-2 tracking-tight">Publish News</h1>
                <p class="text-base-content/70 text-lg">
                    <span class="badge badge-warning badge-lg mr-2">Editor</span>
                    Your article will be published immediately
                </p>
            </div>
        </div>
    </div>

    @livewire('editor-news-submission-form')
@endsection