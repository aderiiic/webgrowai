
@extends('layouts.guest', ['title' => 'Priser – WebGrow AI'])

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-900 py-20" x-data="{ annual: false }">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Back to Home Link -->
            <div class="mb-8">
                <a href="{{ route('welcome') }}" class="inline-flex items-center text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Tillbaka till startsidan
                </a>
            </div>

            <!-- Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20 mb-6">
                    <svg class="w-4 h-4 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-sm font-medium text-white">14 dagars gratis testperiod – ingen bindningstid</span>
                </div>

                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Välj den plan som passar dig
                </h1>
                <p class="text-xl text-slate-300 max-w-3xl mx-auto mb-8">
                    Alla planer inkluderar full tillgång till AI-generering, automatisk publicering och SEO-verktyg
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
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/0 via-purple-500/0 to-pink-500/0 group-hover:from-blue-500/10 group-hover:via-purple-500/10 group-hover:to-pink-500/10 transition-all duration-500"></div>

                    <div class="relative p-8">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Starter</h3>
                            <p class="text-slate-400 text-sm">Perfekt för småföretag</p>
                        </div>

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

                        <ul class="space-y-3 mb-8">
                            <li class="flex items-start gap-3 text-slate-300">
                                <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>Sociala medier-texter</span>
                            </li>
                            <li class="flex items-start gap-3 text-slate-300">
                                <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>Bloggtexter & produktbeskrivningar</span>
                            </li>
                            <li class="flex items-start gap-3 text-slate-300">
                                <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>AI-bildgenerering</span>
                            </li>
                            <li class="flex items-start gap-3 text-slate-300">
                                <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>Automatisk publicering</span>
                            </li>
                            <li class="flex items-start gap-3 text-slate-300">
                                <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>1 webbplats</strong>, 1 användare</span>
                            </li>
                        </ul>

                        <a href="{{ route('register') }}" class="block w-full py-4 px-6 bg-white/10 backdrop-blur-sm text-white font-semibold rounded-xl border border-white/20 hover:bg-white/20 hover:border-white/40 transition-all duration-300 text-center group-hover:scale-105">
                            Prova gratis i 14 dagar
                        </a>
                    </div>
                </div>

                <!-- Growth Plan - Featured -->
                <div class="group relative bg-gradient-to-br from-indigo-600 to-purple-600 rounded-3xl border-2 border-white/20 hover:border-white/40 transition-all duration-500 overflow-hidden scale-105 lg:scale-110 hover:scale-110 lg:hover:scale-115 shadow-2xl hover:shadow-purple-500/50">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                        <div class="flex items-center gap-2 bg-gradient-to-r from-emerald-400 to-green-500 text-slate-900 px-6 py-2.5 rounded-full text-sm font-bold shadow-xl border-4 border-slate-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                            <span class="mt-1">Mest populär</span>
                        </div>
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-br from-white/0 via-white/0 to-white/0 group-hover:from-white/10 group-hover:via-white/5 group-hover:to-white/10 transition-all duration-500"></div>

                    <div class="relative p-8 pt-12">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300 border border-white/30">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-bold text-white mb-2">Growth</h3>
                            <p class="text-indigo-100 text-sm">För växande företag</p>
                        </div>

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
                                <span class="font-semibold">Multi-text (25 texter samtidigt)</span>
                            </li>
                            <li class="flex items-start gap-3 text-white">
                                <svg class="w-5 h-5 text-yellow-300 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">SEO-optimering & nyckelordsanalys</span>
                            </li>
                            <li class="flex items-start gap-3 text-white">
                                <svg class="w-5 h-5 text-yellow-300 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">Konverteringsanalys & lead tracking</span>
                            </li>
                            <li class="flex items-start gap-3 text-white">
                                <svg class="w-5 h-5 text-emerald-300 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium"><strong>3 webbplatser</strong>, 2 användare</span>
                            </li>
                        </ul>

                        <a href="{{ route('register') }}" class="block w-full py-4 px-6 bg-white text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transition-all duration-300 text-center group-hover:scale-105 shadow-xl hover:shadow-2xl">
                            Prova gratis i 14 dagar
                        </a>
                    </div>
                </div>

                <!-- Pro Plan -->
                <div class="group relative bg-white/5 backdrop-blur-xl rounded-3xl border border-white/10 hover:border-white/30 transition-all duration-500 overflow-hidden hover:scale-105 hover:shadow-2xl">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/0 via-pink-500/0 to-red-500/0 group-hover:from-purple-500/10 group-hover:via-pink-500/10 group-hover:to-red-500/10 transition-all duration-500"></div>

                    <div class="relative p-8">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Pro</h3>
                            <p class="text-slate-400 text-sm">För stora företag och byråer</p>
                        </div>

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
                                <span class="font-semibold">Multi-text PRO (50 texter samtidigt)</span>
                            </li>
                            <li class="flex items-start gap-3 text-slate-300">
                                <svg class="w-5 h-5 text-purple-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">Avancerad analys & rapporter</span>
                            </li>
                            <li class="flex items-start gap-3 text-slate-300">
                                <svg class="w-5 h-5 text-purple-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">Prioriterad support & dedikerad kontakt</span>
                            </li>
                            <li class="flex items-start gap-3 text-slate-300">
                                <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>10 webbplatser</strong>, 3 användare</span>
                            </li>
                        </ul>

                        <a href="{{ route('register') }}" class="block w-full py-4 px-6 bg-white/10 backdrop-blur-sm text-white font-semibold rounded-xl border border-white/20 hover:bg-white/20 hover:border-white/40 transition-all duration-300 text-center group-hover:scale-105">
                            Prova gratis i 14 dagar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Feature Comparison Table -->
            <div class="max-w-6xl mx-auto mb-16">
                <div class="bg-white/5 backdrop-blur-xl rounded-3xl border border-white/10 overflow-hidden">
                    <div class="p-8 bg-white/5 border-b border-white/10">
                        <h2 class="text-3xl font-bold text-white text-center">Jämför funktioner</h2>
                        <p class="text-slate-300 text-center mt-2">Se vad som ingår i varje plan</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                            <tr class="border-b border-white/10">
                                <th class="text-left p-6 text-white font-semibold">Funktioner</th>
                                <th class="text-center p-6 text-white font-semibold">Starter</th>
                                <th class="text-center p-6 text-white font-semibold bg-white/5">
                                    <div class="flex flex-col items-center">
                                        <span>Growth</span>
                                        <span class="text-xs text-emerald-400 font-normal mt-1">Mest populär</span>
                                    </div>
                                </th>
                                <th class="text-center p-6 text-white font-semibold">Pro</th>
                            </tr>
                            </thead>
                            <tbody class="text-slate-300">
                            <!-- Credits -->
                            <tr class="border-b border-white/10">
                                <td class="p-6 font-medium text-white">Credits per månad</td>
                                <td class="p-6 text-center">5 000</td>
                                <td class="p-6 text-center bg-white/5">15 000</td>
                                <td class="p-6 text-center">50 000</td>
                            </tr>

                            <!-- Websites -->
                            <tr class="border-b border-white/10">
                                <td class="p-6 font-medium text-white">Antal webbplatser</td>
                                <td class="p-6 text-center">1</td>
                                <td class="p-6 text-center bg-white/5">3</td>
                                <td class="p-6 text-center">10</td>
                            </tr>

                            <!-- Users -->
                            <tr class="border-b border-white/10">
                                <td class="p-6 font-medium text-white">Antal användare</td>
                                <td class="p-6 text-center">1</td>
                                <td class="p-6 text-center bg-white/5">2</td>
                                <td class="p-6 text-center">3</td>
                            </tr>

                            <!-- Multi-text -->
                            <tr class="border-b border-white/10">
                                <td class="p-6 font-medium text-white">Multi-text (samtidiga texter)</td>
                                <td class="p-6 text-center">
                                    <svg class="w-5 h-5 text-slate-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                                <td class="p-6 text-center bg-white/5">
                                    <span class="text-emerald-400 font-semibold">25</span>
                                </td>
                                <td class="p-6 text-center">
                                    <span class="text-purple-400 font-semibold">50</span>
                                </td>
                            </tr>

                            <!-- AI Text -->
                            <tr class="border-b border-white/10">
                                <td class="p-6 font-medium text-white">AI-textgenerering</td>
                                <td class="p-6 text-center">
                                    <svg class="w-6 h-6 text-emerald-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                                <td class="p-6 text-center bg-white/5">
                                    <svg class="w-6 h-6 text-emerald-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                                <td class="p-6 text-center">
                                    <svg class="w-6 h-6 text-emerald-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                            </tr>

                            <!-- AI Images -->
                            <tr class="border-b border-white/10">
                                <td class="p-6 font-medium text-white">AI-bildgenerering</td>
                                <td class="p-6 text-center">
                                    <svg class="w-6 h-6 text-emerald-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                                <td class="p-6 text-center bg-white/5">
                                    <svg class="w-6 h-6 text-emerald-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                                <td class="p-6 text-center">
                                    <svg class="w-6 h-6 text-emerald-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                            </tr>

                            <!-- Publishing -->
                            <tr class="border-b border-white/10">
                                <td class="p-6 font-medium text-white">Automatisk publicering</td>
                                <td class="p-6 text-center">
                                    <svg class="w-6 h-6 text-emerald-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                                <td class="p-6 text-center bg-white/5">
                                    <svg class="w-6 h-6 text-emerald-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                                <td class="p-6 text-center">
                                    <svg class="w-6 h-6 text-emerald-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                            </tr>

                            <!-- SEO -->
                            <tr class="border-b border-white/10">
                                <td class="p-6 font-medium text-white">SEO-optimering & nyckelordsanalys</td>
                                <td class="p-6 text-center">
                                    <svg class="w-5 h-5 text-slate-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                                <td class="p-6 text-center bg-white/5">
                                    <svg class="w-6 h-6 text-emerald-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                                <td class="p-6 text-center">
                                    <svg class="w-6 h-6 text-emerald-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                            </tr>

                            <!-- CRO -->
                            <tr class="border-b border-white/10">
                                <td class="p-6 font-medium text-white">Konverteringsanalys & lead tracking</td>
                                <td class="p-6 text-center">
                                    <svg class="w-5 h-5 text-slate-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                                <td class="p-6 text-center bg-white/5">
                                    <svg class="w-6 h-6 text-emerald-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                                <td class="p-6 text-center">
                                    <svg class="w-6 h-6 text-emerald-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                            </tr>

                            <!-- Analytics -->
                            <tr class="border-b border-white/10">
                                <td class="p-6 font-medium text-white">Avancerad analys & rapporter</td>
                                <td class="p-6 text-center">
                                    <svg class="w-5 h-5 text-slate-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                                <td class="p-6 text-center bg-white/5">
                                    <svg class="w-5 h-5 text-slate-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                                <td class="p-6 text-center">
                                    <svg class="w-6 h-6 text-emerald-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                            </tr>

                            <!-- Support -->
                            <tr>
                                <td class="p-6 font-medium text-white">Support</td>
                                <td class="p-6 text-center">E-post</td>
                                <td class="p-6 text-center bg-white/5">Prioriterad</td>
                                <td class="p-6 text-center">Dedikerad kontakt</td>
                            </tr>
                            </tbody>
                        </table>
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
                        <h3 class="text-2xl font-bold text-white mb-2">100% riskfritt att testa</h3>
                        <p class="text-slate-300 text-lg">Prova alla funktioner gratis i 14 dagar – inget betalkort krävs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
