<div>
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Användningsöversikt
                </h1>
                <div class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                    {{ now()->format('F Y') }}
                </div>
            </div>

            <div class="flex items-center space-x-4">
                @if($planName || $planStatus)
                    <div class="flex items-center space-x-3">
                        @php
                            $planColors = [
                                'trial' => ['bg' => 'from-amber-50 to-orange-50', 'border' => 'border-amber-200/50', 'text' => 'text-amber-800'],
                                'active' => ['bg' => 'from-indigo-50 to-blue-50', 'border' => 'border-indigo-200/50', 'text' => 'text-indigo-800'],
                            ];
                            $colors = $planColors[$planStatus] ?? ['bg' => 'from-gray-50 to-slate-50', 'border' => 'border-gray-200/50', 'text' => 'text-gray-800'];
                        @endphp

                        <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r {{ $colors['bg'] }} {{ $colors['border'] }} border rounded-xl">
                            <span class="font-semibold {{ $colors['text'] }}">{{ $planName ?? 'Plan' }}</span>
                            <span class="ml-2 px-2 py-0.5 bg-white bg-opacity-60 {{ $colors['text'] }} text-xs font-medium rounded-full uppercase">
                                {{ $planStatus ?? '—' }}
                            </span>
                            @if($planStatus === 'trial' && $trialDaysLeft !== null)
                                <span class="ml-2 text-xs {{ $colors['text'] }}">• {{ $trialDaysLeft }} dagar kvar</span>
                            @endif
                        </div>
                    </div>
                @endif

                <a href="{{ route('account.upgrade') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-4 4"/>
                    </svg>
                    Uppgradera plan
                </a>
            </div>
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

        <!-- Usage metrics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($rows as $r)
                @php
                    $statusColors = [
                        'stop' => ['bg' => 'from-red-50 to-pink-50', 'border' => 'border-red-200/50', 'text' => 'text-red-800', 'progress' => 'bg-red-500'],
                        'warn' => ['bg' => 'from-amber-50 to-yellow-50', 'border' => 'border-amber-200/50', 'text' => 'text-amber-800', 'progress' => 'bg-amber-500'],
                        'normal' => ['bg' => 'from-indigo-50 to-blue-50', 'border' => 'border-indigo-200/50', 'text' => 'text-indigo-800', 'progress' => 'bg-indigo-500'],
                    ];

                    $status = $r['stop'] ? 'stop' : ($r['warn'] ? 'warn' : 'normal');
                    $colors = $statusColors[$status];
                @endphp

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6 hover:shadow-2xl transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">{{ $r['label'] }}</h3>
                        <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r {{ $colors['bg'] }} {{ $colors['border'] }} border rounded-full">
                            <span class="text-sm font-semibold {{ $colors['text'] }}">
                                {{ $r['used'] }} / {{ $r['quota'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Progress bar -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                            <span>Förbrukning</span>
                            <span>{{ $r['pct'] }}%</span>
                        </div>
                        <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full {{ $colors['progress'] }} rounded-full transition-all duration-500" style="width: {{ $r['pct'] }}%"></div>
                        </div>
                    </div>

                    <!-- Status message -->
                    @if($r['warn'] && !$r['stop'])
                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <p class="text-xs font-medium text-amber-800">Du har nått {{ $r['pct'] }}% av kvoten</p>
                            </div>
                        </div>
                    @elseif($r['stop'])
                        <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-xs font-medium text-red-800">Kvotgräns uppnådd – uppgradera plan eller begär extraanvändning</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Extra usage request -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-8">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Behöver mer kapacitet?</h2>
                    <p class="text-sm text-gray-600">Uppgradera din plan eller begär tillfällig extraanvändning för denna månad</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200/50">
                    <div class="flex items-center space-x-3 mb-3">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-4 4"/>
                        </svg>
                        <h3 class="font-semibold text-emerald-900">Permanent uppgradering</h3>
                    </div>
                    <p class="text-sm text-emerald-800 mb-4">Uppgradera till en högre plan för mer kapacitet varje månad</p>
                    <a href="{{ route('account.upgrade') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors duration-200 text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-4 4"/>
                        </svg>
                        Uppgradera plan
                    </a>
                </div>

                <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200/50">
                    <div class="flex items-center space-x-3 mb-3">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 2l6 2m-6-2l-6 2m12 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V9z"/>
                        </svg>
                        <h3 class="font-semibold text-purple-900">Tillfällig extraanvändning</h3>
                    </div>
                    <p class="text-sm text-purple-800 mb-4">Begär extra kapacitet bara för denna månad</p>
                    <div class="flex items-center space-x-3">
                        <input type="text" wire:model.defer="overageNote" class="flex-1 px-3 py-2 bg-white border border-purple-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200" placeholder="Meddelande (valfritt)">
                        <button wire:click="requestOverage" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors duration-200 text-sm {{ $overageRequested ? 'opacity-50 cursor-not-allowed' : '' }}" @if($overageRequested) disabled @endif>
                            @if($overageRequested)
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Skickad
                            @else
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Begär extra
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
