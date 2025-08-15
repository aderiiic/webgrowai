@extends('layouts.guest', ['title' => 'Nyheter – WebGrow AI'])

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
        <!-- Header -->
        <div class="bg-white border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 py-12">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-slate-800 via-blue-800 to-indigo-800 bg-clip-text text-transparent mb-4">
                        Nyheter & Uppdateringar
                    </h1>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                        Håll dig uppdaterad med de senaste funktionerna, förbättringarna och insikterna från WebGrow AI
                    </p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 py-16">
            @php($paginator = \App\Models\Post::query()->whereNotNull('published_at')->latest('published_at')->paginate(12))

            @if($paginator->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach($paginator as $post)
                        <article class="group bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:border-blue-200 transition-all duration-300 overflow-hidden">
                            <div class="p-8">
                                <!-- Date & Category -->
                                <div class="flex items-center justify-between mb-4">
                                    <time class="text-sm font-medium text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                                        {{ optional($post->published_at)->format('j M Y') }}
                                    </time>
                                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                </div>

                                <!-- Title -->
                                <h2 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-blue-600 transition-colors duration-300 line-clamp-2">
                                    {{ $post->title }}
                                </h2>

                                <!-- Excerpt -->
                                <p class="text-slate-600 leading-relaxed mb-6 line-clamp-3">
                                    {{ $post->excerpt ?: 'Läs mer om denna uppdatering...' }}
                                </p>

                                <!-- Read more link -->
                                <a href="{{ route('news.show', $post->slug) }}"
                                   class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-700 transition-colors duration-200"
                                   data-lead-cta="news_read_more">
                                    Läs hela artikeln
                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            </div>

                            <!-- Hover effect border -->
                            <div class="h-1 bg-gradient-to-r from-blue-500 to-indigo-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-1">
                        {{ $paginator->withQueryString()->links('pagination::tailwind') }}
                    </div>
                </div>
            @else
                <!-- Empty state -->
                <div class="text-center py-20">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-2">Inga nyheter ännu</h3>
                        <p class="text-slate-600 mb-6">Vi arbetar på att få ut spännande uppdateringar till dig. Kom tillbaka snart!</p>
                        <a href="{{ route('welcome') }}"
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-colors duration-200"
                           data-lead-cta="news_empty_back_home">
                            Tillbaka till startsidan
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
