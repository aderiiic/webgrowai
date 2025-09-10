<div class="bg-white border rounded-2xl shadow-sm sticky top-4">
    <div class="p-4 border-b flex items-center justify-between">
        <h4 class="font-semibold text-gray-900">Detaljer</h4>
        <button class="text-sm text-gray-500 hover:text-gray-700" @click="openPanel=false" wire:click="clearSelection">Stäng</button>
    </div>

    @if($selected)
        @php
            $badge = match($selected['status']) {
                'published'  => ['bg'=>'bg-emerald-50','text'=>'text-emerald-700','label'=>'Publicerad'],
                'processing' => ['bg'=>'bg-amber-50','text'=>'text-amber-700','label'=>'Pågår'],
                'queued','scheduled' => ['bg'=>'bg-sky-50','text'=>'text-sky-700','label'=>$selected['status']==='scheduled'?'Schemalagd':'Köad'],
                'failed'     => ['bg'=>'bg-rose-50','text'=>'text-rose-700','label'=>'Misslyckad'],
                'cancelled'  => ['bg'=>'bg-gray-100','text'=>'text-gray-700','label'=>'Avbruten'],
                default      => ['bg'=>'bg-gray-50','text'=>'text-gray-700','label'=>ucfirst($selected['status'])],
            };
        @endphp

        <div class="p-4 space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase text-gray-500">Status</span>
                <span class="inline-flex items-center px-2 py-1 text-xs rounded-full {{ $badge['bg'] }} {{ $badge['text'] }}">{{ $badge['label'] }}</span>
            </div>

            <div>
                <div class="text-sm font-medium text-gray-900">{{ $selected['title'] }}</div>
                <div class="text-xs text-gray-600">
                    Kanal: {{ ucfirst($selected['target']) }}
                    <span class="mx-1 text-gray-300">•</span>
                    Sajt: {{ $selected['site'] ?: '—' }}
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="text-xs text-gray-500">Planerad tid</div>
                    <div class="font-medium text-gray-900">
                        {{ $selected['scheduled_at'] ? \Illuminate\Support\Carbon::parse($selected['scheduled_at'])->format('Y-m-d H:i') : '—' }}
                    </div>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="text-xs text-gray-500">AI‑innehåll</div>
                    <a href="{{ route('ai.detail', $selected['ai_content_id']) }}" class="font-medium text-indigo-600 hover:underline">Öppna</a>
                </div>
            </div>

            @if(!empty($selected['external_url']))
                <div class="text-sm">
                    <div class="text-xs text-gray-500 mb-1">Extern länk</div>
                    <a href="{{ $selected['external_url'] }}" target="_blank" class="text-indigo-600 hover:underline break-all">{{ $selected['external_url'] }}</a>
                </div>
            @endif

            @if(!empty($selected['message']))
                <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-900">
                    {{ $selected['message'] }}
                </div>
            @endif

            <!-- Ändra tid / Avbryt -->
            <div class="pt-2 border-t space-y-3">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Ny tid</label>
                    <input type="datetime-local"
                           wire:model.defer="rescheduleAt"
                           class="w-full px-3 py-2 border rounded-lg text-sm" />
                    @error('rescheduleAt')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-2">
                    @php
                        $canReschedule = $selected && in_array($selected['status'], ['queued','scheduled','processing']);
                        $canCancel = $selected && in_array($selected['status'], ['queued','scheduled','processing']);
                    @endphp

                    <button
                        @if(!$canReschedule) disabled @endif
                    wire:click="reschedulePublication({{ (int)$selected['id'] }})"
                        class="px-3 py-2 rounded-lg {{ $canReschedule ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                        Ändra tid
                    </button>

                    <button
                        @if(!$canCancel) disabled @endif
                    wire:click="cancelPublication({{ (int)$selected['id'] }})"
                        onclick="return confirm('Avbryt denna publicering?')"
                        class="px-3 py-2 rounded-lg {{ $canCancel ? 'bg-rose-600 text-white hover:bg-rose-700' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                        Avbryt
                    </button>
                </div>

                <p class="text-xs text-gray-500">
                    Ändringar av tid respekteras av köade jobb. Om en process redan körs kan publiceringen hinna gå ut.
                </p>
            </div>

            <!-- Snabbplanera nytt -->
            <div class="pt-4 border-t space-y-3">
                <h5 class="text-sm font-semibold text-gray-900">Snabbplanera nytt</h5>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Innehåll</label>
                        <select wire:model.defer="quickContentId" class="w-full px-3 py-2 border rounded-lg text-sm">
                            <option value="">Välj färdigt innehåll…</option>
                            @foreach(($readyContents ?? []) as $rc)
                                <option value="{{ $rc['id'] }}">#{{ $rc['id'] }} — {{ $rc['title'] }}</option>
                            @endforeach
                        </select>
                        @error('quickContentId')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Kanal</label>
                            <select wire:model.defer="quickTarget" class="w-full px-3 py-2 border rounded-lg text-sm">
                                <option value="facebook">Facebook</option>
                                <option value="instagram">Instagram</option>
                                <option value="linkedin">LinkedIn</option>
                                <option value="wp">WordPress</option>
                                <option value="shopify">Shopify</option>
                            </select>
                            @error('quickTarget')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Tid</label>
                            <input type="datetime-local" wire:model.defer="quickScheduleAt" class="w-full px-3 py-2 border rounded-lg text-sm" />
                            @error('quickScheduleAt')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button wire:click="createQuickPublication" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm">
                            Skapa schemalagd
                        </button>
                    </div>
                    <p class="text-xs text-gray-500">Använd AI‑texten som grund. Bilder och finjustering kan läggas till i detaljsidan.</p>
                </div>
            </div>
        </div>
    @else
        <div class="p-6 text-center text-gray-500">Välj en post i listan för att se detaljer.</div>
    @endif
</div>
