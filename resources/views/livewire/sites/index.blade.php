<div class="max-w-5xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Sajter</h1>
        <a href="{{ route('sites.create') }}" class="btn btn-primary">Lägg till sajt</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($sites as $site)
            <div class="border rounded p-4 space-y-2">
                <div class="font-medium">{{ $site->name }}</div>
                <div class="text-sm text-gray-600 truncate">{{ $site->url }}</div>
                <div class="text-xs">Site Key: <span class="font-mono">{{ $site->public_key }}</span></div>
                <div class="flex gap-2 pt-2">
                    <a href="{{ route('sites.edit', $site) }}" class="btn btn-sm">Redigera</a>
                </div>
            </div>
        @empty
            <p>Inga sajter ännu.</p>
        @endforelse
    </div>
</div>
