<div>
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M22.051 9.319c-.425-2.539-2.306-4.446-4.844-4.922-.664-.123-1.321-.123-1.985-.009-1.17.198-2.226.742-3.051 1.567-1.567 1.566-2.477 3.712-2.488 5.872-.011 2.16.887 4.315 2.444 5.883 1.557 1.568 3.701 2.488 5.861 2.499s4.315-.887 5.883-2.444c1.568-1.557 2.488-3.701 2.499-5.861.011-2.151-.887-4.296-2.44-5.854-.311-.311-.644-.596-.994-.857-.35-.261-.717-.499-1.097-.715z"/>
                </svg>
                Mailchimp Historia
            </h1>
            <a href="{{ route('marketing.newsletter') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-600 to-orange-600 text-white font-semibold rounded-xl hover:from-yellow-700 hover:to-orange-700 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Skapa nytt nyhetsbrev
            </a>
        </div>

        <!-- Filter -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Filtrera kampanjer</h2>
                <div class="flex items-center space-x-3">
                    <label class="text-sm font-medium text-gray-700">Status:</label>
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
            </div>
        </div>

        <!-- Campaigns list -->
        <div class="space-y-4">
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

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/50 p-6 hover:shadow-2xl transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <!-- Status badge -->
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r {{ $colors['bg'] }} {{ $colors['border'] }} border rounded-full">
                                    <div class="w-3 h-3 {{ $colors['icon'] }} rounded-full mr-2"></div>
                                    <span class="text-xs font-medium {{ $colors['text'] }} uppercase">{{ $c['status'] ?? 'Okänd' }}</span>
                                </div>

                                @if(!empty($c['id']))
                                    <div class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-gray-50 to-white border border-gray-200/50 rounded-full">
                                        <span class="text-xs font-mono text-gray-600">{{ $c['id'] }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Campaign title -->
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    {{ $c['settings']['subject_line'] ?? '(Ingen ämnesrad)' }}
                                </h3>
                            </div>

                            <!-- Campaign details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200/50">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 2l6 2m-6-2l-6 2m12 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V9z"/>
                                        </svg>
                                        <span class="text-xs font-medium text-blue-700">Skapad</span>
                                    </div>
                                    <div class="text-sm text-blue-900">
                                        @if(!empty($c['create_time']))
                                            {{ \Carbon\Carbon::parse($c['create_time'])->format('Y-m-d H:i') }}
                                        @else
                                            —
                                        @endif
                                    </div>
                                </div>

                                @if(!empty($c['recipients']['list_id']))
                                    <div class="p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200/50">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <span class="text-xs font-medium text-purple-700">Lista ID</span>
                                        </div>
                                        <div class="text-sm font-mono text-purple-900">{{ $c['recipients']['list_id'] }}</div>
                                    </div>
                                @endif
                            </div>

                            <!-- Stats if available -->
                            @if(!empty($c['emails_sent']) || !empty($c['abuse_reports']) || !empty($c['unsubscribed']))
                                <div class="p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200/50 mb-4">
                                    <div class="grid grid-cols-3 gap-4 text-center">
                                        @if(!empty($c['emails_sent']))
                                            <div>
                                                <div class="text-sm font-semibold text-emerald-900">{{ number_format($c['emails_sent']) }}</div>
                                                <div class="text-xs text-emerald-700">Skickade</div>
                                            </div>
                                        @endif
                                        @if(!empty($c['abuse_reports']))
                                            <div>
                                                <div class="text-sm font-semibold text-emerald-900">{{ number_format($c['abuse_reports']) }}</div>
                                                <div class="text-xs text-emerald-700">Spam-rapporter</div>
                                            </div>
                                        @endif
                                        @if(!empty($c['unsubscribed']))
                                            <div>
                                                <div class="text-sm font-semibold text-emerald-900">{{ number_format($c['unsubscribed']) }}</div>
                                                <div class="text-xs text-emerald-700">Avregistrerade</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Action button -->
                        @if(!empty($c['archive_url']))
                            <div class="ml-6 flex-shrink-0">
                                <a href="{{ $c['archive_url'] }}" target="_blank" rel="noopener" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white font-semibold rounded-xl hover:from-yellow-700 hover:to-orange-700 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Öppna i Mailchimp
                                </a>
                            </div>
                        @endif
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
                        <p class="text-gray-600 mb-6">Du har inte skapat några e-postkampanjer ännu, eller så matchar inga kampanjer dina filterinställningar.</p>
                        <a href="{{ route('marketing.newsletter') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-600 to-orange-600 text-white font-semibold rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Skapa din första kampanj
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
