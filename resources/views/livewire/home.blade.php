
<div class="space-y-8">
    <!-- Header med välkomsthälsning -->
    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-white mb-2">
                        Välkommen till din Dashboard
                    </h1>
                    <p class="text-indigo-100 text-lg">
                        Här hittar du alla verktyg för att optimera din webbplats
                    </p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dekorativa element -->
        <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full"></div>
        <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-white/5 rounded-full"></div>
    </div>

    <!-- Snabbåtgärder -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Snabbåtgärder
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <a href="{{ route('seo.audit.run') }}" class="quick-action-btn group">
                <svg class="w-5 h-5 text-emerald-600 group-hover:text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Kör SEO Audit
            </a>
            <a href="{{ route('seo.keywords.fetch') }}" class="quick-action-btn group">
                <svg class="w-5 h-5 text-blue-600 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                </svg>
                Hämta rankingar
            </a>
            <a href="{{ route('seo.keywords.analyze') }}" class="quick-action-btn group">
                <svg class="w-5 h-5 text-purple-600 group-hover:text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                AI-analys (SEO)
            </a>
            <a href="{{ route('cro.analyze.run') }}" class="quick-action-btn group">
                <svg class="w-5 h-5 text-orange-600 group-hover:text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Kör CRO-analys
            </a>
            <a href="{{ route('marketing.newsletter') }}" class="quick-action-btn group">
                <svg class="w-5 h-5 text-teal-600 group-hover:text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Skapa nyhetsbrev
            </a>
            <a href="{{ route('settings.social') }}" class="quick-action-btn group">
                <svg class="w-5 h-5 text-pink-600 group-hover:text-pink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2M7 4h10M7 4L5.5 6M17 4l1.5 2m0 0L17 8.5M18.5 6L21 7.5M6.5 6L4 7.5M4 7.5L6.5 9M4 7.5V12a4 4 0 004 4h8a4 4 0 004-4V7.5"/>
                </svg>
                Koppla sociala kanaler
            </a>
        </div>
    </div>

    <!-- Översiktspaneler -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="flex">
            <div class="dashboard-card flex-1">
                @livewire('dashboard.active-customer-card')
            </div>
        </div>
        <div class="flex">
            <div class="dashboard-card flex-1">
                @livewire('dashboard.seo-health-card')
            </div>
        </div>
    </div>

    <!-- Audit Chips -->
    <div class="dashboard-card">
        @livewire('dashboard.sites-audit-chips')
    </div>

    <!-- Historik och snabblänkar -->
    <div class="grid grid-cols-1 xl:grid-cols-1 gap-6">
        <!-- Veckodigest historik -->
        <div class="dashboard-card">
            @livewire('dashboard.weekly-digest-history')
        </div>

        <!-- Snabblänkar -->
        <div class="dashboard-card">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Snabblänkar
                </h2>
                <button class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Visa alla</button>
            </div>

            <!-- Grid layout för bättre organisation -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('seo.keywords.index') }}" class="enhanced-quick-link group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 group-hover:text-indigo-600 truncate">Nyckelordsförslag</p>
                            <p class="text-sm text-gray-500 truncate">Hitta nya keyword-möjligheter</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1">
                        <span class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full">SEO</span>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('cro.suggestions.index') }}" class="enhanced-quick-link group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 group-hover:text-emerald-600 truncate">CRO-förslag</p>
                            <p class="text-sm text-gray-500 truncate">Optimera din konverteringsgrad</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1">
                        <span class="inline-flex items-center px-2 py-1 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-full">CRO</span>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('leads.index') }}" class="enhanced-quick-link group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 group-hover:text-purple-600 truncate">Leadlista</p>
                            <p class="text-sm text-gray-500 truncate">Hantera dina leads</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1">
                        <span class="inline-flex items-center px-2 py-1 bg-purple-50 text-purple-700 text-xs font-medium rounded-full">LEADS</span>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('publications.index') }}" class="enhanced-quick-link group">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 group-hover:text-orange-600 truncate">Publiceringar</p>
                            <p class="text-sm text-gray-500 truncate">Hantera ditt innehåll</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1">
                        <span class="inline-flex items-center px-2 py-1 bg-orange-50 text-orange-700 text-xs font-medium rounded-full">CONTENT</span>
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-orange-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Kompakt footer med stats -->
            <div class="mt-6 pt-4 border-t border-gray-100">
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span>Senast uppdaterad: just nu</span>
                    <button class="text-indigo-600 hover:text-indigo-800 font-medium">Anpassa länkar</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .quick-action-btn {
            @apply flex items-center space-x-2 px-4 py-3 bg-white/60 backdrop-blur-sm rounded-xl border border-gray-200/50 hover:border-gray-300/50 hover:bg-white/80 transition-all duration-200 font-medium text-sm shadow-sm hover:shadow-md;
        }

        .dashboard-card {
            @apply bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6;
        }

        .quick-link {
            @apply flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-100 hover:border-gray-200 hover:shadow-md transition-all duration-200;
        }
    </style>
</div>
