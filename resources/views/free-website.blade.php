@extends('layouts.guest', ['title' => 'Gratis WordPress Webbsida + AI Marketing - WebGrow AI'])

@section('content')
    <main x-data="{ formOpen: false, email: '', company: '', name: '', message: '' }">
        <!-- Hero Section -->
        <section class="relative min-h-screen bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-900 overflow-hidden">
            <!-- Background elements -->
            <div class="absolute inset-0">
                <div class="absolute top-20 right-20 w-96 h-96 bg-indigo-400/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 left-20 w-80 h-80 bg-purple-400/10 rounded-full blur-3xl"></div>
            </div>

            <!-- Grid pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.03"%3E%3Ccircle cx="7" cy="7" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>

            <div class="relative max-w-7xl mx-auto px-4 py-20 lg:py-32">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="space-y-8">
                        <!-- Badge -->
                        <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500/20 to-green-500/20 backdrop-blur-sm rounded-full border border-emerald-400/30">
                            <svg class="w-4 h-4 text-emerald-400 mr-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                            </svg>
                            <span class="text-emerald-100 font-semibold">Begränsat erbjudande - Endast 5 platser per månad</span>
                        </div>

                        <!-- Headline -->
                        <div class="space-y-6">
                            <h1 class="text-5xl md:text-7xl font-bold leading-tight text-white">
                                Gratis <span class="bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">WordPress</span> webbsida
                            </h1>
                            <p class="text-2xl md:text-3xl text-indigo-200 font-medium">
                                Värde 25 000 kr + AI-marknadsföring
                            </p>
                            <p class="text-xl text-slate-300 leading-relaxed max-w-2xl">
                                Prenumerera på Growth årsplan och få en professionell WordPress-webbsida byggd av vårt expertteam – helt gratis. SEO-optimerad och klar att generera leads från dag ett.
                            </p>
                        </div>

                        <!-- Key benefits -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3 text-slate-200">
                                <div class="w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">Professionell design</span>
                            </div>
                            <div class="flex items-center space-x-3 text-slate-200">
                                <div class="w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">SEO-optimerad</span>
                            </div>
                            <div class="flex items-center space-x-3 text-slate-200">
                                <div class="w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">Max 8 sidor inkl.</span>
                            </div>
                            <div class="flex items-center space-x-3 text-slate-200">
                                <div class="w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">AI integrerat</span>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="pt-4">
                            <button @click="formOpen = true" class="group relative inline-flex items-center px-8 py-5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-xl font-bold rounded-2xl shadow-2xl hover:shadow-indigo-500/25 transform hover:scale-105 transition-all duration-300">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Få min gratis webbsida
                                <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 to-purple-700 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                            </button>
                        </div>
                    </div>

                    <!-- Form Card -->
                    <div class="relative">
                        <div class="bg-white/95 backdrop-blur-lg rounded-3xl p-10 shadow-2xl border border-white/20">
                            <div class="text-center mb-8">
                                <h2 class="text-3xl font-bold text-slate-800 mb-3">Säkra din plats nu</h2>
                                <p class="text-slate-600">Begränsat antal - endast 5 webbsidor per månad</p>
                            </div>

                            <form class="space-y-6" @submit.prevent="console.log('Form submitted')">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-3">Företagsnamn</label>
                                    <input type="text" x-model="company" class="w-full px-5 py-4 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-colors text-lg" placeholder="Ditt företag" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-3">Ditt namn</label>
                                    <input type="text" x-model="name" class="w-full px-5 py-4 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-colors text-lg" placeholder="För- och efternamn" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-3">E-postadress</label>
                                    <input type="email" x-model="email" class="w-full px-5 py-4 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-colors text-lg" placeholder="din@epost.se" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-3">Kort om er verksamhet (valfritt)</label>
                                    <textarea x-model="message" rows="3" class="w-full px-5 py-4 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-colors text-lg resize-none" placeholder="Vad gör ni och vad vill ni uppnå med er nya webbsida?"></textarea>
                                </div>

                                <button type="submit" class="w-full py-5 bg-gradient-to-r from-emerald-600 to-green-600 text-white text-xl font-bold rounded-xl hover:from-emerald-700 hover:to-green-700 transform hover:scale-105 transition-all duration-300 shadow-xl">
                                    Skicka ansökan →
                                </button>

                                <p class="text-xs text-slate-500 text-center leading-relaxed">
                                    Vi kontaktar dig inom 24h med mer information och nästa steg. Inga dolda avgifter - bara Growth årsplan + gratis webbsida.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- What's Included Section -->
        <section class="py-20 bg-white">
            <div class="max-w-6xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-slate-800 mb-6">Vad ingår i din gratis webbsida?</h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto">En komplett WordPress-lösning som normalt kostar 25 000 kr</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl p-8 border border-indigo-100 hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Professionell Design</h3>
                        <p class="text-slate-600 leading-relaxed">Välj från vårt urval av moderna, konverteringsoptimerade mallar. Anpassas med era färger och logotyp.</p>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl p-8 border border-emerald-100 hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-emerald-500 to-green-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Upp till 8 sidor</h3>
                        <p class="text-slate-600 leading-relaxed">Hem, Om oss, Tjänster, Kontakt + 4 valfria undersidor. Perfekt för de flesta företag.</p>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-8 border border-purple-100 hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">SEO-optimerad</h3>
                        <p class="text-slate-600 leading-relaxed">Teknisk SEO, snabba laddningstider, mobiloptimerad och Google-vänlig från start.</p>
                    </div>

                    <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-8 border border-orange-100 hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100-4m0 4v2m0-6V4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">WebGrow AI Integrerat</h3>
                        <p class="text-slate-600 leading-relaxed">Din webbsida levereras med WebGrow AI redan kopplat och redo att optimera ditt innehåll.</p>
                    </div>

                    <div class="bg-gradient-to-br from-teal-50 to-cyan-50 rounded-2xl p-8 border border-teal-100 hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Grundfunktioner</h3>
                        <p class="text-slate-600 leading-relaxed">Kontaktformulär, Google Maps, sociala medier-kopplingar och SSL-certifikat inkluderat.</p>
                    </div>

                    <div class="bg-gradient-to-br from-slate-50 to-gray-50 rounded-2xl p-8 border border-slate-200 hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-slate-600 to-gray-600 rounded-2xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Snabb leverans</h3>
                        <p class="text-slate-600 leading-relaxed">Din webbsida är klar inom 2-3 veckor efter att du skickat ditt innehåll och material.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How it works -->
        <section class="py-20 bg-gradient-to-br from-slate-50 to-indigo-50">
            <div class="max-w-6xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-slate-800 mb-6">Så här fungerar det</h2>
                    <p class="text-xl text-slate-600">Enkelt, smidigt och utan krångel</p>
                </div>

                <div class="grid md:grid-cols-3 gap-12">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="text-white text-2xl font-bold">1</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 mb-4">Ansök idag</h3>
                        <p class="text-slate-600 text-lg leading-relaxed">Fyll i formuläret och berätta kort om ert företag. Vi kontaktar dig inom 24h.</p>
                    </div>

                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-r from-emerald-500 to-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="text-white text-2xl font-bold">2</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 mb-4">Vi bygger</h3>
                        <p class="text-slate-600 text-lg leading-relaxed">Du skickar innehåll och bilder. Vårt team bygger din professionella WordPress-webbsida.</p>
                    </div>

                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="text-white text-2xl font-bold">3</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 mb-4">Du växer</h3>
                        <p class="text-slate-600 text-lg leading-relaxed">Webbsidan går live och WebGrow AI börjar optimera för fler leads och bättre SEO.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="py-20 bg-white">
            <div class="max-w-4xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-slate-800 mb-4">Vanliga frågor</h2>
                </div>

                <div class="space-y-6">
                    <details class="group bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden hover:shadow-md transition-all duration-300">
                        <summary class="cursor-pointer p-8 font-semibold text-slate-800 text-lg flex items-center justify-between">
                            <span>Vad kostar det verkligen? Finns det dolda avgifter?</span>
                            <svg class="w-6 h-6 text-slate-500 group-open:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-8 pb-8 border-t border-slate-200">
                            <p class="text-slate-600 text-lg pt-6 leading-relaxed">Enda kostnaden är Growth årsplan (1 490 kr/mån). Webbsidan är helt gratis - inga dolda avgifter, inga överraskningar. Du betalar bara för WebGrow AI-tjänsten som vanligt.</p>
                        </div>
                    </details>

                    <details class="group bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden hover:shadow-md transition-all duration-300">
                        <summary class="cursor-pointer p-8 font-semibold text-slate-800 text-lg flex items-center justify-between">
                            <span>Vad händer om jag inte är nöjd med webbsidan?</span>
                            <svg class="w-6 h-6 text-slate-500 group-open:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-8 pb-8 border-t border-slate-200">
                            <p class="text-slate-600 text-lg pt-6 leading-relaxed">Vi gör 2 revideringsrundor gratis för att säkerställa att du är nöjd. Dessutom har du 14 dagar gratis på Growth-planen att testa allt.</p>
                        </div>
                    </details>

                    <details class="group bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden hover:shadow-md transition-all duration-300">
                        <summary class="cursor-pointer p-8 font-semibold text-slate-800 text-lg flex items-center justify-between">
                            <span>Hur lång tid tar det att få webbsidan klar?</span>
                            <svg class="w-6 h-6 text-slate-500 group-open:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-8 pb-8 border-t border-slate-200">
                            <p class="text-slate-600 text-lg pt-6 leading-relaxed">2-3 veckor från att du skickat allt innehåll och material. Vi håller dig uppdaterad under hela processen och du får se förhandsvisning innan lansering.</p>
                        </div>
                    </details>
                </div>
            </div>
        </section>

        <!-- Final CTA -->
        <section class="py-20 bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Begränsat erbjudande - Bara 5 webbsidor per månad
                </h2>
                <p class="text-xl text-indigo-200 mb-8 leading-relaxed">
                    Vi bygger bara 5 gratis webbsidor per månad för att säkerställa kvalitet.
                    <strong class="text-white">Nästa öppning: oktober 2025</strong>
                </p>

                <button @click="formOpen = true" class="inline-flex items-center px-10 py-6 bg-gradient-to-r from-emerald-500 to-green-500 text-white text-2xl font-bold rounded-2xl shadow-2xl hover:from-emerald-600 hover:to-green-600 transform hover:scale-105 transition-all duration-300">
                    <svg class="w-8 h-8 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Säkra min gratis webbsida nu
                </button>

                <p class="text-indigo-300 mt-6 flex items-center justify-center gap-6">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Svarar inom 24h
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Endast seriösa företag
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                        Värde 25 000 kr
                    </span>
                </p>
            </div>
        </section>

        <!-- Floating form modal -->
        <div x-show="formOpen" x-cloak class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div @click.outside="formOpen = false" class="bg-white rounded-3xl max-w-lg w-full p-8 shadow-2xl transform transition-all duration-300" x-show="formOpen" x-transition:enter="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Säkra din gratis webbsida</h3>
                    <p class="text-slate-600">Vi kontaktar dig inom 24h</p>
                </div>

                <form class="space-y-4" method="POST" action="">
                    @csrf
                    <input type="text" name="company" x-model="company" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Företagsnamn" required>
                    <input type="text" name="name" x-model="name" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Ditt namn" required>
                    <input type="email" name="email" x-model="email" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="E-postadress" required>
                    <textarea name="message" x-model="message" rows="3" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-colors" placeholder="Kort om er verksamhet (valfritt)"></textarea>

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="formOpen = false" class="flex-1 px-6 py-3 text-slate-600 font-semibold border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">Avbryt</button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-colors">Skicka</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
