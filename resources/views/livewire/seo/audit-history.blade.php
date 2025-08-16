<div>
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                SEO Audit – Historik
            </h1>
            <a href="{{ route('seo.audit.run') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Kör ny audit
            </a>
        </div>

        <!-- Audits list -->
        <div class="space-y-4">
            @forelse($audits as $a)
                @php
                    // Performance color logic
                    $perf = $a->lighthouse_performance;
                    if ($perf === null) {
                        $perfColor = 'text-gray-500';
                        $perfBg = 'bg-gray-100';
                    } elseif ($perf >= 90) {
                        $perfColor = 'text-emerald-700';
                        $perfBg = 'bg-emerald-100';
                    } elseif ($perf >= 70) {
                        $perfColor = 'text-amber-700';
                        $perfBg = 'bg-amber-100';
                    } else {
                        $perfColor = 'text-red-700';
                        $perfBg = 'bg-red-100';
                    }
                @endphp

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6 hover:shadow-2xl transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <!-- Header info -->
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Site #{{ $a->site_id }}</div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $a->created_at->format('Y-m-d H:i') }} ({{ $a->created_at->diffForHumans() }})
                                    </div>
                                </div>
                                <!-- Performance badge -->
                                <div class="inline-flex items-center px-3 py-1 {{ $perfBg }} {{ $perfColor }} rounded-full text-sm font-medium">
                                    Performance: {{ $perf ?? '—' }}
                                </div>
                            </div>

                            <!-- Metrics grid -->
                            <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                                <div class="p-3 bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg border border-green-200/50">
                                    <div class="text-xs font-medium text-green-700">Performance</div>
                                    <div class="text-lg font-bold text-green-900">{{ $a->lighthouse_performance ?? '—' }}</div>
                                </div>
                                <div class="p-3 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-200/50">
                                    <div class="text-xs font-medium text-blue-700">Accessibility</div>
                                    <div class="text-lg font-bold text-blue-900">{{ $a->lighthouse_accessibility ?? '—' }}</div>
                                </div>
                                <div class="p-3 bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg border border-purple-200/50">
                                    <div class="text-xs font-medium text-purple-700">Best Practices</div>
                                    <div class="text-lg font-bold text-purple-900">{{ $a->lighthouse_best_practices ?? '—' }}</div>
                                </div>
                                <div class="p-3 bg-gradient-to-br from-orange-50 to-red-50 rounded-lg border border-orange-200/50">
                                    <div class="text-xs font-medium text-orange-700">SEO Score</div>
                                    <div class="text-lg font-bold text-orange-900">{{ $a->lighthouse_seo ?? '—' }}</div>
                                </div>
                                <div class="p-3 bg-gradient-to-br from-yellow-50 to-amber-50 rounded-lg border border-yellow-200/50">
                                    <div class="text-xs font-medium text-yellow-700">Titelproblem</div>
                                    <div class="text-lg font-bold text-yellow-900">{{ $a->title_issues }}</div>
                                </div>
                                <div class="p-3 bg-gradient-to-br from-red-50 to-pink-50 rounded-lg border border-red-200/50">
                                    <div class="text-xs font-medium text-red-700">Meta-problem</div>
                                    <div class="text-lg font-bold text-red-900">{{ $a->meta_issues }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action button -->
                        <div class="ml-6 flex-shrink-0">
                            <a href="{{ route('seo.audit.detail', $a->id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Visa detaljer
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Ingen audit-historik ännu</h3>
                    <p class="text-gray-600 mb-6">Kör din första SEO-audit för att se resultaten här.</p>
                    <a href="{{ route('seo.audit.run') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Kör SEO Audit
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($audits->hasPages())
            <div class="flex justify-center">
                {{ $audits->links() }}
            </div>
        @endif
    </div>
</div>
