<div>
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-4 4"/>
                </svg>
                Uppgradera eller ändra plan
            </h1>
            <div class="flex gap-3">
                <a href="{{ route('billing.portal') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Hantera betalningar
                </a>
                <a href="{{ route('account.usage') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Se förbrukning
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

        <!-- Current Plan Card -->
        @if($currentSubscription)
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl border-2 border-purple-200 p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4">
                        <div class="w-14 h-14 bg-purple-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-bold text-purple-900">Din nuvarande plan</h3>
                                @if($currentSubscription['on_grace_period'])
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Upphör snart
                                    </span>
                                @endif
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl font-bold text-purple-900">{{ $currentSubscription['plan_name'] }}</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $currentSubscription['is_yearly'] ? 'Årsvis' : 'Månadsvis' }}
                                    </span>
                                </div>
                                <p class="text-purple-700">
                                    <span class="text-lg font-semibold">{{ number_format($currentSubscription['price'], 0, ',', ' ') }} kr</span>
                                    <span class="text-sm">{{ $currentSubscription['is_yearly'] ? '/år' : '/månad' }}</span>
                                    <span class="text-sm ml-2">({{ number_format($currentSubscription['price'] * 1.25, 0, ',', ' ') }} kr inkl. moms)</span>
                                </p>
                                @if($currentSubscription['on_grace_period'] && $currentSubscription['ends_at'])
                                    <p class="text-sm text-amber-700 font-medium">
                                        ⚠️ Prenumerationen upphör: {{ \Carbon\Carbon::parse($currentSubscription['ends_at'])->format('Y-m-d') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('billing.portal') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition-colors">
                        Hantera
                    </a>
                </div>
            </div>
        @endif

        <!-- Info card -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-200/50 p-6">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Ändra eller avsluta din prenumeration</h3>
                    <p class="text-blue-800 mb-3">
                        Välj en ny plan nedan eller klicka på <strong>"Hantera betalningar"</strong> för att:
                    </p>
                    <ul class="space-y-1 text-sm text-blue-700">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Avsluta prenumeration (löper ut på sista dagen innan förnyelse)
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Visa och ladda ner fakturor
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Uppdatera betalningsmetod
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <strong>Byt plan</strong> (Stripe hanterar proration automatiskt)
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Billing Cycle Toggle -->
        <div class="text-center">
            <div class="inline-flex items-center bg-white rounded-full p-1 shadow-lg border">
                <button
                    class="px-6 py-2 rounded-full text-sm font-medium transition-all duration-200
                           {{ $billing_cycle === 'monthly' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' }}"
                    wire:click="$set('billing_cycle', 'monthly')"
                >
                    Månadsvis
                </button>
                <button
                    class="px-6 py-2 rounded-full text-sm font-medium transition-all duration-200
                           {{ $billing_cycle === 'annual' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' }}"
                    wire:click="$set('billing_cycle', 'annual')"
                >
                    Årsvis <span class="ml-1 text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">SPARA</span>
                </button>
            </div>
        </div>

        <!-- Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 lg:gap-8">
            @foreach($plans as $index => $plan)
                @php
                    $priceMonthly = (int)($plan['price_monthly'] ?? 0);
                    $priceYearly  = (int)($plan['price_yearly']  ?? 0);
                    $inclMonthly  = (int) round($priceMonthly * 1.25);
                    $inclYearly   = (int) round($priceYearly  * 1.25);

                    $priceIdMonthly = (string)($plan['stripe_price_monthly'] ?? '');
                    $priceIdYearly  = (string)($plan['stripe_price_yearly']  ?? '');

                    $isPopular = $index === 1;
                    $isCurrentPlan = $currentPlanId === $plan['id'];

                    // Beräkna årlig besparing
                    $monthlyCost = $priceMonthly * 12;
                    $yearlySavings = $monthlyCost > 0 ? round((($monthlyCost - $priceYearly) / $monthlyCost) * 100) : 0;
                @endphp

                <div class="relative group">
                    @if($isCurrentPlan)
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                            <span class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-1 rounded-full text-sm font-medium shadow-lg">
                                Din plan
                            </span>
                        </div>
                    @elseif($isPopular)
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                            <span class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-1 rounded-full text-sm font-medium shadow-lg">
                                Populärast
                            </span>
                        </div>
                    @endif

                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-2
                               {{ $isCurrentPlan ? 'border-purple-300 ring-2 ring-purple-100' : ($isPopular ? 'border-indigo-200 ring-2 ring-indigo-100' : 'border-gray-100') }}
                               p-6 lg:p-8 h-full flex flex-col">

                        <!-- Plan Name -->
                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan['name'] }}</h2>
                        </div>

                        <!-- Price Display -->
                        <div class="text-center mb-8">
                            @if($billing_cycle === 'monthly')
                                <div class="mb-2">
                                    <span class="text-4xl font-bold text-gray-900">{{ number_format($priceMonthly, 0, ',', ' ') }}</span>
                                    <span class="text-lg text-gray-600 ml-1">kr/mån</span>
                                </div>
                                <p class="text-sm text-gray-500">
                                    {{ number_format($inclMonthly, 0, ',', ' ') }} kr inkl. moms
                                </p>
                            @else
                                <div class="mb-2">
                                    <span class="text-4xl font-bold text-gray-900">{{ number_format($priceYearly, 0, ',', ' ') }}</span>
                                    <span class="text-lg text-gray-600 ml-1">kr/år</span>
                                </div>
                                <p class="text-sm text-gray-500 mb-1">
                                    {{ number_format($inclYearly, 0, ',', ' ') }} kr inkl. moms
                                </p>
                                @if($yearlySavings > 0)
                                    <div class="inline-flex items-center bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-medium">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Spara {{ $yearlySavings }}%
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Price Breakdown -->
                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div>
                                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Månad</div>
                                    <div class="text-sm font-semibold text-gray-900">{{ number_format($priceMonthly, 0, ',', ' ') }} kr</div>
                                </div>
                                <div>
                                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">År</div>
                                    <div class="text-sm font-semibold text-gray-900">{{ number_format($priceYearly, 0, ',', ' ') }} kr</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-auto">
                            @if($isCurrentPlan)
                                <button
                                    disabled
                                    class="w-full bg-gray-300 cursor-not-allowed text-gray-600 font-semibold py-4 px-6 rounded-xl"
                                >
                                    <span class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Nuvarande plan
                                    </span>
                                </button>
                            @else
                                @if($billing_cycle === 'monthly')
                                    <form method="POST" action="{{ route('billing.checkout') }}" class="w-full">
                                        @csrf
                                        <input type="hidden" name="price" value="{{ $priceIdMonthly }}">
                                        <button
                                            type="submit"
                                            class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed
                                                   text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200
                                                   transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl
                                                   focus:outline-none focus:ring-4 focus:ring-indigo-200"
                                            {{ empty($priceIdMonthly) ? 'disabled' : '' }}
                                        >
                                            <span class="flex items-center justify-center">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                {{ $currentSubscription ? 'Byt till denna' : 'Välj månadsvis' }}
                                            </span>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('billing.checkout') }}" class="w-full">
                                        @csrf
                                        <input type="hidden" name="price" value="{{ $priceIdYearly }}">
                                        <button
                                            type="submit"
                                            class="w-full bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-400 disabled:cursor-not-allowed
                                                   text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200
                                                   transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl
                                                   focus:outline-none focus:ring-4 focus:ring-emerald-200"
                                            {{ empty($priceIdYearly) ? 'disabled' : '' }}
                                        >
                                            <span class="flex items-center justify-center">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                {{ $currentSubscription ? 'Byt till denna' : 'Välj årsvis' }}
                                            </span>
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
