
<div>
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                </svg>
                Nyckelordsförslag
            </h1>
            <a href="{{ route('seo.keywords.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
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

        <!-- Main content -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-8">
            <div class="space-y-8">
                <!-- Title comparison -->
                <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-blue-900">Sidtitel</h3>
                    </div>

                    <div class="space-y-3">
                        <div class="p-3 bg-white/60 rounded-lg">
                            <div class="text-xs font-medium text-gray-600 mb-1">Nuvarande titel</div>
                            <div class="text-sm text-gray-800">{{ $current['title'] ?? 'Ingen titel hittad' }}</div>
                        </div>
                        <div class="p-3 bg-white/60 rounded-lg border-l-4 border-green-400">
                            <div class="text-xs font-medium text-green-700 mb-1">Föreslagen titel</div>
                            <div class="text-sm font-semibold text-green-800">{{ $suggested['title'] ?? 'Ingen föreslagen titel' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Meta description comparison -->
                <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200/50">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-purple-900">Meta-beskrivning</h3>
                    </div>

                    <div class="space-y-3">
                        <div class="p-3 bg-white/60 rounded-lg">
                            <div class="text-xs font-medium text-gray-600 mb-1">Nuvarande meta-beskrivning</div>
                            <div class="text-sm text-gray-800">{{ $current['meta'] ?? 'Ingen meta-beskrivning hittad' }}</div>
                        </div>
                        <div class="p-3 bg-white/60 rounded-lg border-l-4 border-green-400">
                            <div class="text-xs font-medium text-green-700 mb-1">Föreslagen meta-beskrivning</div>
                            <div class="text-sm font-semibold text-green-800">{{ $suggested['meta'] ?? 'Ingen föreslagen meta-beskrivning' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Keywords -->
                <div class="p-6 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200/50">
                    <div class="flex items-center mb-4">
                        <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-emerald-900">Rekommenderade nyckelord</h3>
                    </div>

                    <div class="p-3 bg-white/60 rounded-lg">
                        @if(!empty($suggested['keywords']) && count($suggested['keywords']) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($suggested['keywords'] as $keyword)
                                    <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-sm font-medium border border-emerald-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                        </svg>
                                        {{ $keyword }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-gray-600">Inga nyckelord föreslagna</div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-center space-x-4 pt-6">
                    @if($sug->status !== 'applied')
                        @if($showWpApply)
                            <button wire:click="apply" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Applicera till WordPress
                            </button>
                        @endif
                        <button wire:click="dismiss" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Avfärda förslag
                        </button>
                    @else
                        <div class="p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-xl">
                            <div class="flex items-center justify-center space-x-3">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-emerald-900">Förslaget har applicerats</div>
                                    <div class="text-sm text-emerald-700">Applicerat {{ $sug->applied_at?->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
