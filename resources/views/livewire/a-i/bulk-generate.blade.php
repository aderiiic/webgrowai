<div>
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Massgenerering av AI-innehåll</h1>
                <p class="mt-2 text-gray-600">Skapa flera versioner av samma text med olika variabler</p>
            </div>
            <a href="{{ route('ai.list') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                ← Tillbaka
            </a>
        </div>

        @if($errors->has('general'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-sm text-red-800">{{ $errors->first('general') }}</p>
            </div>
        @endif

        <form wire:submit="submit" class="space-y-6">
            <!-- Template Text -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <label class="block text-sm font-medium text-gray-900 mb-2">
                    Textmall <span class="text-red-500">*</span>
                </label>
                <p class="text-sm text-gray-600 mb-3">
                    Använd <code class="bg-gray-100 px-2 py-0.5 rounded">@{{variabel}}</code> för platshållare
                </p>
                <textarea
                    wire:model.blur="template_text"
                    rows="4"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Besök vår butik i @{{stad}} för att upptäcka @{{produkt}}!"
                ></textarea>
                @error('template_text')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Variables Input -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <label class="block text-sm font-medium text-gray-900 mb-2">
                    Variabeldata (CSV-format) <span class="text-red-500">*</span>
                </label>
                <p class="text-sm text-gray-600 mb-3">
                    Klistra in data med första raden som kolumnnamn (tab- eller kommaseparerad)
                </p>
                <textarea
                    wire:model.blur="variables_input"
                    rows="8"
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 font-mono text-sm"
                    placeholder="stad	produkt&#10;Malmö	Soffor&#10;Göteborg	Bord&#10;Uppsala	Stolar"
                ></textarea>
                @error('variables_input')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Preview -->
                @if($previewText)
                    <div class="mt-4 p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-indigo-200">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Förhandsvisning (första raden):</h4>
                        <p class="text-gray-800">{{ $previewText }}</p>
                    </div>
                @endif

                @if($estimatedCount > 0)
                    <div class="mt-4 flex items-center space-x-4 text-sm">
                        <span class="text-gray-600">
                            <strong class="text-gray-900">{{ $estimatedCount }}</strong> texter kommer att genereras
                        </span>
                        <span class="text-gray-600">
                            Kostnad: <strong class="text-indigo-600">{{ $estimatedCost }}</strong> krediter
                        </span>
                    </div>
                @endif
            </div>

            <!-- Content Type & Settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Content Type -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <label class="block text-sm font-medium text-gray-900 mb-3">
                        Typ av innehåll
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="content_type" value="social" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Sociala medier</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="content_type" value="blog" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Blogg/Artiklar</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="content_type" value="newsletter" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Nyhetsbrev</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="content_type" value="multi" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Generisk/Flera kanaler</span>
                        </label>
                    </div>
                </div>

                <!-- Tone -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <label class="block text-sm font-medium text-gray-900 mb-3">
                        Textlängd
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="tone" value="short" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Kort (10 krediter/text)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="tone" value="long" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Lång (50 krediter/text)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Site Selection -->
            @if($sites->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Webbplats (valfritt)
                    </label>
                    <select wire:model="site_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Ingen webbplats</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}">{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Plan Limit Info -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-amber-900">Din plan tillåter max {{ $maxTexts }} texter per batch</h4>
                        <p class="mt-1 text-sm text-amber-700">
                            Uppgradera till Growth (100 texter) eller Pro (200 texter) för större volymer.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('ai.list') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Avbryt
                </a>
                <button
                    type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    @if($estimatedCount === 0 || $estimatedCount > $maxTexts) disabled @endif
                >
                    Generera {{ $estimatedCount }} texter
                </button>
            </div>
        </form>
    </div>
</div>
