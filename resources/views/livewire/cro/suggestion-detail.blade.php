<div class="max-w-7xl mx-auto space-y-8">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-200/50 p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Förslag för Bättre Konvertering</h1>
                        <p class="text-sm text-gray-600 mt-1">Öka antalet besökare som blir kunder</p>
                    </div>
                </div>
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

    <!-- Context Card -->
    @if(!empty($context))
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900 mb-2">Om din webbplats</h3>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $context }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Suggestions Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Headline Card -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Rubrik</h3>
            </div>

            <div class="space-y-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nuvarande</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-700 break-words">{{ $s['title']['current'] ?? '—' }}</p>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-2 bg-white text-sm text-gray-500">→</span>
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wide">✨ Förslag</span>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-emerald-50 to-green-50 rounded-lg border border-emerald-200">
                        <p class="text-sm text-gray-900 font-medium break-words">{{ $s['title']['suggested'] ?? '—' }}</p>
                    </div>
                </div>

                @if(!empty($s['title']['subtitle']))
                    <div class="pt-2 border-t border-gray-100">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Underrubrik</span>
                        <p class="mt-1 text-sm text-gray-700">{{ $s['title']['subtitle'] }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- CTA Card -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Handlingsknapp (CTA)</h3>
            </div>

            <div class="space-y-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nuvarande</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-700 break-words">{{ $s['cta']['current'] ?? '—' }}</p>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-2 bg-white text-sm text-gray-500">→</span>
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wide">✨ Förslag</span>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-emerald-50 to-green-50 rounded-lg border border-emerald-200">
                        <p class="text-sm text-gray-900 font-medium break-words">{{ $s['cta']['suggested'] ?? '—' }}</p>
                    </div>
                </div>

                @if(!empty($s['cta']['placement']))
                    <div class="pt-2 border-t border-gray-100">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Placering</span>
                        <p class="mt-1 text-sm text-gray-700">{{ $s['cta']['placement'] }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Formulär</h3>
            </div>

            <div class="space-y-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nuvarande</span>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-700 break-words">{{ is_string($s['form']['current'] ?? null) ? $s['form']['current'] : '—' }}</p>
                    </div>
                </div>

                @php $fields = $s['form']['suggested']['fields'] ?? []; @endphp
                @if(!empty($s['form']['suggested']['placement']) || !empty($fields))
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="px-2 bg-white text-sm text-gray-500">→</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wide">✨ Förslag</span>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-emerald-50 to-green-50 rounded-lg border border-emerald-200 space-y-2">
                            @if(!empty($s['form']['suggested']['placement']))
                                <p class="text-sm text-gray-900 font-medium">
                                    <span class="text-gray-600">Placering:</span> {{ $s['form']['suggested']['placement'] }}
                                </p>
                            @endif
                            @if(!empty($fields))
                                <div>
                                    <span class="text-xs font-medium text-gray-600">Fält:</span>
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        @foreach(array_filter($fields, fn($f) => is_string($f) && $f !== '') as $field)
                                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-white border border-emerald-200 text-gray-700">
                                                {{ $field }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Why This Works -->
    @if(!empty($insights))
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl border border-indigo-200/50 p-6 md:p-8">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-indigo-900 mb-3">Varför detta ökar konverteringen</h3>
                    <ul class="space-y-2">
                        @foreach($insights as $i)
                            <li class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm text-indigo-900 leading-relaxed break-words">{{ $i }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Implementation Guide -->
    @if($showManualApplyHint)
        <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Steg för steg - Så här gör du</h3>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <span class="flex-shrink-0 w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold">1</span>
                            <p class="text-sm text-gray-700 pt-0.5">Uppdatera rubriken och CTA-knappen på sidan</p>
                        </div>
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <span class="flex-shrink-0 w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                            <p class="text-sm text-gray-700 pt-0.5">Placera formuläret där det syns direkt och håll det kort (max 3-4 fält)</p>
                        </div>
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <span class="flex-shrink-0 w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-xs font-bold">3</span>
                            <p class="text-sm text-gray-700 pt-0.5">Publicera ändringarna och följ upp resultaten i Analytics eller GA4</p>
                        </div>
                    </div>

                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start space-x-2">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-blue-900">
                                <strong>Tips:</strong> Testa ändringarna i 2-4 veckor för att se verklig effekt. Små förändringar kan ge stora resultat!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
