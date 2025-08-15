@extends('layouts.guest', ['title' => ($post->title ?? 'Nyhet').' – WebGrow AI'])

@section('content')
    <article class="max-w-3xl mx-auto px-4 py-10">
        <div class="text-xs text-gray-500">{{ optional($post->published_at)->format('Y-m-d') }}</div>
        <h1 class="text-3xl font-semibold mt-1">{{ $post->title }}</h1>
        @if($post->excerpt)
            <p class="mt-2 text-gray-600">{{ $post->excerpt }}</p>
        @endif
        <div class="prose max-w-none mt-6">
            {!! \Illuminate\Support\Str::of($post->body_md ?? '')->markdown() !!}
        </div>
    </article>
@endsection
