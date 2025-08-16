<div>
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6 h-full flex flex-col">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Veckovis planering & tips
            </h2>
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">Filter:</label>
                <select wire:model="filterTag" class="px-3 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                    <option value="">Alla</option>
                    <option value="monday">Måndag</option>
                    <option value="friday">Fredag</option>
                </select>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto">
            @forelse($plans as $plan)
                <div class="mb-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200/50 p-4 hover:border-gray-300/50 hover:shadow-md transition-all duration-200">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r
                                @if($plan->run_tag === 'monday') from-blue-50 to-indigo-50 text-blue-700 border border-blue-200/50
                                @elseif($plan->run_tag === 'friday') from-purple-50 to-pink-50 text-purple-700 border border-purple-200/50
                                @else from-gray-50 to-gray-100 text-gray-700 border border-gray-200/50 @endif
                                rounded-full text-xs font-medium">
                                @if($plan->run_tag === 'monday')
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                                    </svg>
                                @elseif($plan->run_tag === 'friday')
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/>
                                    </svg>
                                @endif
                                {{ ucfirst($plan->run_tag) }}
                            </div>

                            <div class="text-sm text-gray-600">
                                {{ $plan->run_date->format('Y-m-d') }}
                            </div>

                            <div class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-medium">
                                {{ $plan->type }}
                            </div>

                            @if($plan->emailed_at)
                                <div class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-medium">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    Mailad
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Title -->
                    <h3 class="font-semibold text-gray-900 mb-3">{{ $plan->title }}</h3>

                    <!-- Content -->
                    <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                        {!! \Illuminate\Support\Str::of($plan->content_md ?? '—')->markdown() !!}
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Ingen historik ännu</h3>
                    <p class="text-gray-600">Veckorapporter visas här när de genereras.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($plans->hasPages())
            <div class="mt-6 pt-4 border-t border-gray-200/50">
                {{ $plans->links() }}
            </div>
        @endif
    </div>
</div>
