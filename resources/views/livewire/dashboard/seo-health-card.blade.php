<div wire:poll.30s="loadLatest">
    <div class="bg-white rounded-xl shadow-sm border p-6 h-full flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Webbplatshälsa</h2>
                    <p class="text-sm text-gray-500">Hur bra fungerar din webbplats?</p>
                </div>
            </div>

            @if($monthAuditTotal > 0)
                <div class="text-right">
                    <div class="text-sm font-semibold text-blue-600">{{ $monthAuditTotal }}</div>
                    <div class="text-xs text-gray-500">kontroller</div>
                </div>
            @endif
        </div>

        <!-- Välj webbplats -->
        @if($sites->count() > 1)
            <div class="mb-4">
                <select wire:model.live="siteId" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                    @foreach($sites as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Notifications -->
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Huvudinnehåll -->
        <div class="flex-1">
            @if($latest)
                <!-- Hälsostatus -->
                <div class="bg-blue-50 rounded-xl p-4 mb-4">
                    <h3 class="text-sm font-semibold text-blue-900 mb-3">Webbplatsbedömning</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $latest->lighthouse_performance ?? '—' }}</div>
                            <div class="text-xs text-gray-600">Hastighet</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $latest->lighthouse_seo ?? '—' }}</div>
                            <div class="text-xs text-gray-600">Sökoptimering</div>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-blue-200">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="text-center">
                                <div class="text-lg font-semibold text-purple-600">{{ $latest->lighthouse_accessibility ?? '—' }}</div>
                                <div class="text-xs text-gray-600">Tillgänglighet</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-semibold text-orange-600">{{ $latest->lighthouse_best_practices ?? '—' }}</div>
                                <div class="text-xs text-gray-600">Kvalitet</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Problem som behöver fixas -->
                @if($latest->title_issues > 0 || $latest->meta_issues > 0)
                    <div class="bg-yellow-50 rounded-xl p-4 mb-4">
                        <h3 class="text-sm font-semibold text-yellow-900 mb-3">Saker att förbättra</h3>
                        <div class="space-y-2">
                            @if($latest->title_issues > 0)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-yellow-800">Sidtitlar</span>
                                    <span class="text-sm font-semibold text-yellow-900">{{ $latest->title_issues }} problem</span>
                                </div>
                            @endif
                            @if($latest->meta_issues > 0)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-yellow-800">Beskrivningar</span>
                                    <span class="text-sm font-semibold text-yellow-900">{{ $latest->meta_issues }} problem</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Senaste kontroll -->
                <div class="text-center text-xs text-gray-500 mb-4">
                    Kontrollerad {{ $latest->created_at->diffForHumans() }}
                </div>
            @else
                <!-- Ingen kontroll -->
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Ingen kontroll än</h3>
                    <p class="text-gray-600 text-sm">Kontrollera hur bra din webbplats fungerar.</p>
                </div>
            @endif
        </div>

        <!-- Åtgärder -->
        <div class="space-y-3 mt-auto">
            <button wire:click="runAudit" class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Kontrollera webbplats
            </button>

            <div class="flex space-x-2">
                @if($latest)
                    <a href="{{ route('seo.audit.detail', $latest->id) }}" class="flex-1 flex items-center justify-center px-3 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Se detaljer
                    </a>
                @endif
                <a href="{{ route('seo.audit.history') }}" class="flex-1 flex items-center justify-center px-3 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Historik
                </a>
            </div>
        </div>
    </div>
</div>
