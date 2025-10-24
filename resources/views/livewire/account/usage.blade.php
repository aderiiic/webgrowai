<div>
    <div class="max-w-6xl mx-auto space-y-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Användningsöversikt
                </h1>
                <div class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                    {{ now()->format('F Y') }}
                </div>
            </div>

            <a href="{{ route('account.upgrade') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                Uppgradera plan
            </a>
        </div>

        @php
            $plans = app(\App\Services\Billing\PlanService::class);
            $current = app(\App\Support\CurrentCustomer::class);
            $customer = $current->get();
            $period = now()->format('Y-m');

            $monthlyQuota = $customer ? ($plans->getQuota($customer, 'credits.monthly') ?? null) : null;
            $creditsUsed = $customer ? (int) DB::table('usage_metrics')
                ->where('customer_id', $customer->id)
                ->where('period', $period)
                ->where('metric_key', 'credits.used')
                ->value('used_value') : 0;

            $pct = ($monthlyQuota && $monthlyQuota > 0) ? (int) round(($creditsUsed / max(1, $monthlyQuota)) * 100) : 0;

            $sitesQuota = $customer ? ($plans->getQuota($customer, 'sites') ?? null) : null;
            $sitesUsed = $customer ? DB::table('sites')->where('customer_id', $customer->id)->count() : 0;
            $sitesPct = ($sitesQuota && $sitesQuota > 0) ? (int) round(($sitesUsed / max(1, $sitesQuota)) * 100) : 0;
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Krediter denna månad</h3>
                    @if(!is_null($monthlyQuota))
                        <span class="inline-flex items-center px-3 py-1 bg-indigo-50 border border-indigo-200 text-indigo-800 rounded-full text-sm font-semibold">
                            {{ $creditsUsed }} / {{ $monthlyQuota }}
                        </span>
                    @else
                        <span class="text-sm text-gray-500">Obegränsat</span>
                    @endif
                </div>

                <div class="mb-4">
                    <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                        <span>Förbrukning</span>
                        <span>{{ min(100, $pct) }}%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-500 rounded-full transition-all duration-500" style="width: {{ min(100, $pct) }}%"></div>
                    </div>
                </div>

                <p class="text-sm text-gray-600">
                    Dina krediter nollställs första dagen i varje månad. Överskrider du dagliga/timvisa mjuka gränser kan åtgärder pausas till senare.
                </p>
            </div>

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Sajter</h3>
                    @if(!is_null($sitesQuota))
                        <span class="inline-flex items-center px-3 py-1 bg-indigo-50 border border-indigo-200 text-indigo-800 rounded-full text-sm font-semibold">
                            {{ $sitesUsed }} / {{ $sitesQuota }}
                        </span>
                    @else
                        <span class="text-sm text-gray-500">Obegränsat</span>
                    @endif
                </div>

                <div class="mb-4">
                    <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                        <span>Förbrukning</span>
                        <span>{{ min(100, $sitesPct) }}%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-500 rounded-full transition-all duration-500" style="width: {{ min(100, $sitesPct) }}%"></div>
                    </div>
                </div>

                <p class="text-sm text-gray-600">
                    Uppgradera plan för att kunna lägga till fler sajter.
                </p>
            </div>
        </div>
    </div>
</div>
