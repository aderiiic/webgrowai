<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Kund – Plan & förbrukning</h1>
                    <p class="mt-2 text-sm text-gray-600">Hantera kundens prenumeration och användning</p>
                </div>
                <a href="{{ route('admin.usage.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Tillbaka
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="space-y-8">
            <!-- Customer Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="ml-3 text-lg font-semibold text-gray-900">Kunduppgifter</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-xl font-bold">
                                {{ substr($customer->name, 0, 1) }}
                            </span>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-xl font-bold text-gray-900">{{ $customer->name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $customer->billing_email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plan Management -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h2 class="ml-3 text-lg font-semibold text-gray-900">Prenumerationshantering</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid md:grid-cols-4 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Plan</label>
                            <select wire:model="planId" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 bg-white">
                                @foreach($plans as $p)
                                    <option value="{{ $p['id'] }}">{{ $p['name'] }} ({{ number_format($p['price']/100, 0, ',', ' ') }} kr/mån)</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select wire:model="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 bg-white">
                                <option value="active">Aktiv</option>
                                <option value="trial">Trial</option>
                                <option value="paused">Pausad</option>
                                <option value="cancelled">Avbruten</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Fakturering</label>
                            <select wire:model="billing_cycle" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200 bg-white">
                                <option value="monthly">Månatlig</option>
                                <option value="annual">Årlig</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Trial slutar</label>
                            <input type="date" wire:model="trial_ends_at"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors duration-200">
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <button wire:click="savePlan"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Spara ändringar
                        </button>

                        <button wire:click="setTrialGrowth"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Sätt Trial: Growth (14d)
                        </button>

                        <button wire:click="createDraftInvoice"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Skapa fakturautkast ({{ now()->format('Y-m') }})
                        </button>
                    </div>
                </div>
            </div>

            <!-- Usage Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h2 class="ml-3 text-lg font-semibold text-gray-900">Förbrukning ({{ now()->format('Y-m') }})</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        @forelse($rows as $r)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="text-sm font-semibold text-gray-900">{{ $r['label'] }}</div>
                                    @if($r['quota'] !== null)
                                        <div class="text-sm text-gray-600">
                                            <span class="font-bold text-gray-900">{{ number_format($r['used']) }}</span> / {{ number_format($r['quota']) }}
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-600">
                                            <span class="font-bold text-gray-900">{{ number_format($r['used']) }}</span> (ingen kvot)
                                        </div>
                                    @endif
                                </div>

                                @if($r['quota'] !== null)
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="h-3 rounded-full transition-all duration-300 {{ $r['pct'] >= 90 ? 'bg-red-500' : ($r['pct'] >= 75 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                             style="width: {{ min(100, $r['pct']) }}%"></div>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        {{ $r['pct'] }}% använt
                                        @if($r['pct'] >= 90)
                                            <span class="text-red-600 font-medium">(Nära kvotgräns!)</span>
                                        @elseif($r['pct'] >= 75)
                                            <span class="text-yellow-600 font-medium">(Hög användning)</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2z"/>
                                </svg>
                                <p class="text-sm text-gray-500">Ingen användningsdata tillgänglig för denna period</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Overage Management -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="p-2 bg-orange-100 rounded-lg">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <h2 class="ml-3 text-lg font-semibold text-gray-900">Extraanvändning (innevarande period)</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <input type="text" wire:model.defer="overageNote"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                                   placeholder="Anteckning om godkännandet (valfritt)">

                            <button wire:click="toggleOverageApproval"
                                    class="inline-flex items-center px-4 py-2 {{ $overageApproved ? 'bg-red-600 hover:bg-red-700' : 'bg-orange-600 hover:bg-orange-700' }} text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 {{ $overageApproved ? 'focus:ring-red-500' : 'focus:ring-orange-500' }} focus:ring-offset-2">
                                @if($overageApproved)
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Återkalla godkännande
                                @else
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Godkänn övertramp
                                @endif
                            </button>
                        </div>

                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-800">
                                        <strong>Information:</strong> När godkänt kommer kvotgränser inte att blockera användning och extra förbrukning kan faktureras som tillägg.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
