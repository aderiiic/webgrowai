<div>
    <div class="bg-white rounded-xl shadow-sm border p-6 h-full flex flex-col">
        @if($customer)
            <!-- Header med kundnamn -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $customer->name }}</h2>
                        <p class="text-sm text-gray-500">Aktiv kund</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">{{ $sites->count() }} webbplats{{ $sites->count() !== 1 ? 'er' : '' }}</p>
                </div>
            </div>

            <!-- Denna månads aktivitet -->
            <div class="bg-blue-50 rounded-xl p-4 mb-6">
                <h3 class="text-sm font-semibold text-blue-900 mb-3">Denna månads aktivitet</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-xl font-bold text-blue-600">{{ $monthGenerateTotal }}</div>
                        <div class="text-xs text-blue-600">AI-artiklar</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-bold text-green-600">{{ $monthPublishTotal }}</div>
                        <div class="text-xs text-green-600">Publicerade</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-bold text-orange-600">{{ $monthMailchimpTotal }}</div>
                        <div class="text-xs text-orange-600">Nyhetsbrev</div>
                    </div>
                </div>
            </div>

            <!-- Dina webbplatser -->
            @if($sites->isNotEmpty())
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Dina webbplatser</h3>
                    <div class="space-y-2">
                        @foreach($sites->take(3) as $site)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">{{ $site->name }}</p>
                                        <p class="text-xs text-gray-500">{{ parse_url($site->url, PHP_URL_HOST) ?? $site->url }}</p>
                                    </div>
                                </div>
                                <a href="{{ $site->url }}" target="_blank" class="text-blue-600 hover:text-blue-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                        @if($sites->count() > 3)
                            <div class="text-center py-2">
                                <span class="text-xs text-gray-500">+{{ $sites->count() - 3 }} till</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Åtgärder -->
            <div class="mt-auto space-y-3">
                <a href="{{ route('sites.index') }}" class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Hantera webbplatser
                </a>
                <div class="flex space-x-2">
                    <a href="{{ route('sites.create') }}" class="flex-1 flex items-center justify-center px-3 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Lägg till
                    </a>
                    <a href="{{ request()->url() }}?customer=" class="flex-1 flex items-center justify-center px-3 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4"/>
                        </svg>
                        Byt kund
                    </a>
                </div>
            </div>
        @else
            <!-- Ingen kund vald -->
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Ingen kund vald</h3>
                <p class="text-gray-600 text-sm mb-4">Välj en kund för att komma igång.</p>
            </div>
        @endif
    </div>
</div>
