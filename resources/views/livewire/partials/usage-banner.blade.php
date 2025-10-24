<div>
    @if(!empty($alerts))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            @foreach($alerts as $a)
                <div class="mb-3 last:mb-0 bg-white/90 backdrop-blur-lg rounded-xl shadow-lg border
                    @if($a['type'] === 'stop') border-red-200/50
                    @else border-amber-200/50 @endif
                    p-4 hover:shadow-xl transition-all duration-200">

                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @if($a['type'] === 'stop')
                                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center space-x-3 mb-1">
                                    <h3 class="font-semibold
                                        @if($a['type'] === 'stop') text-red-800
                                        @else text-amber-800 @endif">
                                        {{ $a['label'] }}
                                    </h3>

                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($a['type'] === 'stop') bg-red-100 text-red-800 border border-red-200
                                        @else bg-amber-100 text-amber-800 border border-amber-200 @endif">
                                        {{ $a['used'] }} / {{ $a['quota'] }} ({{ $a['pct'] }}%)
                                    </div>
                                </div>

                                <p class="text-sm
                                    @if($a['type'] === 'stop') text-red-700
                                    @else text-amber-700 @endif">
                                    @if($a['type'] === 'stop')
                                        Du har nått din kvot. Uppgradera plan för fler krediter.
                                    @else
                                        Du närmar dig din kreditkvot för denna månaden.
                                    @endif
                                </p>

                                <div class="mt-3 w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full transition-all duration-300
                                        @if($a['type'] === 'stop') bg-gradient-to-r from-red-500 to-red-600
                                        @else bg-gradient-to-r from-amber-500 to-orange-500 @endif"
                                         style="width: {{ min($a['pct'], 100) }}%">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 flex-shrink-0">
                            <a href="{{ route('account.upgrade') }}"
                               class="inline-flex items-center px-4 py-2
                                   @if($a['type'] === 'stop')
                                       bg-gradient-to-r from-red-600 to-red-700 text-white hover:from-red-700 hover:to-red-800 shadow-lg
                                   @else
                                       bg-gradient-to-r from-amber-600 to-orange-600 text-white hover:from-amber-700 hover:to-orange-700 shadow-lg
                                   @endif
                                   font-semibold rounded-xl focus:ring-2 focus:ring-offset-2
                                   @if($a['type'] === 'stop') focus:ring-red-500 @else focus:ring-amber-500 @endif
                                   transition-all duration-200 hover:shadow-xl transform hover:scale-[1.02]">
                                Uppgradera
                            </a>

                            <a href="{{ route('account.usage') }}"
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                                Visa förbrukning
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
