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

            <!-- Customer Sites Management -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                                </svg>
                            </div>
                            <h2 class="ml-3 text-lg font-semibold text-gray-900">Kundens sajter</h2>
                        </div>
                        <button wire:click="toggleAddSiteForm"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Lägg till sajt
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Add Site Form (toggleable) -->
                    @if($showAddSiteForm ?? false)
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 mb-4">Lägg till ny sajt för kunden</h4>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sajtnamn *</label>
                                    <input type="text" wire:model.defer="newSiteName"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Kundens webbplats">
                                    @error('newSiteName')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">URL *</label>
                                    <input type="url" wire:model.defer="newSiteUrl"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="https://exempel.se">
                                    @error('newSiteUrl')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-4 flex gap-3">
                                <button wire:click="createSiteForCustomer"
                                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Skapa sajt
                                </button>
                                <button wire:click="toggleAddSiteForm"
                                        class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                                    Avbryt
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Sites List -->
                    @if($customer->sites && $customer->sites->count() > 0)
                        <div class="space-y-4">
                            @foreach($customer->sites as $site)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $site->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $site->url }}</p>
                                            <p class="text-xs text-gray-500">Site Key: {{ $site->public_key }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <!-- Integration Status -->
                                        @php
                                            $integration = null;
                                            $hasIntegration = false;
                                            $integrationStatus = 'Ej kopplad';
                                            $integrationClass = 'bg-yellow-100 text-yellow-700';

                                            // Kontrollera om det finns integrationer
                                            if ($site->integrations && $site->integrations->count() > 0) {
                                                $integration = $site->integrations->first();

                                                if ($integration && $integration->status === 'connected') {
                                                    $hasIntegration = true;
                                                    $integrationStatus = 'Kopplad (' . ucfirst($integration->provider) . ')';
                                                    $integrationClass = 'bg-green-100 text-green-700';
                                                } elseif ($integration && $integration->status === 'error') {
                                                    $integrationStatus = 'Fel (' . ucfirst($integration->provider) . ')';
                                                    $integrationClass = 'bg-red-100 text-red-700';
                                                } elseif ($integration && $integration->status === 'pending') {
                                                    $integrationStatus = 'Väntar (' . ucfirst($integration->provider) . ')';
                                                    $integrationClass = 'bg-blue-100 text-blue-700';
                                                } elseif ($integration) {
                                                    $integrationStatus = 'Okonfigurerad (' . ucfirst($integration->provider) . ')';
                                                    $integrationClass = 'bg-orange-100 text-orange-700';
                                                }
                                            } else {
                                                // Ingen integration finns - visa som "Ej kopplad"
                                                $integrationStatus = 'Ej kopplad';
                                                $integrationClass = 'bg-gray-100 text-gray-700';
                                            }
                                        @endphp
                                        <div class="text-xs px-2 py-1 rounded-full {{ $integrationClass }}">
                                            {{ $integrationStatus }}
                                        </div>

                                        <!-- Action buttons -->
                                        <a href="{{ route('sites.integrations.connect', ['site' => $site->id]) }}"
                                           class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-lg transition-colors duration-200"
                                           title="Hantera koppling">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v4m0 8v4m8-8h-4M8 12H4"/>
                                            </svg>
                                            {{ $hasIntegration ? 'Hantera' : 'Koppla' }}
                                        </a>

                                        <a href="{{ route('sites.edit', $site) }}"
                                           class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-colors duration-200"
                                           title="Redigera sajt">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Redigera
                                        </a>

                                        <button wire:click="runSeoAudit({{ $site->id }})"
                                                class="inline-flex items-center px-3 py-1 bg-purple-100 hover:bg-purple-200 text-purple-700 text-xs font-medium rounded-lg transition-colors duration-200"
                                                title="Kör SEO audit">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            SEO Audit
                                        </button>

                                        <button wire:click="deleteSite({{ $site->id }})"
                                                onclick="return confirm('Är du säker på att du vill ta bort denna sajt? Detta kan inte ångras.')"
                                                class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded-lg transition-colors duration-200"
                                                title="Ta bort sajt">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Ta bort
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Quick Actions för kunden -->
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h4 class="text-sm font-semibold text-blue-900 mb-3">Snabbåtgärder för kundsupport</h4>
                            <div class="flex flex-wrap gap-2">
                                <button wire:click="impersonateCustomer"
                                        class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Logga in som kund
                                </button>

                                @if($customer->sites->first())
                                    <a href="{{ route('onboarding.tracker') }}"
                                       class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Hjälp med tracking-installation
                                    </a>
                                @endif

                                <button wire:click="resetCustomerOnboarding"
                                        class="inline-flex items-center px-3 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Återställ onboarding
                                </button>
                            </div>
                            <p class="text-xs text-blue-700 mt-2">
                                <strong>Tips:</strong> "Logga in som kund" låter dig se exakt vad kunden ser och hjälpa dem genom onboardingprocessen.
                            </p>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Inga sajter registrerade</h3>
                            <p class="text-sm text-gray-600 mb-4">Kunden har inte lagt till några sajter än. Du kan hjälpa dem genom att lägga till en sajt åt dem.</p>
                            <button wire:click="toggleAddSiteForm"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Lägg till första sajten
                            </button>
                        </div>
                    @endif
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
