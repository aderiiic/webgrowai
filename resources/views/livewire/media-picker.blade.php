<div x-data="{ open: @entangle('open') }">
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/40" @click="open=false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">Bildbank</h3>
                <button class="text-gray-500 hover:text-gray-700" @click="open=false">Stäng</button>
            </div>

            <div class="flex gap-2 mb-3">
                <input type="text"
                       class="w-full px-3 py-2 border rounded-lg"
                       placeholder="Sök..."
                       wire:model.live.debounce.300ms="search">

                <label class="px-3 py-2 border rounded-lg bg-gray-50 cursor-pointer relative overflow-hidden">
                    <span wire:loading.remove wire:target="upload">Ladda upp</span>
                    <span class="text-gray-600" wire:loading wire:target="upload">Laddar upp…</span>
                    <input type="file"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                           accept="image/*"
                           wire:model="upload">
                </label>
            </div>

            @error('upload')
            <div class="mb-2 text-sm text-red-600">{{ $message }}</div>
            @enderror

            <div class="grid grid-cols-4 gap-3 max-h-[420px] overflow-auto" wire:loading.class="opacity-50" wire:target="upload">
                @forelse($items as $it)
                    <button type="button"
                            class="group relative rounded-lg overflow-hidden border-2 hover:ring-2 hover:ring-indigo-500 @if($selectedId == $it['id']) border-indigo-500 ring-2 ring-indigo-500 @else border-gray-200 @endif"
                            wire:click="pick({{ $it['id'] }})">
                        <img class="w-full h-28 object-cover"
                             src="{{ route('assets.thumb', $it['id']) }}"
                             alt="{{ $it['original_name'] ?? 'Bild' }}">
                        <div class="absolute bottom-0 inset-x-0 bg-black/50 text-white text-xs px-2 py-1 flex justify-between">
                            <span>#{{ $it['id'] }}</span>
                            @if(!empty($it['last_used_at']))
                                <span>Senast: {{ \Illuminate\Support\Carbon::parse($it['last_used_at'])->diffForHumans() }}</span>
                            @endif
                        </div>
                        @if($selectedId == $it['id'])
                            <div class="absolute top-1 right-1 bg-indigo-500 text-white rounded-full p-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                    </button>
                @empty
                    <div class="col-span-4 text-center text-gray-500 py-8">Inga bilder ännu</div>
                @endforelse
            </div>

            <div class="mt-3 flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    @if($selectedId > 0)
                        Vald bild: #{{ $selectedId }}
                    @else
                        Ingen bild vald
                    @endif
                </div>
                <div class="flex gap-2">
                    <button class="px-3 py-2 border rounded-lg" wire:click="prev">Föregående</button>
                    <button class="px-3 py-2 border rounded-lg" wire:click="next">Nästa</button>
                </div>
            </div>

            <!-- Ta bort Alpine.js @click från denna knapp -->
            <div class="mt-3 flex justify-end">
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 @if($selectedId <= 0) opacity-50 cursor-not-allowed @endif"
                        @if($selectedId <= 0) disabled @endif
                        wire:click="confirmSelection">
                    @if($selectedId > 0)
                        Använd vald bild
                    @else
                        Stäng
                    @endif
                </button>
            </div>
        </div>
    </div>
</div>
