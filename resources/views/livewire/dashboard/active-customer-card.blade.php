<div class="border rounded-lg p-4 bg-white shadow-sm">
    <div class="flex items-center justify-between flex-wrap gap-2">
        <h2 class="text-lg font-semibold">Aktiv kund</h2>

        @if($customer)
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-2 border rounded-full px-3 py-1 text-xs bg-indigo-50 text-indigo-700 border-indigo-200">
                  Genereringar ({{ now()->format('Y-m') }}): <span class="font-semibold">{{ $monthGenerateTotal }}</span>
                </span>
                <span class="inline-flex items-center gap-2 border rounded-full px-3 py-1 text-xs bg-emerald-50 text-emerald-700 border-emerald-200">
                  Publicerade till WP: <span class="font-semibold">{{ $monthPublishTotal }}</span>
                </span>
                <span class="inline-flex items-center gap-2 border rounded-full px-3 py-1 text-xs bg-amber-50 text-amber-700 border-amber-200">
                  Mailchimp-kampanjer: <span class="font-semibold">{{ $monthMailchimpTotal }}</span>
                </span>
            </div>
        @endif
    </div>

    @if($customer)
        <div class="mt-2">
            <div class="flex items-center gap-2">
        <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 text-indigo-700 px-3 py-1 text-xs font-medium border border-indigo-200">
          {{ $customer->name }}
        </span>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="rounded border p-3">
                    <div class="text-sm text-gray-500">Sajter</div>
                    <div class="text-2xl font-semibold">{{ $sites->count() }}</div>
                </div>
                <div class="rounded border p-3">
                    <div class="text-sm text-gray-500">Status</div>
                    <div class="text-base font-medium">{{ ucfirst($customer->status ?? 'active') }}</div>
                </div>
                <div class="rounded border p-3">
                    <div class="text-sm text-gray-500">Kontakt</div>
                    <div class="text-base">{{ $customer->contact_email ?? '—' }}</div>
                </div>
            </div>

            @if($sites->isNotEmpty())
                <div class="mt-4">
                    <div class="text-sm text-gray-600 mb-2">Sajter</div>
                    <ul class="space-y-1">
                        @foreach($sites->take(5) as $site)
                            <li class="flex items-center justify-between text-sm">
                                <div class="truncate">
                                    <span class="font-medium">{{ $site->name }}</span>
                                    <span class="text-gray-500">—</span>
                                    <a href="{{ $site->url }}" class="text-indigo-600 hover:underline" target="_blank" rel="noopener">
                                        {{ $site->url }}
                                    </a>
                                </div>
                                <a href="{{ route('sites.edit', $site) }}" class="text-indigo-600 hover:underline shrink-0">Redigera</a>
                            </li>
                        @endforeach
                    </ul>
                    @if($sites->count() > 5)
                        <div class="mt-2 text-xs text-gray-500">…och {{ $sites->count() - 5 }} till</div>
                    @endif
                </div>
            @endif

            <div class="mt-5 flex flex-wrap gap-2">
                <a href="{{ route('sites.index') }}" class="btn btn-sm btn-primary">Hantera sajter</a>
                <a href="{{ route('sites.create') }}" class="btn btn-sm">Lägg till sajt</a>
                <a href="{{ request()->url() }}?customer=" class="btn btn-sm">Byt kund</a>
            </div>
        </div>
    @else
        <div class="mt-2 text-sm text-gray-600">
            Ingen aktiv kund vald ännu. Välj en via kundväljaren uppe i menyn.
        </div>
    @endif
</div>
