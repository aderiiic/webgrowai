<div>
    @if($status === 'trial' && $daysLeft !== null)
        <div class="mb-4">
            <div class="flex items-center justify-between p-3 rounded-xl border bg-gradient-to-r from-amber-50 to-orange-50 border-amber-200/60">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm">
                        <span class="font-semibold text-amber-900">Provperiod</span>
                        <span class="text-amber-800">— {{ $daysLeft }} dagar kvar</span>
                        @if($planName)
                            <span class="text-amber-700">• Plan: {{ $planName }}</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <a href="{{ route('billing.pricing') }}"
                       class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">
                        Uppgradera nu
                    </a>
                    <a href="{{ route('billing.portal') }}"
                       class="px-3 py-1.5 rounded-lg bg-white text-amber-800 border border-amber-300 text-sm hover:bg-amber-100">
                        Hantera
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
