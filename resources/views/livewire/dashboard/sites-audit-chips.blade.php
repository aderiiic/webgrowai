<div wire:poll.20s="refreshLatest" class="border rounded-lg p-4 bg-white shadow-sm">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <h2 class="text-lg font-semibold">Sajtöversikt – senaste audits</h2>
        @if(session('success'))
            <div class="text-sm text-green-700 bg-green-50 border border-green-200 rounded px-3 py-1">
                {{ session('success') }}
            </div>
        @endif
    </div>

    @if($sites->isEmpty())
        <div class="mt-2 text-sm text-gray-600">Inga sajter ännu.</div>
    @else
        <div class="mt-4 flex flex-wrap gap-3">
            @foreach($sites as $site)
                @php
                    $latest = $latestBySite[$site->id] ?? null;
                    $perf = $latest['lighthouse_performance'] ?? null;
                    $chipClasses = 'inline-flex items-center gap-2 border rounded-full px-3 py-1 text-xs font-medium ' . $this->performanceColor($perf);
                @endphp

                <div class="flex items-center gap-2">
          <span class="{{ $chipClasses }}">
            <span class="truncate max-w-[160px]" title="{{ $site->name }}">{{ $site->name }}</span>
            <span class="text-gray-400">•</span>
            <span>Perf: <span class="font-semibold">{{ $perf ?? '—' }}</span></span>
            @if($latest)
                  <span class="text-gray-400">•</span>
                  <span title="{{ \Illuminate\Support\Carbon::parse($latest['created_at'])->toDateTimeString() }}">
                {{ \Illuminate\Support\Carbon::parse($latest['created_at'])->diffForHumans() }}
              </span>
              @endif
          </span>

                    <button class="btn btn-xs" wire:click="runAudit({{ $site->id }})">Kör</button>

                    @if($latest)
                        <a class="btn btn-xs" href="{{ route('seo.audit.detail', $latest['id']) }}">Detaljer</a>
                    @endif

                    <a class="btn btn-xs" href="{{ route('sites.wordpress', $site) }}">WP</a>
                </div>
            @endforeach
        </div>
    @endif
</div>
