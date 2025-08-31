
<div>
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between space-y-4 lg:space-y-0">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M22.051 9.319c-.425-2.539-2.306-4.446-4.844-4.922-.664-.123-1.321-.123-1.985-.009-1.17.198-2.226.742-3.051 1.567-1.567 1.566-2.477 3.712-2.488 5.872-.011 2.16.887 4.315 2.444 5.883 1.557 1.568 3.701 2.488 5.861 2.499s4.315-.887 5.883-2.444c1.568-1.557 2.488-3.701 2.499-5.861.011-2.151-.887-4.296-2.44-5.854-.311-.311-.644-.596-.994-.857-.35-.261-.717-.499-1.097-.715z"/>
                </svg>
                Kampanjhistorik
            </h1>
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <a href="{{ route('marketing.newsletter') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-yellow-600 to-orange-600 text-white font-semibold rounded-xl hover:from-yellow-700 hover:to-orange-700 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Skapa nytt nyhetsbrev
                </a>
                <button wire:click="refreshCampaigns" class="inline-flex items-center justify-center px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Uppdatera
                </button>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl p-6 border border-blue-200/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700">Totala kampanjer</p>
                        <p class="text-3xl font-bold text-blue-900">{{ count($campaigns) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-2xl p-6 border border-green-200/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-700">Skickade</p>
                        <p class="text-3xl font-bold text-green-900">{{ collect($campaigns)->where('status', 'sent')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-amber-100 rounded-2xl p-6 border border-yellow-200/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-700">Schemalagda</p>
                        <p class="text-3xl font-bold text-yellow-900">{{ collect($campaigns)->where('status', 'scheduled')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-50 to-slate-100 rounded-2xl p-6 border border-gray-200/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Utkast</p>
                        <p class="text-3xl font-bold text-gray-900">{{ collect($campaigns)->where('status', 'save')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between space-y-4 lg:space-y-0">
                <h2 class="text-lg font-semibold text-gray-900">Filtrera och sök</h2>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="searchTerm" placeholder="Sök kampanjer..." class="pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <!-- Status Filter -->
                    <div class="flex items-center space-x-3">
                        <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Status:</label>
                        <select wire:model.live="statusFilter" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200">
                            <option value="">Alla status</option>
                            <option value="save">Utkast</option>
                            <option value="scheduled">Schemalagd</option>
                            <option value="sending">Skickas</option>
                            <option value="sent">Skickad</option>
                            <option value="paused">Pausad</option>
                            <option value="cancel">Avbruten</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="flex items-center space-x-2">
                        <input type="date" wire:model.live="dateFrom" class="px-3 py-2 bg-white border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        <span class="text-gray-500">-</span>
                        <input type="date" wire:model.live="dateTo" class="px-3 py-2 bg-white border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Campaigns list -->
        <div class="space-y-6">
            @forelse($campaigns as $c)
                @php
                    $statusColors = [
                        'save' => ['bg' => 'from-gray-50 to-slate-50', 'border' => 'border-gray-200/50', 'text' => 'text-gray-800', 'icon' => 'bg-gray-500'],
                        'scheduled' => ['bg' => 'from-blue-50 to-indigo-50', 'border' => 'border-blue-200/50', 'text' => 'text-blue-800', 'icon' => 'bg-blue-500'],
                        'sending' => ['bg' => 'from-yellow-50 to-amber-50', 'border' => 'border-yellow-200/50', 'text' => 'text-yellow-800', 'icon' => 'bg-yellow-500'],
                        'sent' => ['bg' => 'from-green-50 to-emerald-50', 'border' => 'border-green-200/50', 'text' => 'text-green-800', 'icon' => 'bg-green-500'],
                        'paused' => ['bg' => 'from-orange-50 to-red-50', 'border' => 'border-orange-200/50', 'text' => 'text-orange-800', 'icon' => 'bg-orange-500'],
                        'cancel' => ['bg' => 'from-red-50 to-pink-50', 'border' => 'border-red-200/50', 'text' => 'text-red-800', 'icon' => 'bg-red-500'],
                    ];
                    $colors = $statusColors[strtolower($c['status'] ?? '')] ?? ['bg' => 'from-purple-50 to-pink-50', 'border' => 'border-purple-200/50', 'text' => 'text-purple-800', 'icon' => 'bg-purple-500'];
                @endphp

                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 hover:shadow-2xl transition-all duration-200">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between space-y-4 lg:space-y-0">
                            <!-- Campaign Content -->
                            <div class="flex-1 min-w-0">
                                <!-- Status and ID -->
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r {{ $colors['bg'] }} {{ $colors['border'] }} border rounded-full">
                                        <div class="w-3 h-3 {{ $colors['icon'] }} rounded-full mr-2"></div>
                                        <span class="text-xs font-medium {{ $colors['text'] }} uppercase">{{ $c['status'] ?? 'Okänd' }}</span>
                                    </div>

                                    @if(!empty($c['id']))
                                        <div class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-gray-50 to-white border border-gray-200/50 rounded-full">
                                            <span class="text-xs font-mono text-gray-600">{{ $c['id'] }}</span>
                                        </div>
                                    @endif

                                    @if(!empty($c['type']))
                                        <div class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200/50 rounded-full">
                                            <span class="text-xs font-medium text-indigo-700 capitalize">{{ $c['type'] }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Campaign Title -->
                                <div class="mb-4">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2 break-words">
                                        {{ $c['settings']['subject_line'] ?? '(Ingen ämnesrad)' }}
                                    </h3>
                                    @if(!empty($c['settings']['preview_text']))
                                        <p class="text-sm text-gray-600">{{ $c['settings']['preview_text'] }}</p>
                                    @endif
                                </div>

                                <!-- Campaign Details Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
                                    <!-- Created -->
                                    <div class="p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 2l6 2m-6-2l-6 2m12 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V9z"/>
                                            </svg>
                                            <span class="text-xs font-medium text-blue-700">Skapad</span>
                                        </div>
                                        <div class="text-sm text-blue-900">
                                            @if(!empty($c['create_time']))
                                                {{ \Carbon\Carbon::parse($c['create_time'])->format('j M Y H:i') }}
                                            @else
                                                —
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Send Time -->
                                    @if(!empty($c['send_time']))
                                        <div class="p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200/50">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-xs font-medium text-green-700">Skickad</span>
                                            </div>
                                            <div class="text-sm text-green-900">
                                                {{ \Carbon\Carbon::parse($c['send_time'])->format('j M Y H:i') }}
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Audience -->
                                    @if(!empty($c['recipients']['list_name']) || !empty($c['recipients']['list_id']))
                                        <div class="p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200/50">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                <span class="text-xs font-medium text-purple-700">Målgrupp</span>
                                            </div>
                                            <div class="text-sm text-purple-900">
                                                {{ $c['recipients']['list_name'] ?? 'Lista: ' . $c['recipients']['list_id'] }}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Performance Stats -->
                                @if($c['status'] === 'sent' && (!empty($c['emails_sent']) || !empty($c['report_summary'])))
                                    <div class="p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200/50 mb-4">
                                        <h4 class="text-sm font-semibold text-emerald-900 mb-3 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            Kampanjresultat
                                        </h4>

                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                            @if(!empty($c['emails_sent']))
                                                <div class="text-center">
                                                    <div class="text-lg font-bold text-emerald-900">{{ number_format($c['emails_sent']) }}</div>
                                                    <div class="text-xs text-emerald-700">Skickade</div>
                                                </div>
                                            @endif

                                            @if(!empty($c['report_summary']['opens']))
                                                <div class="text-center">
                                                    <div class="text-lg font-bold text-emerald-900">{{ number_format($c['report_summary']['opens']) }}</div>
                                                    <div class="text-xs text-emerald-700">Öppningar</div>
                                                </div>
                                            @endif

                                            @if(!empty($c['report_summary']['clicks']))
                                                <div class="text-center">
                                                    <div class="text-lg font-bold text-emerald-900">{{ number_format($c['report_summary']['clicks']) }}</div>
                                                    <div class="text-xs text-emerald-700">Klick</div>
                                                </div>
                                            @endif

                                            @if(!empty($c['unsubscribed']))
                                                <div class="text-center">
                                                    <div class="text-lg font-bold text-emerald-900">{{ number_format($c['unsubscribed']) }}</div>
                                                    <div class="text-xs text-emerald-700">Avregistrerade</div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Open and Click Rates -->
                                        @if(!empty($c['report_summary']['open_rate']) || !empty($c['report_summary']['click_rate']))
                                            <div class="mt-3 pt-3 border-t border-emerald-200">
                                                <div class="flex justify-center space-x-6 text-sm">
                                                    @if(!empty($c['report_summary']['open_rate']))
                                                        <div class="flex items-center">
                                                            <span class="text-emerald-700">Öppningsgrad:</span>
                                                            <span class="ml-1 font-semibold text-emerald-900">{{ number_format($c['report_summary']['open_rate'] * 100, 1) }}%</span>
                                                        </div>
                                                    @endif
                                                    @if(!empty($c['report_summary']['click_rate']))
                                                        <div class="flex items-center">
                                                            <span class="text-emerald-700">Klickgrad:</span>
                                                            <span class="ml-1 font-semibold text-emerald-900">{{ number_format($c['report_summary']['click_rate'] * 100, 1) }}%</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col space-y-3 lg:ml-6 lg:flex-shrink-0">
                                @if(!empty($c['archive_url']))
                                    <a href="{{ $c['archive_url'] }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white font-semibold rounded-xl hover:from-yellow-700 hover:to-orange-700 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Förhandsgranska
                                    </a>
                                @endif

                                @if(!empty($c['web_id']) && $c['status'] !== 'sent')
                                    <a href="https://{{ config('services.mailchimp.subdomain', 'admin') }}.mailchimp.com/campaigns/edit?id={{ $c['web_id'] }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Redigera
                                    </a>
                                @endif

                                @if(!empty($c['web_id']))
                                    <a href="https://{{ config('services.mailchimp.subdomain', 'admin') }}.mailchimp.com/reports/summary?id={{ $c['web_id'] }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                        Rapporter
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-gradient-to-br from-yellow-100 to-orange-200 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Inga kampanjer hittades</h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                            @if($statusFilter || $searchTerm || $dateFrom || $dateTo)
                                Inga kampanjer matchar dina sökkriterier. Prova att justera filtren ovan.
                            @else
                                Du har inte skapat några e-postkampanjer ännu. Kom igång genom att skapa ditt första nyhetsbrev!
                            @endif
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('marketing.newsletter') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-600 to-orange-600 text-white font-semibold rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Skapa din första kampanj
                            </a>
                            @if($statusFilter || $searchTerm || $dateFrom || $dateTo)
                                <button wire:click="clearFilters" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Rensa filter
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination if needed -->
        @if(count($campaigns) >= 20)
            <div class="flex justify-center">
                <button wire:click="loadMoreCampaigns" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                    Ladda fler kampanjer
                </button>
            </div>
        @endif
    </div>
</div>
