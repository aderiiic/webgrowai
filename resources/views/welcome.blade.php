
@extends('layouts.guest', ['title' => 'WebGrow AI – Mer trafik, fler leads, mindre handpåläggning'])

@section('content')
    <main x-data="{ demoOpen:false }">
        <!-- Hero -->
        <section class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-indigo-50 to-purple-100">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23f1f5f9" fill-opacity="0.4"%3E%3Ccircle cx="7" cy="7" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
            <div class="relative max-w-7xl mx-auto px-4 py-20 lg:py-32 grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-800 border border-indigo-200">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                        Lanserat! AI-driven marknadsföring
                    </div>
                    <h1 class="text-4xl md:text-6xl font-bold leading-tight bg-gradient-to-r from-gray-900 via-indigo-900 to-purple-900 bg-clip-text text-transparent">
                        Mer trafik. Fler leads. Mindre handpåläggning.
                    </h1>
                    <p class="text-xl text-slate-600 max-w-2xl leading-relaxed">
                        WebGrow AI sköter SEO‑förslag, CRO‑insikter, AI‑publicering till WordPress, Shopify & sociala kanaler – på autopilot.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="group relative px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 text-center"
                               data-lead-cta="hero_register">
                                <span class="relative z-10">Starta gratis idag</span>
                                <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 to-purple-700 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                            </a>
                        @endif
                        <button @click="demoOpen=true"
                                class="px-8 py-4 bg-white/80 backdrop-blur-sm text-slate-800 font-semibold rounded-xl border border-slate-200 hover:bg-white hover:shadow-lg transition-all duration-200 flex items-center justify-center"
                                data-lead-cta="hero_book_demo">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Boka demo
                        </button>
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 text-sm text-slate-500">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            14 dagar gratis
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Ingen bindningstid
                        </div>
                        <a href="#pricing" class="text-indigo-600 hover:text-indigo-700 font-medium" data-lead-cta="hero_see_pricing">
                            Se priser →
                        </a>
                    </div>
                </div>
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-slate-200/50 shadow-2xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-slate-800">Live flöden som körs</h3>
                        <div class="flex items-center text-emerald-600">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse mr-2"></div>
                            <span class="text-sm font-medium">Aktiv</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-lg border border-emerald-200">
                            <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-slate-800">SEO-optimering</h4>
                                <p class="text-sm text-slate-600">AI föreslår meta‑titel/description för dina sidor</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-200">
                            <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-slate-800">CRO-insikter</h4>
                                <p class="text-sm text-slate-600">Rubriker, CTA och formulärplacering optimeras</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-200">
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-slate-800">AI-publicering</h4>
                                <p class="text-sm text-slate-600">WordPress/Shopify/Custom sidor automation</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <a href="{{ route('news.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-medium transition-colors">
                            Läs vad som är nytt
                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Platform Integration -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                        Integrerar med dina befintliga verktyg
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                        Enkelt och säkert via oAuth. Som Shopify partner erbjuder vi gratis onboarding och support.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8 mb-16">
                    <!-- WordPress -->
                    <div class="group text-center p-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21.469,14.825l-5.406-9.237C15.529,4.826,15.001,4.5,14.47,4.5h-4.94c-0.531,0-1.058,0.326-1.592,1.088L2.531,14.825c-0.531,0.766-0.531,2.013,0,2.775L7.938,26.837C8.472,27.603,9,27.928,9.531,27.928h4.938c0.531,0,1.059-0.325,1.592-1.086l5.406-9.237C21.999,16.838,21.999,15.591,21.469,14.825z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">WordPress</h3>
                        <p class="text-slate-600 leading-relaxed">Säker oAuth-anslutning för automatisk optimering av innehåll och SEO-inställningar.</p>
                        <div class="mt-4 inline-flex items-center text-blue-600 font-medium">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                            Säker integration
                        </div>
                    </div>

                    <!-- Shopify -->
                    <div class="group text-center p-8 bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl border border-emerald-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M15.337 2.001c-0.907,0-1.76,0.655-2.332,1.603-0.458-0.174-0.985-0.266-1.562-0.266-2.156,0-4.003,1.299-5.229,3.284-1.117-0.426-2.162-0.37-2.805,0.273-0.764,0.763-0.764,2.212,0,2.975l8.834,8.834c0.381,0.381,0.893,0.596,1.438,0.596s1.057-0.215,1.438-0.596l8.834-8.834c0.764-0.763,0.764-2.212,0-2.975-0.643-0.643-1.688-0.699-2.805-0.273-1.226-1.985-3.073-3.284-5.229-3.284-0.577,0-1.104,0.092-1.562,0.266C17.097,2.656,16.244,2.001,15.337,2.001z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Shopify</h3>
                        <p class="text-slate-600 leading-relaxed">Shopify Partner med specialiserad e-handelsoptimering och produktmarknadsföring.</p>
                        <div class="mt-4 inline-flex items-center text-emerald-600 font-medium">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                            <a href="https://webbi.se" target="_blank" class="hover:underline">Webbi - Shopify Partner</a>
                        </div>
                    </div>

                    <!-- Custom Sites -->
                    <div class="group text-center p-8 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Custom Sidor</h3>
                        <p class="text-slate-600 leading-relaxed">API-integration för anpassade webbplatser och plattformar via säker oAuth-autentisering.</p>
                        <div class="mt-4 inline-flex items-center text-purple-600 font-medium">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                            Flexibel API
                        </div>
                    </div>
                </div>

                <!-- Support Features -->
                <div class="bg-gradient-to-r from-slate-50 to-indigo-50 rounded-2xl p-8 text-center">
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">Fullständig support från dag ett</h3>
                    <div class="grid md:grid-cols-3 gap-6 mb-8">
                        <div class="flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold text-slate-800">Gratis onboarding</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold text-slate-800">Personlig support</span>
                        </div>
                        <div class="flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold text-slate-800">Shopify partner</span>
                        </div>
                    </div>
                    <p class="text-slate-600 max-w-2xl mx-auto">
                        Vi hjälper dig att komma igång snabbt och säkert. Som Shopify partner har vi djup expertis inom e-handel och kan anpassa lösningen efter dina specifika behov.
                    </p>
                </div>
            </div>
        </section>

        <!-- Free Website Program -->
        <!-- Free Website Program - Refined Premium Version -->
        <section class="py-32 bg-slate-900 relative overflow-hidden">
            <!-- Subtle background elements -->
            <div class="absolute inset-0">
                <div class="absolute top-40 right-20 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 left-20 w-80 h-80 bg-indigo-500/3 rounded-full blur-3xl"></div>
            </div>

            <!-- Minimal grid pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.02"%3E%3Ccircle cx="7" cy="7" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>

            <div class="relative max-w-7xl mx-auto px-4">
                <div class="grid lg:grid-cols-2 gap-20 items-center">
                    <div class="space-y-12">
                        <!-- Clean badge -->
                        <div class="inline-flex items-center px-6 py-2 rounded-full bg-slate-800/50 border border-slate-700/50 backdrop-blur-sm">
                            <div class="w-2 h-2 bg-indigo-400 rounded-full mr-3 animate-pulse"></div>
                            <span class="text-slate-300 font-medium">Exklusivt erbjudande</span>
                        </div>

                        <div class="space-y-8">
                            <h2 class="text-5xl md:text-6xl font-bold leading-tight text-white">
                                Gratis webbsida med
                                <span class="text-indigo-400">Growth årsplan</span>
                            </h2>

                            <p class="text-xl text-slate-400 leading-relaxed max-w-xl">
                                Prenumerera på Growth årsplan och få en helt gratis, professionell webbsida byggd av vårt team. Designad för konvertering och SEO-optimerad från start.
                            </p>
                        </div>

                        <!-- Clean features list -->
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="w-6 h-6 rounded-full bg-indigo-500/20 border border-indigo-500/40 flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                    <svg class="w-3 h-3 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold mb-1">Professionell design & utveckling</h4>
                                    <p class="text-slate-500 text-sm">Byggs av vårt expertteam med fokus på konvertering</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-6 h-6 rounded-full bg-indigo-500/20 border border-indigo-500/40 flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                    <svg class="w-3 h-3 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold mb-1">SEO-optimerad från start</h4>
                                    <p class="text-slate-500 text-sm">Teknisk SEO och prestandaoptimering inkluderat</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-6 h-6 rounded-full bg-indigo-500/20 border border-indigo-500/40 flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                    <svg class="w-3 h-3 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold mb-1">Fullständigt integrerat</h4>
                                    <p class="text-slate-500 text-sm">WebGrow AI fungerar direkt utan extra konfiguration</p>
                                </div>
                            </div>
                        </div>

                        <!-- Minimal value proposition -->
                        <div class="bg-slate-800/30 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-slate-500 text-sm mb-1">Normalt värde</p>
                                    <p class="text-slate-300 text-2xl font-bold">25 000 - 50 000 kr</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-indigo-400 text-sm font-medium mb-1">För dig</p>
                                    <p class="text-white text-2xl font-bold">Helt gratis</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Minimal premium form -->
                    <div class="relative">
                        <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl p-8 border border-slate-700/50" x-data="{ email: '' }">
                            <div class="text-center mb-8">
                                <h3 class="text-2xl font-bold text-white mb-3">Intresserad? Hör av dig</h3>
                                <p class="text-slate-400">Vi kontaktar dig inom 24h med mer information</p>
                            </div>

                            <form class="space-y-6" @submit.prevent="console.log('Form submitted:', email)">
                                <div>
                                    <label for="free-website-email" class="block text-sm font-medium text-slate-300 mb-3">
                                        Din e-postadress
                                    </label>
                                    <input
                                        type="email"
                                        id="free-website-email"
                                        x-model="email"
                                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                        placeholder="din@epost.se"
                                        required>
                                </div>

                                <button
                                    type="submit"
                                    class="w-full px-6 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-[1.01] active:scale-[0.99] shadow-lg"
                                    data-lead-cta="free_website_signup">
                                    Få mer information
                                </button>

                                <p class="text-xs text-slate-500 text-center">
                                    Vi kontaktar dig inom 24h med mer information om programmet och nästa steg.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                        Allt du behöver för att växa snabbare
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                        Kraftfulla AI-verktyg som arbetar tillsammans för att maximera din ROI
                    </p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="group p-8 bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl border border-emerald-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Nyckelordsoptimering</h3>
                        <p class="text-slate-600 leading-relaxed">SerpAPI‑baserad rankingkoll, AI‑förslag på keywords & meta—Apply till WP med ett klick.</p>
                    </div>
                    <div class="group p-8 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl border border-indigo-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">CRO‑insikter</h3>
                        <p class="text-slate-600 leading-relaxed">Förbättringar på rubriker, CTA, formulär. Ett klick för att uppdatera sidor och öka konverteringar.</p>
                    </div>
                    <div class="group p-8 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">AI‑publicering</h3>
                        <p class="text-slate-600 leading-relaxed">Generera & schemalägg innehåll till WordPress, Shopify & Instagram automatiskt.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing -->
        <section id="pricing" class="py-20 bg-gradient-to-br from-slate-50 to-indigo-50">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Priser som växer med dig</h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto">Börja litet och skala upp. 14 dagar gratis, ingen bindningstid.</p>
                    <div class="inline-flex items-center mt-6 px-4 py-2 bg-gradient-to-r from-emerald-100 to-green-100 rounded-full">
                        <span class="text-emerald-800 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            15% rabatt vid årsprenumeration på alla planer
                        </span>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    <!-- Starter -->
                    <div class="relative bg-white rounded-2xl border border-slate-200 shadow-lg hover:shadow-xl transition-all duration-300 p-8">
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-slate-600 mb-2">Starter</h3>
                            <div class="mb-4">
                                <div class="flex items-center justify-center gap-2 mb-2">
                                    <span class="text-lg text-slate-400 line-through">1 290 kr</span>
                                    <span class="text-4xl font-bold text-slate-800">790 kr</span>
                                    <span class="text-slate-600">/mån</span>
                                </div>
                                <div class="text-xs text-orange-600 font-medium">Founders-pris första året</div>
                                <div class="text-xs text-slate-500 mt-1">Sedan 1 290 kr/mån • 1 098 kr/mån (årlig)</div>
                            </div>
                            <p class="text-slate-600 mb-6">För mindre sajter som vill igång snabbt.</p>
                        </div>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                500 AI‑genereringar/mån
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                50 publiceringar/mån
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                2 SEO‑audits/mån
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Lead tracking: 5 000 events/mån
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Grundläggande statistik
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Koppling till sociala medier
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                1 sajt, 2 användare
                            </li>
                        </ul>
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="w-full inline-flex items-center justify-center px-6 py-3 bg-slate-800 text-white font-semibold rounded-xl hover:bg-slate-900 transition-colors duration-200"
                               data-lead-cta="pricing_starter_register">Prova gratis</a>
                        @endif
                    </div>

                    <!-- Growth - Most Popular -->
                    <div class="relative bg-white rounded-2xl border-2 border-indigo-500 shadow-2xl hover:shadow-3xl transition-all duration-300 p-8 scale-105">
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 whitespace-nowrap">
                    <span class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                        Populär + gratis webbsida
                    </span>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-indigo-600 mb-2">Growth</h3>
                            <div class="mb-4">
                                <div class="flex items-center justify-center gap-2 mb-2">
                                    <span class="text-lg text-slate-400 line-through">3 990 kr</span>
                                    <span class="text-4xl font-bold text-slate-800">2 490 kr</span>
                                    <span class="text-slate-600">/mån</span>
                                </div>
                                <div class="text-xs text-orange-600 font-medium">Founders-pris första året</div>
                                <div class="text-xs text-slate-500 mt-1">Sedan 3 990 kr/mån • 3 392 kr/mån (årlig)</div>
                            </div>
                            <p class="text-slate-600 mb-6">För växande bolag med flera flöden.</p>
                        </div>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                2 500 AI‑genereringar/mån
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                200 publiceringar/mån
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                8 SEO‑audits/mån
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Lead tracking: 25 000 events/mån
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Avancerad statistik & analys
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Koppling till sociala medier
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                3 sajter, 5 användare
                            </li>
                            <li class="flex items-center text-yellow-600 bg-yellow-50 p-2 rounded-lg border border-yellow-200">
                                <svg class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                                </svg>
                                <strong>Gratis webbsida vid årsplan!</strong>
                            </li>
                        </ul>
                        <div class="space-y-3">
                            <button @click="demoOpen=true"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg"
                                    data-lead-cta="pricing_growth_demo">Boka demo</button>
                        </div>
                    </div>

                    <!-- Pro -->
                    <div class="relative bg-white rounded-2xl border border-slate-200 shadow-lg hover:shadow-xl transition-all duration-300 p-8">
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-slate-600 mb-2">Pro</h3>
                            <div class="mb-4">
                                <div class="flex items-center justify-center gap-2 mb-2">
                                    <span class="text-lg text-slate-400 line-through">12 900 kr</span>
                                    <span class="text-4xl font-bold text-slate-800">8 900 kr</span>
                                    <span class="text-slate-600">/mån</span>
                                </div>
                                <div class="text-xs text-orange-600 font-medium">Founders-pris första året</div>
                                <div class="text-xs text-slate-500 mt-1">Sedan 12 900 kr/mån • 10 965 kr/mån (årlig)</div>
                            </div>
                            <p class="text-slate-600 mb-6">För byråer och större team.</p>
                        </div>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                10 000 AI‑genereringar/mån
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                1 000 publiceringar/mån
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                30 SEO‑audits/mån
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Lead tracking: 100 000 events/mån
                            </li>
                            <li class="flex items-center text-slate-700">
                                <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                10 sajter, 20 användare
                            </li>
                        </ul>
                        <button @click="demoOpen=true"
                                class="w-full px-6 py-3 bg-slate-800 text-white font-semibold rounded-xl hover:bg-slate-900 transition-colors duration-200"
                                data-lead-cta="pricing_pro_demo">Boka demo</button>
                    </div>
                </div>

                <div class="max-w-4xl mx-auto mt-12 bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 rounded-2xl p-6">
                    <div class="text-center">
                        <h3 class="text-lg font-bold text-orange-900 mb-2 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            Founders-erbjudande
                        </h3>
                        <p class="text-orange-800">
                            <strong>Exklusivt för early adopters:</strong> Upp till 69% rabatt första året! Hjälp oss forma framtidens AI-marknadsföring.
                        </p>
                    </div>
                </div>

                <div class="text-center text-sm text-slate-500 mt-6 max-w-4xl mx-auto">
                    Tillägg: AI 0,30 kr/st, WP‑publicering 0,80 kr/st, audit 99 kr/st, leads 0,001 kr/event. Alla priser exkl. moms.
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section id="testimonials" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Vad säger kunderna?</h2>
                    <p class="text-xl text-slate-600">Riktig feedback från riktiga företag som växer</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-gradient-to-br from-emerald-50 to-green-50 border border-emerald-200/50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300">
                        <div class="flex text-emerald-500 mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                        <blockquote class="text-slate-700 mb-6 leading-relaxed italic">
                            "Vi ökade våra demo‑bokningar med 42% på två månader. Att kunna tillämpa förslag direkt till WordPress sparar oss timmar varje vecka."
                        </blockquote>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-r from-emerald-400 to-green-400 rounded-full flex items-center justify-center text-white font-semibold">S</div>
                            <div class="ml-4">
                                <div class="font-semibold text-slate-800">Sara</div>
                                <div class="text-sm text-slate-600">Marketing Lead, SaaS‑bolag</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-200/50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300">
                        <div class="flex text-indigo-500 mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                        <blockquote class="text-slate-700 mb-6 leading-relaxed italic">
                            "SEO‑förslagen är konkreta och träffsäkra. Vi fick snabb effekt på flera viktiga sidor och bättre CTR redan första månaden."
                        </blockquote>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-r from-indigo-400 to-purple-400 rounded-full flex items-center justify-center text-white font-semibold">J</div>
                            <div class="ml-4">
                                <div class="font-semibold text-slate-800">Johan</div>
                                <div class="text-sm text-slate-600">E‑commerce Manager, E‑handel</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200/50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300">
                        <div class="flex text-purple-500 mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                        <blockquote class="text-slate-700 mb-6 leading-relaxed italic">
                            "Veckodigesten ger teamet en tydlig plan och sparar massor av tid inför varje vecka. Kunderna märker skillnaden direkt."
                        </blockquote>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-white font-semibold">A</div>
                            <div class="ml-4">
                                <div class="font-semibold text-slate-800">Anna</div>
                                <div class="text-sm text-slate-600">Content Lead, Byrå</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section id="faq" class="py-20 bg-gradient-to-br from-slate-50 to-indigo-50">
            <div class="max-w-4xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Vanliga frågor</h2>
                    <p class="text-xl text-slate-600">Få svar på det du undrar över</p>
                </div>
                <div class="space-y-4">
                    <details class="group bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>Behöver jag låsa upp allt från start?</span>
                            <svg class="w-5 h-5 text-slate-500 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6">
                            <p class="text-slate-600 leading-relaxed">Nej, du kan börja på Starter och uppgradera när behoven växer. Alla planer ger dig full tillgång till plattformen – bara med olika volymer och funktioner.</p>
                        </div>
                    </details>
                    <details class="group bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>Hur fungerar publiceringen till WordPress och Shopify?</span>
                            <svg class="w-5 h-5 text-slate-500 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6">
                            <p class="text-slate-600 leading-relaxed">Du kopplar din WP med ett säkert app‑lösenord eller Shopify via oAuth. Därefter kan du tillämpa AI‑förslag direkt till sidor/texter med ett klick. Ingen FTP eller teknisk kunskap krävs.</p>
                        </div>
                    </details>
                    <details class="group bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>Är det bindningstid?</span>
                            <svg class="w-5 h-5 text-slate-500 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6">
                            <p class="text-slate-600 leading-relaxed">Ingen bindningstid alls. 14 dagar gratis att testa – avsluta när som helst. Vi tror på att leverera värde, inte att låsa in kunder.</p>
                        </div>
                    </details>
                    <details class="group bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>Vad händer om jag överskrider mina kvoter?</span>
                            <svg class="w-5 h-5 text-slate-500 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6">
                            <p class="text-slate-600 leading-relaxed">Du kan antingen uppgradera till nästa plan eller betala för extra användning enligt våra tilläggspriser. Du får alltid en varning innan något debiteras.</p>
                        </div>
                    </details>
                    <details class="group bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                        <summary class="cursor-pointer p-6 font-semibold text-slate-800 flex items-center justify-between">
                            <span>Hur fungerar det gratis webbsideprogrammet?</span>
                            <svg class="w-5 h-5 text-slate-500 group-open:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-6 pb-6">
                            <p class="text-slate-600 leading-relaxed">När du prenumererar på Growth årsplan bygger vårt team en professionell webbsida åt dig helt gratis. Den är SEO-optimerad och integrerad med WebGrow AI från start. Normalt värde 25 000-50 000 kr.</p>
                        </div>
                    </details>
                </div>
            </div>
        </section>

        <!-- Book a demo modal -->
        <div x-show="demoOpen" x-cloak class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div @click.outside="demoOpen=false" class="bg-white rounded-2xl max-w-md w-full p-8 shadow-2xl">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Boka en demo</h3>
                    <p class="text-slate-600">Fyll i så återkommer vi inom 24h</p>
                </div>
                <form class="space-y-4" method="POST" action="{{ route('demo.request') }}">
                    @csrf
                    <div>
                        <input type="text" name="name" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Ditt namn" required>
                    </div>
                    <div>
                        <input type="email" name="email" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Din e‑post" required>
                    </div>
                    <div>
                        <input type="text" name="company" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Företag (valfritt)">
                    </div>
                    <div>
                        <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none" placeholder="Vad vill du fokusera på i demon? (valfritt)"></textarea>
                    </div>
                    <div class="flex items-center justify-between gap-3 pt-4">
                        <button type="button" class="px-6 py-3 text-slate-600 font-medium rounded-xl border border-slate-300 hover:bg-slate-50 transition-colors" @click="demoOpen=false">Avbryt</button>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-colors shadow-lg" data-lead-cta="book_demo_submit">Skicka</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Blog teaser -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex items-center justify-between mb-12">
                    <div>
                        <h3 class="text-3xl font-bold text-slate-800 mb-2">Senaste nytt</h3>
                        <p class="text-slate-600">Håll dig uppdaterad med de senaste funktionerna</p>
                    </div>
                    <a href="{{ route('news.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-semibold transition-colors">
                        Visa alla
                        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    @foreach(\App\Models\Post::query()->whereNotNull('published_at')->latest('published_at')->take(3)->get() as $post)
                        <a href="{{ route('news.show', $post->slug) }}" class="group block bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg hover:border-indigo-200 transition-all duration-300">
                            <div class="text-sm text-slate-500 mb-3">{{ optional($post->published_at)->format('j M Y') }}</div>
                            <h4 class="text-lg font-semibold text-slate-800 mb-3 group-hover:text-indigo-600 transition-colors">{{ $post->title }}</h4>
                            <p class="text-slate-600 line-clamp-3 leading-relaxed">{{ $post->excerpt }}</p>
                            <div class="flex items-center mt-4 text-indigo-600 font-medium text-sm group-hover:text-indigo-700 transition-colors">
                                Läs mer
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
@endsection
