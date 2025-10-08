<div class="max-w-7xl mx-auto space-y-8">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border border-green-200/50 p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">SEO-förslag för Sökmotorer</h1>
                        <p class="text-sm text-gray-600 mt-1">Hjälp Google att hitta och ranka din sida bättre</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-3">
                    <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    @php $url = $current['url'] ?? ($current['permalink'] ?? null); @endphp
                    <a href="{{ is_string($url) ? $url : '#' }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-700 break-all hover:underline">
                        {{ is_string($url) ? $url : 'URL okänd' }}
                    </a>
                </div>
                @if(session('success'))
                    <div class="mt-3 flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
                    </div>
                @endif
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @php
                    $status = is_string($sug->status ?? null) ? $sug->status : 'new';
                    $badge = match($status){
                        'applied'=>'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'dismissed'=>'bg-gray-100 text-gray-800 border-gray-200',
                        default=>'bg-amber-100 text-amber-800 border-amber-200'
                    };
                    $label = match($status){
                        'applied'=>'✓ Klar',
                        'dismissed'=>'× Avfärdad',
                        default=>'⚡ Nytt förslag'
                    };
                @endphp
                <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-semibold border {{ $badge }}">{{ $label }}</span>
                <button wire:click="dismiss" class="px-4 py-2 text-sm font-medium rounded-xl bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                    Avfärda
                </button>
                <button wire:click="markApplied" class="px-4 py-2 text-sm font-medium rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 text-white hover:from-emerald-700 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Markera som klar
                </button>
            </div>
        </div>
    </div>

    <!-- Yoast Status -->
    @php
        $hasYoast = !empty($yoast['title']) || !empty($yoast['description']);
    @endphp
    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
        <div class="flex items-start space-x-3">
            @if($hasYoast)
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            @else
                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            @endif
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-1">
                    @if($hasYoast)
                        Yoast SEO upptäckt ✓
                    @else
                        Yoast SEO ej upptäckt
                    @endif
                </h3>
                <p class="text-sm text-gray-600">
                    @if($hasYoast)
                        Du kan enkelt uppdatera metadata via Yoast-panelen i WordPress. Se steg-för-steg guide nedan.
                    @else
                        Ingen Yoast-data hittades. Du behöver uppdatera sidans titel och metabeskrivning manuellt i temat eller editorn.
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Comparison Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Current Values -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Nuvarande</h3>
            </div>

            <div class="space-y-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-600 uppercase tracking-wide">Sidtitel</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        @php $curTitle = (isset($current['title']) && is_string($current['title'])) ? $current['title'] : null; @endphp
                        <p class="text-sm text-gray-700 break-words">{{ $curTitle ?? ($yoast['title'] ?? '—') }}</p>
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-600 uppercase tracking-wide">Metabeskrivning</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        @php $curMeta = (isset($current['meta']) && is_string($current['meta'])) ? $current['meta'] : null; @endphp
                        <p class="text-sm text-gray-700 break-words">{{ $curMeta ?? ($yoast['description'] ?? '—') }}</p>
                    </div>
                </div>

                @if(!empty($current['keyword']) && is_string($current['keyword']))
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            <span class="text-xs font-medium text-gray-600 uppercase tracking-wide">Fokusnyckelord</span>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-700 break-words">{{ $current['keyword'] }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Suggested Values -->
        <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl border border-emerald-200 p-6 shadow-sm">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-emerald-900">✨ Förslag</h3>
            </div>

            <div class="space-y-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">Sidtitel</span>
                    </div>
                    <div class="p-3 bg-white rounded-lg border border-emerald-200">
                        @php $sugTitle = (isset($suggested['title']) && is_string($suggested['title'])) ? $suggested['title'] : null; @endphp
                        <p class="text-sm text-gray-900 font-medium break-words">{{ $sugTitle ?? '—' }}</p>
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                        </svg>
                        <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">Metabeskrivning</span>
                    </div>
                    <div class="p-3 bg-white rounded-lg border border-emerald-200">
                        @php $sugMeta = (isset($suggested['meta']) && is_string($suggested['meta'])) ? $suggested['meta'] : null; @endphp
                        <p class="text-sm text-gray-900 font-medium break-words">{{ $sugMeta ?? '—' }}</p>
                    </div>
                </div>

                @if(!empty($suggested['keyword']) && is_string($suggested['keyword']))
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">Fokusnyckelord</span>
                        </div>
                        <div class="p-3 bg-white rounded-lg border border-emerald-200">
                            <p class="text-sm text-gray-900 font-medium break-words">{{ $suggested['keyword'] }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Why This Is Better -->
    @if(!empty($insights))
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-200/50 p-6 md:p-8">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-blue-900 mb-3">Varför detta är bättre för SEO</h3>
                    <ul class="space-y-2">
                        @foreach((array)$insights as $i)
                            <li class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm text-blue-900 leading-relaxed break-words">{{ is_string($i) ? $i : json_encode($i) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Implementation Guide -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
        <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Steg för steg - Så här uppdaterar du i WordPress</h3>

                <div class="space-y-3">
                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                        <span class="flex-shrink-0 w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold">1</span>
                        <p class="text-sm text-gray-700 pt-0.5">Logga in på WordPress och öppna sidan/inlägget i editorn</p>
                    </div>

                    @if($hasYoast)
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <span class="flex-shrink-0 w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                            <p class="text-sm text-gray-700 pt-0.5">Scrolla ner till <strong>Yoast SEO-panelen</strong> under editorn (oftast i botten)</p>
                        </div>

                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <span class="flex-shrink-0 w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold">3</span>
                            <div class="flex-1 pt-0.5">
                                <p class="text-sm text-gray-700 mb-2">Kopiera och klistra in <strong>"Föreslaget"</strong> från ovan:</p>
                                <ul class="ml-4 space-y-1 text-sm text-gray-600">
                                    <li>• Fältet "SEO title" → din nya sidtitel</li>
                                    <li>• Fältet "Meta description" → din nya metabeskrivning</li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <span class="flex-shrink-0 w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                            <p class="text-sm text-gray-700 pt-0.5">Hitta och redigera <strong>sidans Titel</strong> i editorn eller temat</p>
                        </div>

                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <span class="flex-shrink-0 w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold">3</span>
                            <p class="text-sm text-gray-700 pt-0.5">Lägg till eller uppdatera <strong>Metabeskrivning</strong> i temat eller via ett SEO-plugin</p>
                        </div>
                    @endif

                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                        <span class="flex-shrink-0 w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold">{{ $hasYoast ? '4' : '4' }}</span>
                        <p class="text-sm text-gray-700 pt-0.5">Klicka <strong>"Uppdatera"</strong> för att spara ändringarna</p>
                    </div>

                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                        <span class="flex-shrink-0 w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold">{{ $hasYoast ? '5' : '5' }}</span>
                        <div class="flex-1 pt-0.5">
                            <p class="text-sm text-gray-700 mb-1"><strong>Valfritt:</strong> Be Google indexera sidan snabbare</p>
                            <p class="text-xs text-gray-600">Gå till Google Search Console → URL Inspection → Begär omindexering</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start space-x-2">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-blue-900">
                            <strong>Tips:</strong> Det kan ta 1-4 veckor innan du ser resultat i sökmotorerna. Tålamod lönar sig!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
