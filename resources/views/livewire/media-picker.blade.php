<div x-data="{ open: @entangle('open') }">
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/40" @click="open=false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold">Bildbank</h3>
                <button class="text-gray-500 hover:text-gray-700" @click="open=false">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>

            <div class="p-4 border-b">
                <div class="flex gap-2">
                    <input type="text"
                           class="flex-1 px-3 py-2 border rounded-lg"
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
                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex-1 overflow-y-auto p-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3" wire:loading.class="opacity-50" wire:target="upload">
                    @forelse($items as $it)
                        <button type="button"
                                class="group relative rounded-lg overflow-hidden border-2 hover:ring-2 hover:ring-indigo-500 aspect-square @if($selectedId == $it['id']) border-indigo-500 ring-2 ring-indigo-500 @else border-gray-200 @endif"
                                wire:click="pick({{ $it['id'] }})">
                            <img class="w-full h-full object-cover"
                                 src="{{ route('assets.thumb', $it['id']) }}"
                                 alt="{{ $it['original_name'] ?? 'Bild' }}">

                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-2">
                                <div class="text-white text-xs">
                                    <div class="font-medium">#{{ $it['id'] }}</div>
                                    @if(!empty($it['last_used_at']))
                                        <div class="opacity-80">{{ \Illuminate\Support\Carbon::parse($it['last_used_at'])->diffForHumans() }}</div>
                                    @endif
                                </div>
                            </div>

                            @if($selectedId == $it['id'])
                                <div class="absolute top-2 right-2 bg-indigo-500 text-white rounded-full p-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                        </button>
                    @empty
                        <div class="col-span-full text-center text-gray-500 py-8">Inga bilder ännu</div>
                    @endforelse
                </div>
            </div>

            <div class="p-4 border-t bg-gray-50">
                <div class="flex justify-between items-center mb-3">
                    <div class="text-sm text-gray-600">
                        @if($selectedId > 0)
                            Vald bild: #{{ $selectedId }}
                        @else
                            Ingen bild vald
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <button class="px-3 py-2 border rounded-lg text-sm hover:bg-gray-50" wire:click="prev">← Föregående</button>
                        <button class="px-3 py-2 border rounded-lg text-sm hover:bg-gray-50" wire:click="next">Nästa →</button>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors @if($selectedId <= 0) opacity-50 cursor-not-allowed @endif"
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
</div>
