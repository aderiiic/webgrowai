<div class="max-w-7xl mx-auto space-y-8">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border border-green-200/50 p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Nyckelordsförslag</h1>
                        <p class="text-sm text-gray-600 mt-1">Optimera dina sidor för sökmotorer</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mt-3 flex items-center gap-2 p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mt-3 flex items-center gap-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <!-- Filter Buttons -->
                <div class="inline-flex bg-white border border-gray-200 rounded-xl p-1 shadow-sm">
                    <button wire:click="$set('status','new')" class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ $status==='new'?'bg-indigo-600 text-white shadow-sm':'text-gray-700 hover:bg-gray-50' }}">
                        Nya
                    </button>
                    <button wire:click="$set('status','applied')" class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ $status==='applied'?'bg-indigo-600 text-white shadow-sm':'text-gray-700 hover:bg-gray-50' }}">
                        Tillämpade
                    </button>
                    <button wire:click="$set('status','dismissed')" class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ $status==='dismissed'?'bg-indigo-600 text-white shadow-sm':'text-gray-700 hover:bg-gray-50' }}">
                        Avfärdade
                    </button>
                    <button wire:click="$set('status','all')" class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ $status==='all'?'bg-indigo-600 text-white shadow-sm':'text-gray-700 hover:bg-gray-50' }}">
                        Alla
                    </button>
                </div>

                <!-- Search Input -->
                <div class="relative">
                    <input
                        type="text"
                        wire:model.debounce.400ms="q"
                        placeholder="Sök i URL, titel, meta..."
                        class="w-full sm:w-64 pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                    />
                    <svg class="w-4 h-4 text-gray-500 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 110-16 8 8 0 010 16z"/>
                    </svg>
                    @if(!empty($q))
                        <button wire:click="$set('q', null)" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 transition-colors duration-200" aria-label="Rensa">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif
                </div>

                <!-- Run Analysis Button -->
                @if($isPremium)
                    <button
                        wire:click="rerun"
                        wire:loading.attr="disabled"
                        wire:target="rerun"
                        @disabled($running)
                        class="inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold transition-all duration-200 shadow-md hover:shadow-lg {{ $running ? 'bg-gray-400 text-gray-200 cursor-not-allowed' : 'bg-gradient-to-r from-emerald-600 to-green-600 text-white hover:from-emerald-700 hover:to-green-700' }}">
                        <span wire:loading.remove wire:target="rerun" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            {{ $running ? 'Kör...' : 'Kör om analys' }}
                        </span>
                        <span wire:loading wire:target="rerun" class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4A4 4 0 004 12z"/>
                            </svg>
                            Kör...
                        </span>
                    </button>
                @else
                    <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span class="text-xs text-gray-600 font-medium">Premium krävs</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Running Status -->
    @if($running)
        <div class="flex items-center gap-2 p-4 bg-blue-50 border border-blue-200 rounded-xl">
            <svg class="w-5 h-5 animate-spin text-blue-600" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4A4 4 0 004 12z"/>
            </svg>
            <p class="text-sm text-blue-700 font-medium">Analys pågår - uppdatera sidan om en stund för att se resultaten</p>
        </div>
    @endif

    <!-- Results Table/List -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            URL & Status
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Nuvarande
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Föreslaget
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4"></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($rows as $row)
                    @php
                        $c = is_array($row->current) ? $row->current : [];
                        $s = is_array($row->suggested) ? $row->suggested : [];
                        $yoast = [
                            'title' => (isset($c['yoast_title']) && is_string($c['yoast_title'])) ? $c['yoast_title'] : null,
                            'desc'  => (isset($c['yoast_description']) && is_string($c['yoast_description'])) ? $c['yoast_description'] : null,
                        ];
                        $url = is_string($row->url) ? $row->url : null;
                        $badge = match($row->status){
                            'applied'   => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'dismissed' => 'bg-gray-100 text-gray-800 border-gray-200',
                            default     => 'bg-amber-100 text-amber-800 border-amber-200'
                        };
                        $icon = match($row->status){
                            'applied'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
                            'dismissed' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
                            default     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>'
                        };
                        $label = match($row->status){
                            'applied'=>'Tillämpad','dismissed'=>'Avfärdad', default=>'Ny'
                        };
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                @if($url)
                                    <a href="{{ $url }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-800 hover:underline break-all mb-2">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        {{ $url }}
                                    </a>
                                @else
                                    <span class="text-sm text-gray-500">—</span>
                                @endif
                                @if($yoast['title'] || $yoast['desc'])
                                    <div class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-medium">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Yoast
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-2 max-w-xs">
                                <div>
                                    <div class="text-xs font-medium text-gray-500 mb-1">Titel</div>
                                    <div class="text-sm text-gray-700 break-words line-clamp-2">{{ (isset($c['title']) && is_string($c['title'])) ? $c['title'] : ($yoast['title'] ?? '—') }}</div>
                                </div>
                                <div>
                                    <div class="text-xs font-medium text-gray-500 mb-1">Meta</div>
                                    <div class="text-sm text-gray-700 break-words line-clamp-2">{{ (isset($c['meta']) && is_string($c['meta'])) ? $c['meta'] : ($yoast['desc'] ?? '—') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-2 max-w-xs">
                                <div>
                                    <div class="text-xs font-medium text-emerald-600 mb-1">Titel</div>
                                    <div class="text-sm text-gray-900 font-medium break-words line-clamp-2">{{ (isset($s['title']) && is_string($s['title'])) ? $s['title'] : '—' }}</div>
                                </div>
                                <div>
                                    <div class="text-xs font-medium text-emerald-600 mb-1">Meta</div>
                                    <div class="text-sm text-gray-900 font-medium break-words line-clamp-2">{{ (isset($s['meta']) && is_string($s['meta'])) ? $s['meta'] : '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border {{ $badge }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {!! $icon !!}
                                    </svg>
                                    {{ $label }}
                                </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('seo.keywords.detail', $row->id) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Visa
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Inga nyckelordsförslag hittades</h3>
                                <p class="text-sm text-gray-600 mb-4">Kör en ny analys för att få SEO-förslag</p>
                                @if($isPremium)
                                    <button wire:click="rerun" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Kör analys nu
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile/Tablet Card View -->
        <div class="lg:hidden divide-y divide-gray-100">
            @forelse($rows as $row)
                @php
                    $c = is_array($row->current) ? $row->current : [];
                    $s = is_array($row->suggested) ? $row->suggested : [];
                    $yoast = [
                        'title' => (isset($c['yoast_title']) && is_string($c['yoast_title'])) ? $c['yoast_title'] : null,
                        'desc'  => (isset($c['yoast_description']) && is_string($c['yoast_description'])) ? $c['yoast_description'] : null,
                    ];
                    $url = is_string($row->url) ? $row->url : null;
                    $badge = match($row->status){
                        'applied'   => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'dismissed' => 'bg-gray-100 text-gray-800 border-gray-200',
                        default     => 'bg-amber-100 text-amber-800 border-amber-200'
                    };
                    $label = match($row->status){
                        'applied'=>'Tillämpad','dismissed'=>'Avfärdad', default=>'Ny'
                    };
                @endphp
                <div class="p-4 space-y-4">
                    <!-- Header -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            @if($url)
                                <a href="{{ $url }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-800 break-all">
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Visa sida
                                </a>
                            @endif
                            @if($yoast['title'] || $yoast['desc'])
                                <div class="mt-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-medium">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Yoast upptäckt
                                </div>
                            @endif
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-lg border {{ $badge }} whitespace-nowrap">
                            {{ $label }}
                        </span>
                    </div>

                    <!-- Current vs Suggested -->
                    <div class="grid grid-cols-1 gap-3">
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="text-xs font-medium text-gray-600 mb-2">Nuvarande</div>
                            <div class="space-y-2">
                                <div>
                                    <div class="text-xs text-gray-500">Titel:</div>
                                    <div class="text-sm text-gray-700 break-words">{{ (isset($c['title']) && is_string($c['title'])) ? $c['title'] : ($yoast['title'] ?? '—') }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500">Meta:</div>
                                    <div class="text-sm text-gray-700 break-words">{{ (isset($c['meta']) && is_string($c['meta'])) ? $c['meta'] : ($yoast['desc'] ?? '—') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-gradient-to-br from-emerald-50 to-green-50 rounded-lg border border-emerald-200">
                            <div class="text-xs font-semibold text-emerald-700 mb-2">Föreslaget</div>
                            <div class="space-y-2">
                                <div>
                                    <div class="text-xs text-emerald-600">Titel:</div>
                                    <div class="text-sm text-gray-900 font-medium break-words">{{ (isset($s['title']) && is_string($s['title'])) ? $s['title'] : '—' }}</div>
                                </div>
                                <div>
                                    <div class="text-xs text-emerald-600">Meta:</div>
                                    <div class="text-sm text-gray-900 font-medium break-words">{{ (isset($s['meta']) && is_string($s['meta'])) ? $s['meta'] : '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('seo.keywords.detail', $row->id) }}" class="inline-flex items-center justify-center w-full gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Visa detaljer
                    </a>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Inga nyckelordsförslag hittades</h3>
                        <p class="text-sm text-gray-600 mb-4">Kör en ny analys för att få SEO-förslag</p>
                        @if($isPremium)
                            <button wire:click="rerun" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Kör analys nu
                            </button>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($rows->hasPages())
        <div class="flex justify-center">
            {{ $rows->links() }}
        </div>
    @endif
</div>
