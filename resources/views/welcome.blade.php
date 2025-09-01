
@extends('layouts.guest', ['title' => 'WebGrow AI – Mer trafik, fler leads, mindre handpåläggning'])

@section('content')
    <main x-data="{ demoOpen:false }">
        <!-- Hero -->
        <section class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-indigo-50 to-purple-100">
            <!-- Subtil bakgrundsmönster -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;utf8,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23f1f5f9%22%20fill-opacity%3D%220.4%22%3E%3Ccircle%20cx%3D%227%22%20cy%3D%227%22%20r%3D%221%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>

            <!-- Huvudinnehåll -->
            <div class="relative max-w-7xl mx-auto px-4 py-16 sm:py-20 lg:py-28">
                <div class="grid lg:grid-cols-2 gap-8 lg:gap-16 items-center">

                    <!-- Vänster kolumn - Text -->
                    <div class="space-y-6 sm:space-y-8">
                        <!-- Badge -->
                        <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white/80 backdrop-blur-sm text-indigo-800 border border-indigo-200 shadow-sm">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                            Nu lanserat i Sverige
                        </div>

                        <!-- Huvudrubrik -->
                        <div class="space-y-4">
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight text-gray-900">
                                Automatiserad
                                <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            digital tillväxt
                        </span>
                                för svenska företag
                            </h1>

                            <p class="text-lg sm:text-xl text-slate-600 leading-relaxed max-w-xl">
                                AI som sköter SEO-optimering, innehållsproduktion och konverteringsförbättringar automatiskt.
                                Medan du fokuserar på ditt företag.
                            </p>
                        </div>

                        <!-- CTA-knappar -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            @if(Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 text-center"
                                   data-lead-cta="hero_register">
                                    Testa gratis i 14 dagar
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            @endif

                            <a href="#demo-form"
                               @click="demoOpen=true"
                               class="inline-flex items-center justify-center px-8 py-4 bg-white/90 backdrop-blur-sm text-slate-800 font-semibold rounded-xl border border-slate-200 hover:bg-white hover:shadow-md hover:border-slate-300 transition-all duration-300"
                               data-lead-cta="hero_book_demo">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Se demo (3 min)
                            </a>
                        </div>

                        <!-- Fördelar -->
                        <div class="flex flex-wrap items-center gap-6 text-sm text-slate-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">Inget kreditkort krävs</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">Uppsäg när som helst</span>
                            </div>
                            <a href="#pricing" class="text-indigo-600 hover:text-indigo-700 font-medium transition-colors" data-lead-cta="hero_pricing">
                                Från 390 kr/mån →
                            </a>
                        </div>
                    </div>

                    <!-- Höger kolumn - Dashboard preview -->
                    <div class="relative">
                        <!-- Huvudkort -->
                        <div class="bg-white/95 backdrop-blur-sm rounded-2xl border border-slate-200 shadow-2xl p-6 sm:p-8">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-800">Pågående optimeringar</h3>
                                </div>
                                <div class="flex items-center gap-2 text-emerald-600">
                                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                    <span class="text-sm font-medium">Live</span>
                                </div>
                            </div>

                            <!-- Aktiva processer -->
                            <div class="space-y-3">
                                <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-medium text-slate-800">SEO-analys slutförd</h4>
                                        <p class="text-sm text-slate-600">23 förbättringsförslag identifierade</p>
                                    </div>
                                    <div class="text-blue-600 font-medium text-sm">+127%</div>
                                </div>

                                <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                                    <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-medium text-slate-800">Innehåll genererat</h4>
                                        <p class="text-sm text-slate-600">5 blogginlägg publicerade automatiskt</p>
                                    </div>
                                    <div class="text-emerald-600 font-medium text-sm">Nytt</div>
                                </div>

                                <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-100">
                                    <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-medium text-slate-800">Konvertering ökar</h4>
                                        <p class="text-sm text-slate-600">CTA-optimering visar resultat</p>
                                    </div>
                                    <div class="text-amber-600 font-medium text-sm">+34%</div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="mt-6 pt-6 border-t border-slate-200">
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('news.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-medium text-sm transition-colors">
                                        Se alla uppdateringar
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                        </svg>
                                    </a>
                                    <div class="text-xs text-slate-500">
                                        Senast uppdaterat: nu
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Flytande notifikation -->
                        <div class="absolute -top-4 -right-4 bg-white border border-emerald-200 rounded-lg shadow-lg p-3 max-w-48 animate-bounce" style="animation-delay: 2s; animation-duration: 3s;">
                            <div class="flex items-center gap-2 text-sm">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                <span class="font-medium text-slate-800">Ny lead!</span>
                            </div>
                            <p class="text-xs text-slate-600 mt-1">Automatiskt registrerad från webbsidan</p>
                        </div>
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
                            <span class="text-slate-300 font-medium">Begränsat erbjudande</span>
                        </div>

                        <div class="space-y-8">
                            <h2 class="text-5xl md:text-6xl font-bold leading-tight text-white">
                                Gratis webbsida med
                                <span class="text-indigo-400">Growth årsplan</span>
                            </h2>

                            <p class="text-xl text-slate-400 leading-relaxed max-w-xl">
                                Prenumerera på Growth årsplan och få en professionell webbsida byggd av vårt team. Designad för konvertering och SEO-optimerad från start.
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
                                    <h4 class="text-white font-semibold mb-1">Standard WordPress-mall</h4>
                                    <p class="text-slate-500 text-sm">Professionell mall anpassad till ditt varumärke och innehåll</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-6 h-6 rounded-full bg-indigo-500/20 border border-indigo-500/40 flex items-center justify-center mr-4 mt-1 flex-shrink-0">
                                    <svg class="w-3 h-3 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold mb-1">Upp till 8 sidor</h4>
                                    <p class="text-slate-500 text-sm">Hem, Om, Tjänster, Kontakt + 4 valfria sidor</p>
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
                                    <h4 class="text-white font-semibold mb-1">WebGrow AI integrerat</h4>
                                    <p class="text-slate-500 text-sm">Fungerar direkt utan extra konfiguration</p>
                                </div>
                            </div>

                            <!-- Begränsningar -->
                            <div class="bg-slate-800/20 rounded-lg p-4 border border-slate-700/30">
                                <h5 class="text-slate-300 font-medium mb-2 text-sm">Vad som ingår:</h5>
                                <ul class="text-xs text-slate-400 space-y-1">
                                    <li>• Standard WordPress-installation på vårt webbhotell</li>
                                    <li>• Professionell mall från vårt urval</li>
                                    <li>• Grundläggande anpassning (färger, logotyp, text)</li>
                                    <li>• Max 8 sidor (startsida + 7 undersidor)</li>
                                    <li>• Standardfunktioner (kontaktformulär, Google Maps)</li>
                                </ul>
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
                                    Begränsat antal platser. Vi kontaktar dig inom 24h med mer information om programmet och nästa steg.
                                </p>
                            </form>
                        </div>

                        <!-- Minimal value proposition -->
                        <div class="bg-slate-800/30 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 mt-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-slate-500 text-sm mb-1">Normalt värde</p>
                                    <p class="text-slate-300 text-2xl font-bold">15 000 - 25 000 kr</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-indigo-400 text-sm font-medium mb-1">För dig</p>
                                    <p class="text-white text-2xl font-bold">Helt gratis</p>
                                </div>
                            </div>
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
                            10% rabatt vid årsprenumeration på alla planer
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
                                    <span class="text-lg text-slate-400 line-through">590 kr</span>
                                    <span class="text-4xl font-bold text-slate-800">390 kr</span>
                                    <span class="text-slate-600">/mån</span>
                                </div>
                                <div class="text-xs text-orange-600 font-medium">Founders-pris livstid</div>
                                <div class="text-xs text-slate-500 mt-1">Sedan 590 kr/mån • 531 kr/mån (årlig)</div>
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
                                    <span class="text-lg text-slate-400 line-through">2 490 kr</span>
                                    <span class="text-4xl font-bold text-slate-800">1 490 kr</span>
                                    <span class="text-slate-600">/mån</span>
                                </div>
                                <div class="text-xs text-orange-600 font-medium">Founders-pris</div>
                                <div class="text-xs text-slate-500 mt-1">Sedan 2 490 kr/mån • 2 241 kr/mån (årlig)</div>
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
                                    <span class="text-lg text-slate-400 line-through">7 990 kr</span>
                                    <span class="text-4xl font-bold text-slate-800">4 990 kr</span>
                                    <span class="text-slate-600">/mån</span>
                                </div>
                                <div class="text-xs text-orange-600 font-medium">Founders-pris</div>
                                <div class="text-xs text-slate-500 mt-1">Sedan 7 990 kr/mån • 7 191 kr/mån (årlig)</div>
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

                <!-- Trusted by section -->
                <div class="mt-20">
                    <div class="text-center mb-12">
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-8">Används av flera svenska företag</p>
                    </div>

                    <!-- Logo carousel with rotation effect -->
                    <div class="relative overflow-hidden bg-gradient-to-r from-slate-50 via-white to-slate-50 rounded-2xl py-12">
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
        <section id="faq" class="py-20 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 relative overflow-hidden">
            <!-- Subtle decorative elements -->
            <div class="absolute top-20 right-20 w-32 h-32 bg-indigo-200/30 rounded-full blur-3xl"></div>
            <div class="absolute bottom-40 left-10 w-24 h-24 bg-blue-200/40 rounded-full blur-2xl"></div>

            <div class="max-w-4xl mx-auto px-4 relative">
                <div class="text-center mb-16">
                    <div class="inline-flex items-center px-4 py-2 bg-white/60 backdrop-blur-sm rounded-full border border-indigo-200/50 mb-6">
                        <svg class="w-4 h-4 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-indigo-700 font-medium text-sm">Får vi ofta frågor</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-slate-800 via-indigo-800 to-purple-800 bg-clip-text text-transparent mb-4">
                        Svar på det viktigaste
                    </h2>
                    <p class="text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
                        Här hittar du svar på frågor som nästan alla ställer när de överväger WebGrow AI
                    </p>
                </div>

                <div class="space-y-6">
                    <details class="group bg-white/70 backdrop-blur-sm border border-indigo-200/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <summary class="cursor-pointer p-8 font-semibold text-slate-800 flex items-center justify-between hover:bg-indigo-50/50 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                                <span>Måste jag välja den dyraste planen från början?</span>
                            </div>
                            <svg class="w-6 h-6 text-indigo-500 group-open:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-8 pb-8 border-t border-indigo-100/50">
                            <p class="text-slate-600 leading-relaxed text-lg pt-6">
                                Absolut inte! De flesta börjar med <strong>Starter</strong> för att känna efter. Du kan uppgradera när som helst – mitt i månaden om du vill. Alla planer ger samma kraftfulla funktioner, bara med olika volymer. Perfekt för att växa i din egen takt.
                            </p>
                        </div>
                    </details>

                    <details class="group bg-white/70 backdrop-blur-sm border border-indigo-200/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <summary class="cursor-pointer p-8 font-semibold text-slate-800 flex items-center justify-between hover:bg-indigo-50/50 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                </div>
                                <span>Hur svårt är det att koppla WordPress och Shopify?</span>
                            </div>
                            <svg class="w-6 h-6 text-indigo-500 group-open:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-8 pb-8 border-t border-indigo-100/50">
                            <p class="text-slate-600 leading-relaxed text-lg pt-6">
                                Enklare än du tror! För <strong>WordPress</strong> behöver du bara skapa ett app-lösenord (visar vi hur), och för <strong>Shopify</strong> är det bara att klicka "Anslut". Sedan kan du tillämpa AI-förslag med ett enda klick – ingen kod, inget krångel. Tar max 5 minuter att sätta upp.
                            </p>
                        </div>
                    </details>

                    <details class="group bg-white/70 backdrop-blur-sm border border-indigo-200/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <summary class="cursor-pointer p-8 font-semibold text-slate-800 flex items-center justify-between hover:bg-indigo-50/50 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <span>Tänk om jag vill sluta efter några månader?</span>
                            </div>
                            <svg class="w-6 h-6 text-indigo-500 group-open:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-8 pb-8 border-t border-indigo-100/50">
                            <p class="text-slate-600 leading-relaxed text-lg pt-6">
                                Inga problem alls! Vi har <strong>noll bindningstid</strong> – avsluta när du vill, även mitt i månaden. Vi tjänar bara pengar om du får värde, och det är så vi vill ha det. 14 dagar gratis att testa, sedan månadsbetalt tills du säger stopp.
                            </p>
                        </div>
                    </details>

                    <details class="group bg-white/70 backdrop-blur-sm border border-indigo-200/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <summary class="cursor-pointer p-8 font-semibold text-slate-800 flex items-center justify-between hover:bg-indigo-50/50 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                                <span>Vad händer om jag använder mer än min plan tillåter?</span>
                            </div>
                            <svg class="w-6 h-6 text-indigo-500 group-open:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-8 pb-8 border-t border-indigo-100/50">
                            <p class="text-slate-600 leading-relaxed text-lg pt-6">
                                Du får alltid en <strong>varning i förväg</strong> innan något händer! Sedan kan du antingen uppgradera till nästa plan (gör det direkt i appen) eller betala för extra enligt våra schyssta tilläggspriser. Inga överraskningar på fakturan – löfte!
                            </p>
                        </div>
                    </details>

                    <details class="group bg-white/70 backdrop-blur-sm border border-indigo-200/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <summary class="cursor-pointer p-8 font-semibold text-slate-800 flex items-center justify-between hover:bg-indigo-50/50 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <span>Hur funkar det där med gratis webbsida egentligen?</span>
                            </div>
                            <svg class="w-6 h-6 text-indigo-500 group-open:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-8 pb-8 border-t border-indigo-100/50">
                            <p class="text-slate-600 leading-relaxed text-lg pt-6">
                                Så här funkar det: du prenumererar på <strong>Growth årsplan</strong>, sen bygger vårt team en riktig professionell WordPress-webbsida åt dig – helt gratis! Max 8 sidor, SEO-klar och med WebGrow AI redan kopplat. Normalt skulle det kosta 15-25k, men för dig kostar det noll extra.
                            </p>
                        </div>
                    </details>

                    <details class="group bg-white/70 backdrop-blur-sm border border-indigo-200/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <summary class="cursor-pointer p-8 font-semibold text-slate-800 flex items-center justify-between hover:bg-indigo-50/50 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <span>Kommer AI:n att låta som en robot på svenska?</span>
                            </div>
                            <svg class="w-6 h-6 text-indigo-500 group-open:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-8 pb-8 border-t border-indigo-100/50">
                            <p class="text-slate-600 leading-relaxed text-lg pt-6">
                                Raka motsatsen! Vi har tränat AI:n specifikt för <strong>svensk marknadsföring</strong> med rätt ton och känsla. Den förstår svenska SEO-mönster och skriver naturligt, engagerande innehåll. Många av våra kunder säger att texterna känns mer professionella än vad de själva skriver.
                            </p>
                        </div>
                    </details>
                </div>

                <!-- Call to action at bottom -->
                <div class="mt-16 text-center">
                    <div class="bg-gradient-to-r from-indigo-600/10 via-purple-600/10 to-pink-600/10 rounded-2xl p-8 border border-indigo-200/50 backdrop-blur-sm">
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Har du andra frågor?</h3>
                        <p class="text-slate-600 mb-6">Vi svarar gärna på allt du undrar – inga dumma frågor!</p>
                        <button @click="demoOpen=true" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Boka demo med oss
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Book a demo modal -->
        <!-- Demo Modal (om den inte redan finns) -->
        <div x-show="demoOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="demoOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="demoOpen=false"></div>

                <div x-show="demoOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

                    <!-- Stäng-knapp -->
                    <div class="absolute top-0 right-0 pt-4 pr-4">
                        <button @click="demoOpen=false" class="bg-white rounded-md text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Formulär -->
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Boka en demo</h3>

                            <!-- Success meddelande -->
                            @if(session('success'))
                                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('demo.request') }}" method="POST" class="space-y-4">
                                @csrf

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Namn *</label>
                                    <input type="text" name="name" required value="{{ old('name') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">E-post *</label>
                                    <input type="email" name="email" required value="{{ old('email') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Företag</label>
                                    <input type="text" name="company" value="{{ old('company') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('company')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Meddelande (valfritt)</label>
                                    <textarea name="notes" rows="3" placeholder="Berätta kort om ditt företag och vad du är intresserad av att se..."
                                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
                                    @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex gap-3 pt-4">
                                    <button type="button" @click="demoOpen=false"
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
                    @foreach(\App\Models\Post::with('featuredImage')->whereNotNull('published_at')->latest('published_at')->take(3)->get() as $post)
                        <a href="{{ route('news.show', $post->slug) }}" class="group block bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-lg hover:border-indigo-200 transition-all duration-300">
                            @if($post->featuredImage)
                                <div class="aspect-w-16 aspect-h-9 overflow-hidden">
                                    <img src="{{ $post->featured_image_url }}"
                                         alt="{{ $post->title }}"
                                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                            @else
                                <div class="h-48 bg-gradient-to-br from-indigo-100 to-blue-200 flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 text-indigo-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                        <span class="text-sm text-indigo-500 font-medium">WebGrow AI</span>
                                    </div>
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="text-sm text-slate-500 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ optional($post->published_at)->format('j M Y') }}
                                </div>
                                <h4 class="text-lg font-semibold text-slate-800 mb-3 group-hover:text-indigo-600 transition-colors line-clamp-2">{{ $post->title }}</h4>
                                @if($post->excerpt)
                                    <p class="text-slate-600 line-clamp-3 leading-relaxed mb-4">{{ $post->excerpt }}</p>
                                @endif
                                <div class="flex items-center text-indigo-600 font-medium text-sm group-hover:text-indigo-700 transition-colors">
                                    Läs mer
                                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
@endsection
