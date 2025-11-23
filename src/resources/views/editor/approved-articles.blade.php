@extends('layouts.editor')

@section('title', 'Approved Articles')

@section('content')
    <div class="mb-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-black mb-2 tracking-tight">Approved Articles</h1>
                <p class="text-base-content/70 text-lg">All approved articles from community members</p>
            </div>
        </div>
    </div>

    @livewire('approved-articles-list')
@endsection