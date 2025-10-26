<div x-data="{ showInsights: JSON.parse(localStorage.getItem('blog_compose_showInsights') ?? 'false') }">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-600 to-teal-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Blogginlägg</h1>
                            <p class="text-sm text-gray-600 hidden sm:block">SEO‑vänligt, strukturerat och engagerande</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        @php
                            $insights = null;
                            if (!empty($site_id)) {
                                $weekStart = \Illuminate\Support\Carbon::now()->startOfWeek(\Illuminate\Support\Carbon::MONDAY);
                                $insights = \App\Models\SiteInsight::where('site_id', (int)$site_id)
                                    ->where('week_start', $weekStart->toDateString())
                                    ->first();
                            }
                        @endphp
                        @if($insights)
                            <button
                                @click="showInsights = !showInsights; localStorage.setItem('blog_compose_showInsights', JSON.stringify(showInsights))"
                                class="inline-flex items-center px-4 py-2 rounded-xl transition-all duration-200 text-sm font-medium"
                                :class="showInsights ? 'bg-emerald-600 text-white shadow-lg' : 'bg-white text-emerald-700 border border-emerald-200 hover:bg-emerald-50'">
                                <svg class="w-4 h-4 mr-2 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     :class="showInsights ? 'rotate-180' : ''">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                                <span x-text="showInsights ? 'Dölj insights' : 'Visa insights'"></span>
                            </button>
                        @endif

                        <a href="{{ route('ai.select-type') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Tillbaka
                        </a>
                    </div>
                </div>
            </div>

            @if($insights)
                @php $p = $insights->payload ?? []; @endphp
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200/50 rounded-2xl shadow-lg overflow-hidden" x-show="showInsights" x-collapse>
                    <div class="p-4 sm:p-6">
                        @if(!empty($p['summary']))
                            <div class="mb-4 sm:mb-6 p-4 bg-white rounded-2xl border border-emerald-100 shadow-sm">
                                <p class="text-emerald-800 leading-relaxed text-sm sm:text-base">{{ $p['summary'] }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-emerald-100 shadow-sm">
                                <h4 class="font-bold text-emerald-900 text-sm sm:text-base mb-3">Optimala tider</h4>
                                <div class="space-y-3">
                                    @forelse(($p['timeslots'] ?? []) as $t)
                                        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                                            <div class="font-semibold text-emerald-900 text-sm sm:text-base">{{ $t['dow'] ?? '' }} {{ $t['time'] ?? '' }}</div>
                                            @if(!empty($t['why']))
                                                <div class="text-xs sm:text-sm text-emerald-700 mt-1 break-words">{{ $t['why'] }}</div>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="text-sm text-emerald-700 italic">Inga specifika tider tillgängliga</div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-emerald-100 shadow-sm">
                                <h4 class="font-bold text-emerald-900 text-sm sm:text-base mb-3">Föreslagna ämnen</h4>
                                <div class="space-y-3">
                                    @forelse(($p['topics'] ?? []) as $t)
                                        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                                            <div class="font-semibold text-emerald-900 text-sm sm:text-base break-words">{{ $t['title'] ?? '' }}</div>
                                            @if(!empty($t['why']))
                                                <div class="text-xs sm:text-sm text-emerald-700 mt-2 break-words">{{ $t['why'] }}</div>
                                            @endif
                                            <button type="button"
                                                    @click="$wire.set('title', {{ \Illuminate\Support\Js::from($t['title'] ?? '') }}); document.getElementById('titleInput')?.scrollIntoView({behavior: 'smooth', block: 'center'}); document.getElementById('titleInput')?.focus();"
                                                    class="w-full mt-3 inline-flex items-center px-2 py-1 text-xs sm:text-sm bg-white text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-50 transition">
                                                Använd ämne
                                            </button>
                                        </div>
                                    @empty
                                        <div class="text-sm text-emerald-700 italic">Inga ämnesförslag tillgängliga</div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-emerald-100 shadow-sm">
                                <h4 class="font-bold text-emerald-900 text-sm sm:text-base mb-3">Rekommenderade åtgärder</h4>
                                <div class="space-y-3">
                                    @forelse(($p['actions'] ?? []) as $t)
                                        <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                                            <div class="font-semibold text-emerald-900 text-sm sm:text-base break-words">{{ $t['action'] ?? '' }}</div>
                                            @if(!empty($t['why']))
                                                <div class="text-xs sm:text-sm text-emerald-700 mt-1 break-words">{{ $t['why'] }}</div>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="text-sm text-emerald-700 italic">Inga åtgärder föreslagna</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-8">
                <div class="space-y-8">
                    <!-- Rubrik -->
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-sky-50 rounded-xl border border-blue-200/50">
                        <label class="text-lg font-semibold text-gray-900">Rubrik</label>
                        <p class="text-sm text-blue-700 mb-3">Kort, lockande och med fokusnyckelord. Undvik VERSALER.</p>
                        <input id="titleInput" type="text" wire:model.defer="title" class="w-full px-4 py-3 bg-white border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Skriv rubriken här...">
                        @error('title') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Längd -->
                    <div class="p-6 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-200/50">
                        <label class="text-lg font-semibold text-gray-900">Längd (ord)</label>
                        <p class="text-sm text-amber-700 mb-3">Optimal längd 800–1500 ord för läsbarhet och SEO.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="word_length" value="short" class="sr-only peer">
                                <div class="p-4 bg-white border-2 border-gray-200 rounded-xl peer-checked:border-amber-500 peer-checked:bg-amber-50">
                                    <div class="font-medium text-gray-900">Kort</div>
                                    <div class="text-xs text-gray-600 mt-1">~800 ord</div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="word_length" value="optimal" class="sr-only peer">
                                <div class="p-4 bg-white border-2 border-gray-200 rounded-xl peer-checked:border-amber-500 peer-checked:bg-amber-50">
                                    <div class="font-medium text-gray-900">Optimal</div>
                                    <div class="text-xs text-gray-600 mt-1">800–1500 ord (rekommenderas)</div>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="word_length" value="long" class="sr-only peer">
                                <div class="p-4 bg-white border-2 border-gray-200 rounded-xl peer-checked:border-amber-500 peer-checked:bg-amber-50">
                                    <div class="font-medium text-gray-900">Lång</div>
                                    <div class="text-xs text-gray-600 mt-1">1500+ ord</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Fokusnyckelord -->
                    <div class="p-6 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl border border-yellow-200/50">
                        <label class="text-lg font-semibold text-gray-900">Fokusnyckelord</label>
                        <p class="text-sm text-yellow-700 mb-3">Separera med kommatecken. Använd naturligt i rubriker och text.</p>
                        <input type="text" wire:model.defer="keywords" class="w-full px-4 py-3 bg-white border border-yellow-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" placeholder="ex: e-handel, konverteringsoptimering, SEO">
                    </div>

                    <!-- Målgrupp + Syfte -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="p-6 bg-gradient-to-r from-teal-50 to-cyan-50 rounded-xl border border-teal-200/50">
                            <label class="text-lg font-semibold text-gray-900">Målgrupp</label>
                            <p class="text-sm text-teal-700 mb-3">Vem skriver du för?</p>
                            <input type="text" wire:model.defer="audience" class="w-full px-4 py-3 bg-white border border-teal-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="ex: småföretagare inom e-handel">
                        </div>
                        <div class="p-6 bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl border border-rose-200/50">
                            <label class="text-lg font-semibold text-gray-900">Syfte</label>
                            <p class="text-sm text-rose-700 mb-3">Vad vill du uppnå med texten?</p>
                            <input type="text" wire:model.defer="goal" class="w-full px-4 py-3 bg-white border border-rose-300 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500" placeholder="ex: driva prenumerationer, thought leadership">
                        </div>
                    </div>

                    <!-- Ton -->
                    <div class="p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-200/50">
                        <label class="text-lg font-semibold text-gray-900">Varumärkesröst / Ton</label>
                        <p class="text-sm text-indigo-700 mb-3">ex: professionell, personlig, informell</p>
                        <input type="text" wire:model.defer="brand_voice" class="w-full px-4 py-3 bg-white border border-indigo-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="ex: professionell men varm">
                    </div>

                    <!-- CTA -->
                    <div class="p-6 bg-gradient-to-r from-green-50 to-lime-50 rounded-xl border border-green-200/50">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <label class="text-lg font-semibold text-gray-900">CTA</label>
                                <p class="text-sm text-green-700">Vill du inkludera en CTA i slutet?</p>
                            </div>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="include_cta" class="sr-only peer">
                                <span class="relative w-11 h-6 bg-gray-200 inline-block rounded-full peer peer-checked:bg-green-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:w-5 after:h-5 after:rounded-full after:transition-all peer-checked:after:translate-x-full"></span>
                            </label>
                        </div>
                        <div x-show="$wire.include_cta">
                            <input type="text" wire:model.defer="cta_text" class="w-full px-4 py-3 bg-white border border-green-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="ex: Prenumerera på vårt nyhetsbrev">
                            @error('cta_text') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Länk + Källa -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="p-6 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl border border-emerald-200/50">
                            <label class="text-lg font-semibold text-gray-900">Länk i slutet (valfritt)</label>
                            <input type="url" wire:model.defer="link_url" class="mt-2 w-full px-4 py-3 bg-white border border-emerald-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="https://dinhemsida.se/...">
                            @error('link_url') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="p-6 bg-gradient-to-r from-sky-50 to-blue-50 rounded-xl border border-sky-200/50">
                            <label class="text-lg font-semibold text-gray-900">Skapa utifrån en källa (valfritt)</label>
                            <input type="url" wire:model.defer="source_url" class="mt-2 w-full px-4 py-3 bg-white border border-sky-300 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500" placeholder="https://exempel.se/guides/...">
                            @error('source_url') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            <p class="mt-2 text-xs text-sky-700">Vi försöker läsa sidan och använder ett utdrag som underlag.</p>
                        </div>
                    </div>

                    <!-- Språk + Site + Bild -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="p-6 bg-gradient-to-r from-lime-50 to-emerald-50 rounded-xl border border-lime-200/50">
                            <label class="text-lg font-semibold text-gray-900">Språk</label>
                            <select wire:model="language" class="mt-2 w-full px-4 py-3 bg-white border border-lime-300 rounded-xl focus:ring-2 focus:ring-lime-500 focus:border-lime-500">
                                <option value="sv_SE">Svenska</option>
                                <option value="en_US">Engelska</option>
                                <option value="de_DE">Tyska</option>
                            </select>
                        </div>
                        <div class="p-6 bg-gradient-to-r from-slate-50 to-gray-50 rounded-xl border border-slate-200/50">
                            <label class="text-lg font-semibold text-gray-900">Hemsida</label>
                            <select wire:model="site_id" class="mt-2 w-full px-4 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-slate-500">
                                <option value="">Ingen hemsida vald</option>
                                @foreach($sites as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('ai.select-type') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50">
                            Avbryt
                        </a>
                        <button wire:click="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700">
                            Skapa mitt blogginlägg
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tips-list utifrån dina riktlinjer -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-emerald-100 p-6">
                <ul class="list-disc ml-6 space-y-1 text-sm text-gray-700">
                    <li>Klockren rubrik med viktiga nyckelord, undvik versaler.</li>
                    <li>Tydlig struktur: inledning, brödtext med underrubriker, avslutning.</li>
                    <li>Anpassa stil efter målgrupp och röst (personlig/professionell/informell).</li>
                    <li>Kort, koncist och engagerande språk.</li>
                    <li>800–1500 ord rekommenderas för läsbarhet och SEO.</li>
                    <li>Flera underrubriker för skumläsning; hjälp sökmotorer förstå innehållet.</li>
                    <li>Använd nyckelord naturligt i rubriker och text.</li>
                    <li>Tydlig call‑to‑action i slutet (om vald).</li>
                    <li>Korrekt stavning/grammatik, gärna korrläs.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
