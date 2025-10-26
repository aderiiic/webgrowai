<div x-data>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1 14a9 9 0 110-18 9 9 0 010 18z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Optimera text (SEO)</h1>
                        <p class="text-sm text-gray-600">Klistra in befintlig text så förbättrar vi SEO och läsbarhet</p>
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
                    <label class="text-lg font-semibold text-gray-900">Titel</label>
                    <input type="text" wire:model.defer="title" class="mt-2 w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500" placeholder="T.ex. Optimera produkttext för X">
                    @error('title') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-lg font-semibold text-gray-900">Befintlig text</label>
                    <textarea rows="10" wire:model.defer="original_text" class="mt-2 w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500" placeholder="Klistra in text här..."></textarea>
                    @error('original_text') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    <p class="mt-2 text-xs text-gray-500">Kostnad beräknas på textmängd.</p>
                </div>

                <div>
                    <label class="text-lg font-semibold text-gray-900">Nyckelord (valfritt)</label>
                    <input type="text" wire:model.defer="keywords" class="mt-2 w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500" placeholder="ex: dam sneakers, vita skor, bekväma">
                </div>

                <div>
                    <label class="text-lg font-semibold text-gray-900">Varumärkesröst (valfritt)</label>
                    <input type="text" wire:model.defer="brand_voice" class="mt-2 w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500" placeholder="ex: professionell men vänlig">
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="text-lg font-semibold text-gray-900">Språk</label>
                        <select wire:model="language" class="mt-2 w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            <option value="sv_SE">Svenska</option>
                            <option value="en_US">Engelska</option>
                            <option value="de_DE">Tyska</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-lg font-semibold text-gray-900">Hemsida</label>
                        <select wire:model="site_id" class="mt-2 w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
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
                    <button wire:click="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700">
                        Optimera text
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
