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
                        <!-- Målgruppsbadge -->
                        <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                            För växande företag som vill spara tid
                        </div>

                        <!-- Huvudrubrik -->
                        <div class="space-y-4">
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight text-gray-900">
                                AI skriver, schemalägger och
                                <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                    publicerar automatiskt
                                </span>
                            </h1>

                            <p class="text-lg sm:text-xl text-slate-600 leading-relaxed max-w-xl">
                                WebGrow AI skapar produkttexter, blogginlägg och sociala medier-innehåll för din e-handel.
                                Schemalägg publicering och få värdefulla insikter – utan att lyfta ett finger.
                            </p>
                        </div>

                        <!-- Värdeförslag -->
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3 p-3 bg-white/60 backdrop-blur-sm rounded-xl border border-white/40">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-800 text-sm">Smart textgenerering</h3>
                                    <p class="text-xs text-slate-600">AI skriver för din målgrupp</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-3 bg-white/60 backdrop-blur-sm rounded-xl border border-white/40">
                                <div class="w-8 h-8 bg-gradient-to-r from-emerald-500 to-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-800 text-sm">Automatisk schemaläggning</h3>
                                    <p class="text-xs text-slate-600">Planera innehåll i förväg</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-3 bg-white/60 backdrop-blur-sm rounded-xl border border-white/40">
                                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-800 text-sm">Direkt publicering</h3>
                                    <p class="text-xs text-slate-600">Webben & sociala medier</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 p-3 bg-white/60 backdrop-blur-sm rounded-xl border border-white/40">
                                <div class="w-8 h-8 bg-gradient-to-r from-amber-500 to-orange-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-800 text-sm">Värdefulla insikter</h3>
                                    <p class="text-xs text-slate-600">Optimera för bättre resultat</p>
                                </div>
                            </div>
                        </div>

                        <!-- CTA-knappar -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            @if(Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 text-center"
                                   data-lead-cta="hero_register">
                                    Starta gratis period (14 dagar)
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
                                Boka möte för demo
                            </a>
                        </div>

                        <!-- Kvalitetssäkringsgaranti -->
                        <div class="bg-gradient-to-r from-white/60 to-blue-50/60 backdrop-blur-sm rounded-xl p-4 border border-white/50">
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-slate-800 text-sm mb-1">Kvalitetskontroll & säkerhet</h4>
                                    <p class="text-xs text-slate-600 leading-relaxed">
                                        All AI-genererat innehåll granskas automatiskt för kvalitet. Säker datahantering enligt GDPR.
                                        Du godkänner alltid innan publicering.
                                    </p>
                                </div>
                            </div>
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
                                <span class="font-medium">Personlig support och guidning</span>
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
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-800">Innehållsproduktion denna vecka</h3>
                                </div>
                                <div class="flex items-center gap-2 text-emerald-600">
                                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                    <span class="text-sm font-medium">Aktiv</span>
                                </div>
                            </div>

                            <!-- Aktiva processer -->
                            <div class="space-y-3">
                                <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-medium text-slate-800">23 produkttexter</h4>
                                        <p class="text-sm text-slate-600">Genererade och schemalagda för publicering</p>
                                    </div>
                                    <div class="text-blue-600 font-medium text-sm">Redo</div>
                                </div>

                                <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-100">
                                    <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-medium text-slate-800">5 blogginlägg</h4>
                                        <p class="text-sm text-slate-600">Publicerade automatiskt enligt schema</p>
                                    </div>
                                    <div class="text-emerald-600 font-medium text-sm">Publicerat</div>
                                </div>

                                <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-medium text-slate-800">12 sociala medier-inlägg</h4>
                                        <p class="text-sm text-slate-600">Facebook & Instagram schemalagda</p>
                                    </div>
                                    <div class="text-purple-600 font-medium text-sm">Idag 15:00</div>
                                </div>

                                <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-100">
                                    <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-medium text-slate-800">Prestandainsikter</h4>
                                        <p class="text-sm text-slate-600">+34% engagemang denna vecka</p>
                                    </div>
                                    <div class="text-amber-600 font-medium text-sm">+34%</div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="mt-6 pt-6 border-t border-slate-200">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm">
                                        <span class="text-slate-500">Nästa publicering:</span>
                                        <span class="font-medium text-slate-700 ml-1">Imorgon 09:00</span>
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        Uppdateras live
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Flytande notifikation -->
                        <div class="absolute -top-4 -right-4 bg-white border border-emerald-200 rounded-lg shadow-lg p-3 max-w-48 animate-bounce" style="animation-delay: 2s; animation-duration: 3s;">
                            <div class="flex items-center gap-2 text-sm">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                <span class="font-medium text-slate-800">Nytt innehåll!</span>
                            </div>
                            <p class="text-xs text-slate-600 mt-1">Produkttext automatiskt publicerad</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="py-24 bg-gradient-to-br from-white via-indigo-50/30 to-purple-50/30 relative overflow-hidden">
            <!-- Bakgrundsdekor -->
            <div class="absolute inset-0">
                <div class="absolute top-20 right-20 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 left-20 w-80 h-80 bg-purple-500/3 rounded-full blur-3xl"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4">
                <!-- Header -->
                <div class="text-center mb-16">
                    <div class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-full text-indigo-800 border border-indigo-200/50 mb-6">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold">AI som förstår svenska företag</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-bold text-slate-800 mb-6">
                        WebGrow skriver texter som
                        <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    faktiskt säljer
                </span>
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                        Inte bara mer innehåll - smartare innehåll som är anpassat för svenska kunder,
                        SEO-optimerat och skrivet för att konvertera besökare till kunder.
                    </p>
                </div>

                <!-- Huvuddemo med laptop-bild -->
                <div class="grid lg:grid-cols-2 gap-16 items-center mb-16">
                    <!-- Vänster: Process -->
                    <div class="space-y-8">
                        <div class="space-y-6">
                            <!-- Steg 1 -->
                            <div class="flex items-start space-x-4 group">
                                <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                                    1
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-800 mb-2">AI analyserar din bransch</h3>
                                    <p class="text-slate-600 leading-relaxed">
                                        Vår AI förstår skillnaden mellan en tandläkarmottagning och en byggfirma.
                                        Den anpassar språk, ton och fokus för just din målgrupp.
                                    </p>
                                </div>
                            </div>

                            <!-- Steg 2 -->
                            <div class="flex items-start space-x-4 group">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                                    2
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-800 mb-2">Skapar säljande innehåll</h3>
                                    <p class="text-slate-600 leading-relaxed">
                                        Produkttexter som får kunder att köpa. Blogginlägg som bygger förtroende.
                                        Sociala inlägg som engagerar - allt på svenska som låter naturligt.
                                    </p>
                                </div>
                            </div>

                            <!-- Steg 3 -->
                            <div class="flex items-start space-x-4 group">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                                    3
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-800 mb-2">Du godkänner och publicerar</h3>
                                    <p class="text-slate-600 leading-relaxed">
                                        Inget publiceras utan din kontroll. Granska förslaget, justera om du vill,
                                        och publicera med ett klick - eller schemalägg för senare.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- CTA -->
                        <div class="pt-6">
                            <button @click="demoOpen=true"
                                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                Se AI:n i aktion
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m2-10v18a2 2 0 01-2 2H5a2 2 0 01-2-2V4a2 2 0 012-2h14a2 2 0 012 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Höger: Laptop-bild med floating elements -->
                    <div class="relative">
                        <div class="transform hover:scale-105 transition-all duration-500">
                            <img
                                src="https://webbiab.s3.eu-north-1.amazonaws.com/webgrowai/laptop-webgrowai-1-nytt.png"
                                alt="WebGrow AI Dashboard - Smart innehållsgenerering"
                                class="w-full max-w-lg mx-auto drop-shadow-2xl"
                            />

                            <!-- AI Processing indicator -->
                            <div class="absolute -top-6 -left-6 bg-white rounded-xl shadow-xl p-4 border border-emerald-200 animate-pulse">
                                <div class="flex items-center text-emerald-600 font-medium">
                                    <div class="w-3 h-3 bg-emerald-500 rounded-full mr-2 animate-pulse"></div>
                                    <span class="text-sm">AI skriver...</span>
                                </div>
                                <div class="text-xs text-slate-500 mt-1">Tandläkarmottagning</div>
                            </div>

                            <!-- Quality check -->
                            <div class="absolute top-4 -right-6 bg-white rounded-xl shadow-xl p-4 border border-blue-200">
                                <div class="flex items-center text-blue-600 font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm">SEO-optimerat</span>
                                </div>
                                <div class="text-xs text-slate-500 mt-1">Branschspecifikt</div>
                            </div>

                            <!-- Ready to publish -->
                            <div class="absolute -bottom-6 right-1/4 bg-white rounded-xl shadow-xl p-4 border border-purple-200 animate-bounce" style="animation-delay: 2s; animation-duration: 3s;">
                                <div class="flex items-center text-purple-600 font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                    </svg>
                                    <span class="text-sm">Redo att publicera</span>
                                </div>
                                <div class="text-xs text-slate-500 mt-1">3 inlägg färdiga</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results Grid - ersätter floating stats -->
                <div class="grid md:grid-cols-3 gap-8 mb-16">
                    <!-- Engagement Results -->
                    <div class="group bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl p-8 border border-emerald-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <div class="text-3xl font-bold text-emerald-600">+127%</div>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Högre engagemang</h3>
                        <p class="text-slate-600 text-sm">AI-genererat innehåll får mer likes, kommentarer och delningar än traditionellt innehåll</p>
                    </div>

                    <!-- Lead Generation -->
                    <div class="group bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-8 border border-indigo-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                                </svg>
                            </div>
                            <div class="text-3xl font-bold text-indigo-600">+89%</div>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Fler kvalificerade leads</h3>
                        <p class="text-slate-600 text-sm">Branschspecifika texter attraherar rätt typ av kunder som är redo att köpa</p>
                    </div>

                    <!-- Time Saved -->
                    <div class="group bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-8 border border-purple-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="text-3xl font-bold text-purple-600">12h/vecka</div>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">Sparad tid</h3>
                        <p class="text-slate-600 text-sm">Fokusera på att driva företaget istället för att sitta och skriva texter</p>
                    </div>
                </div>

                <!-- Branschspecifika exempel -->
                <div class="bg-gradient-to-r from-slate-50 to-indigo-50 rounded-2xl p-8 mb-12">
                    <h3 class="text-2xl font-bold text-slate-800 mb-6 text-center">Fungerar för alla typer av företag</h3>
                    <div class="grid md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-slate-800 mb-1">Tjänsteföretag</h4>
                            <p class="text-sm text-slate-600">Konsulter, jurister, revisorer, fastighetsmäklare</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-slate-800 mb-1">Hälsa & Vård</h4>
                            <p class="text-sm text-slate-600">Tandläkare, kliniker, vårdcentraler, privata mottagningar</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-slate-800 mb-1">Hantverk & Bygg</h4>
                            <p class="text-sm text-slate-600">Byggfirmor, elektriker, VVS, målare, snickare</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"/>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-slate-800 mb-1">E-handel & Butik</h4>
                            <p class="text-sm text-slate-600">Webshoppar, lokala butiker, produktförsäljning</p>
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
                        Fungerar med din befintliga webbplats
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                        Oavsett om du driver en tandläkarmottagning, byggfirma, konsultverksamhet eller butik online -
                        vi kopplar till din webbplats på 5 minuter och börjar producera relevant innehåll direkt.
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
                        <p class="text-slate-600 leading-relaxed mb-4">
                            Populäraste valet för svenska företag. Logga in med ditt WordPress-konto så kopplar vi automatiskt.
                            Perfekt för tjänsteföretag, konsulter och lokala verksamheter.
                        </p>
                        <div class="space-y-2 text-sm text-slate-500">
                            <div class="flex items-center justify-center">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                Blogginlägg & tjänstesidor
                            </div>
                            <div class="flex items-center justify-center">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                SEO-optimering automatiskt
                            </div>
                        </div>
                    </div>

                    <!-- Shopify -->
                    <div class="group text-center p-8 bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl border border-emerald-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M15.337 2.001c-0.907,0-1.76,0.655-2.332,1.603-0.458-0.174-0.985-0.266-1.562-0.266-2.156,0-4.003,1.299-5.229,3.284-1.117-0.426-2.162-0.37-2.805,0.273-0.764,0.763-0.764,2.212,0,2.975l8.834,8.834c0.381,0.381,0.893,0.596,1.438,0.596s1.057-0.215,1.438-0.596l8.834-8.834c0.764-0.763,0.764-2.212,0-2.975-0.643-0.643-1.688-0.699-2.805-0.273-1.226-1.985-3.073-3.284-5.229-3.284-0.577,0-1.104,0.092-1.562,0.266C17.097,2.656,16.244,2.001,15.337,2.001z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Shopify E-handel</h3>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            Certifierad Shopify-partner. Enkel uppsättning för växande e-handelsföretag.
                            AI genererar produkttexter och blogginlägg som får kunder att köpa.
                        </p>
                        <div class="space-y-2 text-sm text-slate-500">
                            <div class="flex items-center justify-center">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                Produkttexter som säljer
                            </div>
                            <div class="flex items-center justify-center">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                <a href="https://webbi.se" target="_blank" class="hover:underline">Certifierad partner</a>
                            </div>
                        </div>
                    </div>

                    <!-- Custom/Other Sites -->
                    <div class="group text-center p-8 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-200/50 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Andra plattformar</h3>
                        <p class="text-slate-600 leading-relaxed mb-4">
                            Har ni Squarespace, Wix, egen utvecklad sajt eller annat? Vi hjälper er att koppla det också.
                            Personlig service för att få igång er innehållsproduktion snabbt.
                        </p>
                        <div class="space-y-2 text-sm text-slate-500">
                            <div class="flex items-center justify-center">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                Anpassad integration
                            </div>
                            <div class="flex items-center justify-center">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                Personlig support
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Security & Quality -->
                <div class="grid md:grid-cols-2 gap-8 mb-12">
                    <!-- Datasäkerhet -->
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-2xl p-6 border border-indigo-200/50">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-slate-800">Säker datahantering</h4>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">GDPR-kompatibel (svensk GDPR-expert)</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">SSL-krypterad dataöverföring</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">Svensk teknik & support</span>
                            </div>
                        </div>
                    </div>

                    <!-- Kvalitetssäkring -->
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl p-6 border border-emerald-200/50">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-green-500 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-slate-800">AI-kvalitet som funkar</h4>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">Tränad på svensk affärsttext</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">Du godkänner innan publicering</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-slate-700">Branschspecifik anpassning</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support Features -->
                <div class="bg-gradient-to-r from-slate-800 to-indigo-900 rounded-2xl p-8 text-center text-white">
                    <h3 class="text-2xl font-bold mb-6">Fullservice från dag ett</h3>
                    <div class="grid md:grid-cols-3 gap-6 mb-8">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h4 class="font-semibold mb-2">Gratis uppsättning</h4>
                            <p class="text-sm text-white/80">Vi kopplar din sajt och konfigurerar allt utan extra kostnad</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h4 class="font-semibold mb-2">Personlig support</h4>
                            <p class="text-sm text-white/80">Telefon, mejl och videomöten när du behöver hjälp</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h4 class="font-semibold mb-2">Svensk expertis</h4>
                            <p class="text-sm text-white/80">Shopify-partners och WordPress-experter baserade i Sverige</p>
                        </div>
                    </div>
                    <p class="text-white/90 max-w-2xl mx-auto">
                        Vi förstår svenska företag och hjälper er från start till mål. Tekniken sköter vi – ni fokuserar på att driva företaget framåt.
                    </p>
                </div>
            </div>
        </section>

        <!-- Features -->
        <!-- Differentiering & Fördelar -->
        <section id="features" class="py-32 bg-gradient-to-br from-slate-50 via-indigo-50/20 to-purple-50/30 relative overflow-hidden">
            <!-- Subtila dekorativa element -->
            <div class="absolute inset-0">
                <div class="absolute top-40 right-20 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 left-20 w-80 h-80 bg-purple-500/3 rounded-full blur-3xl"></div>
            </div>

            <!-- Minimal grid pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23f1f5f9" fill-opacity="0.3"%3E%3Ccircle cx="7" cy="7" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>

            <div class="relative max-w-7xl mx-auto px-4">
                <!-- Header -->
                <div class="text-center mb-20">
                    <div class="inline-flex items-center px-6 py-3 bg-white/60 backdrop-blur-sm rounded-full border border-indigo-200/50 mb-8">
                        <svg class="w-5 h-5 text-indigo-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-indigo-800 font-semibold">Varför välja WebGrow AI</span>
                    </div>
                    <h2 class="text-4xl md:text-6xl font-bold leading-tight mb-8">
                        <span class="text-slate-800">Skillnaden som</span><br>
                        <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                    gör skillnad
                </span>
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                        Medan andra verktyg bara genererar text, bygger WebGrow AI ett komplett system
                        för att växa ditt företag online.
                    </p>
                </div>

                <!-- Huvudförmåner Grid -->
                <div class="grid lg:grid-cols-2 gap-16 items-center mb-24">
                    <!-- Vänster: Bild -->
                    <div class="relative">
                        <div class="relative z-10 transform hover:scale-105 transition-all duration-700">
                            <img
                                src="https://webbiab.s3.eu-north-1.amazonaws.com/webgrowai/laptop-webgrowai-2-nytt.png"
                                alt="WebGrow AI Dashboard - Smart innehållsgenerering"
                                class="w-full max-w-lg mx-auto drop-shadow-2xl"
                            />

                            <!-- Floating insight card -->
                            <div class="absolute -top-6 -right-6 bg-white rounded-xl shadow-xl p-4 border border-emerald-200 animate-bounce" style="animation-delay: 1s; animation-duration: 3s;">
                                <div class="flex items-center text-emerald-600 font-semibold text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    +127% ROI
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Genomsnitt hos kunder</p>
                            </div>

                            <!-- Performance indicator -->
                            <div class="absolute -bottom-6 -left-6 bg-white rounded-xl shadow-xl p-4 border border-blue-200">
                                <div class="flex items-center text-blue-600 font-semibold text-sm">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-2 animate-pulse"></div>
                                    Real-time analys
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Kontinuerlig optimering</p>
                            </div>
                        </div>

                        <!-- Bakgrundsdekor -->
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-100/30 to-purple-100/30 rounded-3xl -z-10 blur-3xl transform scale-110"></div>
                    </div>

                    <!-- Höger: Innehåll -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-3xl font-bold text-slate-800 mb-4">
                                Inte bara AI-text –
                                <span class="text-indigo-600">intelligent tillväxt</span>
                            </h3>
                            <p class="text-lg text-slate-600 leading-relaxed">
                                WebGrow AI går längre än traditionella innehållsverktyg. Vi analyserar vad som fungerar,
                                optimerar kontinuerligt och ger dig konkreta insikter för att växa ditt företag.
                            </p>
                        </div>

                        <!-- Förmåner lista -->
                        <div class="space-y-6">
                            <div class="flex items-start group">
                                <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl flex items-center justify-center mr-4 mt-1 group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-slate-800 mb-2">Mäter och optimerar automatiskt</h4>
                                    <p class="text-slate-600 leading-relaxed">
                                        Andra verktyg skriver bara text. Vi mäter hur väl ditt innehåll presterar och
                                        förbättrar det kontinuerligt baserat på riktiga resultat.
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start group">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center mr-4 mt-1 group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-slate-800 mb-2">Branschspecifik svenska AI</h4>
                                    <p class="text-slate-600 leading-relaxed">
                                        Tränad på svenska affärstexter och anpassad för svenska marknaden.
                                        Förstår skillnaden mellan olika branscher och målgrupper.
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start group">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-4 mt-1 group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-slate-800 mb-2">Komplett innehållsstrategi</h4>
                                    <p class="text-slate-600 leading-relaxed">
                                        Inte bara enstaka texter – vi skapar en sammanhängande strategi som bygger
                                        ditt varumärke och driver försäljning över tid.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jämförelse sektion -->
                <div class="bg-white/70 backdrop-blur-sm rounded-3xl p-8 md:p-12 border border-indigo-200/50 mb-24">
                    <div class="text-center mb-12">
                        <h3 class="text-3xl font-bold text-slate-800 mb-4">WebGrow AI vs andra lösningar</h3>
                        <p class="text-lg text-slate-600">Se skillnaden i praktiken</p>
                    </div>

                    <div class="grid md:grid-cols-3 gap-8">
                        <!-- Traditionella verktyg -->
                        <div class="text-center p-6 bg-slate-50 rounded-2xl border border-slate-200">
                            <div class="w-16 h-16 bg-slate-400 rounded-xl flex items-center justify-center mx-auto mb-6">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 mb-3">Traditionella AI-verktyg</h4>
                            <ul class="space-y-2 text-sm text-slate-600">
                                <li>• Genererar bara text</li>
                                <li>• Ingen branschkunskap</li>
                                <li>• Manuell publicering</li>
                                <li>• Ingen uppföljning</li>
                                <li>• Engelska fokus</li>
                            </ul>
                        </div>

                        <!-- Anställda -->
                        <div class="text-center p-6 bg-orange-50 rounded-2xl border border-orange-200">
                            <div class="w-16 h-16 bg-orange-400 rounded-xl flex items-center justify-center mx-auto mb-6">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 mb-3">Anställd/Byrå</h4>
                            <ul class="space-y-2 text-sm text-slate-600">
                                <li>• Dyrt (25 000+ kr/mån)</li>
                                <li>• Lång ledtid</li>
                                <li>• Begränsad kapacitet</li>
                                <li>• Risk för personalomsättning</li>
                                <li>• Svårt att skala</li>
                            </ul>
                        </div>

                        <!-- WebGrow AI -->
                        <div class="text-center p-6 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl border border-indigo-300 relative">
                            <!-- "Vinnare" badge -->
                            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-1 rounded-full text-xs font-bold shadow-lg">
                                    BÄSTA VALET
                                </div>
                            </div>

                            <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mx-auto mb-6 mt-4">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-slate-800 mb-3">WebGrow AI</h4>
                            <ul class="space-y-2 text-sm text-indigo-700 font-medium">
                                <li>✓ Komplett innehållsstrategi</li>
                                <li>✓ Svensk branschexpertis</li>
                                <li>✓ Automatisk publicering</li>
                                <li>✓ Mäter och optimerar</li>
                                <li>✓ Kostnadseffektivt</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Resultat showcase -->
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <!-- Höger: Bild -->
                    <div class="lg:order-2 relative">
                        <div class="relative z-10 transform hover:scale-105 transition-all duration-700">
                            <img
                                src="https://webbiab.s3.eu-north-1.amazonaws.com/webgrowai/laptop-webgrowai-3-nytt.png"
                                alt="WebGrow AI Dashboard - Smart innehållsgenerering"
                                class="w-full max-w-lg mx-auto drop-shadow-2xl"
                            />

                            <!-- Growth indicator -->
                            <div class="absolute -top-6 -left-6 bg-white rounded-xl shadow-xl p-4 border border-purple-200 animate-pulse">
                                <div class="flex items-center text-purple-600 font-semibold text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    +340% Trafik
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Genomsnittlig ökning</p>
                            </div>

                            <!-- Time saved indicator -->
                            <div class="absolute -bottom-6 -right-6 bg-white rounded-xl shadow-xl p-4 border border-emerald-200">
                                <div class="flex items-center text-emerald-600 font-semibold text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    12h/vecka sparade
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Mer tid för företaget</p>
                            </div>
                        </div>

                        <!-- Bakgrundsdekor -->
                        <div class="absolute inset-0 bg-gradient-to-l from-purple-100/30 to-pink-100/30 rounded-3xl -z-10 blur-3xl transform scale-110"></div>
                    </div>

                    <!-- Vänster: Innehåll -->
                    <div class="lg:order-1 space-y-8">
                        <div>
                            <h3 class="text-3xl font-bold text-slate-800 mb-4">
                                Resultat som
                                <span class="text-purple-600">talar för sig själva</span>
                            </h3>
                            <p class="text-lg text-slate-600 leading-relaxed">
                                WebGrow AI levererar mätbara resultat från dag ett. Våra kunder ser genomsnittligt
                                en ökning på över 340% i organisk trafik inom de första 6 månaderna.
                            </p>
                        </div>

                        <!-- Resultat metrics -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-gradient-to-br from-emerald-50 to-green-50 p-6 rounded-2xl border border-emerald-200/50">
                                <div class="text-3xl font-bold text-emerald-600 mb-2">89%</div>
                                <h4 class="font-semibold text-slate-800 mb-1">Fler kvalificerade leads</h4>
                                <p class="text-sm text-slate-600">Bättre innehåll = rätt kunder</p>
                            </div>

                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-200/50">
                                <div class="text-3xl font-bold text-blue-600 mb-2">67%</div>
                                <h4 class="font-semibold text-slate-800 mb-1">Högre konvertering</h4>
                                <p class="text-sm text-slate-600">Optimerat för försäljning</p>
                            </div>

                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-200/50">
                                <div class="text-3xl font-bold text-purple-600 mb-2">12h</div>
                                <h4 class="font-semibold text-slate-800 mb-1">Sparad tid per vecka</h4>
                                <p class="text-sm text-slate-600">Fokusera på att driva företaget</p>
                            </div>

                            <div class="bg-gradient-to-br from-orange-50 to-red-50 p-6 rounded-2xl border border-orange-200/50">
                                <div class="text-3xl font-bold text-orange-600 mb-2">ROI</div>
                                <h4 class="font-semibold text-slate-800 mb-1">427% genomsnittlig ROI</h4>
                                <p class="text-sm text-slate-600">Lönsam investering från start</p>
                            </div>
                        </div>

                        <!-- CTA -->
                        <div class="pt-6">
                            <button @click="demoOpen=true" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                Se dina möjliga resultat
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing -->
        <section id="pricing" class="py-20 bg-gradient-to-br from-slate-50 to-indigo-50">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Välj rätt paket för ditt företag</h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto">Börja med det som passar dig idag. Du kan alltid ändra eller avbryta när som helst.</p>
                    <div class="inline-flex items-center mt-6 px-4 py-2 bg-gradient-to-r from-emerald-100 to-green-100 rounded-full">
                        <span class="text-emerald-800 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Betala årligt och spara 10% på alla paket
                        </span>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    <!-- Starter -->
                    <div class="relative bg-white rounded-2xl border border-slate-200 shadow-lg hover:shadow-xl transition-all duration-300 p-8 flex flex-col">
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-slate-600 mb-2">Starter</h3>
                            <div class="mb-4">
                                <div class="flex items-center justify-center gap-2 mb-2">
                                    <span class="text-lg text-slate-400 line-through">590 kr</span>
                                    <span class="text-4xl font-bold text-slate-800">390 kr</span>
                                    <span class="text-slate-600">/mån</span>
                                </div>
                                <div class="text-xs text-orange-600 font-medium">Early bird-pris</div>
                                <div class="text-xs text-slate-500 mt-1">Vanligt pris 590 kr/mån • Årlig 531 kr/mån</div>
                            </div>
                            <p class="text-slate-600 mb-6">Perfekt för att komma igång med AI-innehåll.</p>
                        </div>

                        <div class="flex-1 mb-6">
                            <h4 class="font-semibold text-slate-800 mb-3">Grundläggande funktioner:</h4>
                            <ul class="space-y-3">
                                <li class="flex items-start text-slate-700">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium">500 AI-texter per månad</span>
                                        <p class="text-sm text-slate-500">Blogginlägg, produkttexter och sociala medier-inlägg</p>
                                    </div>
                                </li>
                                <li class="flex items-start text-slate-700">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium">50 automatiska publiceringar</span>
                                        <p class="text-sm text-slate-500">Schemalägg och publicera direkt på din webbplats</p>
                                    </div>
                                </li>
                                <li class="flex items-start text-slate-700">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium">Spåra upp till 5 000 besökare</span>
                                        <p class="text-sm text-slate-500">Se vem som besöker din sajt och vad de gör</p>
                                    </div>
                                </li>
                                <li class="flex items-start text-slate-700">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium">1 webbplats + 2 användare</span>
                                        <p class="text-sm text-slate-500">Du och en kollega kan arbeta tillsammans</p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="mt-auto">
                            @if(Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="w-full inline-flex items-center justify-center px-6 py-3 bg-slate-800 text-white font-semibold rounded-xl hover:bg-slate-900 transition-colors duration-200"
                                   data-lead-cta="pricing_starter_register">Prova gratis i 14 dagar</a>
                            @endif
                        </div>
                    </div>

                    <!-- Growth - Most Popular -->
                    <div class="relative bg-white rounded-2xl border-2 border-indigo-500 shadow-2xl hover:shadow-3xl transition-all duration-300 p-8 scale-105 flex flex-col">
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 whitespace-nowrap">
                            <span class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                                Mest populära + gratis hemsida
                            </span>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-indigo-600 mb-2">Growth</h3>
                            <div class="mb-4">
                                <div class="flex items-center justify-center gap-2 mb-2">
                                    <span class="text-lg text-slate-400 line-through">2 290 kr</span>
                                    <span class="text-4xl font-bold text-slate-800">1 290 kr</span>
                                    <span class="text-slate-600">/mån</span>
                                </div>
                                <div class="text-xs text-orange-600 font-medium">Early bird-pris</div>
                                <div class="text-xs text-slate-500 mt-1">Vanligt pris 2 290 kr/mån • Årlig 2 061 kr/mån</div>
                            </div>
                            <p class="text-slate-600 mb-6">För företag som vill växa med smart analys.</p>
                        </div>

                        <div class="flex-1 mb-6">
                            <h4 class="font-semibold text-slate-800 mb-3">Allt från Starter, plus:</h4>
                            <ul class="space-y-3">
                                <li class="flex items-start text-slate-700">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium">2 500 AI-texter per månad</span>
                                        <p class="text-sm text-slate-500">5x mer innehåll för att synas oftare</p>
                                    </div>
                                </li>
                                <li class="flex items-start text-slate-700">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium">200 publiceringar per månad</span>
                                        <p class="text-sm text-slate-500">Konsekvent närvaro på webben utan extra jobb</p>
                                    </div>
                                </li>

                                <!-- CRO, SEO & Nyckelord endast i Growth/Pro -->
                                <li class="flex items-start text-indigo-700 bg-indigo-50 p-3 rounded-lg border border-indigo-200">
                                    <svg class="w-5 h-5 text-indigo-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <span class="font-bold">SEO & Nyckelordsanalys</span>
                                        <p class="text-sm text-indigo-600">AI analyserar din konkurrens och föreslår bättre nyckelord</p>
                                    </div>
                                </li>

                                <li class="flex items-start text-purple-700 bg-purple-50 p-3 rounded-lg border border-purple-200">
                                    <svg class="w-5 h-5 text-purple-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <span class="font-bold">CRO-optimering</span>
                                        <p class="text-sm text-purple-600">Gör fler besökare till kunder med AI-förbättringar</p>
                                    </div>
                                </li>

                                <li class="flex items-start text-slate-700">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium">Spåra upp till 25 000 besökare</span>
                                        <p class="text-sm text-slate-500">Förstå dina kunder bättre</p>
                                    </div>
                                </li>
                                <li class="flex items-start text-slate-700">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium">3 webbplatser + 5 användare</span>
                                        <p class="text-sm text-slate-500">Hantera flera sajter och större team</p>
                                    </div>
                                </li>
                                <li class="flex items-center text-yellow-600 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                                    <svg class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <span class="font-bold"><a href="{{ route('free-website') }}" class="hover:text-yellow-900">Gratis professionell hemsida</a>!</span>
                                        <p class="text-xs text-yellow-700">Värde 15 000-25 000 kr (endast vid årlig betalning)</p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="mt-auto">
                            <button @click="demoOpen=true"
                                    class="w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg"
                                    data-lead-cta="pricing_growth_demo">Se demonstration</button>
                        </div>
                    </div>

                    <!-- Pro -->
                    <div class="relative bg-white rounded-2xl border border-slate-200 shadow-lg hover:shadow-xl transition-all duration-300 p-8 flex flex-col">
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-slate-600 mb-2">Pro</h3>
                            <div class="mb-4">
                                <div class="flex items-center justify-center gap-2 mb-2">
                                    <span class="text-lg text-slate-400 line-through">7 990 kr</span>
                                    <span class="text-4xl font-bold text-slate-800">4 490 kr</span>
                                    <span class="text-slate-600">/mån</span>
                                </div>
                                <div class="text-xs text-orange-600 font-medium">Early bird-pris</div>
                                <div class="text-xs text-slate-500 mt-1">Vanligt pris 7 990 kr/mån • Årlig 7 191 kr/mån</div>
                            </div>
                            <p class="text-slate-600 mb-6">För etablerade företag och marknadsföringsbyråer.</p>
                        </div>

                        <div class="flex-1 mb-6">
                            <h4 class="font-semibold text-slate-800 mb-3">Allt från Growth, plus:</h4>
                            <ul class="space-y-3">
                                <li class="flex items-start text-slate-700">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium">10 000 AI-texter per månad</span>
                                        <p class="text-sm text-slate-500">Obegränsad kreativitet för stora kampanjer</p>
                                    </div>
                                </li>
                                <li class="flex items-start text-slate-700">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium">1 000 publiceringar per månad</span>
                                        <p class="text-sm text-slate-500">Massiv digital närvaro på alla kanaler</p>
                                    </div>
                                </li>

                                <!-- Avancerad analys endast i Pro -->
                                <li class="flex items-start text-slate-700 bg-gradient-to-r from-indigo-50 to-purple-50 p-3 rounded-lg border border-indigo-200">
                                    <svg class="w-5 h-5 text-indigo-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <span class="font-bold text-slate-800">Avancerad SEO & CRO-analys</span>
                                        <p class="text-sm text-slate-600">Djupare insikter och mer frekventa analyser</p>
                                    </div>
                                </li>

                                <li class="flex items-start text-slate-700">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium">Obegränsad besökarspårning</span>
                                        <p class="text-sm text-slate-500">Fullständig översikt över all trafik</p>
                                    </div>
                                </li>
                                <li class="flex items-start text-slate-700">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium">10 webbplatser + 20 användare</span>
                                        <p class="text-sm text-slate-500">Perfekt för byråer eller stora organisationer</p>
                                    </div>
                                </li>
                                <li class="flex items-start text-slate-700">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium">Prioriterad support</span>
                                        <p class="text-sm text-slate-500">Direktkontakt med våra experter</p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="mt-auto">
                            <button @click="demoOpen=true"
                                    class="w-full px-6 py-3 bg-slate-800 text-white font-semibold rounded-xl hover:bg-slate-900 transition-colors duration-200"
                                    data-lead-cta="pricing_pro_demo">Kontakta oss</button>
                        </div>
                    </div>
                </div>

                <div class="max-w-4xl mx-auto mt-12 bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 rounded-2xl p-6">
                    <div class="text-center">
                        <h3 class="text-lg font-bold text-orange-900 mb-2 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            Early access program
                        </h3>
                        <p class="text-orange-800">
                            Genom att vara med tidigare än andra kan du ta del av speciella priser. Anmäl intresse för vårt Early Access program.
                        </p>
                    </div>
                </div>

                <div class="text-center text-sm text-slate-500 mt-6 max-w-4xl mx-auto">
                    Extra kostnad endast om du går över din plan: AI-texter 0,30 kr/st, Publiceringar 0,80 kr/st, Analyser 99 kr/st, Besöksspårning 0,001 kr/event. Alla priser exkl. moms.
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
                            "Vi ökade våra kundförfrågningar med 42% på två månader. Att få färdiga texter
                            som vi bara behöver godkänna sparar oss 8 timmar i veckan."
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
                    <!-- FAQ rubriker/svar förenklade -->
                    <details class="group bg-white/70 backdrop-blur-sm border border-indigo-200/50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <summary class="cursor-pointer p-8 font-semibold text-slate-800 flex items-center justify-between hover:bg-indigo-50/50 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-green-500 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                                <span>Måste jag börja på en dyr plan?</span>
                            </div>
                            <svg class="w-6 h-6 text-indigo-500 group-open:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-8 pb-8 border-t border-indigo-100/50">
                            <p class="text-slate-600 leading-relaxed text-lg pt-6">
                                Nej. De flesta börjar på Starter och uppgraderar när volymen växer. Samma smarta funktioner – olika nivåer.
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
                                <span>Hur svårt är det att koppla WordPress/Shopify?</span>
                            </div>
                            <svg class="w-6 h-6 text-indigo-500 group-open:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-8 pb-8 border-t border-indigo-100/50">
                            <p class="text-slate-600 leading-relaxed text-lg pt-6">
                                Det tar cirka 5 minuter. Du loggar in – klart. Sedan kan du godkänna förslag och publicera med ett klick.
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
                                <span>Kan jag avsluta när jag vill?</span>
                            </div>
                            <svg class="w-6 h-6 text-indigo-500 group-open:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-8 pb-8 border-t border-indigo-100/50">
                            <p class="text-slate-600 leading-relaxed text-lg pt-6">
                                Ja. Ingen bindningstid. 14 dagar gratis – betala månadsvis tills du säger stopp.
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
                                <span>Vad händer om vi använder mer än planen?</span>
                            </div>
                            <svg class="w-6 h-6 text-indigo-500 group-open:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-8 pb-8 border-t border-indigo-100/50">
                            <p class="text-slate-600 leading-relaxed text-lg pt-6">
                                Ni får en varning i god tid. Uppgradera i appen eller betala lite extra – inga överraskningar på fakturan.
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
                                <span>Låter AI‑texterna “robot” på svenska?</span>
                            </div>
                            <svg class="w-6 h-6 text-indigo-500 group-open:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="px-8 pb-8 border-t border-indigo-100/50">
                            <p class="text-slate-600 leading-relaxed text-lg pt-6">
                                Nej. Texterna skrivs på naturlig svenska med rätt ton. Du godkänner alltid innan publicering.
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

        <!-- Kontakt -->
        <section id="kontakt" class="py-20 bg-gradient-to-br from-slate-900 via-indigo-900 to-slate-800 relative overflow-hidden">
            <!-- Dekorativa element -->
            <div class="absolute inset-0">
                <div class="absolute top-20 right-20 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 left-20 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl"></div>
            </div>

            <!-- Subtil bakgrundsmönster -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="7" cy="7" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>

            <div class="relative max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20 mb-6">
                        <svg class="w-4 h-4 text-indigo-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-white font-medium text-sm">Kontakta oss</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">
                        Kom igång idag
                    </h2>
                    <p class="text-xl text-slate-300 max-w-3xl mx-auto leading-relaxed">
                        Har du frågor eller vill diskutera hur WebGrow AI kan hjälpa ditt företag? Hör av dig så svarar vi inom 24 timmar.
                    </p>
                </div>

                <div class="grid lg:grid-cols-2 gap-12">
                    <!-- Vänster kolumn - Kontaktinformation -->
                    <div class="space-y-8">
                        <div class="space-y-6">
                            <!-- Email -->
                            <div class="flex items-start space-x-4 group">
                                <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20 group-hover:bg-white/20 transition-all duration-300">
                                    <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white mb-1">E-post</h3>
                                    <a href="mailto:info@webbi.se" class="text-slate-300 hover:text-indigo-300 transition-colors text-lg">info@webbi.se</a>
                                    <p class="text-sm text-slate-400 mt-1">Vi svarar inom 24 timmar</p>
                                </div>
                            </div>

                            <!-- Telefon -->
                            <div class="flex items-start space-x-4 group">
                                <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20 group-hover:bg-white/20 transition-all duration-300">
                                    <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white mb-1">Telefon</h3>
                                    <p class="text-slate-300 text-lg">+46 8 123 45 67</p>
                                    <p class="text-sm text-slate-400 mt-1">Vardagar 9:00 - 17:00</p>
                                </div>
                            </div>

                            <!-- Adress -->
                            <div class="flex items-start space-x-4 group">
                                <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20 group-hover:bg-white/20 transition-all duration-300">
                                    <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-white mb-1">Kontor</h3>
                                    <p class="text-slate-300 text-lg">Stockholm, Sverige</p>
                                    <p class="text-sm text-slate-400 mt-1">Svensk support och utveckling</p>
                                </div>
                            </div>
                        </div>

                        <!-- Fördelar med att kontakta oss -->
                        <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                            <h3 class="text-lg font-bold text-white mb-4">Vad kan vi hjälpa dig med?</h3>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-emerald-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-slate-300">Gratis rådgivning om AI-innehåll</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-emerald-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-slate-300">Hjälp med integration av din sajt</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-emerald-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-slate-300">Personlig demo av plattformen</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-emerald-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-slate-300">Diskussion om rätt paket</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Höger kolumn - Kontaktformulär -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20" x-data="contactForm()">
                        <h3 class="text-2xl font-bold text-white mb-6">Skicka meddelande</h3>

                        <!-- Success meddelande -->
                        <div x-show="showSuccess" x-cloak class="mb-6 p-4 bg-emerald-500/20 border border-emerald-400/30 rounded-xl">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-emerald-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-emerald-300 font-medium">Tack för ditt meddelande! Vi återkommer inom 24 timmar.</p>
                            </div>
                        </div>

                        <!-- Error meddelande -->
                        <div x-show="showError" x-cloak class="mb-6 p-4 bg-red-500/20 border border-red-400/30 rounded-xl">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-red-300 font-medium" x-text="errorMessage">Något gick fel. Försök igen.</p>
                            </div>
                        </div>

                        <form @submit.prevent="submitForm()" class="space-y-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-200 mb-3">Namn *</label>
                                    <input type="text" x-model="form.name" required
                                           class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                           placeholder="Ditt namn">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-200 mb-3">E-post *</label>
                                    <input type="email" x-model="form.email" required
                                           class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                           placeholder="din@epost.se">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-200 mb-3">Företag</label>
                                <input type="text" x-model="form.company"
                                       class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                       placeholder="Ditt företag (valfritt)">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-200 mb-3">Ämne</label>
                                <select x-model="form.subject"
                                        class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                                    <option value="" disabled>Välj ämne</option>
                                    <option value="demo" class="text-slate-800">Boka demo</option>
                                    <option value="questions" class="text-slate-800">Allmänna frågor</option>
                                    <option value="support" class="text-slate-800">Support</option>
                                    <option value="integration" class="text-slate-800">Hjälp med integration</option>
                                    <option value="pricing" class="text-slate-800">Priser och paket</option>
                                    <option value="other" class="text-slate-800">Annat</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-200 mb-3">Meddelande *</label>
                                <textarea x-model="form.message" required rows="4"
                                          class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 resize-none"
                                          placeholder="Berätta vad vi kan hjälpa dig med..."></textarea>
                            </div>

                            <button type="submit" :disabled="isSubmitting"
                                    class="w-full px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-[1.01] active:scale-[0.99] shadow-lg"
                                    :class="{ 'cursor-not-allowed opacity-50': isSubmitting }">
                                <span x-show="!isSubmitting">Skicka meddelande</span>
                                <span x-show="isSubmitting" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Skickar...
                                </span>
                            </button>

                            <p class="text-xs text-slate-400 text-center">
                                Vi behandlar din information enligt vår integritetspolicy och kontaktar dig endast angående din förfrågan.
                            </p>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Alpine.js script för kontaktformulär -->
            <script>
                function contactForm() {
                    return {
                        form: {
                            name: '',
                            email: '',
                            company: '',
                            subject: '',
                            message: ''
                        },
                        isSubmitting: false,
                        showSuccess: false,
                        showError: false,
                        errorMessage: '',

                        async submitForm() {
                            this.isSubmitting = true;
                            this.showSuccess = false;
                            this.showError = false;

                            try {
                                const response = await fetch('/contact', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify(this.form)
                                });

                                if (response.ok) {
                                    this.showSuccess = true;
                                    this.form = {
                                        name: '',
                                        email: '',
                                        company: '',
                                        subject: '',
                                        message: ''
                                    };
                                } else {
                                    const errorData = await response.json();
                                    this.errorMessage = errorData.message || 'Något gick fel. Försök igen.';
                                    this.showError = true;
                                }
                            } catch (error) {
                                this.errorMessage = 'Något gick fel. Kontrollera din internetanslutning och försök igen.';
                                this.showError = true;
                            }

                            this.isSubmitting = false;
                        }
                    }
                }
            </script>
        </section>
    </main>
@endsection
