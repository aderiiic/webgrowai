
@extends('layouts.guest', ['title' => 'WebGrow AI – AI som skriver, publicerar och optimerar för svenska företag'])

@section('content')
    <main x-data="{ demoOpen: false }">
        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/50">
            <!-- Subtle background pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;utf8,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23f1f5f9%22%20fill-opacity%3D%220.3%22%3E%3Ccircle%20cx%3D%227%22%20cy%3D%227%22%20r%3D%221%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>

            <div class="relative max-w-7xl mx-auto px-4 py-16 sm:py-24 lg:py-32">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <!-- Left Column - Content -->
                    <div class="space-y-8">
                        <!-- Target Badge -->
                        <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200/50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ __('messages.hero_badge') }}
                        </div>

                        <!-- Main Headline -->
                        <div class="space-y-6">
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight text-gray-900">
                                {{ __('messages.hero_headline_part1') }}
                                <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                    {{ __('messages.hero_headline_part2') }}
                                </span>
                                {{ __('messages.hero_headline_part3') }}
                            </h1>

                            <p class="text-xl text-slate-600 leading-relaxed max-w-2xl">
                                {{ __('messages.hero_sub') }}
                            </p>
                        </div>

                        <!-- Key Benefits -->
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3 p-3 bg-white/60 backdrop-blur-sm rounded-xl border border-white/40">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-800 text-sm">Smart textgenerering</h3>
                                    <p class="text-xs text-slate-600">AI anpassad för svensk bransch</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-3 bg-white/60 backdrop-blur-sm rounded-xl border border-white/40">
                                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m2-2l1.586-1.586a2 2 0 012.828 0L22 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-800 text-sm">AI-bildgenerering</h3>
                                    <p class="text-xs text-slate-600">Professionella bilder till texter</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-3 bg-white/60 backdrop-blur-sm rounded-xl border border-white/40">
                                <div class="w-8 h-8 bg-gradient-to-r from-emerald-500 to-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-800 text-sm">Schemaläggning</h3>
                                    <p class="text-xs text-slate-600">Automatisk publicering</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-3 bg-white/60 backdrop-blur-sm rounded-xl border border-white/40">
                                <div class="w-8 h-8 bg-gradient-to-r from-amber-500 to-orange-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-800 text-sm">Branschanalys</h3>
                                    <p class="text-xs text-slate-600">Trender och insikter</p>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            @if(Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                                    {{ __('messages.cta_start_trial') }}
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            @endif

                            <button @click="demoOpen = true"
                                    class="inline-flex items-center justify-center px-8 py-4 bg-white/90 backdrop-blur-sm text-slate-800 font-semibold rounded-xl border border-slate-200 hover:bg-white hover:shadow-md hover:border-slate-300 transition-all duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8z"/>
                                </svg>
                                {{ __('messages.cta_see_demo') }}
                            </button>
                        </div>

                        <!-- Trust Indicators -->
                        <div class="flex flex-wrap items-center gap-6 text-sm text-slate-600 pt-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">Inget kreditkort krävs</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">Svensk support</span>
                            </div>
                            <a href="#pricing" class="text-indigo-600 hover:text-indigo-700 font-medium transition-colors">
                                Från 390 kr/mån →
                            </a>
                        </div>
                    </div>

                    <!-- Right Column - Dashboard Preview -->
                    <div class="relative lg:order-2">
                        <div class="relative z-10 transform hover:scale-105 transition-all duration-700">
                            <img
                                src="https://webbiab.s3.eu-north-1.amazonaws.com/webgrowai/laptop-webgrowai-1-nytt.png"
                                alt="WebGrow AI Dashboard - Smart innehållsgenerering för svenska företag"
                                class="w-full max-w-lg mx-auto drop-shadow-2xl"
                                loading="eager"
                            />

                            <!-- Floating Elements -->
                            <div class="absolute -top-6 -right-6 bg-white rounded-xl shadow-xl p-4 border border-emerald-200 animate-pulse">
                                <div class="flex items-center text-emerald-600 font-semibold text-sm">
                                    <div class="w-3 h-3 bg-emerald-500 rounded-full mr-2 animate-pulse"></div>
                                    <span>AI arbetar...</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Genererar innehåll</p>
                            </div>

                            <div class="absolute -bottom-6 -left-6 bg-white rounded-xl shadow-xl p-4 border border-blue-200">
                                <div class="flex items-center text-blue-600 font-semibold text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>23 texter redo</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Publiceras imorgon</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Target Industries -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                        Fungerar för alla typer av svenska företag
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                        Vår AI är tränad på svenska affärstexter och förstår olika branscher och målgrupper
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
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Tjänsteföretag</h3>
                        <p class="text-sm text-slate-600">Konsulter, jurister, revisorer, fastighetsmäklare</p>
                    </div>

                    <!-- E-handel -->
                    <div class="group text-center p-6 bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl border border-emerald-200/50 hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">E-handel</h3>
                        <p class="text-sm text-slate-600">Webshoppar, produktförsäljning, butiker online</p>
                    </div>

                    <!-- Hälsa & Vård -->
                    <div class="group text-center p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-200/50 hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Hälsa & Vård</h3>
                        <p class="text-sm text-slate-600">Tandläkare, kliniker, vårdcentraler</p>
                    </div>

                    <!-- Hantverk & Bygg -->
                    <div class="group text-center p-6 bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl border border-orange-200/50 hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Hantverk & Bygg</h3>
                        <p class="text-sm text-slate-600">Byggfirmor, elektriker, VVS, målare</p>
                    </div>
                </div>

                <!-- Results Grid -->
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center p-8 bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl border border-emerald-200/50">
                        <div class="text-4xl font-bold text-emerald-600 mb-2">+127%</div>
                        <h4 class="text-lg font-bold text-slate-800 mb-2">Högre engagemang</h4>
                        <p class="text-slate-600">AI-innehåll presterar bättre än manuellt skrivna texter</p>
                    </div>

                    <div class="text-center p-8 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl border border-indigo-200/50">
                        <div class="text-4xl font-bold text-indigo-600 mb-2">+89%</div>
                        <h4 class="text-lg font-bold text-slate-800 mb-2">Fler leads</h4>
                        <p class="text-slate-600">Branschspecifika texter attraherar rätt kunder</p>
                    </div>

                    <div class="text-center p-8 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-200/50">
                        <div class="text-4xl font-bold text-purple-600 mb-2">12h</div>
                        <h4 class="text-lg font-bold text-slate-800 mb-2">Sparad tid/vecka</h4>
                        <p class="text-slate-600">Fokusera på att driva företaget framåt</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Key Features -->
        <section class="py-20 bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/50" id="features">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                        Allt under ett tak
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                        Slipp jonglera med flera verktyg. WebGrow AI är din kompletta lösning för innehållsmarknadsföring.
                    </p>
                </div>

                <div class="grid lg:grid-cols-2 gap-16 items-center mb-16">
                    <!-- Text & Image Generation -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-800 mb-4">
                                Smart text- och bildgenerering
                            </h3>
                            <p class="text-lg text-slate-600 mb-6">
                                AI som förstår svenska och din bransch. Skapar texter och bilder som faktiskt engagerar dina kunder.
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
                                    <h4 class="text-lg font-semibold text-slate-800 mb-1">Textgenerering för alla behov</h4>
                                    <p class="text-slate-600">Blogginlägg, produkttexter, sociala medier-inlägg, nyhetsbrev</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m2-2l1.586-1.586a2 2 0 012.828 0L22 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-800 mb-1">Professionell bildgenerering</h4>
                                    <p class="text-slate-600">AI-genererade bilder som matchar dina texter perfekt</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-800 mb-1">Branschanalys och trender</h4>
                                    <p class="text-slate-600">AI analyserar din bransch och föreslår populära ämnen</p>
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
                            src="https://webbiab.s3.eu-north-1.amazonaws.com/webgrowai/laptop-webgrowai-3-nytt.png"
                            alt="WebGrow AI schemaläggning - Automatisk publicering på sociala medier"
                            class="w-full max-w-lg mx-auto drop-shadow-2xl"
                            loading="lazy"
                        />
                    </div>

                    <!-- Publishing & Scheduling -->
                    <div class="space-y-8 lg:order-2">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-800 mb-4">
                                Automatisk publicering & schemaläggning
                            </h3>
                            <p class="text-lg text-slate-600 mb-6">
                                Publicera direkt eller schemalägg innehåll för senare. Hantera alla dina kanaler från en plats.
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
                                    <h4 class="text-lg font-semibold text-slate-800 mb-1">Smart schemaläggning</h4>
                                    <p class="text-slate-600">AI föreslår optimala publiceringstider för max räckvidd</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-800 mb-1">Direktpublicering</h4>
                                    <p class="text-slate-600">WordPress, Shopify, Facebook, Instagram, LinkedIn</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-800 mb-1">Full kontroll</h4>
                                    <p class="text-slate-600">Du godkänner alltid innan publicering</p>
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
                        Varför välja WebGrow AI?
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                        Vi sticker ut genom att fokusera på svenska företag och leverera mer än bara text
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8 mb-16">
                    <!-- Better than competitors -->
                    <div class="text-center p-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-200/50">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-4">Bättre än konkurrenterna</h3>
                        <ul class="text-left space-y-2 text-slate-600">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Tränad på svenska texter
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Branschspecifik kunskap
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Mäter och optimerar
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
                        <h3 class="text-xl font-bold text-slate-800 mb-4">Bättre pris</h3>
                        <ul class="text-left space-y-2 text-slate-600">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Från 390 kr/mån
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Billigare än anställd
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Ingen bindningstid
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
                        <h3 class="text-xl font-bold text-slate-800 mb-4">Trygghet & Säkerhet</h3>
                        <ul class="text-left space-y-2 text-slate-600">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                GDPR-kompatibel
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Svensk support
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Du kontrollerar allt
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- All-in-one benefit -->
                <div class="bg-gradient-to-r from-slate-800 to-indigo-900 rounded-2xl p-12 text-center text-white">
                    <h3 class="text-3xl font-bold mb-6">Allt under ett tak - ingen krångel</h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                        <div>
                            <div class="text-2xl font-bold mb-2">1 verktyg</div>
                            <p class="text-sm text-slate-300">Istället för 5-10 olika tjänster</p>
                        </div>
                        <div>
                            <div class="text-2xl font-bold mb-2">1 faktura</div>
                            <p class="text-sm text-slate-300">Enkel ekonomi och administration</p>
                        </div>
                        <div>
                            <div class="text-2xl font-bold mb-2">1 support</div>
                            <p class="text-sm text-slate-300">Ring oss när du behöver hjälp</p>
                        </div>
                        <div>
                            <div class="text-2xl font-bold mb-2">1 inloggning</div>
                            <p class="text-sm text-slate-300">Hantera allt från samma ställe</p>
                        </div>
                    </div>
                    <button @click="demoOpen = true" class="inline-flex items-center px-8 py-4 bg-white text-slate-800 font-semibold rounded-xl hover:bg-slate-100 transition-colors">
                        Se hur enkelt det är
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </button>
                </div>
            </div>
        </section>

        <!-- Pricing -->
        <section id="pricing" class="py-20 bg-gradient-to-br from-slate-50 to-indigo-50">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Enkel prissättning</h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto">Välj det paket som passar ditt företag bäst. Inga dolda kostnader.</p>

                    <!-- Annual discount notice -->
                    <div class="mt-6 inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 rounded-full text-sm font-medium border border-emerald-200/50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        15% extra rabatt på årsabonnemang
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto mb-12">
                    <!-- Starter -->
                    <div class="relative bg-white rounded-2xl border border-slate-200 shadow-lg p-8 flex flex-col h-full">
                        <div class="text-center mb-8">
                            <h3 class="text-lg font-semibold text-slate-600 mb-2">Starter</h3>
                            <div class="mb-4">
                                <div class="flex items-center justify-center gap-2 mb-2">
                                    <span class="text-lg text-slate-400 line-through">590 kr</span>
                                    <span class="text-4xl font-bold text-slate-800">390 kr</span>
                                </div>
                                <div class="text-slate-600 text-sm">
                                    <span>/mån</span>
                                    <span class="block text-xs text-emerald-600 font-medium">Årspris: 332 kr/mån</span>
                                </div>
                                <div class="text-xs text-orange-600 font-medium">Early bird-pris</div>
                            </div>
                            <p class="text-slate-600">Perfekt för små företag</p>
                        </div>

                        <ul class="space-y-3 mb-8 flex-1">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">500 AI-texter/månad</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">25 bildgenereringar/månad</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">50 schemalagda publiceringar</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">Facebook, Instagram, LinkedIn & din webbplats</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">1 webbplats</span>
                            </li>
                        </ul>

                        <div class="mt-auto">
                            <a href="{{ route('register') }}" class="w-full inline-flex items-center justify-center px-6 py-3 bg-slate-800 text-white font-semibold rounded-xl hover:bg-slate-900 transition-colors">
                                Starta 14 dagar gratis
                            </a>
                        </div>
                    </div>

                    <!-- Growth - Popular -->
                    <div class="relative bg-white rounded-2xl border-2 border-indigo-500 shadow-2xl p-8 scale-105 flex flex-col h-full">
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                            <span class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                                Mest populär
                            </span>
                        </div>

                        <div class="text-center mb-8">
                            <h3 class="text-lg font-semibold text-indigo-600 mb-2">Growth</h3>
                            <div class="mb-4">
                                <div class="flex items-center justify-center gap-2 mb-2">
                                    <span class="text-lg text-slate-400 line-through">1 490 kr</span>
                                    <span class="text-4xl font-bold text-slate-800">990 kr</span>
                                </div>
                                <div class="text-slate-600 text-sm">
                                    <span>/mån</span>
                                    <span class="block text-xs text-emerald-600 font-medium">Årspris: 842 kr/mån</span>
                                </div>
                                <div class="text-xs text-orange-600 font-medium">Early bird-pris</div>
                            </div>
                            <p class="text-slate-600">För växande företag</p>
                        </div>

                        <ul class="space-y-3 mb-8 flex-1">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">2 500 AI-texter/månad</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">100 bildgenereringar/månad</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">200 schemalagda publiceringar</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">Möjlighet till <a class="text-purple-800 hover:text-purple-600" href="{{ route('free-website') }}">gratis webbsida</a></span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">3 webbplatser</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-indigo-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700 font-semibold">SEO & CRO-analys</span>
                            </li>
                        </ul>

                        <div class="mt-auto">
                            <a href="{{ route('register') }}" class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-colors">
                                Starta 14 dagar gratis
                            </a>
                        </div>
                    </div>

                    <!-- Pro -->
                    <div class="relative bg-white rounded-2xl border border-slate-200 shadow-lg p-8 flex flex-col h-full">
                        <div class="text-center mb-8">
                            <h3 class="text-lg font-semibold text-slate-600 mb-2">Pro</h3>
                            <div class="mb-4">
                                <div class="flex items-center justify-center gap-2 mb-2">
                                    <span class="text-lg text-slate-400 line-through">3 990 kr</span>
                                    <span class="text-4xl font-bold text-slate-800">2 990 kr</span>
                                </div>
                                <div class="text-slate-600 text-sm">
                                    <span>/mån</span>
                                    <span class="block text-xs text-emerald-600 font-medium">Årspris: 2 542 kr/mån</span>
                                </div>
                                <div class="text-xs text-orange-600 font-medium">Early bird-pris</div>
                            </div>
                            <p class="text-slate-600">För stora företag</p>
                        </div>

                        <ul class="space-y-3 mb-8 flex-1">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">10 000 AI-texter/månad</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">500 bildgenereringar/månad</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">1 000 schemalagda publiceringar</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">10 webbplatser</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-purple-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700 font-semibold">Avancerad analys</span>
                            </li>
                        </ul>

                        <div class="mt-auto">
                            <button @click="demoOpen = true" class="w-full px-6 py-3 bg-slate-800 text-white font-semibold rounded-xl hover:bg-slate-900 transition-colors">
                                Kontakta oss
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Guarantee -->
                <div class="text-center bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 rounded-2xl p-8">
                    <h3 class="text-xl font-bold text-emerald-900 mb-2">14 dagar gratis - utan risk</h3>
                    <p class="text-emerald-800">Testa alla funktioner. Ingen bindningstid. Avsluta när som helst.</p>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-20 bg-white" id="testimonials">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Vad säger kunderna?</h2>
                    <p class="text-xl text-slate-600">Riktig feedback från svenska företag</p>
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
                            "Våra kundförfrågningar ökade med 42% på två månader. AI:n skriver verkligen texter som säljer."
                        </blockquote>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center text-white font-semibold">S</div>
                            <div class="ml-4">
                                <div class="font-semibold text-slate-800">Sara</div>
                                <div class="text-sm text-slate-600">VD, Konsultbolag</div>
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
                            "Sparar 8 timmar i veckan och får bättre resultat än när jag skrev själv. Otroligt!"
                        </blockquote>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center text-white font-semibold">J</div>
                            <div class="ml-4">
                                <div class="font-semibold text-slate-800">Johan</div>
                                <div class="text-sm text-slate-600">Marknadschef, E-handel</div>
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
                            "Äntligen svenskt innehåll som låter naturligt. Branschkunskapen är imponerande."
                        </blockquote>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-semibold">A</div>
                            <div class="ml-4">
                                <div class="font-semibold text-slate-800">Anna</div>
                                <div class="text-sm text-slate-600">Grundare, Tech-startup</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-20">
                    <div class="text-center mb-12">
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-8">Används av flera svenska företag</p>
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
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Vanliga frågor</h2>
                    <p class="text-xl text-slate-600">Svar på det du undrar över</p>
                </div>

                <div class="space-y-6">
                    <details class="group bg-white rounded-2xl shadow-lg overflow-hidden">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>Måste jag börja på en dyr plan?</span>
                            <svg class="w-5 h-5 text-indigo-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600">
                            Nej. De flesta börjar på Starter (390 kr/mån) och uppgraderar när volymen växer.
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl shadow-lg overflow-hidden">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>Kan jag avsluta när jag vill?</span>
                            <svg class="w-5 h-5 text-indigo-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600">
                            Ja. Ingen bindningstid. Avsluta när som helst.
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl shadow-lg overflow-hidden">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>Låter AI-texterna naturliga på svenska?</span>
                            <svg class="w-5 h-5 text-indigo-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600">
                            Ja. Vår AI är tränad specifikt på svenska affärstexter och förstår olika branscher.
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl shadow-lg overflow-hidden">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>Publiceras innehåll automatiskt?</span>
                            <svg class="w-5 h-5 text-indigo-500 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6 text-slate-600">
                            Nej. Du godkänner alltid innan publicering. Du har full kontroll.
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
                    Redo att börja växa?
                </h2>
                <p class="text-xl text-slate-300 mb-12 max-w-2xl mx-auto">
                    Gå med hundratals svenska företag som redan använder WebGrow AI för att skapa bättre innehåll på mindre tid.
                </p>

                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="group inline-flex items-center justify-center px-8 py-4 bg-white text-slate-900 font-bold rounded-xl shadow-2xl hover:shadow-3xl hover:scale-105 transition-all duration-300 text-lg">
                            Starta gratis idag
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
                        Se demo först
                    </button>
                </div>

                <div class="mt-8 text-slate-400 text-sm">
                    ✓ 14 dagar gratis • ✓ Inget kreditkort krävs • ✓ Svensk support
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
