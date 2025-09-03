
@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-12">
        <!-- Hero Section -->
        <div class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 rounded-3xl shadow-2xl border border-indigo-100/50 p-8">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl flex items-center justify-center shadow-lg mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Komplett guide till WebGrow AI</h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Lär dig att använda alla funktioner för att växa ditt digitala närvaro</p>
            </div>

            <!-- Quick Navigation -->
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 text-center">Hoppa till:</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                    <a href="#seo-audit" class="flex flex-col items-center p-3 bg-white rounded-xl hover:bg-blue-50 transition-colors border border-gray-200 hover:border-blue-300">
                        <svg class="w-6 h-6 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="text-xs font-medium text-center">SEO Audit</span>
                    </a>
                    <a href="#keywords" class="flex flex-col items-center p-3 bg-white rounded-xl hover:bg-indigo-50 transition-colors border border-gray-200 hover:border-indigo-300">
                        <svg class="w-6 h-6 text-indigo-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                        </svg>
                        <span class="text-xs font-medium text-center">Nyckelord</span>
                    </a>
                    <a href="#cro" class="flex flex-col items-center p-3 bg-white rounded-xl hover:bg-purple-50 transition-colors border border-gray-200 hover:border-purple-300">
                        <svg class="w-6 h-6 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span class="text-xs font-medium text-center">CRO-förslag</span>
                    </a>
                    <a href="#ai-content" class="flex flex-col items-center p-3 bg-white rounded-xl hover:bg-pink-50 transition-colors border border-gray-200 hover:border-pink-300">
                        <svg class="w-6 h-6 text-pink-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        <span class="text-xs font-medium text-center">AI-innehåll</span>
                    </a>
                    <a href="#leads" class="flex flex-col items-center p-3 bg-white rounded-xl hover:bg-emerald-50 transition-colors border border-gray-200 hover:border-emerald-300">
                        <svg class="w-6 h-6 text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="text-xs font-medium text-center">Leads</span>
                    </a>
                    <a href="#publications" class="flex flex-col items-center p-3 bg-white rounded-xl hover:bg-orange-50 transition-colors border border-gray-200 hover:border-orange-300">
                        <svg class="w-6 h-6 text-orange-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span class="text-xs font-medium text-center">Publiceringar</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- SEO Audit Section -->
        <section id="seo-audit" class="space-y-8">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-8 border border-blue-200">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">SEO Audit - Analysera din sajts prestanda</h2>
                        <p class="text-blue-800 mt-2">Få en komplett teknisk analys av din webbplats och konkreta förbättringsförslag</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="2.5"/></svg> Vad analyseras?</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                <div>
                                    <strong class="text-gray-900">Performance (0-100):</strong>
                                    <p class="text-gray-600 text-sm">Laddningstider, bildoptimering, serverrespons</p>
                                </div>
                            </li>
                            <li class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                <div>
                                    <strong class="text-gray-900">SEO Score (0-100):</strong>
                                    <p class="text-gray-600 text-sm">Meta-taggar, rubriker, mobiloptimering</p>
                                </div>
                            </li>
                            <li class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                <div>
                                    <strong class="text-gray-900">Accessibility:</strong>
                                    <p class="text-gray-600 text-sm">Tillgänglighet för funktionsnedsatta</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.25 4.5c3 0 5.25 2.25 5.25 5.25 0 2.25-1.5 4.5-3.75 6.75L9 23.25l1.5-6.75C8.25 14.25 6 12 6 9c0-3 2.25-4.5 5.25-4.5h3zM9 9l6 6"/><path d="M4.5 19.5c0-1.5 1.5-3 3-3"/></svg> Steg-för-steg:</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <span class="w-6 h-6 bg-blue-500 text-white rounded-full text-sm flex items-center justify-center font-semibold">1</span>
                                <div>
                                    <p class="font-medium">Gå till <a href="{{ route('seo.audit.history') }}" class="text-blue-600 hover:underline">SEO Audit</a></p>
                                    <p class="text-sm text-gray-600">Hitta under SEO-menyn</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="w-6 h-6 bg-blue-500 text-white rounded-full text-sm flex items-center justify-center font-semibold">2</span>
                                <div>
                                    <p class="font-medium">Klicka "Kör ny audit"</p>
                                    <p class="text-sm text-gray-600">Analysen tar 2-5 minuter</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="w-6 h-6 bg-blue-500 text-white rounded-full text-sm flex items-center justify-center font-semibold">3</span>
                                <div>
                                    <p class="font-medium">Granska resultaten</p>
                                    <p class="text-sm text-gray-600">Se vad som kan förbättras</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 bg-blue-100 rounded-xl p-6">
                    <h4 class="font-semibold text-blue-900 mb-3"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M8.25 14.25A6 6 0 1 1 15.75 9c0 2.25-1.5 3.75-3 4.5v.75H11.25v-.75c-1.5-.75-3-2.25-3-4.5"/><path d="M9 21h6"/></svg> Tolka resultaten:</h4>
                    <div class="grid md:grid-cols-3 gap-4 text-sm">
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                            <span><strong class="text-green-700">90-100:</strong> Utmärkt!</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                            <span><strong class="text-yellow-700">70-89:</strong> Bra, kan förbättras</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                            <span><strong class="text-red-700">0-69:</strong> Behöver åtgärder</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Keywords Section -->
        <section id="keywords" class="space-y-8">
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8 border border-indigo-200">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">Nyckelordsförslag - Hitta rätt sökord</h2>
                        <p class="text-indigo-800 mt-2">AI-driven analys som identifierar nyckelord du kan ranka för</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="6"/><path d="M20 20l-4-4"/></svg> Vad du får:</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-indigo-500 rounded-full mt-2"></div>
                                <div>
                                    <strong class="text-gray-900">Nyckelordspotential:</strong>
                                    <p class="text-gray-600 text-sm">Ord du kan börja ranka för</p>
                                </div>
                            </li>
                            <li class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-indigo-500 rounded-full mt-2"></div>
                                <div>
                                    <strong class="text-gray-900">Svårighetsgrad:</strong>
                                    <p class="text-gray-600 text-sm">Hur hårt konkurransen är</p>
                                </div>
                            </li>
                            <li class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-indigo-500 rounded-full mt-2"></div>
                                <div>
                                    <strong class="text-gray-900">Innehållsförslag:</strong>
                                    <p class="text-gray-600 text-sm">Vad du ska skriva om</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 4h6l1 2h3v14H5V6h3zM9 4v2h6V4"/></svg> Så gör du:</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <span class="w-6 h-6 bg-indigo-500 text-white rounded-full text-sm flex items-center justify-center font-semibold">1</span>
                                <div>
                                    <p class="font-medium">Gå till <a href="{{ route('seo.keywords.index') }}" class="text-indigo-600 hover:underline">Nyckelordsförslag</a></p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="w-6 h-6 bg-indigo-500 text-white rounded-full text-sm flex items-center justify-center font-semibold">2</span>
                                <div>
                                    <p class="font-medium">Klicka "Hämta rankning & analys"</p>
                                    <p class="text-sm text-gray-600">Analysen tar 5-15 minuter</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="w-6 h-6 bg-indigo-500 text-white rounded-full text-sm flex items-center justify-center font-semibold">3</span>
                                <div>
                                    <p class="font-medium">Implementera förslagen</p>
                                    <p class="text-sm text-gray-600">Markera som "Applied" när klart</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 bg-indigo-100 rounded-xl p-6">
                    <h4 class="font-semibold text-indigo-900 mb-3"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="2.5"/></svg> Pro-tips:</h4>
                    <div class="grid md:grid-cols-2 gap-4 text-sm text-indigo-800">
                        <div>• Börja med nyckelord som har <strong>låg svårighetsgrad</strong></div>
                        <div>• Implementera 2-3 förslag åt gången</div>
                        <div>• Kör ny analys månadsvis för att se utvecklingen</div>
                        <div>• Kombinera med SEO-audits för bäst effekt</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CRO Section -->
        <section id="cro" class="space-y-8">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-8 border border-purple-200">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">CRO-förslag - Öka dina konverteringar</h2>
                        <p class="text-purple-800 mt-2">AI-analys som hjälper dig få fler besökare att bli kunder</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19h16M6 17v-6M12 17V7M18 17v-10"/></svg> CRO analyserar:</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                                <div>
                                    <strong class="text-gray-900">Call-to-Actions:</strong>
                                    <p class="text-gray-600 text-sm">Knappar och länktext som driver handlingar</p>
                                </div>
                            </li>
                            <li class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                                <div>
                                    <strong class="text-gray-900">Social proof:</strong>
                                    <p class="text-gray-600 text-sm">Recensioner och trovärdighet</p>
                                </div>
                            </li>
                            <li class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                                <div>
                                    <strong class="text-gray-900">Urgency:</strong>
                                    <p class="text-gray-600 text-sm">Tidsbegränsade erbjudanden</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h7l-1 8 10-12h-7l1-8z"/></svg> Kom igång:</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <span class="w-6 h-6 bg-purple-500 text-white rounded-full text-sm flex items-center justify-center font-semibold">1</span>
                                <div>
                                    <p class="font-medium">Gå till <a href="{{ route('cro.suggestions.index') }}" class="text-purple-600 hover:underline">Konverteringsförslag</a></p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="w-6 h-6 bg-purple-500 text-white rounded-full text-sm flex items-center justify-center font-semibold">2</span>
                                <div>
                                    <p class="font-medium">Klicka "Kör analys"</p>
                                    <p class="text-sm text-gray-600">AI analyserar upp till 12 sidor</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="w-6 h-6 bg-purple-500 text-white rounded-full text-sm flex items-center justify-center font-semibold">3</span>
                                <div>
                                    <p class="font-medium">Implementera enkla förslag först</p>
                                    <p class="text-sm text-gray-600">Testa ett i taget för bäst resultat</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 bg-purple-100 rounded-xl p-6">
                    <h4 class="font-semibold text-purple-900 mb-3"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="2.5"/></svg> Vanliga förbättringar:</h4>
                    <div class="grid md:grid-cols-3 gap-4 text-sm text-purple-800">
                        <div>• <strong>Bättre rubriker</strong> - mer övertygande budskap</div>
                        <div>• <strong>Tydligare knappar</strong> - "Köp nu" istället för "Klicka här"</div>
                        <div>• <strong>Förtroendemarkörer</strong> - kundrecensioner och garantier</div>
                        <div>• <strong>Enklare formulär</strong> - färre fält = fler konverteringar</div>
                        <div>• <strong>Snabbare sidor</strong> - optimerade bilder och kod</div>
                        <div>• <strong>Mobiloptimering</strong> - fungerar perfekt på telefon</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- AI Content Section -->
        <section id="ai-content" class="space-y-8">
            <div class="bg-gradient-to-r from-pink-50 to-rose-50 rounded-2xl p-8 border border-pink-200">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-pink-500 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">AI-innehåll - Professionell text på sekunder</h2>
                        <p class="text-pink-800 mt-2">Skapa SEO-optimerat innehåll för hemsida, blogg och sociala medier</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Blogginlägg</h3>
                        <p class="text-sm text-gray-600">SEO-optimerade artiklar som engagerar läsare och bygger expertis</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Sociala medier</h3>
                        <p class="text-sm text-gray-600">Engagerande inlägg för Facebook, Instagram, LinkedIn</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Produkttext</h3>
                        <p class="text-sm text-gray-600">Säljande beskrivningar för e-handel och marknadsföring</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="2.5"/></svg> Steg-för-steg guide:</h3>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <span class="w-6 h-6 bg-pink-500 text-white rounded-full text-sm flex items-center justify-center font-semibold">1</span>
                                <div>
                                    <p class="font-medium">Välj texttyp</p>
                                    <p class="text-sm text-gray-600">Blogginlägg, produkttext, sociala medier</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="w-6 h-6 bg-pink-500 text-white rounded-full text-sm flex items-center justify-center font-semibold">2</span>
                                <div>
                                    <p class="font-medium">Beskriv ditt ämne</p>
                                    <p class="text-sm text-gray-600">Ju mer detaljer, desto bättre resultat</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="w-6 h-6 bg-pink-500 text-white rounded-full text-sm flex items-center justify-center font-semibold">3</span>
                                <div>
                                    <p class="font-medium">Ange målgrupp</p>
                                    <p class="text-sm text-gray-600">T.ex. "småföretagare" eller "föräldrar"</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <span class="w-6 h-6 bg-pink-500 text-white rounded-full text-sm flex items-center justify-center font-semibold">4</span>
                                <div>
                                    <p class="font-medium">Välj tonalitet</p>
                                    <p class="text-sm text-gray-600">Professionell, vänlig, auktoritativ</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="w-6 h-6 bg-pink-500 text-white rounded-full text-sm flex items-center justify-center font-semibold">5</span>
                                <div>
                                    <p class="font-medium">Publicera direkt</p>
                                    <p class="text-sm text-gray-600">WordPress, Facebook, Instagram, LinkedIn</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 bg-pink-100 rounded-xl p-6">
                    <h4 class="font-semibold text-pink-900 mb-3"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M8.25 14.25A6 6 0 1 1 15.75 9c0 2.25-1.5 3.75-3 4.5v.75H11.25v-.75c-1.5-.75-3-2.25-3-4.5"/><path d="M9 21h6"/></svg> Tips för bäst resultat:</h4>
                    <div class="grid md:grid-cols-2 gap-4 text-sm text-pink-800">
                        <div>• <strong>Var specifik</strong> - "CRM-tips för småföretag" bättre än "business tips"</div>
                        <div>• <strong>Inkludera nyckelord</strong> - för bättre SEO-rankning</div>
                        <div>• <strong>Beskriv målgruppen</strong> - åldern, intressena, problemen</div>
                        <div>• <strong>Redigera alltid</strong> - gör texten personlig och unik</div>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('ai.compose') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-600 to-rose-600 text-white font-semibold rounded-xl hover:from-pink-700 hover:to-rose-700 transition-all duration-200 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Skapa AI-innehåll nu
                    </a>
                </div>
            </div>
        </section>

        <!-- Leads Section -->
        <section id="leads" class="space-y-8">
            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl p-8 border border-emerald-200">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">Leads - Identifiera potentiella kunder</h2>
                        <p class="text-emerald-800 mt-2">Håll koll på vilka besökare som visar köpintresse</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19h16M6 17v-6M12 17V7M18 17v-10"/></svg> Lead-scoring mäter:</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mt-2"></div>
                                <div>
                                    <strong class="text-gray-900">Sidvisningar:</strong>
                                    <p class="text-gray-600 text-sm">Hur många sidor besökaren tittat på</p>
                                </div>
                            </li>
                            <li class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mt-2"></div>
                                <div>
                                    <strong class="text-gray-900">Tid på sajten:</strong>
                                    <p class="text-gray-600 text-sm">Längre tid = högre intresse</p>
                                </div>
                            </li>
                            <li class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mt-2"></div>
                                <div>
                                    <strong class="text-gray-900">Återkommande besök:</strong>
                                    <p class="text-gray-600 text-sm">Flera sessioner tyder på seriöst intresse</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="2.5"/></svg> Lead-kvalitet:</h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <span class="text-green-600 font-semibold text-sm">90+</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Het lead</p>
                                    <p class="text-sm text-gray-600">Mycket intresserad - kontakta omedelbart!</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <span class="text-yellow-600 font-semibold text-sm">70+</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Varm lead</p>
                                    <p class="text-sm text-gray-600">Gott intresse - följ upp inom kort</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                    <span class="text-red-600 font-semibold text-sm">&lt;70</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Kall lead</p>
                                    <p class="text-sm text-gray-600">Lågt intresse - vårda med innehåll</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 bg-emerald-100 rounded-xl p-6">
                    <h4 class="font-semibold text-emerald-900 mb-3"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="2.5"/></svg> Så använder du leads:</h4>
                    <div class="grid md:grid-cols-2 gap-4 text-sm text-emerald-800">
                        <div>• <strong>Kontakta heta leads först</strong> - de är redo att köpa</div>
                        <div>• <strong>Personlig uppföljning</strong> - ring eller maila direkt</div>
                        <div>• <strong>Vårda varma leads</strong> - skicka relevant information</div>
                        <div>• <strong>Automatisera kalla leads</strong> - e-postsekvenser och nyhetsbrev</div>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('leads.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all duration-200 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Se dina leads
                    </a>
                </div>
            </div>
        </section>

        <!-- Publications Section -->
        <section id="publications" class="space-y-8">
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-2xl p-8 border border-orange-200">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900">Publiceringar - Centraliserad innehållshantering</h2>
                        <p class="text-orange-800 mt-2">Håll koll på allt innehåll du publicerat på olika plattformar</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 4h6l1 2h3v14H5V6h3zM9 4v2h6V4"/></svg> Vad visas här:</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                                <div>
                                    <strong class="text-gray-900">WordPress-inlägg:</strong>
                                    <p class="text-gray-600 text-sm">Blogginlägg och sidor publicerade på din hemsida</p>
                                </div>
                            </li>
                            <li class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                                <div>
                                    <strong class="text-gray-900">Sociala medier:</strong>
                                    <p class="text-gray-600 text-sm">Inlägg på Facebook, Instagram, LinkedIn</p>
                                </div>
                            </li>
                            <li class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                                <div>
                                    <strong class="text-gray-900">Schemalagda inlägg:</strong>
                                    <p class="text-gray-600 text-sm">Innehåll som väntar på att publiceras</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19h16M6 17v-6M12 17V7M18 17v-10"/></svg> Statusar du ser:</h3>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                                <div>
                                    <strong class="text-gray-900">Publicerad:</strong>
                                    <span class="text-sm text-gray-600">Innehållet är live</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                                <div>
                                    <strong class="text-gray-900">Köad/Pågår:</strong>
                                    <span class="text-sm text-gray-600">Väntar på publicering</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                                <div>
                                    <strong class="text-gray-900">Misslyckad:</strong>
                                    <span class="text-sm text-gray-600">Problem med publiceringen</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 bg-orange-100 rounded-xl p-6">
                    <h4 class="font-semibold text-orange-900 mb-3"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M8.25 14.25A6 6 0 1 1 15.75 9c0 2.25-1.5 3.75-3 4.5v.75H11.25v-.75c-1.5-.75-3-2.25-3-4.5"/><path d="M9 21h6"/></svg> Tips för publiceringar:</h4>
                    <div class="grid md:grid-cols-2 gap-4 text-sm text-orange-800">
                        <div>• <strong>Schemalägg strategiskt</strong> - publicera när din målgrupp är aktiv</div>
                        <div>• <strong>Följ upp misslyckades</strong> - klicka "Kör om" för att försöka igen</div>
                        <div>• <strong>Analysera prestanda</strong> - se GA4-data direkt i listan</div>
                        <div>• <strong>Håll koll på externa ID</strong> - för att hitta inläggen på plattformarna</div>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('publications.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-600 to-amber-600 text-white font-semibold rounded-xl hover:from-orange-700 hover:to-amber-700 transition-all duration-200 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Se alla publiceringar
                    </a>
                </div>
            </div>
        </section>

        <!-- Quick Start Workflow -->
        <section class="space-y-8">
            <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-8 text-white">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold mb-4"><svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.25 4.5c3 0 5.25 2.25 5.25 5.25 0 2.25-1.5 4.5-3.75 6.75L9 23.25l1.5-6.75C8.25 14.25 6 12 6 9c0-3 2.25-4.5 5.25-4.5h3zM9 9l6 6"/><path d="M4.5 19.5c0-1.5 1.5-3 3-3"/></svg> Rekommenderat arbetsflöde</h2>
                    <p class="text-gray-300 text-lg">Så kommer du igång på bästa sätt</p>
                </div>

                <div class="grid md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-white font-bold text-lg">1</span>
                        </div>
                        <h3 class="font-semibold mb-2">Kör SEO Audit</h3>
                        <p class="text-sm text-gray-400">Se vad som behöver fixas på din sajt först</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-white font-bold text-lg">2</span>
                        </div>
                        <h3 class="font-semibold mb-2">Analysera nyckelord</h3>
                        <p class="text-sm text-gray-400">Hitta vilka ord du ska satsa på</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-white font-bold text-lg">3</span>
                        </div>
                        <h3 class="font-semibold mb-2">Skapa AI-innehåll</h3>
                        <p class="text-sm text-gray-400">Publicera optimerat innehåll baserat på dina nyckelord</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-pink-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-white font-bold text-lg">4</span>
                        </div>
                        <h3 class="font-semibold mb-2">Optimera för leads</h3>
                        <p class="text-sm text-gray-400">Använd CRO-förslag för att öka konverteringarna</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Support Section -->
        <section class="space-y-8">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl p-8 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-2">Behöver du mer hjälp?</h3>
                            <p class="text-indigo-100">Vi finns här för att hjälpa dig lyckas med din digitala marknadsföring</p>
                        </div>
                    </div>
                    <div class="flex flex-col space-y-3">
                        <a href="mailto:info@webbi.se" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 px-6 py-3 rounded-xl font-medium transition-all duration-200 text-center">
                            <svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18v12H3zM3 6l9 7 9-7"/></svg> Kontakta support
                        </a>
                        <a href="{{ route('dashboard') }}" class="bg-white text-indigo-600 hover:bg-gray-50 px-6 py-3 rounded-xl font-medium transition-all duration-200 text-center">
                            <svg aria-hidden="true" viewBox="0 0 24 24" width="1em" height="1em" style="vertical-align:-0.125em" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l9-7 9 7v9H6v-6h6v6"/></svg> Till Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
@endpush
