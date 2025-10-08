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
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">CRO-förslag</h1>
                        <p class="text-sm text-gray-600 mt-1">Förbättra konverteringen på din webbplats</p>
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

                @if($running)
                    <div class="mt-3 flex items-center gap-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <svg class="w-4 h-4 animate-spin text-blue-600" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4A4 4 0 004 12z"/>
                        </svg>
                        <p class="text-sm text-blue-700 font-medium">Analys pågår - uppdatera sidan om en stund för att se resultaten</p>
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

                <!-- Run Analysis Button -->
                @if($isPremium ?? false)
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

    <!-- Suggestions Table/List -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Titel & URL
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                            Mål
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Status
                        </div>
                    </th>
                    <th class="px-6 py-4"></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($sugs as $s)
                    @php
                        $suggestions = is_array($s->suggestions) ? $s->suggestions : [];
                        $title = isset($suggestions['title']) && is_string($suggestions['title']) ? $suggestions['title'] : 'Förslag';
                        $goal  = isset($suggestions['goal']) && is_string($suggestions['goal'])
                                    ? $suggestions['goal']
                                    : (isset($suggestions['target']) && is_string($suggestions['target']) ? $suggestions['target'] : 'Förbättra konvertering');
                        $url   = isset($suggestions['url']) && is_string($suggestions['url']) ? $suggestions['url'] : null;
                        $badge = match($s->status){
                            'applied'   => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'dismissed' => 'bg-gray-100 text-gray-800 border-gray-200',
                            default     => 'bg-amber-100 text-amber-800 border-amber-200'
                        };
                        $icon = match($s->status){
                            'applied'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
                            'dismissed' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
                            default     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>'
                        };
                        $label = match($s->status){
                            'applied'=>'Tillämpad','dismissed'=>'Avfärdad', default=>'Ny'
                        };
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900 break-words mb-1">{{ $title }}</div>
                            @if($url)
                                <a href="{{ $url }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:text-indigo-800 hover:underline break-all">
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    {{ $url }}
                                </a>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700 break-words">{{ $goal }}</div>
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
                            <a href="{{ route('cro.suggestion.detail', $s->id) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-all duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Visa detaljer
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Inga förslag tillgängliga</h3>
                                <p class="text-sm text-gray-600 mb-4">Kör en ny analys för att få förslag på förbättringar</p>
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

        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-gray-100">
            @forelse($sugs as $s)
                @php
                    $suggestions = is_array($s->suggestions) ? $s->suggestions : [];
                    $title = isset($suggestions['title']) && is_string($suggestions['title']) ? $suggestions['title'] : 'Förslag';
                    $goal  = isset($suggestions['goal']) && is_string($suggestions['goal'])
                                ? $suggestions['goal']
                                : (isset($suggestions['target']) && is_string($suggestions['target']) ? $suggestions['target'] : 'Förbättra konvertering');
                    $url   = isset($suggestions['url']) && is_string($suggestions['url']) ? $suggestions['url'] : null;
                    $badge = match($s->status){
                        'applied'   => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'dismissed' => 'bg-gray-100 text-gray-800 border-gray-200',
                        default     => 'bg-amber-100 text-amber-800 border-amber-200'
                    };
                    $label = match($s->status){
                        'applied'=>'Tillämpad','dismissed'=>'Avfärdad', default=>'Ny'
                    };
                @endphp
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 break-words mb-1">{{ $title }}</h3>
                            @if($url)
                                <a href="{{ $url }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:text-indigo-800 break-all">
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Visa sida
                                </a>
                            @endif
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-lg border {{ $badge }} whitespace-nowrap">
                            {{ $label }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-700 break-words">
                        <span class="font-medium text-gray-900">Mål:</span> {{ $goal }}
                    </div>

                    <a href="{{ route('cro.suggestion.detail', $s->id) }}" class="inline-flex items-center justify-center w-full gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-all duration-200">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Inga förslag tillgängliga</h3>
                        <p class="text-sm text-gray-600 mb-4">Kör en ny analys för att få förslag på förbättringar</p>
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
    @if($sugs->hasPages())
        <div class="flex justify-center">
            {{ $sugs->links() }}
        </div>
    @endif
</div>
