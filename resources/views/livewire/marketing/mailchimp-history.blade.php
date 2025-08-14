<div class="max-w-5xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Mailchimp – Kampanjhistorik</h1>
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600">Status</label>
            <select wire:model.live="statusFilter" class="select select-bordered select-sm">
                <option value="">Alla</option>
                <option value="save">Utkast</option>
                <option value="scheduled">Schemalagd</option>
                <option value="sending">Skickas</option>
                <option value="sent">Skickad</option>
                <option value="paused">Pausad</option>
                <option value="cancel">Avbruten</option>
            </select>
        </div>
    </div>

    <div class="space-y-3">
        @forelse($campaigns as $c)
            <div class="border rounded p-3">
                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        <div class="font-medium">{{ $c['settings']['subject_line'] ?? '(utan ämne)' }}</div>
                        <div class="text-gray-600">Status: {{ ucfirst($c['status'] ?? '—') }} • Skapad: {{ $c['create_time'] ?? '—' }}</div>
                        <div class="text-xs text-gray-500">ID: {{ $c['id'] ?? '—' }} • Lista: {{ $c['recipients']['list_id'] ?? '—' }}</div>
                    </div>
                    @if(!empty($c['archive_url']))
                        <a href="{{ $c['archive_url'] }}" class="btn btn-sm" target="_blank" rel="noopener">Öppna i Mailchimp</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-sm text-gray-600">Inga kampanjer hittades.</div>
        @endforelse
    </div>
</div>
