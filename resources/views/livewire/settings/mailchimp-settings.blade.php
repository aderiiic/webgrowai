
<div>
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M22.051 9.319c-.425-2.539-2.306-4.446-4.844-4.922-.664-.123-1.321-.123-1.985-.009-1.17.198-2.226.742-3.051 1.567-1.567 1.566-2.477 3.712-2.488 5.872-.011 2.16.887 4.315 2.444 5.883 1.557 1.568 3.701 2.488 5.861 2.499s4.315-.887 5.883-2.444c1.568-1.557 2.488-3.701 2.499-5.861.011-2.151-.887-4.296-2.44-5.854-.311-.311-.644-.596-.994-.857-.35-.261-.717-.499-1.097-.715z"/>
                </svg>
                Mailchimp Inställningar
            </h1>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
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

        <!-- Info card -->
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl border border-yellow-200/50 p-6">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-yellow-900 mb-2">Mailchimp API-integration</h3>
                    <p class="text-yellow-800">Anslut ditt Mailchimp-konto för att automatiskt skapa och skicka e-postkampanjer. Du hittar din API-nyckel och Audience ID i din Mailchimp-kontoinställning.</p>
                </div>
            </div>
        </div>

        <!-- Settings form -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-8">
            <div class="space-y-8">
                <!-- API credentials -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </div>
                            <div>
                                <label class="text-lg font-semibold text-gray-900">API-nyckel</label>
                                <p class="text-sm text-blue-700">Din Mailchimp API-nyckel</p>
                            </div>
                        </div>
                        <input type="password" wire:model.defer="api_key" class="w-full px-4 py-3 bg-white border border-blue-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="••••••••••">
                        <p class="mt-2 text-xs text-blue-600">Lämna tomt för att behålla nuvarande nyckel</p>
                    </div>

                    <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200/50">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-purple-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <label class="text-lg font-semibold text-gray-900">Audience ID</label>
                                <p class="text-sm text-purple-700">ID för din Mailchimp-lista</p>
                            </div>
                        </div>
                        <input type="text" wire:model.defer="audience_id" class="w-full px-4 py-3 bg-white border border-purple-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200" placeholder="Lista-ID från Mailchimp">
                    </div>
                </div>

                <!-- Email settings -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="p-6 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200/50">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <label class="text-lg font-semibold text-gray-900">Avsändarnamn</label>
                                <p class="text-sm text-emerald-700">Namnet som visas som avsändare</p>
                            </div>
                        </div>
                        <input type="text" wire:model.defer="from_name" class="w-full px-4 py-3 bg-white border border-emerald-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200" placeholder="Ditt företagsnamn">
                    </div>

                    <div class="p-6 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl border border-orange-200/50">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <label class="text-lg font-semibold text-gray-900">Svara-till e-post</label>
                                <p class="text-sm text-orange-700">E-postadress för svar</p>
                            </div>
                        </div>
                        <input type="email" wire:model.defer="reply_to" class="w-full px-4 py-3 bg-white border border-orange-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200" placeholder="svar@dittforetag.se">
                    </div>
                </div>

                <!-- Action buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <button wire:click="test" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Testa anslutning
                    </button>

                    <button wire:click="save" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-yellow-600 to-orange-600 text-white font-semibold rounded-xl hover:from-yellow-700 hover:to-orange-700 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h2m0 0h9a2 2 0 002-2V9a2 2 0 00-2-2H9m8 0V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2"/>
                        </svg>
                        Spara inställningar
                    </button>
                </div>
            </div>

            <!-- Status message -->
            @if($message)
                <div class="mt-6 p-4 rounded-xl {{ $status === 'active' ? 'bg-emerald-50 border border-emerald-200' : 'bg-red-50 border border-red-200' }}">
                    <div class="flex items-center space-x-3">
                        @if($status === 'active')
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                        <p class="text-sm font-medium {{ $status === 'active' ? 'text-emerald-800' : 'text-red-800' }}">{{ $message }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
