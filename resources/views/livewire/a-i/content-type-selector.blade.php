
<div>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-5xl mx-auto space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">Vilken typ av text vill du skapa?</h1>
                <p class="text-lg text-gray-600">Välj vad som passar dina behov bäst</p>
            </div>

            <!-- Content Type Cards - 4 i rad på desktop, 2 på tablet, 1 på mobil -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Social Media Content -->
                <a href="{{ route('ai.social-media') }}" class="group bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl hover:bg-white/90 transition-all duration-300 transform hover:-translate-y-1 hover:border-blue-300">
                    <div class="text-center space-y-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center mx-auto group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Sociala medier</h3>
                            <p class="text-sm text-gray-600">Facebook, Instagram & LinkedIn</p>
                        </div>
                    </div>
                </a>

                <!-- Blog & Articles -->
                <a href="{{ route('ai.blog') }}" class="group bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl hover:bg-white/90 transition-all duration-300 transform hover:-translate-y-1 hover:border-emerald-300">
                    <div class="text-center space-y-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center mx-auto group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Blogg & artiklar</h3>
                            <p class="text-sm text-gray-600">SEO-optimerat & djupgående innehåll</p>
                        </div>
                    </div>
                </a>

                <!-- SEO & Website Content -->
                <div class="group bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                    <div class="text-center space-y-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">SEO & Hemsidetexter</h3>
                            <p class="text-sm text-gray-600">Optimera eller skapa produkttexter</p>
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-3">
                            <a href="{{ route('ai.seo.optimize') }}" class="inline-flex items-center justify-center px-3 py-2 rounded-lg border text-sm font-medium bg-white hover:bg-purple-50 border-purple-200 text-purple-700 transition">
                                Optimera text (SEO)
                            </a>
                            <a href="{{ route('ai.seo.product') }}" class="inline-flex items-center justify-center px-3 py-2 rounded-lg border text-sm font-medium bg-white hover:bg-pink-50 border-pink-200 text-pink-700 transition">
                                Skapa produkttext
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Multi-text Generation -->
                <a href="{{ route('ai.bulk.generate') }}" class="group bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 hover:shadow-xl hover:bg-white/90 transition-all duration-300 transform hover:-translate-y-1 hover:border-orange-300">
                    <div class="text-center space-y-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mx-auto group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v2a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Massgenerering</h3>
                            <p class="text-sm text-gray-600">Flera varianter av samma text med variabler</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Bottom actions -->
            <div class="text-center pt-8">
                <div class="inline-flex items-center space-x-4 bg-white/60 backdrop-blur-sm rounded-xl border border-white/30 p-4">
                    <span class="text-sm text-gray-600">Behöver fler alternativ?</span>
                    <a href="{{ route('ai.compose') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-all duration-200 text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Avancerad editor
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
