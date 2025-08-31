{{-- resources/views/livewire/analytics/overview.blade.php --}}
<div>
    <div class="max-w-7xl mx-auto space-y-8">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Analys
                @if($activeSiteId) <span class="ml-2 text-lg text-gray-600">(Sajt #{{ $activeSiteId }})</span>@endif
            </h1>
        </div>

        @if($activeSiteId)
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <p class="text-sm text-blue-800">Visar statistik för vald sajt.</p>
            </div>
        @else
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <p class="text-sm text-amber-800">Ingen specifik sajt vald. Visar aggregerad bild för alla sajter.</p>
            </div>
        @endif

        <a href="{{ route('analytics.ga4.connect') }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-lg">
            Koppla GA4
        </a>

        {{-- Starter: Webb + Publicering + Social bas --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Webbtrafik --}}
            <div class="bg-white rounded-2xl shadow p-6 border">
                <h3 class="font-semibold mb-3">Webbtrafik (7 dagar)</h3>
                @if($website['connected'])
                    <div class="text-sm text-gray-700 space-y-1">
                        <div>Besökare: <span class="font-semibold">{{ $website['visitors_7d'] }}</span></div>
                        <div>Sessions: <span class="font-semibold">{{ $website['sessions_7d'] }}</span></div>
                        <div>Trend: <span class="font-semibold {{ $website['trend_pct'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ $website['trend_pct'] }}%</span></div>
                    </div>
                @else
                    <div class="text-sm text-gray-600">Ingen webb-integration hittad. <a href="{{ route('settings.social') }}" class="text-indigo-600 underline">Koppla</a></div>
                @endif
            </div>

            {{-- Publiceringar --}}
            <div class="bg-white rounded-2xl shadow p-6 border">
                <h3 class="font-semibold mb-3">Publiceringar</h3>
                <div class="text-sm text-gray-700 space-y-1">
                    <div>Publicerade (30d): <span class="font-semibold">{{ $publications['published_30d'] }}</span></div>
                    <div>Misslyckade (30d): <span class="font-semibold">{{ $publications['failed_30d'] }}</span></div>
                    <div>Genomsnitt/vecka: <span class="font-semibold">{{ $publications['avg_per_week'] }}</span></div>
                </div>
            </div>

            {{-- Social bas --}}
            <div class="bg-white rounded-2xl shadow p-6 border">
                <h3 class="font-semibold mb-3">Socialt (7 dagar)</h3>
                <div class="text-sm text-gray-700 space-y-2">
                    @foreach(['facebook' => 'Facebook', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn'] as $key => $label)
                        @php($m = $social[$key] ?? null)
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ $label }}</span>
                            @if($m && $m['connected'])
                                <span class="font-semibold">Reach: {{ $m['reach'] }}, Eng: {{ $m['engagement'] }}</span>
                            @else
                                <a href="{{ route('settings.social') }}" class="text-indigo-600 underline">Koppla</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Avancerad sektion för större planer --}}
        @if($cap->advanced)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl shadow p-6 border lg:col-span-2">
                    <h3 class="font-semibold mb-3">Toppinnehåll (30 dagar)</h3>
                    @if(empty($advanced['topContent']))
                        <div class="text-sm text-gray-600">Ingen data ännu.</div>
                    @else
                        <ul class="text-sm space-y-2">
                            @foreach($advanced['topContent'] as $row)
                                <li class="flex justify-between">
                                    <span class="truncate">{{ $row['title'] }}</span>
                                    <span class="font-semibold">{{ $row['score'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="bg-white rounded-2xl shadow p-6 border">
                    <h3 class="font-semibold mb-3">Bästa tider att posta</h3>
                    @if(empty($advanced['bestPostTimes']))
                        <div class="text-sm text-gray-600">Ingen data ännu.</div>
                    @else
                        <div class="text-sm text-gray-700">
                            @foreach($advanced['bestPostTimes'] as $row)
                                <div>{{ $row['day'] }}: <span class="font-semibold">{{ $row['hour'] }}</span></div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl shadow p-6 border">
                    <h3 class="font-semibold mb-3">Engagemangstrend</h3>
                    <div class="text-sm text-gray-700">
                        30d: <span class="font-semibold {{ ($advanced['engagementTrends']['pct_30d'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ $advanced['engagementTrends']['pct_30d'] ?? 0 }}%</span>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow p-6 border">
                    <h3 class="font-semibold mb-3">Sajter jämförelse</h3>
                    @if($activeSiteId)
                        <div class="text-sm text-gray-600">Välj “Alla sajter” för att jämföra.</div>
                    @else
                        @if(empty($advanced['siteCompare']))
                            <div class="text-sm text-gray-600">Ingen data.</div>
                        @else
                            <ul class="text-sm space-y-2">
                                @foreach($advanced['siteCompare'] as $row)
                                    <li class="flex justify-between">
                                        <span class="truncate">{{ $row['site_name'] }}</span>
                                        <span class="font-semibold">Score: {{ $row['score'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @endif
                </div>
                <div class="bg-white rounded-2xl shadow p-6 border">
                    <h3 class="font-semibold mb-3">Insikter</h3>
                    @if(!$advanced['insights'])
                        <div class="text-sm text-gray-600">Ingen data ännu.</div>
                    @else
                        <ul class="list-disc ml-5 text-sm text-gray-700 space-y-2">
                            @foreach($advanced['insights'] as $tip)
                                <li>{{ $tip }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endif

        {{-- Digest-info för större planer --}}
        @if($cap->advanced && $cap->digest)
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                <div class="text-sm text-emerald-800">
                    Veckodigest skickas automatiskt på söndagar kväll. Hantera inställningar under “Veckodigest”.
                </div>
            </div>
        @endif
    </div>
</div>
