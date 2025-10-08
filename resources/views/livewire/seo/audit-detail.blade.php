<div class="max-w-7xl mx-auto space-y-8">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border border-green-200/50 p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">SEO-audit: {{ $audit->site->name ?? 'Sajt' }}</h1>
                        <div class="flex items-center gap-2 text-sm text-gray-600 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Körd: {{ optional($audit->created_at)->format('Y-m-d H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Controls -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <!-- Category Filter -->
                <div class="inline-flex bg-white border border-gray-200 rounded-xl p-1 shadow-sm overflow-x-auto">
                    <button wire:click="$set('filter','all')" class="px-3 py-2 text-xs font-medium rounded-lg whitespace-nowrap transition-all duration-200 {{ $filter==='all'?'bg-indigo-600 text-white shadow-sm':'text-gray-700 hover:bg-gray-50' }}">Alla</button>
                    <button wire:click="$set('filter','title')" class="px-3 py-2 text-xs font-medium rounded-lg whitespace-nowrap transition-all duration-200 {{ $filter==='title'?'bg-indigo-600 text-white shadow-sm':'text-gray-700 hover:bg-gray-50' }}">Titlar</button>
                    <button wire:click="$set('filter','meta')" class="px-3 py-2 text-xs font-medium rounded-lg whitespace-nowrap transition-all duration-200 {{ $filter==='meta'?'bg-indigo-600 text-white shadow-sm':'text-gray-700 hover:bg-gray-50' }}">Meta</button>
                    <button wire:click="$set('filter','content')" class="px-3 py-2 text-xs font-medium rounded-lg whitespace-nowrap transition-all duration-200 {{ $filter==='content'?'bg-indigo-600 text-white shadow-sm':'text-gray-700 hover:bg-gray-50' }}">Innehåll</button>
                    <button wire:click="$set('filter','links')" class="px-3 py-2 text-xs font-medium rounded-lg whitespace-nowrap transition-all duration-200 {{ $filter==='links'?'bg-indigo-600 text-white shadow-sm':'text-gray-700 hover:bg-gray-50' }}">Länkar</button>
                    <button wire:click="$set('filter','performance')" class="px-3 py-2 text-xs font-medium rounded-lg whitespace-nowrap transition-all duration-200 {{ $filter==='performance'?'bg-indigo-600 text-white shadow-sm':'text-gray-700 hover:bg-gray-50' }}">Hastighet</button>
                    <button wire:click="$set('filter','lighthouse')" class="px-3 py-2 text-xs font-medium rounded-lg whitespace-nowrap transition-all duration-200 {{ $filter==='lighthouse'?'bg-indigo-600 text-white shadow-sm':'text-gray-700 hover:bg-gray-50' }}">Lighthouse</button>
                </div>

                <!-- Search Input -->
                <div class="relative">
                    <input
                        type="text"
                        wire:model.debounce.400ms="q"
                        placeholder="Sök i fynd..."
                        class="w-full sm:w-64 pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                    />
                    <svg class="w-4 h-4 text-gray-500 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/>
                    </svg>
                    @if(!empty($q))
                        <button wire:click="clearSearch" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 transition-colors duration-200" aria-label="Rensa">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Yoast Detection Status -->
    @php $yoastDetected = (bool)($yoastDetected ?? false); @endphp
    <div class="flex items-start gap-3 p-4 bg-white rounded-xl border border-gray-200 shadow-sm">
        @if($yoastDetected)
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-1">Yoast SEO upptäckt</h3>
                <p class="text-xs text-gray-600">Detta underlättar implementering av förbättringar - du kan uppdatera direkt i Yoast-panelen.</p>
            </div>
        @else
            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-1">Yoast SEO ej upptäckt</h3>
                <p class="text-xs text-gray-600">Du behöver uppdatera meta-data manuellt i temat eller via ett SEO-plugin.</p>
            </div>
        @endif
    </div>

    <!-- Summary Card -->
    @if(!empty($summary))
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl border border-indigo-200/50 p-6 md:p-8">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-indigo-900 mb-3">{{ $summary['headline'] ?? 'Sammanfattning' }}</h3>
                    <ul class="space-y-2">
                        @foreach((array)($summary['tips'] ?? []) as $tip)
                            <li class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                                <span class="text-sm text-indigo-900 leading-relaxed">{{ is_string($tip) ? $tip : json_encode($tip) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Findings List -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm divide-y divide-gray-100 overflow-hidden">
        @forelse($items as $it)
            @php
                $rawData = $it->data;
                $data = [];
                if (is_string($rawData) && strlen($rawData)) {
                    try { $decoded = json_decode($rawData, true); $data = is_array($decoded) ? $decoded : []; } catch (\Throwable $e) { $data = []; }
                } elseif (is_array($rawData)) {
                    $data = $rawData;
                }
                $yoastTitle = isset($data['yoast_title']) && is_string($data['yoast_title']) ? $data['yoast_title'] : null;
                $yoastDesc  = isset($data['yoast_description']) && is_string($data['yoast_description']) ? $data['yoast_description'] : null;
                $urlVal     = $data['url'] ?? ($it->url ?? null);
                $url        = is_string($urlVal) ? $urlVal : null;
                $severity   = is_string($data['severity'] ?? null) ? $data['severity'] : 'info';

                // Severity badge & icon
                $severityConfig = match($severity){
                    'error' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-200', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                    'warn'  => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-200', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'],
                    'notice'=> ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-200', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z/>'],
                    default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-200', 'icon' => '<circle cx="12" cy="12" r="3"/>']
                };

                $type = is_string($it->type ?? null) ? $it->type : 'other';
                $typeLabel = [
                    'title'=>'Titel','meta'=>'Meta','content'=>'Innehåll','links'=>'Länkar','performance'=>'Hastighet','lighthouse'=>'Lighthouse'
                ][$type] ?? ucfirst($type);

                $msg = $it->message;
                if (is_array($msg)) $msg = json_encode($msg);
            @endphp

            <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                <!-- Header -->
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-lg border {{ $severityConfig['bg'] }} {{ $severityConfig['text'] }} {{ $severityConfig['border'] }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $severityConfig['icon'] !!}
                                </svg>
                                {{ ucfirst($severity) }}
                            </span>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-lg bg-gray-100 text-gray-700">
                                {{ $typeLabel }}
                            </span>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 break-words mb-2">{{ is_string($it->title) ? $it->title : 'Fynd' }}</h4>
                        @if($url)
                            <a href="{{ $url }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-800 hover:underline break-all">
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                {{ $url }}
                            </a>
                        @endif
                        @if(!empty($msg) && is_string($msg))
                            <p class="mt-3 text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $msg }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 flex-shrink-0">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ optional($it->created_at)->format('Y-m-d H:i') }}
                    </div>
                </div>

                <!-- Yoast Meta (if detected) -->
                @if($yoastTitle || $yoastDesc)
                    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                        <div class="flex items-center gap-2 text-emerald-800 text-sm font-semibold mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Yoast-metadata upptäckt
                        </div>
                        <dl class="grid sm:grid-cols-2 gap-4 text-sm">
                            @if($yoastTitle)
                                <div>
                                    <dt class="text-emerald-700 font-medium mb-1">Titel</dt>
                                    <dd class="text-emerald-900 break-words">{{ $yoastTitle }}</dd>
                                </div>
                            @endif
                            @if($yoastDesc)
                                <div>
                                    <dt class="text-emerald-700 font-medium mb-1">Beskrivning</dt>
                                    <dd class="text-emerald-900 break-words">{{ $yoastDesc }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @endif

                <!-- Action Guide -->
                @php
                    $plain = $data['plain_help'] ?? null;
                    if(!is_string($plain) || $plain===''){
                        $plain = match($type){
                            'title' => 'Skriv en kort och tydlig titel (50–60 tecken) med viktigaste sökordet i början.',
                            'meta'  => 'Skriv en lockande metabeskrivning (140–160 tecken) som sammanfattar sidans värde.',
                            'content' => 'Säkerställ att rubrikerna besvarar besökarens frågor och att texten är lätt att skumläsa.',
                            'links' => 'Lägg till interna länkar till relevanta sidor som fördjupar ämnet.',
                            'performance' => 'Komprimera bilder och undvik tunga script för snabbare laddning.',
                            'lighthouse' => 'Åtgärda rekommendationer från Lighthouse, börja med de som påverkar prestanda.',
                            default => 'Förbättra detta steg för steg – börja med det som påverkar klick och läsbarhet.'
                        };
                    }
                @endphp
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h5 class="text-sm font-semibold text-gray-900 mb-1">Så här åtgärdar du</h5>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $plain }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Inga fynd för valt filter</h3>
                <p class="text-sm text-gray-600">Prova ett annat filter eller rensa sökningen.</p>
            </div>
        @endforelse
    </div>

    <!-- Lighthouse Details (by category) -->
    @if($filter === 'lighthouse' || $filter === 'all')
        @php
            $cats = [
                'performance' => ['label' => 'Prestanda', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>', 'color' => 'green'],
                'accessibility' => ['label' => 'Tillgänglighet', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>', 'color' => 'blue'],
                'best-practices' => ['label' => 'Best Practices', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>', 'color' => 'purple'],
                'seo' => ['label' => 'SEO', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>', 'color' => 'orange'],
            ];
        @endphp

        @foreach($cats as $key => $config)
            @php $rows = $lighthouse[$key] ?? []; @endphp
            @if(!empty($rows))
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-{{ $config['color'] }}-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-{{ $config['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $config['icon'] !!}
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $config['label'] }} - Detaljer</h3>
                    </div>

                    <ul class="space-y-3">
                        @foreach($rows as $r)
                            <li class="p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div class="font-medium text-gray-900 break-words flex-1">
                                        {{ is_string($r['title'] ?? null) ? $r['title'] : 'Rekommendation' }}
                                    </div>
                                    @if(!empty($r['impact']))
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-lg bg-amber-100 text-amber-800 whitespace-nowrap">
                                            Impact: {{ $r['impact'] }}
                                        </span>
                                    @endif
                                </div>

                                @if(!empty($r['message']))
                                    <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line mb-3">{{ is_string($r['message']) ? $r['message'] : json_encode($r['message']) }}</p>
                                @endif

                                @if(!empty($r['opportunity']) || !empty($r['url']))
                                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600">
                                        @if(!empty($r['opportunity']))
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                                </svg>
                                                Möjlighet: {{ $r['opportunity'] }}
                                            </span>
                                        @endif
                                        @if(!empty($r['url']) && is_string($r['url']))
                                            <a href="{{ $r['url'] }}" target="_blank" class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 hover:underline break-all">
                                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                Visa sida
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endforeach
    @endif

    <!-- Pagination -->
    @if($items->hasPages())
        <div class="flex justify-center">
            {{ $items->links() }}
        </div>
    @endif
</div>
