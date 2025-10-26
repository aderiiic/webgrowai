<div x-data="{ showInsights: JSON.parse(localStorage.getItem('social_compose_showInsights') ?? 'false') }" wire:key="root-{{ $platform }}-{{ $platformChanged ? 'a' : 'b' }}">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Sociala medier</h1>
                            <p class="text-sm text-gray-600 hidden sm:block">Skapa engagerande innehåll för Facebook, Instagram & LinkedIn</p>
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
                                @click="showInsights = !showInsights; localStorage.setItem('social_compose_showInsights', JSON.stringify(showInsights))"
                                class="inline-flex items-center px-4 py-2 rounded-xl transition-all duration-200 text-sm font-medium"
                                :class="showInsights ? 'bg-indigo-600 text-white shadow-lg' : 'bg-white text-indigo-600 border border-indigo-200 hover:bg-indigo-50'">
                                <svg class="w-4 h-4 mr-2 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     :class="showInsights ? 'rotate-180' : ''">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                                <span x-text="showInsights ? 'Dölj insights' : 'Visa insights'"></span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </button>
                        @endif

                        <a href="{{ route('ai.select-type') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Tillbaka
                        </a>
                    </div>
                </div>
            </div>

            @if($errors->has('general'))
                <div class="rounded-md bg-red-50 border border-red-200 p-3 text-red-800 text-sm">
                    {{ $errors->first('general') }}
                </div>
            @endif

            @if($insights)
                @php $p = $insights->payload ?? []; @endphp
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200/50 rounded-2xl lg:rounded-3xl shadow-lg overflow-hidden" x-show="showInsights" x-collapse>
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4 sm:mb-6">
                            <div>
                                <div class="flex items-center gap-2 text-blue-600 mb-2">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <span class="text-sm font-semibold uppercase tracking-wider">Vecka {{ \Illuminate\Support\Carbon::now()->startOfWeek(\Illuminate\Support\Carbon::MONDAY)->isoWeek() }}</span>
                                </div>
                                <h3 class="text-xl sm:text-2xl font-bold text-blue-900">Veckans AI-rekommendationer</h3>
                            </div>
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                <div class="flex items-center gap-2 text-xs sm:text-sm text-blue-600 bg-blue-100 px-3 py-2 rounded-xl">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="truncate">Uppdaterad: {{ $insights->generated_at?->diffForHumans() }}</span>
                                </div>
                                <button
                                    @click="showInsights = false; localStorage.setItem('social_compose_showInsights', 'false')"
                                    class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-white border border-blue-200 text-blue-600 rounded-xl hover:bg-blue-50 transition-all duration-200 text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="hidden sm:inline">Stäng</span>
                                </button>
                            </div>
                        </div>

                        @if(!empty($p['summary']))
                            <div class="mb-4 sm:mb-6 p-4 bg-white rounded-2xl border border-blue-100 shadow-sm">
                                <p class="text-blue-800 leading-relaxed text-sm sm:text-base">{{ $p['summary'] }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                            <!-- Optimala tider -->
                            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-blue-100 shadow-sm">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-blue-900 text-sm sm:text-base">Optimala tider</h4>
                                </div>
                                <div class="space-y-3">
                                    @forelse(($p['timeslots'] ?? []) as $t)
                                        <div class="p-3 bg-blue-50 rounded-xl border border-blue-100">
                                            <div class="font-semibold text-blue-900 text-sm sm:text-base">{{ $t['dow'] ?? '' }} {{ $t['time'] ?? '' }}</div>
                                            @if(!empty($t['why']))
                                                <div class="text-xs sm:text-sm text-blue-700 mt-1 break-words">{{ $t['why'] }}</div>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="text-sm text-blue-600 italic">Inga specifika tider tillgängliga</div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Föreslagna ämnen -->
                            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-blue-100 shadow-sm">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-blue-900 text-sm sm:text-base">Föreslagna ämnen</h4>
                                </div>
                                <div class="space-y-3">
                                    @forelse(($p['topics'] ?? []) as $t)
                                        <div class="p-3 bg-purple-50 rounded-xl border border-purple-100">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="font-semibold text-purple-900 text-sm sm:text-base break-words">{{ $t['title'] ?? '' }}</div>
                                            </div>
                                            @if(!empty($t['why']))
                                                <div class="text-xs sm:text-sm text-purple-700 mt-2 break-words">{{ $t['why'] }}</div>
                                            @endif
                                            <button type="button"
                                                    @click="$wire.set('title', {{ \Illuminate\Support\Js::from($t['title'] ?? '') }}); document.getElementById('titleInput')?.scrollIntoView({behavior: 'smooth', block: 'center'}); document.getElementById('titleInput')?.focus();"
                                                    class="w-full mt-3 inline-flex items-center px-2 py-1 text-xs sm:text-sm bg-white text-purple-700 border border-purple-200 rounded-lg hover:bg-purple-100 transition">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                Använd ämne
                                            </button>
                                        </div>
                                    @empty
                                        <div class="text-sm text-purple-600 italic">Inga ämnesförslag tillgängliga</div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Rekommenderade åtgärder -->
                            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-blue-100 shadow-sm">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-blue-900 text-sm sm:text-base">Rekommenderade åtgärder</h4>
                                </div>
                                <div class="space-y-3">
                                    @forelse(($p['actions'] ?? []) as $t)
                                        <div class="p-3 bg-green-50 rounded-xl border border-green-100">
                                            <div class="font-semibold text-green-900 text-sm sm:text-base break-words">{{ $t['action'] ?? '' }}</div>
                                            @if(!empty($t['why']))
                                                <div class="text-xs sm:text-sm text-green-700 mt-1 break-words">{{ $t['why'] }}</div>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="text-sm text-green-600 italic">Inga åtgärder föreslagna</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        @if(!empty($p['rationale']))
                            <div class="mt-4 sm:mt-6 p-4 bg-indigo-100 rounded-2xl border border-indigo-200">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <h5 class="font-semibold text-indigo-900 mb-2 text-sm sm:text-base">AI-analys</h5>
                                        <p class="text-xs sm:text-sm text-indigo-800 break-words">{{ $p['rationale'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Help banner -->
            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-2xl border border-blue-200/50 p-4 sm:p-6 shadow-lg">
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold text-blue-900 mb-2">Skapa perfekta sociala medier-inlägg!</h2>
                        <p class="text-blue-800 text-sm leading-relaxed">
                            Välj plattform och längd så optimerar vi innehållet för bästa engagemang.
                            <strong>Tips:</strong> Varje plattform har sina egna regler för vad som fungerar bäst!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-8">
                <div class="space-y-8">
                    <!-- Step 1: Välj plattform -->
                    <div class="p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-200/50" wire:key="platform-wrap-{{ $platform }}">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-lg">1</span>
                            </div>
                            <div>
                                <label class="block text-lg font-semibold text-gray-900">Vilken plattform?</label>
                                <p class="text-sm text-indigo-700">Detta påverkar stil, längd och hashtag-strategi</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="platform" value="facebook" class="sr-only peer">
                                <div class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-300 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-900">Facebook</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-2">Konversationell, personlig</p>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" wire:model="platform" value="instagram" class="sr-only peer">
                                <div class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-pink-300 peer-checked:border-pink-500 peer-checked:bg-pink-50 transition-all duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-purple-600 to-pink-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-900">Instagram</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-2">Visuellt, berättande</p>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" wire:model="platform" value="linkedin" class="sr-only peer">
                                <div class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-300 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-900">LinkedIn</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-2">Professionell, insiktsfull</p>
                                </div>
                            </label>
                        </div>

                        @error('platform')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Step 2: Längd (med wire:key för att trigga uppdatering) -->
                    @if($platform)
                        <div class="p-6 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200/50"
                             x-data="{
                                 currentPlatform: @js($platform),
                                 lengthConfigs: {
                                     'facebook': {
                                         'optimal': '40-80 tecken',
                                         'short': '20-39 tecken',
                                         'medium': '81-150 tecken',
                                         'long': '151-300+ tecken'
                                     },
                                     'instagram': {
                                         'optimal': '75-150 tecken',
                                         'short': '1-74 tecken',
                                         'medium': '151-300 tecken',
                                         'long': '301-500+ tecken'
                                     },
                                     'linkedin': {
                                         'optimal': '1200-1600 tecken',
                                         'short': '0-300 tecken',
                                         'medium': '301-1200 tecken',
                                         'long': '1601-2000+ tecken'
                                     }
                                 },
                                 getLengthText(platform, length) {
                                     return this.lengthConfigs[platform]?.[length] || '';
                                 }
                             }"
                             x-init="$watch('$wire.platform', value => currentPlatform = value)">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">2</span>
                                </div>
                                <div>
                                    <label class="block text-lg font-semibold text-gray-900">Längd för <span x-text="currentPlatform.charAt(0).toUpperCase() + currentPlatform.slice(1)"></span></label>
                                    <p class="text-sm text-emerald-700">Optimerat för bästa engagemang på denna plattform</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                @php
                                    $lengthOptions = [
                                        'optimal' => ['label' => 'Bäst (Optimal)', 'desc' => 'Rekommenderas'],
                                        'short'   => ['label' => 'Kort',            'desc' => 'Snabbt att läsa'],
                                        'medium'  => ['label' => 'Medel',           'desc' => 'Balanserat'],
                                        'long'    => ['label' => 'Lång',            'desc' => 'Mer detaljerat'],
                                    ];
                                @endphp

                                @foreach($lengthOptions as $value => $option)
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model="length" value="{{ $value }}" class="sr-only peer">
                                        <div class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-emerald-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all duration-200">
                                            <div class="text-center">
                                                <div class="font-medium text-gray-900">{{ $option['label'] }}</div>
                                                <div class="text-xs text-gray-600 mt-1">{{ $option['desc'] }}</div>
                                                <div class="text-xs text-emerald-600 mt-2 font-medium" x-text="getLengthText(currentPlatform, '{{ $value }}')"></div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            @error('length')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    @endif

                    <!-- Step 3: Rubrik/Ämne -->
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-sky-50 rounded-xl border border-blue-200/50">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-lg">3</span>
                            </div>
                            <div>
                                <label class="text-lg font-semibold text-gray-900">Vad handlar inlägget om?</label>
                                <p class="text-sm text-blue-700">En kort beskrivning av vad du vill kommunicera</p>
                            </div>
                        </div>
                        <input id="titleInput" type="text" wire:model.defer="title" class="w-full px-4 py-3 bg-white border border-blue-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="T.ex. 'Vår nya tjänst lanseras' eller 'Tips för att öka försäljning'">
                        @error('title')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Step 4: CTA & Länk -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200/50">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 bg-purple-500 rounded-xl flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">4</span>
                                </div>
                                <div>
                                    <label class="text-lg font-semibold text-gray-900">Call-to-Action (valfritt)</label>
                                    <p class="text-sm text-purple-700">Vad vill du att läsarna ska göra?</p>
                                </div>
                            </div>
                            <input type="text" wire:model.defer="cta" class="w-full px-4 py-3 bg-white border border-purple-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200" placeholder="T.ex. 'Kontakta oss idag' eller 'Läs mer på vår hemsida'">
                            @error('cta')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-purple-600">💡 Exempel: 'Kommentera vad du tycker' eller 'Skicka DM för mer info'</p>
                        </div>

                        <div class="p-6 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-200/50">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">5</span>
                                </div>
                                <div>
                                    <label class="text-lg font-semibold text-gray-900">Länk (valfritt)</label>
                                    <p class="text-sm text-amber-700">Läggs till i slutet av inlägget</p>
                                </div>
                            </div>
                            <input type="url" wire:model.defer="link_url" class="w-full px-4 py-3 bg-white border border-amber-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all duration-200" placeholder="https://dinhemsida.se">
                            @error('link_url')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Step 6: Hashtags -->
                    <div class="p-6 bg-gradient-to-r from-green-50 to-lime-50 rounded-xl border border-green-200/50">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">6</span>
                                </div>
                                <div>
                                    <label class="text-lg font-semibold text-gray-900">Hashtags</label>
                                    <p class="text-sm text-green-700">Vill du ha hashtags i inlägget?</p>
                                </div>
                            </div>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="include_hashtags" class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>

                        @if($platform)
                            <div class="text-sm text-green-800 p-3 bg-green-100 rounded-lg">
                                @if($platform === 'facebook')
                                    <strong>Facebook:</strong> 0–3 hashtags fungerar bäst. Mer fokus på engagerande text.
                                @elseif($platform === 'instagram')
                                    <strong>Instagram:</strong> 5–10 relevanta hashtags i slutet hjälper med synlighet.
                                @elseif($platform === 'linkedin')
                                    <strong>LinkedIn:</strong> 1–3 professionella hashtags för att nå rätt målgrupp.
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Advanced Settings (Expandable) -->
                    <div class="border-t border-gray-200 pt-6" x-data="{ showAdvanced: false }">
                        <button @click="showAdvanced = !showAdvanced" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2 transform transition-transform duration-200" :class="{'rotate-180': showAdvanced}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                            <span x-text="showAdvanced ? 'Dölj avancerade inställningar' : 'Visa avancerade inställningar'"></span>
                        </button>

                        <div x-show="showAdvanced" x-transition class="mt-6 space-y-6">
                            <!-- Målgrupp och mål -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="p-6 bg-gradient-to-r from-teal-50 to-cyan-50 rounded-xl border border-teal-200/50">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <div class="w-8 h-8 bg-teal-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <label class="text-lg font-semibold text-gray-900">Målgrupp</label>
                                            <p class="text-sm text-teal-700">Vem ska läsa inlägget?</p>
                                        </div>
                                    </div>
                                    <input type="text" wire:model.defer="audience" class="w-full px-4 py-3 bg-white border border-teal-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200" placeholder="T.ex. småföretagare, föräldrar, ungdomar...">
                                    <p class="mt-2 text-xs text-teal-600">💡 Exempel: 'Föräldrar med barn 3-10 år' eller 'Småföretagare som vill växa'</p>
                                </div>

                                <div class="p-6 bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl border border-rose-200/50">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <div class="w-8 h-8 bg-rose-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <label class="text-lg font-semibold text-gray-900">Mål</label>
                                            <p class="text-sm text-rose-700">Vad vill du uppnå?</p>
                                        </div>
                                    </div>
                                    <input type="text" wire:model.defer="goal" class="w-full px-4 py-3 bg-white border border-rose-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-all duration-200" placeholder="T.ex. få fler kunder, bygga förtroende...">
                                    <p class="mt-2 text-xs text-rose-600">💡 Exempel: 'Få fler att kontakta oss' eller 'Visa våra kunskaper'</p>
                                </div>
                            </div>

                            <!-- Nyckelord, varumärkesröst, hemsida, språk -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-6">
                                <div class="p-6 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl border border-yellow-200/50">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <label class="text-lg font-semibold text-gray-900">Nyckelord</label>
                                            <p class="text-sm text-yellow-700">Viktiga ord</p>
                                        </div>
                                    </div>
                                    <input type="text" wire:model.defer="keywords" class="w-full px-4 py-3 bg-white border border-yellow-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200" placeholder="marknadsföring, tips...">
                                </div>

                                <div class="p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-200/50">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <label class="text-lg font-semibold text-gray-900">Ton</label>
                                            <p class="text-sm text-indigo-700">Hur ska du låta?</p>
                                        </div>
                                    </div>
                                    <input type="text" wire:model.defer="brand_voice" class="w-full px-4 py-3 bg-white border border-indigo-300 rounded-xl text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" placeholder="professionell, vänlig...">
                                </div>

                                <div class="p-6 bg-gradient-to-r from-slate-50 to-gray-50 rounded-xl border border-slate-200/50">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <div class="w-8 h-8 bg-slate-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <label class="text-lg font-semibold text-gray-900">Hemsida</label>
                                            <p class="text-sm text-slate-700">Vilken hemsida?</p>
                                        </div>
                                    </div>

                                    <select wire:model="site_id" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-gray-900 focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-200">
                                        <option value="">Ingen hemsida vald</option>
                                        @foreach($sites as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="p-6 bg-gradient-to-r from-lime-50 to-emerald-50 rounded-xl border border-lime-200/50">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <label class="text-lg font-semibold text-gray-900">Språk</label>
                                            <p class="text-sm text-emerald-700">Vilket språk ska texten skrivas på?</p>
                                        </div>
                                    </div>
                                    <select wire:model="language" class="w-full px-4 py-3 bg-white border border-emerald-300 rounded-xl text-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200">
                                        <option value="sv_SE">Svenska (standard)</option>
                                        <option value="en_US">Engelska</option>
                                        <option value="de_DE">Tyska</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-center justify-between pt-8 border-t border-gray-200">
                        <a href="{{ route('ai.select-type') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Avbryt
                        </a>
                        <button wire:click="submit" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-500 to-cyan-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-cyan-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl text-lg">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Skapa mitt inlägg!
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
