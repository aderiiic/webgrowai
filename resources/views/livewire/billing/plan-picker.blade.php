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
            <div class="bg-white/80 backdrop-blur-sm rounded-xl border p-6 shadow-sm">
                <div class="mb-3">
                    <h2 class="text-xl font-semibold">{{ $plan['name'] }}</h2>
                </div>

                <ul class="text-sm text-gray-600 mb-4 space-y-1">
                    <li>All funktionalitet enligt plan</li>
                    <li>Ingen bindningstid, avsluta när som helst</li>
                </ul>

                @php
                    // VIKTIGT: Använd exakt värden från DB (ingen division).
                    $priceMonthly = (int)($plan['price_monthly'] ?? 0);
                    $priceYearly  = (int)($plan['price_yearly']  ?? 0);

                    $priceIdMonthly = (string)($plan['stripe_price_monthly'] ?? '');
                    $priceIdYearly  = (string)($plan['stripe_price_yearly']  ?? '');

                    $isMonthlyPreferred = $cycle === 'monthly';
                @endphp

                <div class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        {{-- Månadsvis knapp --}}
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

                        {{-- Årsvis knapp --}}
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

                    @if(empty($priceIdMonthly) || empty($priceIdYearly))
                        <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-md p-2">
                            Denna plan saknar {{ empty($priceIdMonthly) ? 'månads' : '' }}{{ empty($priceIdMonthly) && empty($priceIdYearly) ? ' och ' : '' }}{{ empty($priceIdYearly) ? 'års' : '' }}‑price i Stripe. Fyll i Price‑ID i admin.
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
