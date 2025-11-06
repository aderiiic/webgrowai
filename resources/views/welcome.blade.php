
@extends('layouts.guest', ['title' => 'WebGrow AI – AI som skriver, publicerar och optimerar för svenska företag'])

@section('content')
    <main x-data="{ demoOpen: false }">
        <!-- Hero Section -->
        <section class="relative h-[850px] md:h-[750px] overflow-hidden">
            <!-- Background Image with Parallax Effect -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');">
                <!-- Gradient Overlay for better text readability -->
                <div class="absolute inset-0 bg-gradient-to-br from-slate-900/85 via-indigo-900/75 to-purple-900/85"></div>

                <!-- Subtle animated gradient overlay -->
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20 animate-pulse"></div>

                <!-- Subtle pattern overlay -->
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;utf8,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.05%22%3E%3Ccircle%20cx%3D%227%22%20cy%3D%227%22%20r%3D%221%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
            </div>

            <!-- Content Container -->
            <div class="relative z-10 h-full flex items-center pb-20">
                <div class="max-w-7xl mx-auto px-4 w-full">
                    <div class="text-center max-w-4xl mx-auto">
                        <!-- Target Badge -->
                        <div class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold bg-white/10 backdrop-blur-md text-white border border-white/20 mb-8 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ __('homepage.hero_badge') }}
                        </div>

                        <!-- Main Headline -->
                        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold leading-tight text-white mb-6 drop-shadow-2xl">
                            {{ __('homepage.hero_headline_part1') }}
                            <span class="bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                        {{ __('homepage.hero_headline_part2') }}
                    </span>
                            {{ __('homepage.hero_headline_part3') }}
                        </h1>

                        <!-- Subtitle -->
                        <p class="text-lg sm:text-xl md:text-2xl text-slate-200 leading-relaxed mb-10 max-w-3xl mx-auto drop-shadow-lg">
                            {{ __('homepage.hero_sub') }}
                        </p>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                            @if(Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="group inline-flex items-center justify-center px-10 py-5 bg-white text-slate-900 font-bold rounded-2xl shadow-2xl hover:shadow-3xl hover:scale-105 transition-all duration-300 text-lg border-4 border-white/30">
                                    {{ __('messages.cta_start_trial') }}
                                    <svg class="w-6 h-6 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            @endif

                            <button @click="demoOpen = true"
                                    class="group inline-flex items-center justify-center px-10 py-5 bg-white/10 backdrop-blur-md text-white font-bold rounded-2xl border-2 border-white/40 hover:bg-white/20 hover:border-white/60 transition-all duration-300 text-lg shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8z"/>
                                </svg>
                                {{ __('messages.cta_see_demo') }}
                            </button>
                        </div>

                        <!-- Trust Indicators -->
                        <div class="flex flex-wrap items-center justify-center gap-6 text-white/90">
                            <div class="flex items-center gap-2 text-sm font-medium bg-white/5 backdrop-blur-sm px-4 py-2 rounded-full border border-white/10">
                                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ __('homepage.hero_info_1') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm font-medium bg-white/5 backdrop-blur-sm px-4 py-2 rounded-full border border-white/10">
                                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ __('homepage.hero_info_2') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm font-medium bg-white/5 backdrop-blur-sm px-4 py-2 rounded-full border border-white/10">
                                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                <span>{{ __('homepage.hero_info_3') }}</span>
                            </div>
                            <a href="#pricing" class="text-sm font-semibold text-blue-300 hover:text-blue-200 transition-colors underline decoration-2 underline-offset-4">
                                {{ __('homepage.hero_info_4') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 hidden md:flex">
                <a href="#features" class="flex flex-col items-center text-white/70 hover:text-white transition-all duration-300 group">
                    <span class="text-sm font-medium mb-3 tracking-wide uppercase">Scrolla ner</span>
                    <div class="w-8 h-12 border-2 border-white/30 rounded-full flex items-start justify-center pt-2 group-hover:border-white/50 transition-colors">
                        <div class="w-1.5 h-3 bg-white/70 rounded-full animate-bounce group-hover:bg-white"></div>
                    </div>
                </a>
            </div>
        </section>

        <!-- Target Industries -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                        {{ __('homepage.target_title') }}
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                        {{ __('homepage.target_subtitle') }}
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                    <!-- Tjänsteföretag -->
                    <div class="group text-center p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-200/50 hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">{{ __('homepage.target_card_title_1') }}</h3>
                        <p class="text-sm text-slate-600">{{ __('homepage.target_card_desc_1') }}</p>
                    </div>

                    <!-- E-handel -->
                    <div class="group text-center p-6 bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl border border-emerald-200/50 hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">{{ __('homepage.target_card_title_2') }}</h3>
                        <p class="text-sm text-slate-600">{{ __('homepage.target_card_desc_2') }}</p>
                    </div>

                    <!-- Hälsa & Vård -->
                    <div class="group text-center p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-200/50 hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">{{ __('homepage.target_card_title_3') }}</h3>
                        <p class="text-sm text-slate-600">{{ __('homepage.target_card_desc_3') }}</p>
                    </div>

                    <!-- Hantverk & Bygg -->
                    <div class="group text-center p-6 bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl border border-orange-200/50 hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">{{ __('homepage.target_card_title_4') }}g</h3>
                        <p class="text-sm text-slate-600">{{ __('homepage.target_card_desc_4') }}</p>
                    </div>
                </div>

                <!-- Results Grid -->
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center p-8 bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl border border-emerald-200/50">
                        <div class="text-4xl font-bold text-emerald-600 mb-2">+127%</div>
                        <h4 class="text-lg font-bold text-slate-800 mb-2">{{ __('homepage.target_stats_1_title') }}</h4>
                        <p class="text-slate-600">{{ __('homepage.target_stats_1_desc') }}</p>
                    </div>

                    <div class="text-center p-8 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl border border-indigo-200/50">
                        <div class="text-4xl font-bold text-indigo-600 mb-2">+89%</div>
                        <h4 class="text-lg font-bold text-slate-800 mb-2">{{ __('homepage.target_stats_2_title') }}</h4>
                        <p class="text-slate-600">{{ __('homepage.target_stats_2_desc') }}</p>
                    </div>

                    <div class="text-center p-8 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-200/50">
                        <div class="text-4xl font-bold text-purple-600 mb-2">12h</div>
                        <h4 class="text-lg font-bold text-slate-800 mb-2">{{ __('homepage.target_stats_3_title') }}</h4>
                        <p class="text-slate-600">{{ __('homepage.target_stats_3_desc') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Key Features -->
        <section class="py-20 bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/50" id="features">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                        {{ __('homepage.features_title') }}
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                        {{ __('homepage.features_subtitle') }}
                    </p>
                </div>

                <div class="grid lg:grid-cols-2 gap-16 items-center mb-16">
                    <!-- Text & Image Generation -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-800 mb-4">
                                {{ __('homepage.features_part_title') }}
                            </h3>
                            <p class="text-lg text-slate-600 mb-6">
                                {{ __('homepage.features_part_desc') }}
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-800 mb-1">{{ __('homepage.features_part_subtitle_1') }}</h4>
                                    <p class="text-slate-600">{{ __('homepage.features_part_subtitle_1_desc') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m2-2l1.586-1.586a2 2 0 012.828 0L22 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-800 mb-1">{{ __('homepage.features_part_subtitle_2') }}</h4>
                                    <p class="text-slate-600">{{ __('homepage.features_part_subtitle_2_desc') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-800 mb-1">{{ __('homepage.features_part_subtitle_3') }}</h4>
                                    <p class="text-slate-600">{{ __('homepage.features_part_subtitle_3_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="relative">
                        <img
                            src="https://webbiab.s3.eu-north-1.amazonaws.com/webgrowai/laptop-webgrowai-2-nytt.png"
                            alt="WebGrow AI funktioner - Textgenerering och bildgenerering"
                            class="w-full max-w-lg mx-auto drop-shadow-2xl"
                            loading="lazy"
                        />
                    </div>
                </div>

                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <!-- Image -->
                    <div class="relative lg:order-1">
                        <img
                            src="https://webbiab.s3.eu-north-1.amazonaws.com/webgrowai/laptop-webgrowai-1-nytt.png"
                            alt="WebGrow AI schemaläggning - Automatisk publicering på sociala medier"
                            class="w-full max-w-lg mx-auto drop-shadow-2xl"
                            loading="lazy"
                        />
                    </div>

                    <!-- Publishing & Scheduling -->
                    <div class="space-y-8 lg:order-2">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-800 mb-4">
                                {{ __('homepage.features_part_title_2') }}
                            </h3>
                            <p class="text-lg text-slate-600 mb-6">
                                {{ __('homepage.features_part_desc_2') }}
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-800 mb-1">{{ __('homepage.features_part_subtitle_4') }}</h4>
                                    <p class="text-slate-600">{{ __('homepage.features_part_subtitle_4_desc') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-800 mb-1">{{ __('homepage.features_part_subtitle_5') }}</h4>
                                    <p class="text-slate-600">{{ __('homepage.features_part_subtitle_5_desc') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-800 mb-1">{{ __('homepage.features_part_subtitle_6') }}</h4>
                                    <p class="text-slate-600">{{ __('homepage.features_part_subtitle_6_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                        {{ __('homepage.why_title') }}
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                        {{ __('homepage.why_subtitle') }}
                    </p>
                </div>

                <!-- NEW: Honest expectations section -->
                <div class="max-w-4xl mx-auto mb-16">
                    <div class="bg-gradient-to-br from-slate-50 to-blue-50 rounded-2xl p-8 border border-slate-200">
                        <h3 class="text-2xl font-bold text-slate-800 mb-6 text-center">{{ __('homepage.honest_title') }}</h3>

                        <div class="grid md:grid-cols-2 gap-8">
                            <!-- What we're NOT -->
                            <div>
                                <h4 class="font-semibold text-red-600 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('homepage.honest_not_title') }}
                                </h4>
                                <ul class="space-y-3 text-slate-700">
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ __('homepage.honest_not_1') }}</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ __('homepage.honest_not_2') }}</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ __('homepage.honest_not_3') }}</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- What we ARE -->
                            <div>
                                <h4 class="font-semibold text-emerald-600 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ __('homepage.honest_yes_title') }}
                                </h4>
                                <ul class="space-y-3 text-slate-700">
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ __('homepage.honest_yes_1') }}</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ __('homepage.honest_yes_2') }}</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ __('homepage.honest_yes_3') }}</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ __('homepage.honest_yes_4') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-8 mb-16">
                    <!-- Better than competitors -->
                    <div class="text-center p-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-200/50">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-4">{{ __('homepage.why_card_1_title') }}</h3>
                        <ul class="text-left space-y-2 text-slate-600">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('homepage.why_card_1_p1') }}
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('homepage.why_card_1_p2') }}
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('homepage.why_card_1_p3') }}
                            </li>
                        </ul>
                    </div>

                    <!-- Better price -->
                    <div class="text-center p-8 bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl border border-emerald-200/50">
                        <div class="w-16 h-16 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-4">{{ __('homepage.why_card_2_title') }}</h3>
                        <ul class="text-left space-y-2 text-slate-600">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('homepage.why_card_2_p1') }}
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('homepage.why_card_2_p2') }}
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('homepage.why_card_2_p3') }}
                            </li>
                        </ul>
                    </div>

                    <!-- Trust & Security -->
                    <div class="text-center p-8 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-200/50">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-4">{{ __('homepage.why_card_3_title') }}</h3>
                        <ul class="text-left space-y-2 text-slate-600">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('homepage.why_card_3_p1') }}
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('homepage.why_card_3_p2') }}
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ __('homepage.why_card_3_p3') }}
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- All-in-one benefit -->
                <div class="bg-gradient-to-r from-slate-800 to-indigo-900 rounded-2xl p-12 text-center text-white">
                    <h3 class="text-3xl font-bold mb-6">{{ __('homepage.why_card_banner_title') }}</h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                        <div>
                            <div class="text-2xl font-bold mb-2">{{ __('homepage.why_card_banner_subtitle_1') }}</div>
                            <p class="text-sm text-slate-300">{{ __('homepage.why_card_banner_desc_1') }}r</p>
                        </div>
                        <div>
                            <div class="text-2xl font-bold mb-2">{{ __('homepage.why_card_banner_subtitle_2') }}</div>
                            <p class="text-sm text-slate-300">{{ __('homepage.why_card_banner_desc_2') }}</p>
                        </div>
                        <div>
                            <div class="text-2xl font-bold mb-2">{{ __('homepage.why_card_banner_subtitle_3') }}</div>
                            <p class="text-sm text-slate-300">{{ __('homepage.why_card_banner_desc_3') }}</p>
                        </div>
                        <div>
                            <div class="text-2xl font-bold mb-2">{{ __('homepage.why_card_banner_subtitle_4') }}</div>
                            <p class="text-sm text-slate-300">{{ __('homepage.why_card_banner_desc_4') }}</p>
                        </div>
                    </div>
                    <button @click="demoOpen = true" class="inline-flex items-center px-8 py-4 bg-white text-slate-800 font-semibold rounded-xl hover:bg-slate-100 transition-colors">
                        {{ __('homepage.why_card_cta') }}
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </button>
                </div>
            </div>
        </section>


        <!-- Pricing -->
        <!-- Pricing -->
        <section id="pricing" class="py-20 bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-900 relative overflow-hidden" x-data="{ annual: false }">
            <!-- Animated background pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.03&quot;%3E%3Ccircle cx=&quot;7&quot; cy=&quot;7&quot; r=&quot;1&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>

            <div class="relative max-w-7xl mx-auto px-4">
                <!-- Header -->
                <div class="text-center mb-16">
                    <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20 mb-6">
                        <svg class="w-4 h-4 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-sm font-medium text-white">{{ __('homepage.pricing_highlight') }}</span>
                    </div>

                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        {{ __('homepage.pricing_title') }}
                    </h2>
                    <p class="text-xl text-slate-300 max-w-3xl mx-auto mb-8">
                        {{ __('homepage.pricing_subtitle') }}
                    </p>

                    <!-- Billing Toggle -->
                    <div class="inline-flex items-center bg-white/10 backdrop-blur-md rounded-full p-1.5 border border-white/20">
                        <button @click="annual = false"
                                :class="!annual ? 'bg-white text-slate-900' : 'text-white'"
                                class="px-6 py-2.5 rounded-full font-semibold text-sm transition-all duration-300">
                            Månadsvis
                        </button>
                        <button @click="annual = true"
                                :class="annual ? 'bg-white text-slate-900' : 'text-white'"
                                class="px-6 py-2.5 rounded-full font-semibold text-sm transition-all duration-300 relative">
                            Årsvis
                            <span class="absolute -top-2 -right-2 bg-emerald-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">-15%</span>
                        </button>
                    </div>
                </div>

                <!-- Pricing Cards -->
                <div class="grid lg:grid-cols-3 gap-8 max-w-6xl mx-auto mb-16">
                    <!-- Starter Plan -->
                    <div class="group relative bg-white/5 backdrop-blur-xl rounded-3xl border border-white/10 hover:border-white/30 transition-all duration-500 overflow-hidden hover:scale-105 hover:shadow-2xl">
                        <!-- Glow effect on hover -->
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 via-purple-500/0 to-pink-500/0 group-hover:from-blue-500/10 group-hover:via-purple-500/10 group-hover:to-pink-500/10 transition-all duration-500"></div>

                        <div class="relative p-8">
                            <!-- Plan Header -->
                            <div class="text-center mb-8">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>

                                <h3 class="text-2xl font-bold text-white mb-2">Starter</h3>
                                <p class="text-slate-400 text-sm">Perfekt för småföretag</p>
                            </div>

                            <!-- Price -->
                            <div class="text-center mb-8 pb-8 border-b border-white/10">
                                <div class="flex items-baseline justify-center gap-1">
                                    <span class="text-5xl font-bold text-white" x-text="annual ? '212' : '249'"></span>
                                    <span class="text-slate-400 text-lg">kr</span>
                                </div>
                                <div class="text-slate-400 text-sm mt-2">
                                    <span x-show="!annual">per månad</span>
                                    <span x-show="annual">per månad (faktureras årligen)</span>
                                </div>
                                <div class="text-center mt-3">
                                    <span class="inline-block px-3 py-1 bg-blue-500/20 text-blue-300 text-xs font-semibold rounded-full border border-blue-500/30">
                                        5 000 credits/månad
                                    </span>
                                </div>
                            </div>

                            <!-- Features -->
                            <ul class="space-y-3 mb-8">
                                <li class="flex items-start gap-3 text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <span class="font-medium">Sociala medier-texter</span>
                                        <p class="text-xs text-slate-400 mt-0.5">Facebook, Instagram & LinkedIn</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3 text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <span class="font-medium">Bloggtexter & produktbeskrivningar</span>
                                        <p class="text-xs text-slate-400 mt-0.5">SEO-optimerat innehåll som säljer</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3 text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <span class="font-medium">AI-bildgenerering</span>
                                        <p class="text-xs text-slate-400 mt-0.5">Unika bilder till dina inlägg</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3 text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <span class="font-medium">Automatisk publicering</span>
                                        <p class="text-xs text-slate-400 mt-0.5">WordPress & sociala medier</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3 text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span><strong>1 webbplats</strong>, 1 användare</span>
                                </li>
                            </ul>

                            <!-- CTA Button -->
                            <a href="{{ route('register') }}" class="block w-full py-4 px-6 bg-white/10 backdrop-blur-sm text-white font-semibold rounded-xl border border-white/20 hover:bg-white/20 hover:border-white/40 transition-all duration-300 text-center group-hover:scale-105">
                                Prova gratis i 14 dagar
                            </a>
                        </div>
                    </div>

                    <!-- Growth Plan - Featured -->
                    <div class="group relative bg-gradient-to-br from-indigo-600 to-purple-600 rounded-3xl border-2 border-white/20 hover:border-white/40 transition-all duration-500 overflow-hidden scale-105 lg:scale-110 hover:scale-110 lg:hover:scale-115 shadow-2xl hover:shadow-purple-500/50">
                        <!-- Popular Badge - FIXED -->
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                            <div class="flex items-center gap-2 bg-gradient-to-r from-emerald-400 to-green-500 text-slate-900 px-6 py-2.5 rounded-full text-sm font-bold shadow-xl border-4 border-slate-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                                <span class="mt-1">Mest populär</span>
                            </div>
                        </div>

                        <!-- Animated glow -->
                        <div class="absolute inset-0 bg-gradient-to-br from-white/0 via-white/0 to-white/0 group-hover:from-white/10 group-hover:via-white/5 group-hover:to-white/10 transition-all duration-500"></div>

                        <div class="relative p-8 pt-12">
                            <!-- Plan Header -->
                            <div class="text-center mb-8">
                                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300 border border-white/30">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                </div>

                                <h3 class="text-3xl font-bold text-white mb-2">Growth</h3>
                                <p class="text-indigo-100 text-sm">För växande företag</p>
                            </div>

                            <!-- Price -->
                            <div class="text-center mb-8 pb-8 border-b border-white/20">
                                <div class="flex items-baseline justify-center gap-1">
                                    <span class="text-6xl font-bold text-white" x-text="annual ? '424' : '499'"></span>
                                    <span class="text-indigo-200 text-xl">kr</span>
                                </div>
                                <div class="text-indigo-100 text-sm mt-2">
                                    <span x-show="!annual">per månad</span>
                                    <span x-show="annual">per månad (faktureras årligen)</span>
                                </div>
                                <div class="text-center mt-3">
                                    <span class="inline-block px-3 py-1 bg-white/20 text-white text-xs font-semibold rounded-full border border-white/30">
                                        15 000 credits/månad
                                    </span>
                                </div>
                            </div>

                            <!-- Features -->
                            <ul class="space-y-3 mb-8">
                                <li class="flex items-start gap-3 text-white">
                                    <svg class="w-5 h-5 text-emerald-300 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium">Allt från Starter +</span>
                                </li>
                                <li class="flex items-start gap-3 text-white">
                                    <svg class="w-5 h-5 text-yellow-300 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <span class="font-semibold">Multi-text</span>
                                        <p class="text-xs text-indigo-100 mt-0.5">Skapa 25 texter samtidigt – perfekt för kampanjer</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3 text-white">
                                    <svg class="w-5 h-5 text-yellow-300 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <span class="font-semibold">SEO-optimering & nyckelordsanalys</span>
                                        <p class="text-xs text-indigo-100 mt-0.5">Rankningsbooost i Google</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3 text-white">
                                    <svg class="w-5 h-5 text-yellow-300 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <span class="font-semibold">Konverteringsanalys & lead tracking</span>
                                        <p class="text-xs text-indigo-100 mt-0.5">Förstå dina besökare – öka försäljningen</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3 text-white">
                                    <svg class="w-5 h-5 text-emerald-300 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium"><strong>3 webbplatser</strong>, 2 användare</span>
                                </li>
                            </ul>

                            <!-- CTA Button -->
                            <a href="{{ route('register') }}" class="block w-full py-4 px-6 bg-white text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transition-all duration-300 text-center group-hover:scale-105 shadow-xl hover:shadow-2xl">
                                Prova gratis i 14 dagar
                            </a>
                        </div>
                    </div>

                    <!-- Pro Plan -->
                    <div class="group relative bg-white/5 backdrop-blur-xl rounded-3xl border border-white/10 hover:border-white/30 transition-all duration-500 overflow-hidden hover:scale-105 hover:shadow-2xl">
                        <!-- Glow effect on hover -->
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/0 via-pink-500/0 to-red-500/0 group-hover:from-purple-500/10 group-hover:via-pink-500/10 group-hover:to-red-500/10 transition-all duration-500"></div>

                        <div class="relative p-8">
                            <!-- Plan Header -->
                            <div class="text-center mb-8">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                </div>

                                <h3 class="text-2xl font-bold text-white mb-2">Pro</h3>
                                <p class="text-slate-400 text-sm">För stora företag och byråer</p>
                            </div>

                            <!-- Price -->
                            <div class="text-center mb-8 pb-8 border-b border-white/10">
                                <div class="flex items-baseline justify-center gap-1">
                                    <span class="text-5xl font-bold text-white" x-text="annual ? '849' : '999'"></span>
                                    <span class="text-slate-400 text-lg">kr</span>
                                </div>
                                <div class="text-slate-400 text-sm mt-2">
                                    <span x-show="!annual">per månad</span>
                                    <span x-show="annual">per månad (faktureras årligen)</span>
                                </div>
                                <div class="text-center mt-3">
                                    <span class="inline-block px-3 py-1 bg-purple-500/20 text-purple-300 text-xs font-semibold rounded-full border border-purple-500/30">
                                        50 000 credits/månad
                                    </span>
                                </div>
                            </div>

                            <!-- Features -->
                            <ul class="space-y-3 mb-8">
                                <li class="flex items-start gap-3 text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Allt från Growth +</span>
                                </li>
                                <li class="flex items-start gap-3 text-slate-300">
                                    <svg class="w-5 h-5 text-purple-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <span class="font-semibold">Multi-text PRO</span>
                                        <p class="text-xs text-slate-400 mt-0.5">Skapa 50 texter samtidigt – skala snabbt</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3 text-slate-300">
                                    <svg class="w-5 h-5 text-purple-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <span class="font-semibold">Avancerad analys & rapporter</span>
                                        <p class="text-xs text-slate-400 mt-0.5">Djup insikt i vad som fungerar</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3 text-slate-300">
                                    <svg class="w-5 h-5 text-purple-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <span class="font-semibold">Prioriterad support & dedikerad kontakt</span>
                                        <p class="text-xs text-slate-400 mt-0.5">Få hjälp direkt när du behöver det</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3 text-slate-300">
                                    <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span><strong>10 webbplatser</strong>, 3 användare</span>
                                </li>
                            </ul>

                            <!-- CTA Button -->
                            <a href="{{ route('register') }}" class="block w-full py-4 px-6 bg-white/10 backdrop-blur-sm text-white font-semibold rounded-xl border border-white/20 hover:bg-white/20 hover:border-white/40 transition-all duration-300 text-center group-hover:scale-105">
                                Prova gratis i 14 dagar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- What credits include -->
                <div class="max-w-4xl mx-auto text-center mb-12">
                    <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 p-8">
                        <h3 class="text-2xl font-bold text-white mb-6">Vad ingår i dina credits?</h3>
                        <div class="grid md:grid-cols-3 gap-6 text-slate-300">
                            <div class="text-center">
                                <svg class="w-8 h-8 text-blue-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <h4 class="font-semibold text-white mb-2">AI-texter</h4>
                                <p class="text-sm">Blogginlägg, produktbeskrivningar, sociala medie-inlägg</p>
                            </div>
                            <div class="text-center">
                                <svg class="w-8 h-8 text-purple-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m2-2l1.586-1.586a2 2 0 012.828 0L22 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <h4 class="font-semibold text-white mb-2">AI-bilder</h4>
                                <p class="text-sm">Unika bilder skapade specifikt för ditt innehåll</p>
                            </div>
                            <div class="text-center">
                                <svg class="w-8 h-8 text-green-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                </svg>
                                <h4 class="font-semibold text-white mb-2">Publicering</h4>
                                <p class="text-sm">Automatisk publicering till alla dina kanaler</p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-white/10">
                            <a href="{{ route('pricing') }}" class="group inline-flex items-center text-blue-300 hover:text-blue-200 font-semibold transition-all duration-300">
                                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                                Jämför våra planer och vad som ingår
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Guarantee Banner -->
                <div class="max-w-4xl mx-auto">
                    <div class="relative bg-gradient-to-r from-emerald-500/20 via-green-500/20 to-teal-500/20 backdrop-blur-xl rounded-2xl border border-emerald-400/30 p-8 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/5 to-green-500/5"></div>
                        <div class="relative text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-500/20 backdrop-blur-sm rounded-full mb-4 border-2 border-emerald-400/50">
                                <svg class="w-8 h-8 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">{{ __('homepage.pricing_free_without_risk') }}</h3>
                            <p class="text-slate-300 text-lg">{{ __('homepage.pricing_test_it') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-20 bg-white" id="testimonials">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">{{ __('homepage.customers_title') }}</h2>
                    <p class="text-xl text-slate-600">{{ __('homepage.customers_subtitle') }}</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-gradient-to-br from-emerald-50 to-green-50 border border-emerald-200/50 rounded-2xl p-8">
                        <div class="flex text-emerald-500 mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <blockquote class="text-slate-700 mb-6 italic">
                            {{ __('homepage.customers_card_1_title') }}
                        </blockquote>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center text-white font-semibold">S</div>
                            <div class="ml-4">
                                <div class="font-semibold text-slate-800">{{ __('homepage.customers_card_1_customer') }}</div>
                                <div class="text-sm text-slate-600">{{ __('homepage.customers_card_1_customer_title') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-200/50 rounded-2xl p-8">
                        <div class="flex text-indigo-500 mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <blockquote class="text-slate-700 mb-6 italic">
                            {{ __('homepage.customers_card_2_title') }}
                        </blockquote>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center text-white font-semibold">J</div>
                            <div class="ml-4">
                                <div class="font-semibold text-slate-800">{{ __('homepage.customers_card_2_customer') }}</div>
                                <div class="text-sm text-slate-600">{{ __('homepage.customers_card_2_customer_title') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200/50 rounded-2xl p-8">
                        <div class="flex text-purple-500 mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <blockquote class="text-slate-700 mb-6 italic">
                            {{ __('homepage.customers_card_3_title') }}
                        </blockquote>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-semibold">A</div>
                            <div class="ml-4">
                                <div class="font-semibold text-slate-800">{{ __('homepage.customers_card_3_customer') }}</div>
                                <div class="text-sm text-slate-600">{{ __('homepage.customers_card_3_customer_title') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-20">
                    <div class="text-center mb-12">
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-8">{{ __('homepage.customers_title_2') }}</p>
                    </div>

                    <!-- Logo carousel with rotation effect -->
                    <div class="relative overflow-hidden rounded-2xl py-12">
                        <div class="flex animate-scroll">
                            <!-- First set of logos -->
                            <div class="flex items-center justify-center min-w-max px-8">
                                <!-- Svenska företag logotyper - gråskala med rotation -->
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-1">
                                        <img src="https://carilo.se/wp-content/uploads/2024/09/LOGGA-CARILO-PNG.png" alt="Carilo" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-2">
                                        <img src="https://webbi.se/wp-content/uploads/2025/07/Webbi-Logotype-Original-Blue.png" alt="Webbi" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-6">
                                        <img src="https://bymoi.se/cdn/shop/files/With_background_Black_logo_version_01_aa1d876f-2703-47e2-9e03-f9e136167492_650x326.png?v=1735120259" alt="CarOnSpot" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-1">
                                        <img src="https://scontent-vie1-1.xx.fbcdn.net/v/t39.30808-6/278241355_409461631179505_3184951711944655993_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=cKiCXMdON0AQ7kNvwF0Sr0r&_nc_oc=AdkONiD_WoP0DbWOj4HLhLiDOvItblIUXIIEhm097WaYsKDaeBgg5Jx8lV81hZAQGhU&_nc_zt=23&_nc_ht=scontent-vie1-1.xx&_nc_gid=-2dtD1nJlrYTb-uYcWODRw&oh=00_AfXJ55Mqdja1gKiS7AYWkqteT7WXn0SND5iIWll5CbLuUw&oe=68BC1315" alt="TheRightWay" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-4">
                                        <img src="https://honeyprince.se/cdn/shop/files/honeyprince-logo.png?v=1733995900&width=180" alt="Honeyprince" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-3">
                                        <img src="https://caronspot.com/storage/img/logotype-no-bg.png" alt="CarOnSpot" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-5">
                                        <img src="https://darm.se/cdn/shop/files/Darm-NoBG.png?v=1755973519&width=120" alt="Darm" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-4">
                                        <img src="https://scontent-vie1-1.xx.fbcdn.net/v/t39.30808-1/509355954_122121348812841568_8951393074492947963_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=2d3e12&_nc_ohc=clYzibfSZj4Q7kNvwHK_RqE&_nc_oc=Adky4VE1O6-Ct-YzFv2-lkAhq-aaaabGGlC9fiHi-NjG6CFqKqzBPjYySGWcM4YszZo&_nc_zt=24&_nc_ht=scontent-vie1-1.xx&_nc_gid=gGDkSbN5Yu61gMpluUM9MA&oh=00_AfXc5UW1fCtoHGVA0ksU9lDYYdLaDPcpkxIbhr_Z4HMMZA&oe=68BC1942" alt="Notisnook" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-4">
                                        <img src="https://webbiab.s3.eu-north-1.amazonaws.com/WebbiQR/WebbiQR+-+new+logo.png" alt="WebbiQR" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-4">
                                        <img src="https://rabattello-bucket.s3.eu-north-1.amazonaws.com/rabattello/rabattello-logo.png" alt="Rabattello" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                            </div>
                            <!-- Duplicate set for seamless loop -->
                            <div class="flex items-center justify-center min-w-max px-8">
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-1">
                                        <img src="https://carilo.se/wp-content/uploads/2024/09/LOGGA-CARILO-PNG.png" alt="Carilo" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-2">
                                        <img src="https://webbi.se/wp-content/uploads/2025/07/Webbi-Logotype-Original-Blue.png" alt="Webbi" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-6">
                                        <img src="https://bymoi.se/cdn/shop/files/With_background_Black_logo_version_01_aa1d876f-2703-47e2-9e03-f9e136167492_650x326.png?v=1735120259" alt="CarOnSpot" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-1">
                                        <img src="https://scontent-vie1-1.xx.fbcdn.net/v/t39.30808-6/278241355_409461631179505_3184951711944655993_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=cKiCXMdON0AQ7kNvwF0Sr0r&_nc_oc=AdkONiD_WoP0DbWOj4HLhLiDOvItblIUXIIEhm097WaYsKDaeBgg5Jx8lV81hZAQGhU&_nc_zt=23&_nc_ht=scontent-vie1-1.xx&_nc_gid=-2dtD1nJlrYTb-uYcWODRw&oh=00_AfXJ55Mqdja1gKiS7AYWkqteT7WXn0SND5iIWll5CbLuUw&oe=68BC1315" alt="TheRightWay" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-4">
                                        <img src="https://honeyprince.se/cdn/shop/files/honeyprince-logo.png?v=1733995900&width=180" alt="Honeyprince" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-3">
                                        <img src="https://caronspot.com/storage/img/logotype-no-bg.png" alt="CarOnSpot" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-5">
                                        <img src="https://darm.se/cdn/shop/files/Darm-NoBG.png?v=1755973519&width=120" alt="Darm" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-4">
                                        <img src="https://scontent-vie1-1.xx.fbcdn.net/v/t39.30808-1/509355954_122121348812841568_8951393074492947963_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=2d3e12&_nc_ohc=clYzibfSZj4Q7kNvwHK_RqE&_nc_oc=Adky4VE1O6-Ct-YzFv2-lkAhq-aaaabGGlC9fiHi-NjG6CFqKqzBPjYySGWcM4YszZo&_nc_zt=24&_nc_ht=scontent-vie1-1.xx&_nc_gid=gGDkSbN5Yu61gMpluUM9MA&oh=00_AfXc5UW1fCtoHGVA0ksU9lDYYdLaDPcpkxIbhr_Z4HMMZA&oe=68BC1942" alt="Notisnook" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-4">
                                        <img src="https://webbiab.s3.eu-north-1.amazonaws.com/WebbiQR/WebbiQR+-+new+logo.png" alt="WebbiQR" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                                <div class="group mx-8">
                                    <div class="w-32 h-20 flex items-center justify-center grayscale hover:grayscale-0 transition-all duration-500 hover:shadow-lg animate-float-4">
                                        <img src="https://rabattello-bucket.s3.eu-north-1.amazonaws.com/rabattello/rabattello-logo.png" alt="Rabattello" class="max-w-24 max-h-12 object-contain">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fade effects -->
                        <div class="absolute inset-y-0 left-0 w-20 bg-gradient-to-r from-white via-white/80 to-transparent pointer-events-none"></div>
                        <div class="absolute inset-y-0 right-0 w-20 bg-gradient-to-l from-white via-white/80 to-transparent pointer-events-none"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="py-20 bg-gradient-to-br from-slate-50 to-indigo-50" id="faq">
            <div class="max-w-4xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">{{ __('homepage.faq_title') }}</h2>
                    <p class="text-xl text-slate-600">{{ __('homepage.faq_subtitle') }}</p>
                </div>

                <div class="space-y-6">
                    <details class="group bg-white rounded-2xl shadow-lg overflow-hidden">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>{{ __('homepage.faq_q_1') }}</span>
                            <svg class="w-5 h-5 text-indigo-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600">
                            {{ __('homepage.faq_a_1') }}
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl shadow-lg overflow-hidden">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>{{ __('homepage.faq_q_2') }}</span>
                            <svg class="w-5 h-5 text-indigo-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600">
                            {{ __('homepage.faq_a_2') }}
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl shadow-lg overflow-hidden">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>{{ __('homepage.faq_q_3') }}</span>
                            <svg class="w-5 h-5 text-indigo-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600">
                            {{ __('homepage.faq_a_3') }}
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl shadow-lg overflow-hidden">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>{{ __('homepage.faq_q_4') }}</span>
                            <svg class="w-5 h-5 text-indigo-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600">
                            {{ __('homepage.faq_a_4') }}
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl shadow-lg overflow-hidden">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>{{ __('homepage.faq_q_4') }}</span>
                            <svg class="w-5 h-5 text-indigo-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600">
                            {{ __('homepage.faq_a_4') }}
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl shadow-lg overflow-hidden">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>{{ __('homepage.faq_q_5') }}</span>
                            <svg class="w-5 h-5 text-indigo-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600">
                            {{ __('homepage.faq_a_5') }}
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl shadow-lg overflow-hidden">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>{{ __('homepage.faq_q_6') }}</span>
                            <svg class="w-5 h-5 text-indigo-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600">
                            {{ __('homepage.faq_a_6') }}
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl shadow-lg overflow-hidden">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>{{ __('homepage.faq_q_7') }}</span>
                            <svg class="w-5 h-5 text-indigo-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600">
                            {{ __('homepage.faq_a_7') }}
                        </div>
                    </details>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-br from-indigo-900 via-purple-900 to-slate-900 relative overflow-hidden" id="kontakt">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="7" cy="7" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>

            <div class="relative max-w-4xl mx-auto px-4 text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-8">
                    {{ __('homepage.contact_title') }}
                </h2>
                <p class="text-xl text-slate-300 mb-12 max-w-2xl mx-auto">
                    {{ __('homepage.contact_subtitle') }}
                </p>

                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="group inline-flex items-center justify-center px-8 py-4 bg-white text-slate-900 font-bold rounded-xl shadow-2xl hover:shadow-3xl hover:scale-105 transition-all duration-300 text-lg">
                            {{ __('homepage.contact_cta_start_trial') }}
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    @endif

                    <button @click="demoOpen = true"
                            class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white text-white font-semibold rounded-xl hover:bg-white hover:text-slate-900 transition-all duration-300 text-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8z"/>
                        </svg>
                        {{ __('homepage.contact_cta_demo') }}
                    </button>
                </div>

                <div class="mt-8 text-slate-400 text-sm">
                    ✓ {{ __('homepage.contact_cta_14_days') }} • ✓ {{ __('homepage.contact_cta_no_credit_card') }} • ✓ {{ __('homepage.contact_cta_support') }}
                </div>
            </div>
        </section>

        <!-- Demo Modal -->
        <div x-show="demoOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="demoOpen"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="demoOpen = false"></div>

                <div x-show="demoOpen"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="flex items-start justify-between">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Boka demonstration</h3>
                            <button @click="demoOpen = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <form action="{{ route('demo.request') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Namn *</label>
                                <input type="text" name="name" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">E-post *</label>
                                <input type="email" name="email" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Företag</label>
                                <input type="text" name="company"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Meddelande (valfritt)</label>
                                <textarea name="notes" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                          placeholder="Vad vill du veta mer om?"></textarea>
                            </div>

                            <div class="flex gap-3 pt-4">
                                <button type="button" @click="demoOpen = false"
                                        class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                                    Avbryt
                                </button>
                                <button type="submit"
                                        class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-colors">
                                    Skicka förfrågan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float-1 { animation: float 3s ease-in-out infinite; }
        .animate-float-2 { animation: float 3s ease-in-out infinite 0.5s; }
        .animate-float-3 { animation: float 3s ease-in-out infinite 1s; }
        .animate-float-4 { animation: float 3s ease-in-out infinite 1.5s; }
        .animate-float-5 { animation: float 3s ease-in-out infinite 2s; }
        .animate-float-6 { animation: float 3s ease-in-out infinite 2.5s; }

        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
        .animate-scroll { animation: scroll 60s linear infinite; }
    </style>
@endsection
