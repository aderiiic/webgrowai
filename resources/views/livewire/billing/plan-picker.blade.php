<?php /** @var array $plans */ ?>
<div class="max-w-5xl mx-auto py-10 space-y-8">
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold">Välj plan</h1>

        <div class="inline-flex items-center space-x-2">
            <span class="text-sm text-gray-600">Fakturering (förvald stil)</span>
            <select wire:model="cycle" class="px-3 py-2 border rounded-lg text-sm">
                <option value="monthly">Månadsvis</option>
                <option value="annual">Årsvis</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($plans as $plan)
            @php
                $priceMonthly = (int)($plan['price_monthly'] ?? 0);
                $priceYearly  = (int)($plan['price_yearly']  ?? 0);
                $inclMonthly  = (int) round($priceMonthly * 1.25);
                $inclYearly   = (int) round($priceYearly  * 1.25);

                $priceIdMonthly = (string)($plan['stripe_price_monthly'] ?? '');
                $priceIdYearly  = (string)($plan['stripe_price_yearly']  ?? '');

                $isMonthlyPreferred = $cycle === 'monthly';
            @endphp

            <div class="bg-white/80 backdrop-blur-sm rounded-xl border p-6 shadow-sm">
                <div class="mb-3">
                    <h2 class="text-xl font-semibold">{{ $plan['name'] }}</h2>
                </div>

                <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="text-xs text-gray-600">Månadspris</div>
                        <div class="text-base font-semibold text-gray-900">
                            {{ number_format($priceMonthly, 0, ',', ' ') }} kr exkl. moms
                        </div>
                        <div class="text-xs text-gray-600">≈ {{ number_format($inclMonthly, 0, ',', ' ') }} kr inkl. 25% (SE)</div>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="text-xs text-gray-600">Årspris</div>
                        <div class="text-base font-semibold text-gray-900">
                            {{ number_format($priceYearly, 0, ',', ' ') }} kr exkl. moms
                        </div>
                        <div class="text-xs text-gray-600">≈ {{ number_format($inclYearly, 0, ',', ' ') }} kr inkl. 25% (SE)</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <form method="POST" action="{{ route('billing.checkout') }}">
                        @csrf
                        <input type="hidden" name="price" value="{{ $priceIdMonthly }}">
                        <button
                            class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg text-white
                                   {{ $isMonthlyPreferred ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-indigo-500 hover:bg-indigo-600' }}
                                   {{ empty($priceIdMonthly) ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ empty($priceIdMonthly) ? 'disabled' : '' }}
                            title="{{ empty($priceIdMonthly) ? 'Stripe Price (månadsvis) saknas för denna plan' : '' }}"
                        >
                            Välj månadsvis — {{ number_format($priceMonthly, 0, ',', ' ') }} kr/mån
                        </button>
                    </form>

                    <form method="POST" action="{{ route('billing.checkout') }}">
                        @csrf
                        <input type="hidden" name="price" value="{{ $priceIdYearly }}">
                        <button
                            class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg text-white
                                   {{ !$isMonthlyPreferred ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-emerald-500 hover:bg-emerald-600' }}
                                   {{ empty($priceIdYearly) ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ empty($priceIdYearly) ? 'disabled' : '' }}
                            title="{{ empty($priceIdYearly) ? 'Stripe Price (årsvis) saknas för denna plan' : '' }}"
                        >
                            Välj årsvis — {{ number_format($priceYearly, 0, ',', ' ') }} kr/år
                        </button>
                    </form>
                </div>

                <p class="mt-3 text-xs text-gray-500">
                    Moms beräknas i Checkout och kan avvika vid giltigt VAT‑nummer eller annan skattejurisdiktion.
                </p>
            </div>
        @endforeach
    </div>
</div>
