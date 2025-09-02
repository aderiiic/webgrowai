<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Skapa ny kund</h1>
                    <p class="mt-2 text-sm text-gray-600">Lägg till en ny kund med plan och användarkonto</p>
                </div>
                <div>
                    <a href="{{ route('admin.customers.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Tillbaka
                    </a>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <div class="text-sm text-green-800">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="text-sm text-red-800">
                        {{ session('error') }}
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit="create" class="space-y-6">
            <!-- Plan Selection -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/60 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Plan</h2>
                        <p class="text-sm text-gray-600">Välj plan för kunden (valfritt)</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Välj plan</label>
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" wire:model="selected_plan_id" value="" name="plan"
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">Ingen plan</div>
                                <div class="text-xs text-gray-500">Kunden kan välja plan senare</div>
                            </div>
                        </label>

                        @foreach($plans as $plan)
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" wire:model="selected_plan_id" value="{{ $plan->id }}" name="plan"
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                <div class="ml-3 flex-1 flex justify-between items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $plan->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $plan->description }}</div>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ number_format($plan->price, 0, ',', ' ') }} kr/mån
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('selected_plan_id')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Konto -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/60 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Användarkonto</h2>
                        <p class="text-sm text-gray-600">Uppgifter för huvudanvändaren</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Namn *</label>
                        <input wire:model="name" type="text"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('name')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">E‑post *</label>
                        <input wire:model.live="email" type="email"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('email')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Telefon (valfritt)</label>
                        <input wire:model="contact_phone" type="text"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('contact_phone')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Företag -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/60 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-600 to-teal-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h3V7H5a2 2 0 00-2 2zm7-2h4a2 2 0 012 2v12h3a2 2 0 002-2V9l-7-6-7 6V5a2 2 0 012-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Företagsuppgifter</h2>
                        <p class="text-sm text-gray-600">Används för fakturering och kontakt</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Företagsnamn *</label>
                        <input wire:model="company_name" type="text"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('company_name')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Org.nr *</label>
                            <input wire:model="org_nr" type="text"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('org_nr')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Momsnr / VAT (valfritt)</label>
                            <input wire:model="vat_nr" type="text"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('vat_nr')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kontaktperson *</label>
                            <input wire:model="contact_name" type="text"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('contact_name')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Faktura e‑post *</label>
                            <input wire:model="billing_email" type="email"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('billing_email')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fakturaadress *</label>
                            <input wire:model="billing_address" type="text"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('billing_address')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Postnummer *</label>
                            <input wire:model="billing_zip" type="text"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('billing_zip')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ort *</label>
                            <input wire:model="billing_city" type="text"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('billing_city')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Land (ISO‑2) *</label>
                            <input wire:model="billing_country" type="text" maxlength="2"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 uppercase tracking-wider focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('billing_country')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/60 p-6">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Ett välkomstmejl med lösenordslänk skickas till kunden.
                        </div>
                        <div class="flex items-center mt-1">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Kunden får 14 dagars kostnadsfri testperiod.
                        </div>
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg wire:loading.remove class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <svg wire:loading class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove>Skapa kund</span>
                        <span wire:loading>Skapar kund...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
