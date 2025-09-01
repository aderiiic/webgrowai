
<div x-data="{ showConfirmation: false }">
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Skapa nyhetsbrev
            </h1>
            <div class="flex space-x-3">
                <a href="{{ route('marketing.mailchimp.history') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Historia
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Tillbaka
                </a>
            </div>
        </div>

        <!-- Success/Error notifications -->
        @if(session('success'))
            <div class="p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-xl">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Creation method selector -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- AI Generated -->
                <div class="relative group">
                    <input type="radio" wire:model.live="creationMode" value="ai" id="mode-ai" class="sr-only">
                    <label for="mode-ai" class="block p-6 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl border-2 border-transparent cursor-pointer hover:border-orange-300 transition-all duration-200 {{ $creationMode === 'ai' ? 'border-orange-500 bg-gradient-to-br from-orange-100 to-amber-100' : '' }}">
                        <div class="flex flex-col items-center text-center space-y-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">AI-genererat</h3>
                                <p class="text-sm text-gray-600">Låt AI skapa ett professionellt nyhetsbrev baserat på ditt senaste innehåll</p>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Mailchimp Template Builder -->
                <div class="relative group">
                    <input type="radio" wire:model.live="creationMode" value="template" id="mode-template" class="sr-only">
                    <label for="mode-template" class="block p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border-2 border-transparent cursor-pointer hover:border-blue-300 transition-all duration-200 {{ $creationMode === 'template' ? 'border-blue-500 bg-gradient-to-br from-blue-100 to-indigo-100' : '' }}">
                        <div class="flex flex-col items-center text-center space-y-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Template Builder</h3>
                                <p class="text-sm text-gray-600">Använd Mailchimp's visuella editor för att bygga nyhetsbrev från grunden</p>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Custom HTML -->
                <div class="relative group">
                    <input type="radio" wire:model.live="creationMode" value="custom" id="mode-custom" class="sr-only">
                    <label for="mode-custom" class="block p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border-2 border-transparent cursor-pointer hover:border-purple-300 transition-all duration-200 {{ $creationMode === 'custom' ? 'border-purple-500 bg-gradient-to-br from-purple-100 to-pink-100' : '' }}">
                        <div class="flex flex-col items-center text-center space-y-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Anpassad HTML</h3>
                                <p class="text-sm text-gray-600">Skapa ditt eget nyhetsbrev med HTML och CSS</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- AI Mode Content -->
        @if($creationMode === 'ai')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic settings -->
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                            </svg>
                            Grundläggande inställningar
                        </h3>

                        <div class="space-y-6">
                            <!-- Subject -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ämnesrad</label>
                                <input type="text" wire:model.defer="subject" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200" placeholder="Nyheter & tips för veckan">
                                @error('subject')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <!-- Send time -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sändningstid</label>
                                    <input type="datetime-local" wire:model.defer="sendAt" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                                    <p class="mt-1 text-xs text-gray-500">Lämna tomt för omedelbar sändning</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Antal artiklar</label>
                                    <input type="number" min="1" max="10" wire:model.defer="numItems" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Selection -->
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Extra innehåll att inkludera
                        </h3>

                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" wire:model.live="includeWebsiteContent" id="include-website" class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500 mt-1">
                                <div>
                                    <label for="include-website" class="text-sm font-medium text-gray-700">Inkludera senaste innehåll från webbsidan</label>
                                    <p class="text-xs text-gray-500 mt-1">Hämtar automatiskt senaste blogginlägg eller nyheter från din webbsida</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <input type="checkbox" wire:model.live="includeProducts" id="include-products" class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500 mt-1">
                                <div>
                                    <label for="include-products" class="text-sm font-medium text-gray-700">💼 Inkludera dina tjänster/produkter</label>
                                    <p class="text-xs text-gray-500 mt-1">AI analyserar ditt innehåll och skapar smart produktpresentationer som matchar din verksamhet</p>
                                </div>
                            </div>

                            @if(config('features.image_generation') === true)
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" wire:model.live="includeCustomImages" id="include-images" class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500 mt-1">
                                    <div>
                                        <label for="include-images" class="text-sm font-medium text-gray-700">Lägg till anpassade bilder</label>
                                        <p class="text-xs text-gray-500 mt-1">Ladda upp egna bilder som ska inkluderas i nyhetsbrevet</p>
                                    </div>
                                </div>
                            @endif
                        </div>


                        @if($includeProducts)
                            <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-blue-900 mb-1">Smart produktpresentation</h4>
                                        <p class="text-sm text-blue-800">AI kommer att analysera dina artiklar för att förstå din verksamhet och skapa relevanta produktbeskrivningar med priser och call-to-action som leder till din webbsida.</p>
                                    </div>
                                </div>

                                <!-- Produkthantering -->
                                <div class="mt-4 space-y-4">
                                    <div class="flex items-center justify-between">
                                        <h5 class="text-sm font-semibold text-gray-900">Hantera dina produkter/tjänster</h5>
                                        <button wire:click="toggleProductManager" class="px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            {{ $showProductManager ? 'Dölj' : 'Visa hantering' }}
                                        </button>
                                    </div>

                                    @if($showProductManager)
                                        <!-- Befintliga produkter -->
                                        @if($availableProducts->count() > 0)
                                            <div class="space-y-2">
                                                <h6 class="text-xs font-medium text-gray-700 uppercase tracking-wide">Dina produkter/tjänster</h6>
                                                @foreach($availableProducts as $product)
                                                    <div class="flex items-start space-x-3 p-3 bg-white rounded-lg border border-gray-200">
                                                        <input type="checkbox" wire:model="selectedProducts" value="{{ $product->id }}" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1">
                                                        <div class="flex-1 min-w-0">
                                                            <h6 class="text-sm font-medium text-gray-900">{{ $product->title }}</h6>
                                                            @if($product->description)
                                                                <p class="text-xs text-gray-600 mt-1">{{ Str::limit($product->description, 80) }}</p>
                                                            @endif
                                                            <div class="flex items-center space-x-2 mt-2 text-xs text-gray-500">
                                                                <span>🔗 {{ Str::limit($product->url, 40) }}</span>
                                                                @if($product->price)
                                                                    <span>•</span>
                                                                    <span>💰 {{ $product->price }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <button wire:click="removeProduct({{ $product->id }})" class="text-red-600 hover:text-red-800 transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Lägg till ny produkt -->
                                        <div class="p-4 bg-gray-50 rounded-lg">
                                            <h6 class="text-sm font-semibold text-gray-900 mb-3">Lägg till ny produkt/tjänst</h6>
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Titel</label>
                                                    <input type="text" wire:model.defer="newProductTitle" class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="T.ex. Webbdesign, Konsultation, Produkt...">
                                                    @error('newProductTitle')
                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Beskrivning (valfritt)</label>
                                                    <textarea wire:model.defer="newProductDescription" rows="2" class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Kort beskrivning av produkten/tjänsten"></textarea>
                                                    @error('newProductDescription')
                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Länk till produktsida</label>
                                                    <input type="url" wire:model.defer="newProductUrl" class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="https://dinwebbsida.se/produkt">
                                                    @error('newProductUrl')
                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Pris (valfritt)</label>
                                                        <input type="text" wire:model.defer="newProductPrice" class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Från 5 000 kr">
                                                        @error('newProductPrice')
                                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Bild-URL (valfritt)</label>
                                                        <input type="url" wire:model.defer="newProductImage" class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="https://...">
                                                        @error('newProductImage')
                                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <button wire:click="addProduct" class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                                    Lägg till produkt
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if(config('features.image_generation') === true)
                            @if($includeCustomImages)
                                <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Ladda upp bilder</label>
                                    <input type="file" wire:model="images" accept="image/*" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF upp till 10MB vardera</p>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Test Email -->
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Testa nyhetsbrevet först
                        </h3>

                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-4 mb-4">
                            <p class="text-sm text-purple-800">
                                <strong>💡 Smart tips:</strong> Skicka alltid ett testmejl först för att se hur ditt nyhetsbrev kommer att se ut. Testmejlet genereras med samma inställningar som du valt ovan.
                            </p>
                        </div>

                        <div class="flex items-end space-x-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">E-postadress för test</label>
                                <input type="email" wire:model.defer="testEmail" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200" placeholder="test@exempel.se">
                            </div>
                            <button wire:click="sendTestEmail" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Skicka test
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Process flow -->
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            Så fungerar det
                        </h3>

                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">1</div>
                                <div>
                                    <h4 class="font-medium text-gray-900 text-sm">Samla innehåll</h4>
                                    <p class="text-xs text-gray-600">Vi hämtar dina senaste AI-artiklar och valt innehåll</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">2</div>
                                <div>
                                    <h4 class="font-medium text-gray-900 text-sm">AI-bearbetning</h4>
                                    <p class="text-xs text-gray-600">AI skapar professionella sammanfattningar och produktpresentationer</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">3</div>
                                <div>
                                    <h4 class="font-medium text-gray-900 text-sm">Skicka eller schemalägg</h4>
                                    <p class="text-xs text-gray-600">Kampanjen skapas i Mailchimp med dina länkar</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="space-y-4">
                        <button @click="showConfirmation = true" class="w-full inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold rounded-xl hover:from-orange-700 hover:to-red-700 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Generera & skicka kampanj
                        </button>

                        <div class="text-xs text-gray-500 text-center space-y-1">
                            <div class="flex items-center justify-center">
                                <svg class="w-3 h-3 mr-1 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                AI skapar innehållet automatiskt
                            </div>
                            <div class="flex items-center justify-center">
                                <svg class="w-3 h-3 mr-1 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Länkar automatiskt till din webbsida
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Template Mode Content -->
        @if($creationMode === 'template')
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-8">
                <div class="text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-200 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                        </svg>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Mailchimp Template Builder</h3>
                    <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
                        Använd Mailchimp's kraftfulla visuella editor för att skapa professionella nyhetsbrev från grunden.
                        Du kommer att dirigeras till Mailchimp där du kan använda deras drag-and-drop editor.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mb-4 mx-auto">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Drag & Drop Editor</h4>
                            <p class="text-sm text-gray-600">Enkelt att använda med fördefinierade block och komponenter</p>
                        </div>

                        <div class="p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-200/50">
                            <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mb-4 mx-auto">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Responsiv Design</h4>
                            <p class="text-sm text-gray-600">Automatiskt anpassat för alla enheter och e-postklienter</p>
                        </div>
                    </div>

                    <a href="https://{{ config('services.mailchimp.subdomain', 'admin') }}.mailchimp.com/campaigns/" target="_blank" rel="noopener" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Öppna Mailchimp Template Builder
                    </a>
                </div>
            </div>
        @endif

        <!-- Custom HTML Mode Content -->
        @if($creationMode === 'custom')
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                    Anpassad HTML
                </h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <!-- Subject -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ämnesrad</label>
                            <input type="text" wire:model.defer="customSubject" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200" placeholder="Ange ämnesrad">
                        </div>

                        <!-- HTML Content -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">HTML-innehåll</label>
                            <textarea wire:model.defer="customHtml" rows="20" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 font-mono text-sm" placeholder="Ange din HTML-kod här..."></textarea>
                            <p class="mt-2 text-xs text-gray-500">Använd komplett HTML-struktur med doctype, head och body</p>
                        </div>

                        <!-- Send settings -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sändningstid (valfritt)</label>
                            <input type="datetime-local" wire:model.defer="customSendAt" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-4">
                            <button wire:click="sendCustomTestEmail" class="flex-1 px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-semibold rounded-xl hover:from-gray-700 hover:to-gray-800 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                                Skicka test
                            </button>
                            <button wire:click="submitCustom" class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200">
                                Skapa kampanj
                            </button>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Preview -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Förhandsvisning</label>
                            <div class="w-full h-96 bg-white border border-gray-300 rounded-xl overflow-hidden">
                                @if($customHtml)
                                    <iframe srcdoc="{{ $customHtml }}" class="w-full h-full border-0"></iframe>
                                @else
                                    <div class="flex items-center justify-center h-full text-gray-500">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <p>Förhandsvisning visas här när du skriver HTML</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- HTML Template Help -->
                        <div class="p-4 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl border border-purple-200/50">
                            <h4 class="font-semibold text-gray-900 mb-2">HTML Mall</h4>
                            <p class="text-sm text-gray-600 mb-3">Behöver du en startmall? Här är en enkel struktur:</p>
                            <button wire:click="loadHtmlTemplate" class="text-xs bg-purple-600 text-white px-3 py-1 rounded-lg hover:bg-purple-700 transition-colors">
                                Ladda grundmall
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Bekräftelsedialog -->
    <div x-show="showConfirmation"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;"
         x-cloak>

        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Bakgrund overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 @click="showConfirmation = false"></div>

            <!-- Dialog -->
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100">
                            <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="mt-0 ml-4 text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Bekräfta kampanjsändning
                            </h3>
                            <div class="mt-4">
                                <p class="text-sm text-gray-600 mb-4">
                                    Innan du skickar kampanjen till alla prenumeranter, vänligen bekräfta följande:
                                </p>

                                <div class="space-y-3 mb-6 text-sm text-gray-700">
                                    <div class="flex items-start space-x-3 p-3 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-lg border border-orange-200">
                                        <svg class="w-5 h-5 text-orange-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <p class="font-semibold">Har du skickat och kontrollerat ett testmejl?</p>
                                            <p class="text-xs text-gray-600 mt-1">För att säkerställa att innehåll och utseende ser bra ut</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start space-x-3 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                                        <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <p class="font-semibold">Godkänner du innehållet och utseendet?</p>
                                            <p class="text-xs text-gray-600 mt-1">Ämnesrad: "{{ $subject }}"</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start space-x-3 p-3 bg-gradient-to-r from-red-50 to-pink-50 rounded-lg border border-red-200">
                                        <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <p class="font-semibold">Kampanjen skickas till alla prenumeranter</p>
                                            <p class="text-xs text-gray-600 mt-1">
                                                {{ $sendAt ? 'Schemalagt för: ' . \Carbon\Carbon::parse($sendAt)->format('Y-m-d H:i') : 'Skickas omedelbart' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end space-y-3 space-y-reverse sm:space-y-0 sm:space-x-3">
                    <button @click="showConfirmation = false"
                            class="w-full sm:w-auto inline-flex justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">
                        Avbryt
                    </button>
                    <button @click="showConfirmation = false; $wire.submit()"
                            class="w-full sm:w-auto inline-flex justify-center px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold rounded-lg hover:from-orange-700 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Ja, skicka kampanj
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
