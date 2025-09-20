
<?php /** @var array $plans */ ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
                Välj din <span class="text-indigo-600">plan</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
                Välj den plan som passar dig bäst. Du kan alltid uppgradera eller ändra senare.
            </p>

            <!-- Billing Cycle Toggle -->
            <div class="inline-flex items-center bg-white rounded-full p-1 shadow-lg border">
                <button
                    class="px-6 py-2 rounded-full text-sm font-medium transition-all duration-200
                           {{ $cycle === 'monthly' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' }}"
                    wire:click="$set('cycle', 'monthly')"
                >
                    Månadsvis
                </button>
                <button
                    class="px-6 py-2 rounded-full text-sm font-medium transition-all duration-200
                           {{ $cycle === 'annual' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' }}"
                    wire:click="$set('cycle', 'annual')"
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

                    $isMonthlyPreferred = $cycle === 'monthly';
                    $isPopular = $index === 1; // Markera andra planen som populär

                    // Beräkna årlig besparing
                    $monthlyCost = $priceMonthly * 12;
                    $yearlySavings = $monthlyCost > 0 ? round((($monthlyCost - $priceYearly) / $monthlyCost) * 100) : 0;
                @endphp

                <div class="relative group">
                    @if($isPopular)
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                            <span class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-1 rounded-full text-sm font-medium shadow-lg">
                                Populärast
                            </span>
                        </div>
                    @endif

                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-2
                               {{ $isPopular ? 'border-indigo-200 ring-2 ring-indigo-100' : 'border-gray-100' }}
                               p-6 lg:p-8 h-full flex flex-col">

                        <!-- Plan Name -->
                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan['name'] }}</h2>
                        </div>

                        <!-- Price Display -->
                        <div class="text-center mb-8">
                            @if($cycle === 'monthly')
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

                        <!-- Price Breakdown (Mobile Optimized) -->
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

                        <!-- Action Buttons -->
                        <div class="mt-auto space-y-3">
                            @if($cycle === 'monthly')
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
                                        title="{{ empty($priceIdMonthly) ? 'Stripe Price (månadsvis) saknas för denna plan' : '' }}"
                                    >
                                        <span class="flex items-center justify-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Välj månadsvis
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
                                        title="{{ empty($priceIdYearly) ? 'Stripe Price (årsvis) saknas för denna plan' : '' }}"
                                    >
                                        <span class="flex items-center justify-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Välj årsvis
                                        </span>
                                    </button>
                                </form>
                            @endif

                            <!-- Alternative Option (Subtle) -->
                            @if($cycle === 'monthly')
                                <form method="POST" action="{{ route('billing.checkout') }}" class="w-full">
                                    @csrf
                                    <input type="hidden" name="price" value="{{ $priceIdYearly }}">
                                    <button
                                        type="submit"
                                        class="w-full border-2 border-gray-200 hover:border-emerald-300 text-gray-700 hover:text-emerald-700
                                               font-medium py-3 px-6 rounded-xl transition-all duration-200
                                               disabled:opacity-50 disabled:cursor-not-allowed bg-white hover:bg-emerald-50"
                                        {{ empty($priceIdYearly) ? 'disabled' : '' }}
                                    >
                                        Eller välj årsvis
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('billing.checkout') }}" class="w-full">
                                    @csrf
                                    <input type="hidden" name="price" value="{{ $priceIdMonthly }}">
                                    <button
                                        type="submit"
                                        class="w-full border-2 border-gray-200 hover:border-indigo-300 text-gray-700 hover:text-indigo-700
                                               font-medium py-3 px-6 rounded-xl transition-all duration-200
                                               disabled:opacity-50 disabled:cursor-not-allowed bg-white hover:bg-indigo-50"
                                        {{ empty($priceIdMonthly) ? 'disabled' : '' }}
                                    >
                                        Eller välj månadsvis
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Footer Info -->
        <div class="text-center mt-12 bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg">
            <div class="max-w-3xl mx-auto">
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Säker betalning
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Avsluta när som helst
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        24/7 Support
                    </div>
                </div>
                <p class="mt-4 text-xs text-gray-500">
                    Moms beräknas automatiskt i kassan baserat på din adress. Priser kan variera med giltigt VAT-nummer eller annan skattejurisdiktion.
                </p>
            </div>
        </div>
    </div>
</div>
