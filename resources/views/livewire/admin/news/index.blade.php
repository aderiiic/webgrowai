<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Interna nyheter</h1>
        <a href="{{ route('admin.news.edit') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/></svg>
            Nytt inlägg
        </a>
    </div>

    <div class="flex items-center gap-3">
        <input type="text" wire:model.debounce.300ms="q" placeholder="Sök titel eller tagg..."
               class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl">
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Titel</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Typ</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Taggar</th>
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Publicerad</th>
                <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600">Visningar</th>
                <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600">Åtgärder</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @foreach($items as $n)
                <tr class="{{ $n->is_pinned ? 'bg-amber-50' : '' }} {{ $n->show_popup ? 'bg-blue-50' : '' }}">
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-2">
                            @if($n->is_pinned)
                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] rounded-full bg-amber-100 text-amber-800">Fäst</span>
                            @endif
                            @if($n->show_popup)
                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] rounded-full bg-blue-100 text-blue-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5V3h5v14z"/>
                                    </svg>
                                    Popup
                                </span>
                            @endif
                            <a href="{{ route('admin.news.edit', ['id' => $n->id]) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-700">
                                {{ $n->title }}
                            </a>
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        @php $map=['bugfix'=>'bg-rose-100 text-rose-800','feature'=>'bg-emerald-100 text-emerald-800','info'=>'bg-slate-100 text-slate-800']; @endphp
                        <span class="px-2 py-0.5 rounded-full text-[11px] {{ $map[$n->type] ?? 'bg-slate-100 text-slate-800' }}">{{ ucfirst($n->type) }}</span>
                    </td>
                    <td class="px-4 py-2">
                        @foreach($n->tagsArray as $t)
                            <span class="px-2 py-0.5 rounded-full text-[11px] bg-gray-100 text-gray-700 mr-1">{{ $t }}</span>
                        @endforeach
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-600">
                        {{ $n->published_at?->format('Y-m-d H:i') ?? '—' }}
                    </td>
                    <td class="px-4 py-2 text-center">
                        <div class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs {{ $n->users_seen_count > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-500' }}">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>{{ $n->users_seen_count }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex items-center justify-end gap-2">
                            <button wire:click="pin({{ $n->id }})" class="px-2 py-1 text-xs rounded bg-white border hover:bg-gray-50">
                                {{ $n->is_pinned ? 'Lossa' : 'Fäst' }}
                            </button>
                            <button wire:click="togglePopup({{ $n->id }})" class="px-2 py-1 text-xs rounded {{ $n->show_popup ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-white border hover:bg-gray-50' }}">
                                {{ $n->show_popup ? 'Stäng av popup' : 'Aktivera popup' }}
                            </button>
                            <a href="{{ route('admin.news.edit', ['id' => $n->id]) }}" class="px-2 py-1 text-xs rounded bg-white border hover:bg-gray-50">Redigera</a>
                            <button wire:click="delete({{ $n->id }})" onclick="return confirm('Ta bort?')" class="px-2 py-1 text-xs rounded bg-rose-600 text-white hover:bg-rose-700">Ta bort</button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="p-3">
            {{ $items->links() }}
        </div>
    </div>
</div>
