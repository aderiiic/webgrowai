
<div>
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Skapa text med AI
            </h1>
            <a href="{{ route('ai.list') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Tillbaka
            </a>
        </div>

        <!-- Help banner -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-200/50 p-6">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-blue-900 mb-2">Skapa professionell text på sekunder!</h2>
                    <p class="text-blue-800 text-sm leading-relaxed">
                        Fyll i informationen nedan så skapar vår AI en färdig text åt dig. Perfekt för blogginlägg, sociala medier eller marknadsföring.
                        <strong>Tips:</strong> Ju mer detaljer du ger, desto bättre blir resultatet!
                    </p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-8">
            <div class="space-y-8">
                <!-- Step 1: Vad vill du skriva? -->
                <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200/50">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-lg">1</span>
                        </div>
                        <div>
                            <label class="block text-lg font-semibold text-gray-900">Vad vill du skriva?</label>
                            <p class="text-sm text-green-700">Välj vilken typ av text du behöver</p>
                        </div>
                    </div>
                    <select wire:model="template_id" class="w-full px-4 py-3 bg-white border border-green-300 rounded-xl text-gray-900 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                        <option value="">Välj texttyp...</option>
                        @foreach($templates as $tpl)
                            <option value="{{ $tpl->id }}">{{ $tpl->name }}</option>
                        @endforeach
                    </select>
                    @error('template_id')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Step 2: Ämne/Titel -->
                <div class="p-6 bg-gradient-to-r from-blue-50 to-sky-50 rounded-xl border border-blue-200/50">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-lg">2</span>
                        </div>
                        <div>
                            <label class="text-lg font-semibold text-gray-900">Vad vill du ha för rubrik?</label>
                            <p class="text-sm text-blue-700">Berätta kort vad du vill skriva om</p>
                        </div>
                    </div>
                    <input type="text" wire:model.defer="title" class="w-full px-4 py-3 bg-white border border-blue-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="T.ex. 'Våra nya tjänster' eller 'Tips för att öka försäljning'">
                    @error('title')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Step 3: Var ska texten användas + Längd -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200/50">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-purple-500 rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-lg">3</span>
                            </div>
                            <div>
                                <label class="text-lg font-semibold text-gray-900">Var ska texten användas?</label>
                                <p class="text-sm text-purple-700">Detta påverkar stil och längd</p>
                            </div>
                        </div>

                        @php
                            $autoChannel = null;
                            if (!empty($selectedTemplate?->slug)) {
                                $map = [
                                    'social-facebook' => 'facebook',
                                    'social-instagram' => 'instagram',
                                    'social-linkedin' => 'linkedin',
                                    'blog' => 'blog',
                                    'campaign' => 'campaign',
                                ];
                                $autoChannel = $map[$selectedTemplate->slug] ?? null;
                            }
                        @endphp

                        @if($autoChannel)
                            <div class="inline-flex items-center px-4 py-2 bg-white border border-purple-300 rounded-xl text-sm text-purple-800">
                                <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="capitalize font-medium">{{ $autoChannel === 'blog' ? 'Blogg/Hemsida' : ($autoChannel === 'campaign' ? 'Marknadsföring' : 'Sociala medier') }}</span>
                            </div>
                            <p class="mt-2 text-xs text-purple-700">Byt texttyp om du vill ändra användningsområde.</p>
                        @else
                            <select wire:model="channel" class="w-full px-4 py-3 bg-white border border-purple-300 rounded-xl text-gray-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                                <option value="auto">Välj användningsområde...</option>
                                <option value="facebook">Facebook</option>
                                <option value="instagram">Instagram</option>
                                <option value="linkedin">LinkedIn</option>
                                <option value="blog">Blogg/Hemsida</option>
                                <option value="campaign">Marknadsföring</option>
                            </select>
                            @error('channel')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        @endif
                    </div>

                    <div class="p-6 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-200/50">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                            </div>
                            <div>
                                <label class="text-lg font-semibold text-gray-900">Textlängd</label>
                                <p class="text-sm text-amber-700">Hur lång ska texten vara?</p>
                            </div>
                        </div>
                        <select wire:model="tone" class="w-full px-4 py-3 bg-white border border-amber-300 rounded-xl text-gray-900 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200">
                            <option value="short">Kort text (snabb att läsa)</option>
                            <option value="long">Längre text (mer detaljerad)</option>
                        </select>
                    </div>
                </div>

                <!-- Step 4: Vem ska läsa texten + Vad vill du uppnå? -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="p-6 bg-gradient-to-r from-teal-50 to-cyan-50 rounded-xl border border-teal-200/50">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-teal-500 rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-lg">4</span>
                            </div>
                            <div>
                                <label class="text-lg font-semibold text-gray-900">Vem ska läsa texten?</label>
                                <p class="text-sm text-teal-700">Beskriv dina läsare</p>
                            </div>
                        </div>
                        <input type="text" wire:model.defer="audience" class="w-full px-4 py-3 bg-white border border-teal-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200" placeholder="T.ex. småföretagare, föräldrar, ungdomar...">
                        <p class="mt-2 text-xs text-teal-600">💡 Exempel: 'Föräldrar med barn 3-10 år' eller 'Småföretagare som vill växa'</p>
                    </div>

                    <div class="p-6 bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl border border-rose-200/50">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-rose-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <label class="text-lg font-semibold text-gray-900">Vad vill du uppnå?</label>
                                <p class="text-sm text-rose-700">Ditt mål med texten</p>
                            </div>
                        </div>
                        <input type="text" wire:model.defer="goal" class="w-full px-4 py-3 bg-white border border-rose-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-all duration-200" placeholder="T.ex. få fler kunder, bygga förtroende...">
                        <p class="mt-2 text-xs text-rose-600">💡 Exempel: 'Få fler att kontakta oss' eller 'Visa våra kunskaper'</p>
                    </div>
                </div>

                <!-- Expandable advanced options -->
                <div class="border-t border-gray-200 pt-6" x-data="{ showAdvanced: false }">
                    <button @click="showAdvanced = !showAdvanced" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2 transform transition-transform duration-200" :class="{'rotate-180': showAdvanced}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        <span x-text="showAdvanced ? 'Dölj avancerade inställningar' : 'Visa fler inställningar (valfritt)'"></span>
                    </button>

                    <div x-show="showAdvanced" x-transition class="mt-6 space-y-6">
                        <!-- Keywords -->
                        <div class="p-6 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl border border-yellow-200/50">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <label class="text-lg font-semibold text-gray-900">Viktiga ord att inkludera</label>
                                    <p class="text-sm text-yellow-700">Separera med kommatecken (hjälper med synlighet på Google)</p>
                                </div>
                            </div>
                            <input type="text" wire:model.defer="keywords" class="w-full px-4 py-3 bg-white border border-yellow-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200" placeholder="webbdesign, marknadsföring, Stockholm...">
                        </div>

                        <!-- Brand voice and site -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-200/50">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <label class="text-lg font-semibold text-gray-900">Hur ska du låta?</label>
                                        <p class="text-sm text-indigo-700">Din ton och stil</p>
                                    </div>
                                </div>
                                <input type="text" wire:model.defer="brand_voice" class="w-full px-4 py-3 bg-white border border-indigo-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" placeholder="professionell, personlig, vänlig...">
                            </div>

                            <div class="p-6 bg-gradient-to-r from-slate-50 to-gray-50 rounded-xl border border-slate-200/50">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-8 h-8 bg-slate-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <label class="text-lg font-semibold text-gray-900">Vilken hemsida?</label>
                                        <p class="text-sm text-slate-700">Koppla till din hemsida</p>
                                    </div>
                                </div>

                                <select wire:model="site_id" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-gray-900 focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-200">
                                    <option value="">Ingen hemsida vald</option>
                                    @foreach($sites as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Image generation -->
                        @if(config('features.image_generation'))
                            <div class="p-6 bg-gradient-to-r from-violet-50 to-purple-50 rounded-xl border border-violet-200/50">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-violet-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <label class="text-lg font-semibold text-gray-900">Skapa bild med AI</label>
                                            <p class="text-sm text-violet-700">Få en passande bild till din text</p>
                                        </div>
                                    </div>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" wire:model="genImage" class="sr-only peer">
                                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 dark:peer-focus:ring-violet-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-violet-600"></div>
                                    </label>
                                </div>

                                <div x-show="$wire.genImage" class="space-y-4">
                                    <div class="text-sm text-violet-800 p-3 bg-violet-100 rounded-lg">
                                        AI skapar automatiskt en bild som passar din text. Bilden laddas upp direkt när du publicerar.
                                    </div>

                                    <div class="space-y-3">
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" wire:model="imagePromptMode" value="auto" class="text-violet-600 focus:ring-violet-500">
                                            <span class="text-gray-800">Låt AI välja bildmotiv (rekommenderas)</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" wire:model="imagePromptMode" value="custom" class="text-violet-600 focus:ring-violet-500">
                                            <span class="text-gray-800">Jag beskriver själv vilken bild jag vill ha</span>
                                        </label>
                                    </div>

                                    <div x-show="$wire.imagePromptMode === 'custom'">
                                        <label class="block text-sm font-medium text-gray-900 mb-2">Beskriv bilden du vill ha</label>
                                        <textarea wire:model.defer="imagePrompt" rows="3" class="w-full px-4 py-3 bg-white border border-violet-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition" placeholder="T.ex. 'En professionell kontorsmiljö med glada medarbetare'"></textarea>
                                        @error('imagePrompt')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action buttons -->
                <div class="flex items-center justify-between pt-8 border-t border-gray-200">
                    <a href="{{ route('ai.list') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Avbryt
                    </a>
                    <button wire:click="submit" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl text-lg">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Skapa min text!
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
