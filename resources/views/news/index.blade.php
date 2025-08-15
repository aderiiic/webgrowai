@extends('layouts.guest', ['title' => 'Nyheter – WebGrow AI'])

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-10 space-y-6">
        <h1 class="text-2xl font-semibold">Nyheter</h1>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php($paginator = \App\Models\Post::query()->whereNotNull('published_at')->latest('published_at')->paginate(12))
            @forelse($paginator as $post)
                <a href="{{ route('news.show', $post->slug) }}" class="block p-5 border rounded-lg bg-white hover:shadow-sm">
                    <div class="text-xs text-gray-500">{{ optional($post->published_at)->format('Y-m-d') }}</div>
                    <div class="mt-1 font-semibold">{{ $post->title }}</div>
                    <p class="mt-2 text-sm text-gray-600 line-clamp-3">{{ $post->excerpt }}</p>
                </a>
            @empty
                <div class="text-sm text-gray-600">Inga inlägg ännu.</div>
            @endforelse
        </div>
        <div>
            {{ $paginator->withQueryString()->links() }}
        </div>
    </div>
@endsection
