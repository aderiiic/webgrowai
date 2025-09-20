@extends('layouts.guest', ['title' => 'WebGrow AI – Mer trafik, fler leads, mindre handpåläggning - Villkor'])
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
            Användarvillkor & Tjänsteavtal
        </h1>
        <div class="inline-flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm rounded-full border border-slate-200">
            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3 animate-pulse"></div>
            <span class="text-sm font-medium text-slate-700">Gäller från: {{ now()->format('j M Y') }}</span>
        </div>
    </div>

    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-slate-200/50 shadow-2xl">
        <div class="p-8 md:p-12">
            @if(!empty($terms))
                <div class="prose prose-slate prose-lg max-w-none">
                    {!! $terms !!}
                </div>
            @else
                <div class="prose prose-slate prose-lg max-w-none">
                    <!-- Introduction -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50 p-6 mb-8">
                        <h2 class="text-xl font-bold text-slate-800 mb-3 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Välkommen till WebGrow AI
                        </h2>
                        <p class="text-slate-700 mb-0">
                            Dessa användarvillkor reglerar din användning av WebGrow AI-plattformen.
                            Genom att registrera dig eller använda våra tjänster accepterar du dessa villkor i sin helhet.
                        </p>
                    </div>

                    <h2>1. Tjänsteleverantör</h2>
                    <p>
                        WebGrow AI tillhandahålls av <strong>Webbi AB</strong>, org.nr 5593313140,
                        med säte i Uddevalla, Sverige ("vi", "oss", "vårt", "Webbi AB").
                    </p>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 my-4">
                        <h4 class="font-semibold text-gray-900 mb-2">Kontaktuppgifter:</h4>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li><strong>Adress:</strong> Uddevalla, Sverige</li>
                            <li><strong>E-post:</strong> info@webbi.se</li>
                            <li><strong>Juridiska frågor:</strong> info@webbi.se</li>
                        </ul>
                    </div>

                    <h2>2. Tjänstebeskrivning</h2>
                    <p>WebGrow AI är en AI-driven marknadsföringsplattform som erbjuder:</p>

                    <div class="grid md:grid-cols-2 gap-6 my-8">
                        <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl border border-emerald-200/50 p-6">
                            <h4 class="text-lg font-semibold text-emerald-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                SEO & CRO-verktyg
                            </h4>
                            <ul class="text-sm text-emerald-800 space-y-1">
                                <li>• AI-genererade nyckelord och meta-taggar</li>
                                <li>• SerpAPI-baserad rankinganalys</li>
                                <li>• Konverteringsoptimering av webbsidor</li>
                                <li>• Prestandamätning och SEO-audits</li>
                            </ul>
                        </div>

                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-200/50 p-6">
                            <h4 class="text-lg font-semibold text-purple-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                AI-publicering & automation
                            </h4>
                            <ul class="text-sm text-purple-800 space-y-1">
                                <li>• Automatisk contentgenerering</li>
                                <li>• WordPress-integration</li>
                                <li>• Sociala medier (Facebook, Instagram, LinkedIn)</li>
                                <li>• Schemaläggning och publicering</li>
                            </ul>
                        </div>
                    </div>

                    <h2>3. Provperiod</h2>
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 rounded-xl p-6 my-6">
                        <h4 class="font-semibold text-emerald-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            14 dagars gratis provperiod
                        </h4>
                        <ul class="text-sm text-emerald-800 space-y-2">
                            <li>• <strong>Längd:</strong> 14 kalenderdagar från registrering</li>
                            <li>• <strong>Tillgång:</strong> Full funktionalitet enligt Starter plan</li>
                            <li>• <strong>Betalning:</strong> Ingen betalning krävs under provperioden</li>
                            <li>• <strong>Avslut:</strong> Avsluta när som helst utan kostnad</li>
                            <li>• <strong>Manuell övergång:</strong> Efter 14 dagar kan du påbörja en prenumeration</li>
                        </ul>
                    </div>

                    <h2>4. Priser och planer</h2>
                    <p>Vi erbjuder tre huvudplaner med olika funktionalitet och volymer. Alla priser anges exklusive moms.</p>

                    <div class="overflow-x-auto my-8">
                        <table class="min-w-full border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Founders-pris</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ordinarie pris</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Årspris (15% rabatt)</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-sm">
                            <tr>
                                <td class="px-4 py-3 font-medium">Starter</td>
                                <td class="px-4 py-3">390 kr/mån*</td>
                                <td class="px-4 py-3">590 kr/mån</td>
                                <td class="px-4 py-3">3 984 kr/mån*</td>
                            </tr>
                            <tr class="bg-blue-50">
                                <td class="px-4 py-3 font-medium text-blue-800">Growth (Mest populär)</td>
                                <td class="px-4 py-3">990 kr/mån*</td>
                                <td class="px-4 py-3">1 490 kr/mån</td>
                                <td class="px-4 py-3"> 10 104kr/mån*</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium">Pro</td>
                                <td class="px-4 py-3">2 990 kr/mån*</td>
                                <td class="px-4 py-3">3 990 kr/mån</td>
                                <td class="px-4 py-3">30 504 kr/mån*</td>
                            </tr>
                            </tbody>
                        </table>
                        <p class="text-xs text-gray-500 mt-2">* Founders-pris gäller första året för early adopters</p>
                    </div>

                    <h3>Tilläggspriser vid överskridande av kvoter:</h3>
                    <ul>
                        <li><strong>AI-genereringar:</strong> 0,30 kr per generation</li>
                        <li><strong>WordPress-publiceringar:</strong> 0,80 kr per publicering</li>
                        <li><strong>SEO-audits:</strong> 99 kr per audit</li>
                        <li><strong>Lead tracking:</strong> 0,001 kr per event</li>
                    </ul>

                    <h2>5. Betalning och fakturering</h2>
                    <div class="grid md:grid-cols-2 gap-6 my-8">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                            <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Kortbetalning (Stripe)
                            </h4>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>• Automatisk månadsdebitering</li>
                                <li>• Betalning senast sista vardagen i månaden</li>
                                <li>• För nästkommande period</li>
                                <li>• Säker hantering via Stripe</li>
                                <li>• Visa, Mastercard, American Express</li>
                            </ul>
                        </div>

                        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                            <h4 class="font-semibold text-green-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Fakturabetalning (undantagsfall)
                            </h4>
                            <ul class="text-sm text-green-800 space-y-1">
                                <li>• Månadsfaktura skickas senast den 14:e</li>
                                <li>• 14 dagars betalningstid</li>
                                <li>• Kan justeras efter överenskommelse</li>
                                <li>• Årsfaktura skickas i början av perioden</li>
                                <li>• Tillgängligt för företagskunder</li>
                            </ul>
                        </div>
                    </div>

                    <h3>Betalningsperioder och proration:</h3>
                    <ul>
                        <li><strong>Månadsbetalning:</strong> I förskott för kommande 30-dagarsperiod</li>
                        <li><strong>Årsbetalning:</strong> I förskott för kommande 12-månadersperiod med 15% rabatt</li>
                        <li><strong>Första fakturan:</strong> Kan vara proportionerlig om registrering sker mitt i månaden</li>
                        <li><strong>Sista fakturan:</strong> Proportionerlig fram till uppsägningsdatum</li>
                        <li><strong>Planändringar:</strong> Träder i kraft omedelbart med justering av nästa faktura</li>
                    </ul>

                    <h2>6. Försenade betalningar</h2>
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 my-6">
                        <h4 class="font-semibold text-amber-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Vid försenad betalning
                        </h4>
                        <div class="space-y-3 text-sm text-amber-800">
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-xs font-bold text-amber-700">1</span>
                                </div>
                                <div>
                                    <strong>7 dagar efter förfallodatum:</strong> Automatisk påminnelse via e-post
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-xs font-bold text-amber-700">2</span>
                                </div>
                                <div>
                                    <strong>14 dagar efter förfallodatum:</strong> Begränsad åtkomst till tjänsten (endast läsning)
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-xs font-bold text-amber-700">3</span>
                                </div>
                                <div>
                                    <strong>30 dagar efter förfallodatum:</strong> Kontot avstängs tillfälligt
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-xs font-bold text-amber-700">4</span>
                                </div>
                                <div>
                                    <strong>60 dagar efter förfallodatum:</strong> Kontot kan avslutas och data raderas
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 p-3 bg-red-100 border border-red-200 rounded-lg">
                            <p class="text-xs text-red-900">
                                <strong>Dröjsmålsränta:</strong> 2% per påbörjad månad enligt Räntelagen.
                                <strong>Inkassoavgift:</strong> Enligt Inkassolagen.
                            </p>
                        </div>
                    </div>

                    <h2>7. Användarrättigheter och ansvar</h2>
                    <h3>Du har rätt att:</h3>
                    <ul>
                        <li>Använda alla funktioner som ingår i din valda plan</li>
                        <li>Få support och hjälp med tekniska frågor</li>
                        <li>Exportera dina data när som helst</li>
                        <li>Ändra eller avsluta din prenumeration</li>
                        <li>Få förhandsvarning om väsentliga ändringar</li>
                    </ul>

                    <h3>Du ansvarar för att:</h3>
                    <ul>
                        <li>Hålla dina inloggningsuppgifter säkra</li>
                        <li>Endast publicera content du har rättighet till</li>
                        <li>Följa gällande lagar och regler</li>
                        <li>Inte missbruka eller överbelasta systemet</li>
                        <li>Hålla dina kontaktuppgifter uppdaterade</li>
                        <li>Betala avgifter i tid</li>
                    </ul>

                    <h2>8. Begränsningar och acceptabel användning</h2>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6 my-6">
                        <h4 class="font-semibold text-red-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L18 18M5.636 5.636L6 6"></path>
                            </svg>
                            Förbjuden användning
                        </h4>
                        <ul class="text-sm text-red-800 space-y-1">
                            <li>• Spam eller massutskick av oönskat innehåll</li>
                            <li>• Upphovsrättskränkande material</li>
                            <li>• Skadligt, trakasserande eller olagligt innehåll</li>
                            <li>• Försök att kringgå säkerhetssystem</li>
                            <li>• Återförsäljning av tjänsten utan tillstånd</li>
                            <li>• Reverse engineering eller kopiering av koden</li>
                        </ul>
                    </div>

                    <h3>Kvotbegränsningar:</h3>
                    <p>Varje plan har specificerade månadsvolymer. Vid överskridande:</p>
                    <ol>
                        <li>Du får varning när du når 80% av kvoten</li>
                        <li>Vid 100% kan du välja att uppgradera eller betala tilläggspris</li>
                        <li>Utan åtgärd pausas funktionen till nästa månad</li>
                        <li>Kritiska funktioner som säkerhetsuppdateringar påverkas inte</li>
                    </ol>

                    <h2>9. Datahantering och säkerhet</h2>
                    <p>
                        Vi är seriösa med datasäkerhet och följer GDPR. Läs mer i vår
                        <a href="{{ route('privacy') }}" class="text-blue-600 hover:text-blue-700 underline">integritetspolicy</a>.
                    </p>

                    <h3>Dina data:</h3>
                    <ul>
                        <li>Du behåller äganderätten till allt innehåll du skapar</li>
                        <li>Vi tar regelbundna säkerhetskopior</li>
                        <li>Du kan när som helst exportera dina data</li>
                        <li>Vid kontoavslut behålls data i 30 dagar för återställning</li>
                        <li>Vi delar aldrig dina data med tredje part utan ditt medgivande</li>
                    </ul>

                    <h2>10. Service Level Agreement (SLA)</h2>
                    <div class="grid md:grid-cols-3 gap-4 my-6">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h4 class="font-semibold text-green-900 mb-2">Upptid</h4>
                            <p class="text-2xl font-bold text-green-700 mb-1">99.5%</p>
                            <p class="text-xs text-green-800">Månadsvis genomsnitt</p>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-900 mb-2">Support</h4>
                            <p class="text-2xl font-bold text-blue-700 mb-1">24h</p>
                            <p class="text-xs text-blue-800">Första svar på support</p>
                        </div>
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <h4 class="font-semibold text-purple-900 mb-2">Säkerhetskopiering</h4>
                            <p class="text-2xl font-bold text-purple-700 mb-1">4x</p>
                            <p class="text-xs text-purple-800">Dagligen</p>
                        </div>
                    </div>

                    <h3>Planerat underhåll:</h3>
                    <ul>
                        <li>Normalt utanför kontorstid (20:00-06:00 CET)</li>
                        <li>Förhandsvarning minst 48 timmar i förväg</li>
                        <li>Målsättning: Under 4 timmar per månad</li>
                    </ul>

                    <h2>11. Uppsägning och avslut</h2>
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-6 my-6">
                        <h4 class="font-semibold text-orange-900 mb-3">Ingen bindningstid</h4>
                        <ul class="text-sm text-orange-800 space-y-2">
                            <li>• Du kan avsluta kontot när som helst</li>
                            <li>• Uppsägning sker via kontoinställningar eller e-post</li>
                            <li>• Uppsägning träder i kraft vid nästa faktureringsperiod</li>
                            <li>• Du har fortsatt åtkomst till betald period</li>
                            <li>• Inga avgifter eller påföljder för tidig uppsägning</li>
                        </ul>
                    </div>

                    <h3>Vi kan avsluta kontot om:</h3>
                    <ul>
                        <li>Du bryter mot dessa användarvillkor</li>
                        <li>Betalning uteblivit i mer än 30 dagar</li>
                        <li>Kontot använts för olaglig verksamhet</li>
                        <li>Vi upphör med tjänsten (med 90 dagars varsel)</li>
                    </ul>

                    <h3>Vid avslut:</h3>
                    <ol>
                        <li>Åtkomst till tjänsten upphör vid periodens slut</li>
                        <li>Data behålls i 30 dagar för möjlig återställning</li>
                        <li>Du kan exportera data före avslut</li>
                        <li>Eventuell återbetalning sker inom 14 dagar</li>
                        <li>Integrationer och API-åtkomst avslutas</li>
                    </ol>

                    <h2>12. Ändringar av villkor</h2>
                    <p>
                        Vi kan uppdatera dessa villkor för att:
                    </p>
                    <ul>
                        <li>Följa nya lagar och regler</li>
                        <li>Förbättra tjänsten</li>
                        <li>Förtydliga befintliga regler</li>
                        <li>Lägga till nya funktioner</li>
                    </ul>

                    <h3>Ändringsprocess:</h3>
                    <ol>
                        <li>E-postmeddelande till alla användare minst 30 dagar före</li>
                        <li>Notifikation i tjänsten</li>
                        <li>Möjlighet att avsluta kontot om du inte accepterar</li>
                        <li>Fortsatt användning = acceptans av nya villkor</li>
                    </ol>

                    <h2>13. Ansvarsbegränsning</h2>
                    <p>
                        Vi strävar efter att leverera en pålitlig tjänst, men kan inte garantera 100% felfri drift.
                        Vårt ansvar är begränsat till:
                    </p>
                    <ul>
                        <li>Återbetalning av betald avgift för perioden då fel uppstått</li>
                        <li>Rättelse av fel inom rimlig tid</li>
                        <li>Ersättning för dataförlust genom vårt återskapande från säkerhetskopior</li>
                    </ul>

                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 my-6">
                        <h4 class="font-semibold text-gray-900 mb-2">Vi ansvarar inte för:</h4>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li>• Indirekta skador eller utebliven vinst</li>
                            <li>• Skador orsakade av tredje parts tjänster (Facebook, Google, etc.)</li>
                            <li>• Force majeure (naturkatastrofer, krig, pandemier)</li>
                            <li>• Skador orsakade av felaktig användning</li>
                            <li>• Förlust av data på grund av användarfel</li>
                        </ul>
                    </div>

                    <h2>14. Immaterialrätt</h2>
                    <h3>Våra rättigheter:</h3>
                    <ul>
                        <li>WebGrow AI-plattformen och all kod är vår immateriella egendom</li>
                        <li>Varumärken, logotyper och design är skyddade</li>
                        <li>Du får licens att använda tjänsten, inte äga den</li>
                    </ul>

                    <h3>Dina rättigheter:</h3>
                    <ul>
                        <li>Du behåller alla rättigheter till content du skapar</li>
                        <li>AI-genererad text tillhör dig när den skapats</li>
                        <li>Du kan använda output kommersiellt</li>
                        <li>Du ansvarar för att content inte kränker andras rättigheter</li>
                    </ul>

                    <h2>15. Tillämplig lag och tvister</h2>
                    <p>
                        Dessa villkor regleras av svensk rätt. Vid tvister ska primärt medling försökas
                        genom Allmänna reklamationsnämnden (ARN) för konsumenttvister.
                    </p>

                    <h3>Tvistlösning:</h3>
                    <ol>
                        <li><strong>Första steget:</strong> Kontakta vår support för lösning</li>
                        <li><strong>Medling:</strong> ARN för konsumenter, branschorganisation för företag</li>
                        <li><strong>Domstol:</strong> Uddevalla tingsrätt för företag, allmän domstol för konsumenter</li>
                    </ol>

                    <!-- Contact Section -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50 p-8 my-8">
                        <h2 class="text-2xl font-bold text-slate-800 mb-6">Frågor om villkoren?</h2>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-3">Kontakta oss</h4>
                                <div class="space-y-2 text-sm text-blue-800">
                                    <p><strong>Webbi AB</strong></p>
                                    <p>451 60 Uddevalla, Sverige</p>
                                    <p>Org.nr: 5593313140</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <a href="mailto:info@webbi.se" class="flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-colors text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Allmän support
                                </a>
                                <a href="mailto:info@webbi.se" class="flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition-colors text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Juridiska frågor
                                </a>
                                <div class="pt-2 text-xs text-slate-600">
                                    <p><strong>Svarstid:</strong> Support inom 24h, juridiska frågor inom 72h</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center text-sm text-slate-500 border-t border-slate-200 pt-8 mt-8">
                        <p>
                            Dessa villkor träder i kraft {{ now()->format('j M Y') }} och ersätter alla tidigare versioner.<br>
                            Genom att använda WebGrow AI accepterar du dessa villkor i sin helhet.
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
