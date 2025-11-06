@extends('layouts.guest', ['title' => 'Registrera – WebGrow AI'])

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Skapa konto</h1>
            <p class="mt-2 text-gray-600">Kom igång med modern SEO, publicering och konverteringsoptimering. Testa WebGrow AI
                under hela 14 dagar utan kostnad. Ingen bindningstid.</p>
        </div>

        <!-- Error summary -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="text-sm text-red-800">
                        <p class="font-semibold">Det finns fel i formuläret:</p>
                        <ul class="list-disc list-inside mt-1 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-6" x-data="{ accountType: '{{ old('account_type', 'personal') }}' }">            @csrf
            <div class="hidden">
                <label>Webbplats</label>
                <input type="text" name="website" tabindex="-1" autocomplete="off">
                <input type="hidden" name="form_started_at" value="{{ time() }}">
            </div>

            <!-- Account Type Selector -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/60 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Kontotyp</h2>
                        <p class="text-sm text-gray-600">Välj om du registrerar som privatperson eller företag</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <!-- Company Option -->
                    <label class="relative flex flex-col p-4 border-2 rounded-xl cursor-pointer transition-all"
                           :class="accountType === 'company' ? 'border-indigo-600 bg-indigo-50/50' : 'border-gray-200 hover:border-gray-300'">
                        <input type="radio" name="account_type" value="company" x-model="accountType" class="sr-only">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center"
                                 :class="accountType === 'company' ? 'bg-indigo-600' : 'bg-gray-200'">
                                <svg class="w-5 h-5" :class="accountType === 'company' ? 'text-white' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">Företag</p>
                                <p class="text-sm text-gray-600">Med org.nr och fakturering</p>
                            </div>
                        </div>
                    </label>

                    <!-- Personal Option -->
                    <label class="relative flex flex-col p-4 border-2 rounded-xl cursor-pointer transition-all"
                           :class="accountType === 'personal' ? 'border-indigo-600 bg-indigo-50/50' : 'border-gray-200 hover:border-gray-300'">
                        <input type="radio" name="account_type" value="personal" x-model="accountType" class="sr-only">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center"
                                 :class="accountType === 'personal' ? 'bg-indigo-600' : 'bg-gray-200'">
                                <svg class="w-5 h-5" :class="accountType === 'personal' ? 'text-white' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">Privatperson</p>
                                <p class="text-sm text-gray-600">Enklare registrering</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Konto -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/60 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Konto</h2>
                        <p class="text-sm text-gray-600">Dina inloggningsuppgifter</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Namn *</label>
                        <input name="name" type="text" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('name')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">E‑post *</label>
                        <input name="email" type="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('email')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Telefon (valfritt)</label>
                        <input name="contact_phone" type="text" value="{{ old('contact_phone') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lösenord *</label>
                        <input name="password" type="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('password')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                        <p class="mt-1 text-xs text-gray-500">Minst 8 tecken, gärna med siffror och symboler.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bekräfta lösenord *</label>
                        <input name="password_confirmation" type="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Företag/Fakturering (conditional) -->
            <div x-show="accountType === 'company'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/60 p-6">
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
                        <input name="company_name" type="text" value="{{ old('company_name') }}" :required="accountType === 'company'"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('company_name')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Org.nr *</label>
                            <input name="org_nr" type="text" value="{{ old('org_nr') }}" :required="accountType === 'company'"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('org_nr')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Momsnr / VAT (valfritt)</label>
                            <input name="vat_nr" type="text" value="{{ old('vat_nr') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kontaktperson *</label>
                            <input name="contact_name" type="text" value="{{ old('contact_name') }}" :required="accountType === 'company'"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('contact_name')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Faktura e‑post *</label>
                            <input name="billing_email" type="email" value="{{ old('billing_email', old('email')) }}" :required="accountType === 'company'"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('billing_email')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fakturaadress *</label>
                            <input name="billing_address" type="text" value="{{ old('billing_address') }}" :required="accountType === 'company'"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('billing_address')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Postnummer *</label>
                            <input name="billing_zip" type="text" value="{{ old('billing_zip') }}" :required="accountType === 'company'"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('billing_zip')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ort *</label>
                            <input name="billing_city" type="text" value="{{ old('billing_city') }}" :required="accountType === 'company'"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('billing_city')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Land (ISO‑2) *</label>
                            <input name="billing_country" type="text" maxlength="2" value="{{ old('billing_country','SE') }}" :required="accountType === 'company'"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white/70 placeholder-gray-400 uppercase tracking-wider focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('billing_country')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Policy + CTA -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/60 p-6">
                <div class="flex items-start gap-3">
                    <input id="terms" name="terms" type="checkbox" value="1" required
                           class="mt-1 h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="terms" class="text-sm text-gray-700">
                        Jag har läst och godkänner
                        <a href="{{ route('terms') }}" target="_blank" rel="noopener" class="text-indigo-600 hover:text-indigo-800 underline underline-offset-2">användarvillkoren</a>
                        och
                        <a href="{{ route('privacy') }}" target="_blank" rel="noopener" class="text-indigo-600 hover:text-indigo-800 underline underline-offset-2">integritetspolicyn</a>.
                    </label>
                </div>
                @error('terms')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror

                <div class="mt-6 flex items-center justify-between flex-wrap gap-3">
                    <p class="text-xs text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c.828 0 1.5-.672 1.5-1.5S12.828 8 12 8s-1.5.672-1.5 1.5S11.172 11 12 11zm0 0v3m9-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Vi skyddar dina uppgifter enligt gällande lagstiftning.
                    </p>

                    <button class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Skapa konto
                    </button>
                </div>
            </div>

            <div class="text-center text-sm text-gray-600">
                Har du redan ett konto?
                <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Logga in</a>
            </div>
        </form>
    </div>
@endsection
