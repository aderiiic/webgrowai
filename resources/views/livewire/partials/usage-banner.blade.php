@if(!empty($alerts))
    <div class="max-w-7xl mx-auto px-4 py-3">
        @foreach($alerts as $a)
            <div class="mb-2 rounded border px-3 py-2 text-sm {{ $a['type'] === 'stop' ? 'bg-rose-50 border-rose-200 text-rose-800' : 'bg-amber-50 border-amber-200 text-amber-800' }}">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <strong>{{ $a['label'] }}</strong> — {{ $a['used'] }} / {{ $a['quota'] }} ({{ $a['pct'] }}%)
                        @if($a['type'] === 'stop')
                            • Kvotgräns uppnådd.
                        @else
                            • Du närmar dig gränsen.
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('account.upgrade') }}" class="btn btn-xs {{ $a['type'] === 'stop' ? 'btn-primary' : '' }}">Uppgradera plan</a>
                        <a href="{{ route('account.usage') }}" class="btn btn-xs">Visa förbrukning</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
