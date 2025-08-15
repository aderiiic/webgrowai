@extends('layouts.guest', ['title' => ($post->title ?? 'Nyhet').' – WebGrow AI'])

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
        <!-- Back navigation -->
        <div class="bg-white border-b border-slate-200">
            <div class="max-w-4xl mx-auto px-4 py-6">
                <a href="{{ route('news.index') }}"
                   class="inline-flex items-center text-slate-600 hover:text-slate-800 font-medium transition-colors duration-200"
                   data-lead-cta="news_back_to_index">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Tillbaka till nyheter
                </a>
            </div>
        </div>

        <!-- Article content -->
        <article class="max-w-4xl mx-auto px-4 py-16">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-lg overflow-hidden">
                <!-- Article header -->
                <div class="px-8 py-12 border-b border-slate-200">
                    <!-- Date -->
                    <div class="flex items-center justify-between mb-6">
                        <time class="inline-flex items-center text-sm font-medium text-slate-500 bg-slate-100 px-4 py-2 rounded-full">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            {{ optional($post->published_at)->format('j F Y') }}
                        </time>
                        <div class="flex items-center text-emerald-600">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse mr-2"></div>
                            <span class="text-sm font-medium">Publicerad</span>
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-slate-800 via-blue-800 to-indigo-800 bg-clip-text text-transparent leading-tight mb-6">
                        {{ $post->title }}
                    </h1>

                    <!-- Excerpt -->
                    @if($post->excerpt)
                        <div class="text-xl text-slate-600 leading-relaxed p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                            {{ $post->excerpt }}
                        </div>
                    @endif
                </div>

                <!-- Article body -->
                <div class="px-8 py-12">
                    <div class="prose prose-lg prose-slate max-w-none
                                prose-headings:font-bold prose-headings:text-slate-800
                                prose-h1:text-3xl prose-h2:text-2xl prose-h3:text-xl
                                prose-p:text-slate-600 prose-p:leading-relaxed
                                prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline
                                prose-strong:text-slate-800 prose-strong:font-semibold
                                prose-ul:text-slate-600 prose-ol:text-slate-600
                                prose-li:marker:text-blue-500
                                prose-blockquote:border-l-4 prose-blockquote:border-blue-500 prose-blockquote:bg-blue-50 prose-blockquote:p-6 prose-blockquote:rounded-r-xl
                                prose-code:bg-slate-100 prose-code:text-slate-800 prose-code:px-1 prose-code:py-0.5 prose-code:rounded
                                prose-pre:bg-slate-900 prose-pre:text-slate-100">
                        {!! \Illuminate\Support\Str::of($post->body_md ?? '')->markdown() !!}
                    </div>
                </div>

                <!-- Article footer -->
                <div class="px-8 py-8 border-t border-slate-200 bg-gradient-to-r from-slate-50 to-blue-50">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-slate-600">
                            Gillade du denna artikel? <a href="{{ route('welcome') }}#pricing" class="text-blue-600 hover:text-blue-700 font-medium" data-lead-cta="news_article_cta">Se våra planer</a>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-slate-500">Dela:</span>
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(request()->url()) }}"
                               target="_blank"
                               class="p-2 bg-white border border-slate-300 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200"
                               data-lead-cta="news_share_twitter">
                                <svg class="w-4 h-4 text-slate-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                               target="_blank"
                               class="p-2 bg-white border border-slate-300 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200"
                               data-lead-cta="news_share_linkedin">
                                <svg class="w-4 h-4 text-slate-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related content or CTA -->
            <div class="mt-12 bg-white rounded-2xl border border-slate-200 shadow-lg p-8">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">Redo att komma igång?</h3>
                    <p class="text-slate-600 mb-6 max-w-2xl mx-auto">
                        Upptäck hur WebGrow AI kan hjälpa ditt företag att växa snabbare med automatiserad SEO, CRO och innehållsmarknadsföring.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('welcome') }}#pricing"
                           class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-colors duration-200 shadow-lg"
                           data-lead-cta="news_article_footer_pricing">
                            Se våra priser
                        </a>
                        <a href="{{ route('news.index') }}"
                           class="px-8 py-3 bg-white text-slate-700 font-semibold rounded-xl border border-slate-300 hover:bg-slate-50 transition-colors duration-200"
                           data-lead-cta="news_article_footer_more_news">
                            Läs fler nyheter
                        </a>
                    </div>
                </div>
            </div>
        </article>
    </div>
@endsection
