<div class="max-w-4xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Min förbrukning ({{ now()->format('Y-m') }})</h1>
        @if($planName || $planStatus)
            <div class="flex items-center gap-2">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full border text-sm
          {{ $planStatus === 'trial' ? 'bg-amber-50 border-amber-200 text-amber-800' : 'bg-indigo-50 border-indigo-200 text-indigo-800' }}">
          <span class="font-medium">{{ $planName ?? 'Plan' }}</span>
          <span class="text-xs px-2 py-0.5 rounded
            {{ $planStatus === 'trial' ? 'bg-amber-100 text-amber-900' : 'bg-indigo-100 text-indigo-900' }}">
            {{ strtoupper($planStatus ?? '-') }}
          </span>
          @if($planStatus === 'trial' && $trialDaysLeft !== null)
                <span class="text-xs text-gray-600">• {{ $trialDaysLeft }} dagar kvar</span>
            @endif
        </span>
                <a href="{{ route('account.upgrade') }}" class="btn btn-xs">Uppgradera plan</a>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="space-y-4">
        @foreach($rows as $r)
            <div class="border rounded p-4 bg-white">
                <div class="flex items-center justify-between text-sm">
                    <div class="font-medium">{{ $r['label'] }}</div>
                    <div class="{{ $r['stop'] ? 'text-rose-700' : ($r['warn'] ? 'text-amber-700' : 'text-gray-600') }}">
                        {{ $r['used'] }} / {{ $r['quota'] }}
                    </div>
                </div>
                <div class="mt-2 w-full h-2 bg-gray-100 rounded">
                    <div class="h-2 rounded {{ $r['stop'] ? 'bg-rose-500' : ($r['warn'] ? 'bg-amber-500' : 'bg-indigo-600') }}"
                         style="width: {{ $r['pct'] }}%"></div>
                </div>
                @if($r['warn'] && !$r['stop'])
                    <div class="mt-2 text-xs text-amber-700">OBS: Du har nått {{ $r['pct'] }}% av kvoten.</div>
                @elseif($r['stop'])
                    <div class="mt-2 text-xs text-rose-700">Kvotgräns uppnådd – uppgradera plan eller begär extraanvändning.</div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="border rounded p-4 bg-white space-y-3">
        <div class="text-sm font-medium">Behöver du mer kapacitet?</div>
        <p class="text-sm text-gray-600">Du kan uppgradera planen eller begära tillfällig extraanvändning för innevarande månad.</p>
        <div class="flex items-center gap-2">
            <a href="{{ route('account.upgrade') }}" class="btn btn-sm">Uppgradera plan</a>
            <div class="flex-1"></div>
            <input type="text" wire:model.defer="overageNote" class="input input-bordered input-sm w-72" placeholder="Meddelande (valfritt)">
            <button class="btn btn-primary btn-sm" wire:click="requestOverage" @disabled($overageRequested)">
            {{ $overageRequested ? 'Begäran skickad' : 'Begär extraanvändning' }}
            </button>
        </div>
    </div>
</div>
