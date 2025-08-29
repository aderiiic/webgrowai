<div>
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Konverteringsförslag
            </h1>
            <a href="{{ route('cro.suggestions.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Tillbaka
            </a>
        </div>

        <!-- Success notification -->
        @if(session('success'))
            <div class="p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-xl">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- URL info -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Sida som analyseras</div>
                        <a href="{{ $sug->url }}" target="_blank" rel="noopener" class="text-lg font-semibold text-purple-600 hover:text-purple-800 hover:underline">
                            {{ $sug->url }}
                        </a>
                    </div>
                </div>

                @php
                    $statusColors = [
                        'new' => ['bg' => 'from-blue-50 to-indigo-50', 'border' => 'border-blue-200/50', 'text' => 'text-blue-800'],
                        'applied' => ['bg' => 'from-green-50 to-emerald-50', 'border' => 'border-green-200/50', 'text' => 'text-green-800'],
                        'dismissed' => ['bg' => 'from-gray-50 to-slate-50', 'border' => 'border-gray-200/50', 'text' => 'text-gray-800'],
                    ];
                    $colors = $statusColors[$sug->status] ?? ['bg' => 'from-purple-50 to-pink-50', 'border' => 'border-purple-200/50', 'text' => 'text-purple-800'];
                @endphp

                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r {{ $colors['bg'] }} {{ $colors['border'] }} border rounded-xl">
                    <span class="text-sm font-medium {{ $colors['text'] }} uppercase">{{ $sug->status }}</span>
                </div>
            </div>
        </div>

        <!-- Suggestions content -->
        <div class="grid grid-cols-1 gap-6">
            <!-- Title suggestions -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707L16.414 6.5a1 1 0 00-.707-.293H7a2 2 0 00-2 2V19a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Rubrik</h2>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200/50">
                        <div class="text-xs font-medium text-gray-600 mb-2">Nuvarande rubrik</div>
                        <div class="text-sm text-gray-900">{{ $s['title']['current'] ?? '—' }}</div>
                    </div>

                    <div class="p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200/50">
                        <div class="text-xs font-medium text-emerald-700 mb-2">Föreslagen rubrik</div>
                        <div class="text-sm font-semibold text-emerald-900">{{ $s['title']['suggested'] ?? '—' }}</div>
                    </div>

                    @if(!empty($s['title']['subtitle']))
                        <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                            <div class="text-xs font-medium text-blue-700 mb-2">Underrubrik</div>
                            <div class="text-sm text-blue-900">{{ $s['title']['subtitle'] }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- CTA suggestions -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Call-to-Action</h2>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200/50">
                        <div class="text-xs font-medium text-gray-600 mb-2">Nuvarande CTA</div>
                        <div class="text-sm text-gray-900">{{ $s['cta']['current'] ?? '—' }}</div>
                    </div>

                    <div class="p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200/50">
                        <div class="text-xs font-medium text-emerald-700 mb-2">Föreslagen CTA</div>
                        <div class="text-sm font-semibold text-emerald-900">{{ $s['cta']['suggested'] ?? '—' }}</div>
                    </div>

                    <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                        <div class="text-xs font-medium text-blue-700 mb-2">Placering</div>
                        <div class="text-sm text-blue-900">{{ $s['cta']['placement'] ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <!-- Form suggestions -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Formulär</h2>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200/50">
                        <div class="text-xs font-medium text-gray-600 mb-2">Nuvarande formulär</div>
                        <div class="text-sm text-gray-900">{{ $s['form']['current'] ?? '—' }}</div>
                    </div>

                    @if(!empty($s['form']['suggested']))
                        <div class="p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200/50">
                            <div class="text-xs font-medium text-emerald-700 mb-2">Föreslagen placering</div>
                            <div class="text-sm text-emerald-900">{{ $s['form']['suggested']['placement'] ?? '—' }}</div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                            <div class="text-xs font-medium text-blue-700 mb-2">Föreslagena fält</div>
                            <div class="text-sm text-blue-900">{{ implode(', ', $s['form']['suggested']['fields'] ?? []) }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action buttons -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
            @if($sug->status !== 'applied')
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Vad vill du göra med detta förslag?</h3>
                        <p class="text-sm text-gray-600">Applicera ändringarna till WordPress eller avfärda förslaget.</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button wire:click="dismiss" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Avfärda
                        </button>
                        @if($showWpApply)
                            <button wire:click="apply" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Applicera till WordPress
                            </button>
                        @endif
                    </div>
                </div>
            @else
                <div class="flex items-center justify-center p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200/50">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-lg font-semibold text-green-900 mb-1">Förslag applicerat</div>
                        <div class="text-sm text-green-700">Applicerat {{ $sug->applied_at?->diffForHumans() }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
