@extends('layouts.guest', ['title' => 'WebGrow AI – Mer trafik, fler leads, mindre handpåläggning - Integritet'])
@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23f1f5f9" fill-opacity="0.4"%3E%3Ccircle cx="7" cy="7" r="1"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>

    <div class="relative max-w-6xl mx-auto px-4 py-12">
        <!-- Header -->
        <div class="text-center mb-16">
            <div class="flex justify-center mb-6">
                <x-authentication-card-logo />
            </div>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight bg-gradient-to-r from-gray-900 via-blue-900 to-indigo-900 bg-clip-text text-transparent mb-6">
                Integritetspolicy
            </h1>
            <div class="inline-flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm rounded-full border border-slate-200">
                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3 animate-pulse"></div>
                <span class="text-sm font-medium text-slate-700">Senast uppdaterad: {{ now()->format('j M Y') }}</span>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-slate-200/50 shadow-2xl">
            <div class="p-8 md:p-12">
                @if(!empty($policy))
                    <div class="prose prose-slate prose-lg max-w-none">
                        {!! $policy !!}
                    </div>
                @else
                    <div class="prose prose-slate prose-lg max-w-none">
                        <!-- Company Info Header -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50 p-6 mb-8">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-slate-800 mb-2">Om WebGrow AI</h2>
                                    <p class="text-slate-700 mb-3">
                                        Denna tjänst tillhandahålls av <strong>Webbi AB</strong><br>
                                        Organisationsnummer: <span class="font-mono text-sm">559331-3140</span><br>
                                        Adress: Sunnanvindsvägen 11, 451 60 Uddevalla, Sverige
                                    </p>
                                    <div class="flex flex-wrap gap-2 text-sm">
                                        <a href="mailto:info@webbi.se" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            info@webbi.se
                                        </a>
                                        <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-700 rounded-full">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                Sverige, EU
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2>Vad WebGrow AI gör</h2>
                        <p class="lead">
                            WebGrow AI är en AI-driven marknadsföringsplattform som automatiserar SEO-optimering,
                            CRO-insikter och contentpublicering för att hjälpa företag att växa snabbare med mindre handpåläggning.
                        </p>

                        <div class="grid md:grid-cols-2 gap-6 my-8">
                            <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl border border-emerald-200/50 p-6">
                                <h3 class="text-lg font-semibold text-emerald-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    SEO & CRO-verktyg
                                </h3>
                                <ul class="text-sm text-emerald-800 space-y-1">
                                    <li>• AI-förslag på nyckelord och meta-taggar</li>
                                    <li>• SerpAPI-baserad rankingkoll</li>
                                    <li>• Konverteringsoptimering av sidor</li>
                                    <li>• Prestandamätning och insikter</li>
                                </ul>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-200/50 p-6">
                                <h3 class="text-lg font-semibold text-purple-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                    AI-publicering
                                </h3>
                                <ul class="text-sm text-purple-800 space-y-1">
                                    <li>• Automatisk contentgenerering</li>
                                    <li>• WordPress-integration</li>
                                    <li>• Sociala kanaler (Facebook, Instagram, LinkedIn)</li>
                                    <li>• Schemaläggning och automation</li>
                                </ul>
                            </div>
                        </div>

                        <h2>Sociala medier-integrationer</h2>
                        <p>Vi stöder anslutning till följande plattformar för automatisk publicering och analys:</p>

                        <div class="grid md:grid-cols-3 gap-4 my-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    <h4 class="font-semibold text-blue-900">Facebook</h4>
                                </div>
                                <ul class="text-xs text-blue-800 space-y-1">
                                    <li>• Sidhantering och publicering</li>
                                    <li>• Insights och engagement-data</li>
                                    <li>• Automatiska inlägg</li>
                                </ul>
                            </div>

                            <div class="bg-pink-50 border border-pink-200 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-6 h-6 text-pink-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                    <h4 class="font-semibold text-pink-900">Instagram</h4>
                                </div>
                                <ul class="text-xs text-pink-800 space-y-1">
                                    <li>• Business-konto integration</li>
                                    <li>• Bildpublicering och Stories</li>
                                    <li>• Hashtag-optimering</li>
                                </ul>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-6 h-6 text-blue-700 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                    <h4 class="font-semibold text-blue-900">LinkedIn</h4>
                                </div>
                                <ul class="text-xs text-blue-800 space-y-1">
                                    <li>• Företagssidor</li>
                                    <li>• Professionellt nätverk</li>
                                    <li>• B2B-contenthantering</li>
                                </ul>
                            </div>
                        </div>

                        <h2>E-handelsplattformar</h2>
                        <p>Vi integrerar även med populära e-handelsplattformar för SEO- och konverteringsoptimering:</p>

                        <div class="grid md:grid-cols-2 gap-4 my-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M21.32 8.87L12 0 2.68 8.87A.5.5 0 003 9.5h2v12a.5.5 0 00.5.5h13a.5.5 0 00.5-.5v-12h2a.5.5 0 00.32-.13z"/>
                                    </svg>
                                    <h4 class="font-semibold text-blue-900">WordPress</h4>
                                </div>
                                <ul class="text-xs text-blue-800 space-y-1">
                                    <li>• Direkt API-integration</li>
                                    <li>• SEO-plugineroptimering</li>
                                    <li>• Contentautomation</li>
                                </ul>
                            </div>

                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-6 h-6 text-green-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                                    </svg>
                                    <h4 class="font-semibold text-green-900">Shopify</h4>
                                </div>
                                <ul class="text-xs text-green-800 space-y-1">
                                    <li>• Produktsidoptimering</li>
                                    <li>• Konverteringsförbättringar</li>
                                    <li>• E-handelsSEO</li>
                                </ul>
                            </div>
                        </div>

                        <h2>Vilka personuppgifter vi behandlar</h2>
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 my-6">
                            <h4 class="font-semibold text-amber-900 mb-3">Kortfattat: Vi samlar endast nödvändig data</h4>
                            <p class="text-amber-800 text-sm">
                                Vi följer principen om dataminimering – vi samlar bara den information som behövs
                                för att leverera tjänsten och förbättra din upplevelse.
                            </p>
                        </div>

                        <h3>Kontouppgifter</h3>
                        <ul>
                            <li><strong>Grundläggande identitetsuppgifter:</strong> Namn, e-postadress, telefonnummer</li>
                            <li><strong>Företagsinformation:</strong> Företagsnamn, bransch, webbplatsadress</li>
                            <li><strong>Faktureringsinformation:</strong> Betalningsmetoder, faktureringsadresser (via säker tredjepartsleverantör)</li>
                            <li><strong>Kommunikationshistorik:</strong> Support-ärenden, feedback, mejlkorrespondens</li>
                        </ul>

                        <h3>Teknisk data och användningsdata</h3>
                        <ul>
                            <li><strong>Sessionsdata:</strong> Inloggningssessioner, säkerhetstoken, preferenser</li>
                            <li><strong>Användningsstatistik:</strong> Funktionsanvändning, klickmönster, tid spenderad i systemet</li>
                            <li><strong>Teknisk information:</strong> IP-adresser, enhetstyp, webbläsare, operativsystem</li>
                            <li><strong>Prestandadata:</strong> Laddningstider, felavhjälpningsloggar</li>
                        </ul>

                        <h3>Integrationsdata</h3>
                        <ul>
                            <li><strong>API-anslutningsdata:</strong> Åtkomsttoken, refresh-token för sociala plattformar</li>
                            <li><strong>WordPress-anslutning:</strong> Site URL, användarnamn/app-lösenord för WP REST API</li>
                            <li><strong>Sociala kontouppgifter:</strong> Sid-ID, användar-ID, grundläggande profilinformation</li>
                            <li><strong>Contentdata:</strong> Texter, bilder och metadata som AI:n bearbetar</li>
                        </ul>

                        <h2>Rättslig grund för behandling</h2>
                        <div class="grid md:grid-cols-3 gap-4 my-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-900 mb-2">Avtal (Art. 6.1.b GDPR)</h4>
                                <p class="text-xs text-blue-800">
                                    För att leverera tjänstens kärnfunktioner enligt vårt avtal med dig.
                                </p>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h4 class="font-semibold text-green-900 mb-2">Samtycke (Art. 6.1.a GDPR)</h4>
                                <p class="text-xs text-green-800">
                                    För specifika integrationer där du uttryckligen ger behörigheter.
                                </p>
                            </div>
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <h4 class="font-semibold text-purple-900 mb-2">Berättigat intresse (Art. 6.1.f GDPR)</h4>
                                <p class="text-xs text-purple-800">
                                    För säkerhet, felavhjälpning och tjänsteförbättringar.
                                </p>
                            </div>
                        </div>

                        <h2>Hur vi skyddar dina uppgifter</h2>
                        <div class="bg-green-50 border border-green-200 rounded-xl p-6 my-6">
                            <h4 class="font-semibold text-green-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Säkerhet i fokus
                            </h4>
                            <div class="grid md:grid-cols-2 gap-4 text-sm text-green-800">
                                <div>
                                    <h5 class="font-medium mb-2">Tekniska skyddsåtgärder:</h5>
                                    <ul class="space-y-1 text-xs">
                                        <li>• TLS 1.3-kryptering för alla dataöverföringar</li>
                                        <li>• Säker databashashing av känslig information</li>
                                        <li>• Regelbundna säkerhetsuppdateringar</li>
                                        <li>• Inträdesloggning och aktivitetsövervakning</li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-medium mb-2">Organisatoriska åtgärder:</h5>
                                    <ul class="space-y-1 text-xs">
                                        <li>• Begränsad personalåtkomst på need-to-know-basis</li>
                                        <li>• Regelbunden säkerhetsutbildning</li>
                                        <li>• Incidentresponsplan</li>
                                        <li>• Dataskyddskonsekvensanalys</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <h2>Tredjepartsleverantörer</h2>
                        <p>För att leverera funktioner kan vi dela data med:</p>

                        <div class="overflow-x-auto my-6">
                            <table class="min-w-full border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Leverantör</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Syfte</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Data som delas</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Plats</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 text-sm">
                                <tr>
                                    <td class="px-4 py-2 font-medium">Meta (Facebook/Instagram)</td>
                                    <td class="px-4 py-2">Autentisering, publicering</td>
                                    <td class="px-4 py-2">Sid-ID, content för publicering</td>
                                    <td class="px-4 py-2">USA (Adequacy Decision)</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">LinkedIn Corporation</td>
                                    <td class="px-4 py-2">B2B-publicering</td>
                                    <td class="px-4 py-2">Företagssida-ID, inläggsdata</td>
                                    <td class="px-4 py-2">USA (Adequacy Decision)</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">OpenAI/Anthropic</td>
                                    <td class="px-4 py-2">AI-contentgenerering</td>
                                    <td class="px-4 py-2">Texter för bearbetning (anonymiserat)</td>
                                    <td class="px-4 py-2">USA (Adequacy Decision)</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">Mailgun/Postmark</td>
                                    <td class="px-4 py-2">Transaktionsmejl</td>
                                    <td class="px-4 py-2">E-postadress, namn</td>
                                    <td class="px-4 py-2">USA/EU</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">SerpAPI</td>
                                    <td class="px-4 py-2">SEO-rankingkoll</td>
                                    <td class="px-4 py-2">Söktermer, webbplats-URL</td>
                                    <td class="px-4 py-2">USA</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <h2>Cookies och spårning</h2>
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 my-6">
                            <h4 class="font-semibold text-blue-900 mb-3">Vi använder minimalt med cookies</h4>
                            <div class="grid md:grid-cols-2 gap-4 text-sm text-blue-800">
                                <div>
                                    <h5 class="font-medium mb-2">Nödvändiga cookies:</h5>
                                    <ul class="space-y-1 text-xs">
                                        <li>• Sessionscookies för inloggning</li>
                                        <li>• CSRF-skydd för säkerhet</li>
                                        <li>• Språk- och regionalinställningar</li>
                                    </ul>
                                </div>
                                <div>
                                    <h5 class="font-medium mb-2">Funktionella cookies (frivilligt):</h5>
                                    <ul class="space-y-1 text-xs">
                                        <li>• Användarpreferenser</li>
                                        <li>• Dashboard-inställningar</li>
                                        <li>• Pausade arbetsflöden</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <h2>Dina rättigheter enligt GDPR</h2>
                        <div class="grid md:grid-cols-2 gap-6 my-6">
                            <div class="space-y-4">
                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Rätt till tillgång
                                    </h4>
                                    <p class="text-sm text-gray-600">Du kan begära en kopia av all data vi har om dig.</p>
                                </div>

                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Rätt till rättelse
                                    </h4>
                                    <p class="text-sm text-gray-600">Du kan be oss rätta felaktiga uppgifter.</p>
                                </div>

                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Rätt till radering
                                    </h4>
                                    <p class="text-sm text-gray-600">Du kan begära att vi raderar dina personuppgifter.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                        </svg>
                                        Rätt till dataportabilitet
                                    </h4>
                                    <p class="text-sm text-gray-600">Du kan få ut dina uppgifter i ett strukturerat format.</p>
                                </div>

                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 18M5.636 5.636L6 6"></path>
                                        </svg>
                                        Rätt att invända
                                    </h4>
                                    <p class="text-sm text-gray-600">Du kan invända mot behandling baserad på berättigat intresse.</p>
                                </div>

                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        Rätt till begränsning
                                    </h4>
                                    <p class="text-sm text-gray-600">Du kan begära att vi begränsar behandlingen.</p>
                                </div>
                            </div>
                        </div>

                        <h2>Så hanterar du dina integrationer</h2>
                        <div class="bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 rounded-xl p-6 my-6">
                            <h4 class="font-semibold text-orange-900 mb-3">Enkel frånkoppling</h4>
                            <div class="space-y-3 text-sm text-orange-800">
                                <div class="flex items-start space-x-3">
                                    <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="text-xs font-bold text-orange-700">1</span>
                                    </div>
                                    <div>
                                        <strong>Via gränssnittet:</strong> Gå till Inställningar → Sociala kanaler och klicka "Koppla från" för respektive plattform
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="text-xs font-bold text-orange-700">2</span>
                                    </div>
                                    <div>
                                        <strong>Via supporten:</strong> Kontakta info@webbi.se och ange vilka integrationer du vill ta bort
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="text-xs font-bold text-orange-700">3</span>
                                    </div>
                                    <div>
                                        <strong>På plattformsnivå:</strong> Du kan även återkalla behörigheter direkt i Facebook, Instagram, LinkedIn etc.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2>Lagring och bevarandetider</h2>
                        <div class="overflow-x-auto my-6">
                            <table class="min-w-full border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Typ av data</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bevarandetid</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Anledning</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 text-sm">
                                <tr>
                                    <td class="px-4 py-2 font-medium">Kontouppgifter</td>
                                    <td class="px-4 py-2">Under avtalsperioden + 3 år</td>
                                    <td class="px-4 py-2">Avtalsuppfyllelse, bokföring</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">Användningsdata</td>
                                    <td class="px-4 py-2">24 månader</td>
                                    <td class="px-4 py-2">Tjänsteförbättringar, support</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">API-token</td>
                                    <td class="px-4 py-2">Tills återkallat</td>
                                    <td class="px-4 py-2">Funktionalitet</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">Säkerhetsloggar</td>
                                    <td class="px-4 py-2">12 månader</td>
                                    <td class="px-4 py-2">Säkerhet, incidenthantering</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">Support-ärenden</td>
                                    <td class="px-4 py-2">3 år efter stängning</td>
                                    <td class="px-4 py-2">Kvalitetsförbättringar</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <h2>Ändringar i denna policy</h2>
                        <p>
                            Vi kan uppdatera denna policy vid behov, till exempel när vi lägger till nya funktioner
                            eller för att följa nya rättsliga krav. Vi meddelar väsentliga ändringar på följande sätt:
                        </p>
                        <ul>
                            <li><strong>E-postmeddelande</strong> till alla registrerade användare minst 30 dagar innan ändringen träder i kraft</li>
                            <li><strong>Notis i tjänsten</strong> när du loggar in</li>
                            <li><strong>Uppdaterat datum</strong> överst på denna sida</li>
                        </ul>
                        <p>
                            Fortsatt användning efter uppdatering innebär att du accepterar de ändrade villkoren.
                            Om du inte accepterar ändringarna kan du avsluta ditt konto innan de träder i kraft.
                        </p>

                        <!-- Contact Section -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50 p-8 my-8">
                            <h2 class="text-2xl font-bold text-slate-800 mb-6">Kontakta oss</h2>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="font-semibold text-blue-900 mb-3">Personuppgiftsansvarig</h4>
                                    <div class="space-y-2 text-sm text-blue-800">
                                        <p><strong>Webbi AB</strong></p>
                                        <p>Org.nr: 559331-3140</p>
                                        <p>Sunnanvindsvägen 11</p>
                                        <p>451 60 Uddevalla, Sverige</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <a href="mailto:info@webbi.se" class="flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-colors text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        info@webbi.se
                                    </a>
                                    <a href="mailto:info@webbi.se" class="flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition-colors text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        privacy@webgrowai.com
                                    </a>
                                    <div class="pt-2 text-xs text-slate-600">
                                        <p><strong>Svarstid:</strong> Vi svarar på GDPR-förfrågningar inom 30 dagar</p>
                                        <p><strong>Klagomålsrätt:</strong> Du har rätt att lämna klagomål till Integritetsskyddsmyndigheten</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center text-sm text-slate-500 border-t border-slate-200 pt-8 mt-8">
                            <p>
                                Denna policy efterlever GDPR och svensk dataskyddslagstiftning.<br>
                                Vid skillnader mellan svenska och engelska versioner gäller den svenska versionen.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Back to home link -->
        <div class="text-center mt-12">
            <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Tillbaka till WebGrow AI
            </a>
        </div>
    </div>
@endsection
