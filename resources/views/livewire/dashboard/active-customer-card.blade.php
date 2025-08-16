<div>
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6 h-full flex flex-col">
        <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Aktiv kund
            </h2>

            @if($customer)
                <div class="flex flex-wrap items-center gap-2">
                    <div class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 rounded-xl border border-indigo-200/50 text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        <span class="font-semibold">{{ $monthGenerateTotal }}</span>
                        <span class="ml-1">genereringar</span>
                    </div>
                    <div class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-emerald-50 to-teal-50 text-emerald-700 rounded-xl border border-emerald-200/50 text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span class="font-semibold">{{ $monthPublishTotal }}</span>
                        <span class="ml-1">publicerade</span>
                    </div>
                    <div class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-amber-50 to-orange-50 text-amber-700 rounded-xl border border-amber-200/50 text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-semibold">{{ $monthMailchimpTotal }}</span>
                        <span class="ml-1">kampanjer</span>
                    </div>
                </div>
            @endif
        </div>

        @if($customer)
            <!-- Kundnamn -->
            <div class="mb-6">
                <div class="inline-flex items-center px-4 py-3 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200/50 shadow-sm">
                    <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse mr-3"></div>
                    <div>
                        <div class="text-sm font-medium text-gray-600">Aktiv kund</div>
                        <div class="text-lg font-bold text-gray-900">{{ $customer->name }}</div>
                    </div>
                </div>
            </div>

            <!-- Statistik grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-blue-700">Sajter</div>
                            <div class="text-2xl font-bold text-blue-900">{{ $sites->count() }}</div>
                        </div>
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-4 border border-emerald-200/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-emerald-700">Status</div>
                            <div class="text-lg font-bold text-emerald-900 capitalize">{{ $customer->status ?? 'active' }}</div>
                        </div>
                        <div class="w-12 h-12 bg-emerald-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-purple-700">Kontakt</div>
                            <div class="text-sm font-medium text-purple-900 truncate">
                                {{ $customer->contact_email ?? 'Ingen email' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sajtlista -->
            @if($sites->isNotEmpty())
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        Sajter
                    </h3>
                    <div class="space-y-3">
                        @foreach($sites->take(5) as $site)
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200/50 hover:border-gray-300/50 hover:shadow-md transition-all duration-200">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="font-semibold text-gray-900 truncate">{{ $site->name }}</div>
                                            <a href="{{ $site->url }}" class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline truncate block" target="_blank" rel="noopener">
                                                {{ $site->url }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('sites.edit', $site) }}" class="ml-4 inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200 flex-shrink-0">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Redigera
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if($sites->count() > 5)
                        <div class="mt-3 text-center">
                            <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                …och {{ $sites->count() - 5 }} sajter till
                            </span>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Åtgärdsknappar -->
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('sites.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Hantera sajter
                </a>
                <a href="{{ route('sites.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Lägg till sajt
                </a>
                <a href="{{ request()->url() }}?customer=" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Byt kund
                </a>
            </div>
        @else
            <!-- Ingen kund vald -->
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Ingen aktiv kund</h3>
                <p class="text-gray-600 mb-4">Välj en kund via kundväljaren uppe i menyn för att komma igång.</p>
                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Välj kund
                </div>
            </div>
        @endif
    </div>
</div>
