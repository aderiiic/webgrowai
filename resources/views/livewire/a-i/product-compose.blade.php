<div x-data>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Skapa produkttext</h1>
                        <p class="text-sm text-gray-600">Säljande och SEO‑vänlig beskrivning</p>
                    </div>
                </div>
            </div>

            @if($errors->has('general'))
                <div class="rounded-md bg-red-50 border border-red-200 p-3 text-red-800 text-sm">
                    {{ $errors->first('general') }}
                </div>
            @endif

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-8 space-y-8">
                <div>
                    <label class="text-lg font-semibold text-gray-900">Produkttitel</label>
                    <input type="text" wire:model.defer="product_title" class="mt-2 w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500" placeholder="T.ex. 'Sneaker X – vit'">
                    @error('product_title') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-lg font-semibold text-gray-900">Produktbeskrivning (valfritt)</label>
                    <textarea rows="5" wire:model.defer="product_description" class="mt-2 w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500" placeholder="Kort beskrivning, material, användning..."></textarea>
                    @error('product_description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-lg font-semibold text-gray-900">Egenskaper (features)</label>
                        <textarea rows="5" wire:model.defer="features" class="mt-2 w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500" placeholder="En per rad eller kommaseparerat"></textarea>
                        @error('features') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-lg font-semibold text-gray-900">USPs (valfritt)</label>
                        <textarea rows="5" wire:model.defer="usps" class="mt-2 w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500" placeholder="En per rad eller kommaseparerat"></textarea>
                        @error('usps') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="text-lg font-semibold text-gray-900">Målgrupp (valfritt)</label>
                    <input type="text" wire:model.defer="audience" class="mt-2 w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500" placeholder="ex: Modeintresserade kvinnor 18–35">
                </div>

                <div>
                    <label class="text-lg font-semibold text-gray-900">Varumärkesröst (valfritt)</label>
                    <input type="text" wire:model.defer="brand_voice" class="mt-2 w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500" placeholder="ex: inspirerande, trygg, premium">
                </div>

                <div>
                    <label class="text-lg font-semibold text-gray-900">Längd</label>
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" wire:model="length" value="short" class="sr-only peer">
                            <div class="p-4 bg-white border-2 border-gray-200 rounded-xl peer-checked:border-amber-500 peer-checked:bg-amber-50">
                                <div class="font-medium text-gray-900">Kort</div>
                                <div class="text-xs text-gray-600 mt-1">100–200 ord</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" wire:model="length" value="optimal" class="sr-only peer">
                            <div class="p-4 bg-white border-2 border-gray-200 rounded-xl peer-checked:border-amber-500 peer-checked:bg-amber-50">
                                <div class="font-medium text-gray-900">Optimal</div>
                                <div class="text-xs text-gray-600 mt-1">200–400 ord</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" wire:model="length" value="long" class="sr-only peer">
                            <div class="p-4 bg-white border-2 border-gray-200 rounded-xl peer-checked:border-amber-500 peer-checked:bg-amber-50">
                                <div class="font-medium text-gray-900">Lång</div>
                                <div class="text-xs text-gray-600 mt-1">400–700 ord</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="text-lg font-semibold text-gray-900">Språk</label>
                        <select wire:model="language" class="mt-2 w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500">
                            <option value="sv_SE">Svenska</option>
                            <option value="en_US">Engelska</option>
                            <option value="de_DE">Tyska</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-lg font-semibold text-gray-900">Hemsida</label>
                        <select wire:model="site_id" class="mt-2 w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500">
                            <option value="">Ingen hemsida vald</option>
                            @foreach($sites as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('ai.select-type') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50">
                        Avbryt
                    </a>
                    <button wire:click="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-semibold rounded-xl hover:from-amber-600 hover:to-orange-700">
                        Skapa produkttext
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
