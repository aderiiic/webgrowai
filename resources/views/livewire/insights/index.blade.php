<div class="max-w-7xl mx-auto space-y-8" @insights-queued.window="window.dispatchEvent(new CustomEvent('showToast',{detail:{type:'info',text:'Jobb startat – uppdaterar automatiskt'}}))" @insights-ready.window="window.dispatchEvent(new CustomEvent('showToast',{detail:{type:'success',text:'Klart! Nya insights är redo'}}))">

    {{-- Flash-meddelanden --}}
    @if (session('success'))
        <div class="rounded-md bg-green-50 border border-green-200 p-3 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="rounded-md bg-red-50 border border-red-200 p-3 text-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Automatisk polling när jobb körs --}}
    @if($polling)
        <div wire:poll.10s="pollForCompletion"></div>
    @endif

    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl text-white p-8">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h1 class="text-3xl font-bold">Content Insights</h1>
                </div>
                <p class="text-indigo-100">Upptäck trender och optimera ditt innehåll för maximal räckvidd</p>
            </div>
            @if($siteInsight)
                <div class="text-right">
                    <div class="text-sm text-indigo-200">Vecka {{ $weekLabel }}</div>
                    <div class="text-xs text-indigo-300">
                        Uppdaterad {{ $siteInsight->generated_at->diffForHumans() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($siteInsight)
        <!-- Huvudsammanfattning -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h2 class="text-xl font-semibold mb-4">Veckans fokus (v. {{ $weekLabel }})</h2>
            <p class="text-gray-700 text-lg leading-relaxed">{{ $siteInsight->payload['summary'] ?? '' }}</p>
        </div>

        <!-- Grid med insights -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Trending Topics -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold">Trender just nu</h3>
                    </div>

                    <div class="space-y-3">
                        @forelse($siteInsight->trending_topics as $trend)
                            <div class="bg-red-50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-medium text-red-900">{{ $trend['topic'] }}</h4>
                                    @if(isset($trend['trend_score']))
                                        <span class="text-sm text-red-600 bg-red-100 px-2 py-1 rounded">{{ $trend['trend_score'] }}%</span>
                                    @endif
                                </div>
                                <p class="text-sm text-red-700 mb-2">{{ $trend['why'] }}</p>
                                <button
                                    wire:click="createContentFromTopic({{ \Illuminate\Support\Js::from($trend['topic']) }})"
                                    class="text-xs text-red-600 hover:text-red-800 font-medium bg-red-100 hover:bg-red-200 px-2 py-1 rounded transition-colors">
                                    Skapa innehåll om detta →
                                </button>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Inga trenddata tillgängliga än</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Ämnesförslag -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold">Ämnesidéer för dig</h3>
                    </div>

                    <div class="space-y-3">
                        @foreach($siteInsight->topics as $topic)
                            <div class="bg-blue-50 rounded-lg p-4">
                                <h4 class="font-medium text-blue-900 mb-1">{{ $topic['title'] }}</h4>
                                <p class="text-sm text-blue-700 mb-2">{{ $topic['why'] }}</p>
                                <button
                                    wire:click="createContentFromTopic({{ \Illuminate\Support\Js::from($topic['title']) }})"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium bg-blue-100 hover:bg-blue-200 px-2 py-1 rounded transition-colors">
                                    Skapa innehåll om detta →
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Bästa tidpunkter -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold">Bästa tidpunkterna</h3>
                    </div>

                    <div class="space-y-3">
                        @foreach($siteInsight->timeslots as $slot)
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-medium text-green-900">{{ $slot['dow'] }}</h4>
                                    <span class="text-sm font-mono text-green-700 bg-green-100 px-2 py-1 rounded">{{ $slot['time'] }}</span>
                                </div>
                                <p class="text-sm text-green-700">{{ $slot['why'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Rekommenderade hashtags (sammanvägt) -->
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold">Trending Hashtags</h3>
                        </div>
                        @if(!empty($siteInsight->recommended_hashtags))
                            <button wire:click="copyHashtags"
                                    class="flex items-center space-x-2 text-xs text-purple-600 hover:text-purple-800 font-medium bg-purple-100 hover:bg-purple-200 px-3 py-1 rounded transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <span>Kopiera alla</span>
                            </button>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @forelse($siteInsight->recommended_hashtags as $hashtag)
                            <button type="button"
                                    class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm hover:bg-purple-200 cursor-pointer transition-colors"
                                    onclick="navigator.clipboard.writeText({{ \Illuminate\Support\Js::from($hashtag) }}); window.dispatchEvent(new CustomEvent('showToast',{detail:{type:'info',text: {{ \Illuminate\Support\Js::from($hashtag.' kopierat!') }} }}));">
                                {{ $hashtag }}
                            </button>
                        @empty
                            <p class="text-gray-500 text-sm">Inga hashtag-förslag tillgängliga</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        @php
            $hasBuckets = !empty($siteInsight->trends_data);
            $googleTopics = $siteInsight->trends_data['topics_by_source']['google'] ?? [];
            $liContent = $siteInsight->trends_data['popular_content_by_platform']['linkedin'] ?? [];
            $igContent = $siteInsight->trends_data['popular_content_by_platform']['instagram'] ?? [];
            $fbContent = $siteInsight->trends_data['popular_content_by_platform']['facebook'] ?? [];
            $liTags = $siteInsight->trends_data['hashtags_by_platform']['linkedin'] ?? [];
            $igTags = $siteInsight->trends_data['hashtags_by_platform']['instagram'] ?? [];
            $fbTags = $siteInsight->trends_data['hashtags_by_platform']['facebook'] ?? [];

            $hasGoogle = count($googleTopics) > 0;
            $hasLinkedIn = count($liContent) > 0 || count($liTags) > 0;
            $hasInstagram = count($igContent) > 0 || count($igTags) > 0;
            $hasFacebook = count($fbContent) > 0 || count($fbTags) > 0;
        @endphp

        @if($hasBuckets && ($hasGoogle || $hasLinkedIn || $hasInstagram || $hasFacebook))
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="p-6 space-y-8">
                    <h3 class="text-lg font-semibold">Vad är populärt – per kanal</h3>

                    <!-- Google: söktrender -->
                    @if($hasGoogle)
                        <div>
                            <div class="flex items-center space-x-2 mb-3">
                                <img src="https://www.google.com/favicon.ico" class="w-4 h-4" alt="Google">
                                <h4 class="font-medium text-gray-900">Söktrender på Google</h4>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($googleTopics as $topic)
                                    <span class="text-sm bg-gray-100 px-3 py-1 rounded-full">{{ $topic }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- LinkedIn endast om data finns --}}
                    @if($hasLinkedIn)
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <div class="flex items-center space-x-2 mb-3">
                                    <svg class="w-4 h-4 text-blue-700" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M4.98 3.5C4.98 4.88 3.88 6 2.5 6S0 4.88 0 3.5C0 2.12 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM.22 8.98h4.56V24H.22zM8.88 8.98h4.37v2.05h.06c.61-1.16 2.1-2.38 4.33-2.38 4.63 0 5.48 3.05 5.48 7.01V24h-4.76v-7.01c0-1.67-.03-3.82-2.33-3.82-2.34 0-2.7 1.83-2.7 3.71V24H8.88z"/>
                                    </svg>
                                    <h4 class="font-medium text-gray-900">Populärt på LinkedIn</h4>
                                </div>
                                <div class="space-y-2">
                                    @foreach($liContent as $item)
                                        <div class="p-3 bg-blue-50 rounded border border-blue-100">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-blue-900">{{ $item['title'] ?? '' }}</span>
                                                @if(!empty($item['engagement_score']))
                                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded">{{ $item['engagement_score'] }}</span>
                                                @endif
                                            </div>
                                            @if(!empty($item['why']))
                                                <div class="text-xs text-blue-700 mt-1">{{ $item['why'] }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2 mb-3">
                                    <svg class="w-4 h-4 text-blue-700" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M4.98 3.5C4.98 4.88 3.88 6 2.5 6S0 4.88 0 3.5C0 2.12 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM.22 8.98h4.56V24H.22zM8.88 8.98h4.37v2.05h.06c.61-1.16 2.1-2.38 4.33-2.38 4.63 0 5.48 3.05 5.48 7.01V24h-4.76v-7.01c0-1.67-.03-3.82-2.33-3.82-2.34 0-2.7 1.83-2.7 3.71V24H8.88z"/>
                                    </svg>
                                    <h4 class="font-medium text-gray-900">Populära hashtags på LinkedIn</h4>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($liTags as $tag)
                                        <button type="button" class="bg-blue-50 text-blue-800 px-3 py-1 rounded-full text-sm hover:bg-blue-100"
                                                onclick="navigator.clipboard.writeText({{ \Illuminate\Support\Js::from($tag) }});">
                                            {{ $tag }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Instagram endast om data finns --}}
                    @if($hasInstagram)
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <div class="flex items-center space-x-2 mb-3">
                                    <svg class="w-4 h-4 text-pink-600" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.9.2 2.3.4.6.2 1 .5 1.5 1 .4.4.8.9 1 1.5.2.4.3 1.1.4 2.3.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.2 1.9-.4 2.3-.2.6-.5 1-1 1.5-.4.4-.9.8-1.5 1-.4.2-1.1.3-2.3.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-1.9-.2-2.3-.4-.6-.2-1-.5-1.5-1a4 4 0 01-1-1.5c-.2-.4-.3-1.1-.4-2.3C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.2-1.9.4-2.3.2-.6.5-1 1-1.5.4-.4.9-.8 1.5-1 .4-.2 1.1-.3 2.3-.4C8.4 2.2 8.8 2.2 12 2.2M12 0C8.7 0 8.3 0 7 0.1 5.7 0.2 4.8 0.4 4 0.7 3.1 1 2.3 1.6 1.6 2.3 0.9 3 0.3 3.8 0.1 4.7 0 5.5-0 6.4 0.1 7.7 0.2 9 0.2 9.4 0.2 12s0 3 .1 4.3C0.2 17.6 0.4 18.5 0.7 19.3c.3.9.9 1.7 1.6 2.4.7.7 1.5 1.3 2.4 1.6.8.3 1.7.5 3 .6 1.3.1 1.7.1 4.3.1s3 0 4.3-.1c1.3-.1 2.2-.3 3-.6.9-.3 1.7-.9 2.4-1.6.7-.7 1.3-1.5 1.6-2.4.3-.8.5-1.7.6-3 .1-1.3.1-1.7.1-4.3s0-3-.1-4.3c-.1-1.3-.3-2.2-.6-3-.3-.9-.9-1.7-1.6-2.4-.7-.7-1.5-1.3-2.4-1.6-.8-.3-1.7-.5-3-.6C15 0 14.6 0 12 0zm0 5.8a6.2 6.2 0 100 12.4 6.2 6.2 0 000-12.4zm0 10.2a4 4 0 110-8 4 4 0 010 8zM18.4 4.6a1.4 1.4 0 100 2.8 1.4 1.4 0 000-2.8z"/>
                                    </svg>
                                    <h4 class="font-medium text-gray-900">Populärt på Instagram</h4>
                                </div>
                                <div class="space-y-2">
                                    @foreach($igContent as $item)
                                        <div class="p-3 bg-pink-50 rounded border border-pink-100">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-pink-900">{{ $item['title'] ?? '' }}</span>
                                                @if(!empty($item['engagement_score']))
                                                    <span class="text-xs bg-pink-100 text-pink-700 px-2 py-0.5 rounded">{{ $item['engagement_score'] }}</span>
                                                @endif
                                            </div>
                                            @if(!empty($item['why']))
                                                <div class="text-xs text-pink-700 mt-1">{{ $item['why'] }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2 mb-3">
                                    <svg class="w-4 h-4 text-pink-600" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.9.2 2.3.4.6.2 1 .5 1.5 1 .4.4.8.9 1 1.5.2.4.3 1.1.4 2.3.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.2 1.9-.4 2.3-.2.6-.5 1-1 1.5-.4.4-.9.8-1.5 1-.4.2-1.1.3-2.3.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-1.9-.2-2.3-.4-.6-.2-1-.5-1.5-1a4 4 0 01-1-1.5c-.2-.4-.3-1.1-.4-2.3C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.2-1.9.4-2.3.2-.6.5-1 1-1.5.4-.4.9-.8 1.5-1 .4-.2 1.1-.3 2.3-.4C8.4 2.2 8.8 2.2 12 2.2M12 0C8.7 0 8.3 0 7 0.1 5.7 0.2 4.8 0.4 4 0.7 3.1 1 2.3 1.6 1.6 2.3 0.9 3 0.3 3.8 0.1 4.7 0 5.5-0 6.4 0.1 7.7 0.2 9 0.2 9.4 0.2 12s0 3 .1 4.3C0.2 17.6 0.4 18.5 0.7 19.3c.3.9.9 1.7 1.6 2.4.7.7 1.5 1.3 2.4 1.6.8.3 1.7.5 3 .6 1.3.1 1.7.1 4.3.1s3 0 4.3-.1c1.3-.1 2.2-.3 3-.6.9-.3 1.7-.9 2.4-1.6.7-.7 1.3-1.5 1.6-2.4.3-.8.5-1.7.6-3 .1-1.3.1-1.7.1-4.3s0-3-.1-4.3c-.1-1.3-.3-2.2-.6-3-.3-.9-.9-1.7-1.6-2.4-.7-.7-1.5-1.3-2.4-1.6-.8-.3-1.7-.5-3-.6C15 0 14.6 0 12 0zm0 5.8a6.2 6.2 0 100 12.4 6.2 6.2 0 000-12.4zm0 10.2a4 4 0 110-8 4 4 0 010 8zM18.4 4.6a1.4 1.4 0 100 2.8 1.4 1.4 0 000-2.8z"/>
                                    </svg>
                                    <h4 class="font-medium text-gray-900">Populära hashtags på Instagram</h4>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($igTags as $tag)
                                        <button type="button" class="bg-pink-50 text-pink-800 px-3 py-1 rounded-full text-sm hover:bg-pink-100"
                                                onclick="navigator.clipboard.writeText({{ \Illuminate\Support\Js::from($tag) }});">
                                            {{ $tag }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Facebook endast om data finns --}}
                    @if($hasFacebook)
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <div class="flex items-center space-x-2 mb-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M22 12.07C22 6.48 17.52 2 11.93 2 6.35 2 1.86 6.48 1.86 12.07c0 4.99 3.66 9.13 8.44 9.93v-7.03H7.9v-2.9H10.3V9.41c0-2.37 1.41-3.68 3.56-3.68 1.03 0 2.1.18 2.1.18v2.3h-1.18c-1.17 0-1.54.73-1.54 1.48v1.78h2.63l-.42 2.9h-2.21V22c4.78-.8 8.44-4.94 8.44-9.93z"/>
                                    </svg>
                                    <h4 class="font-medium text-gray-900">Populärt på Facebook</h4>
                                </div>
                                <div class="space-y-2">
                                    @foreach($fbContent as $item)
                                        <div class="p-3 bg-blue-50 rounded border border-blue-100">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-blue-900">{{ $item['title'] ?? '' }}</span>
                                                @if(!empty($item['engagement_score']))
                                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded">{{ $item['engagement_score'] }}</span>
                                                @endif
                                            </div>
                                            @if(!empty($item['why']))
                                                <div class="text-xs text-blue-700 mt-1">{{ $item['why'] }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2 mb-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M22 12.07C22 6.48 17.52 2 11.93 2 6.35 2 1.86 6.48 1.86 12.07c0 4.99 3.66 9.13 8.44 9.93v-7.03H7.9v-2.9H10.3V9.41c0-2.37 1.41-3.68 3.56-3.68 1.03 0 2.1.18 2.1.18v2.3h-1.18c-1.17 0-1.54.73-1.54 1.48v1.78h2.63l-.42 2.9h-2.21V22c4.78-.8 8.44-4.94 8.44-9.93z"/>
                                    </svg>
                                    <h4 class="font-medium text-gray-900">Populära hashtags på Facebook</h4>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($fbTags as $tag)
                                        <button type="button" class="bg-blue-50 text-blue-800 px-3 py-1 rounded-full text-sm hover:bg-blue-100"
                                                onclick="navigator.clipboard.writeText({{ \Illuminate\Support\Js::from($tag) }});">
                                            {{ $tag }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Handlingsplan -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 12a5 5 0 0010 0"/>
                    </svg>
                    <h3 class="text-lg font-semibold">Din handlingsplan</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($siteInsight->actions as $action)
                        <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-indigo-500">
                            <h4 class="font-medium text-gray-900 mb-2">{{ $action['action'] }}</h4>
                            <p class="text-sm text-gray-600 mb-3">{{ $action['why'] }}</p>
                            <button class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700 transition-colors">
                                Starta nu
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Rationale -->
        <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-6 border border-gray-200">
            <div class="flex items-center space-x-3 mb-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="font-semibold text-gray-900">Varför dessa rekommendationer?</h3>
            </div>
            <p class="text-gray-700">{{ $siteInsight->payload['rationale'] ?? '' }}</p>
        </div>

        <!-- Uppdatera insights -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-1">Vill du ha fräschare insights?</h3>
                    <p class="text-gray-600 text-sm">Generera nya baserat på de senaste trenderna</p>
                </div>
                <button
                    wire:click="generateInsights(true)"
                    @if($loading || $polling) disabled @endif
                    class="flex items-center space-x-2 bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    @if($loading || $polling)
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Genererar...</span>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Uppdatera insights</span>
                    @endif
                </button>
            </div>
        </div>

    @else
        <!-- Ingen data tillgänglig -->
        <div class="bg-white rounded-xl shadow-sm border p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Inga insights tillgängliga än</h3>
            <p class="text-gray-600 mb-4">Vi arbetar på att generera personliga insights för din hemsida.</p>
            <button
                wire:click="generateInsights(true)"
                @if($loading || $polling) disabled @endif
                class="flex items-center space-x-2 bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50 mx-auto">
                @if($loading || $polling)
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Genererar...</span>
                @else
                    <span>Generera insights nu</span>
                @endif
            </button>
        </div>
    @endif

    <script>
        window.addEventListener('copyToClipboard', event => {
            navigator.clipboard.writeText(event.detail.text).then(() => {
                window.dispatchEvent(new CustomEvent('showToast',{detail:{type:'success',text:'Kopierat till urklipp'}}));
            });
        });

        // Minimal toast (kan bytas mot valfritt lib)
        window.addEventListener('showToast', (e) => {
            const d = e.detail || {};
            const el = document.createElement('div');
            el.className = 'fixed bottom-4 right-4 z-50 px-4 py-2 rounded shadow text-white ' + (d.type === 'error' ? 'bg-red-600' : d.type === 'info' ? 'bg-indigo-600' : 'bg-green-600');
            el.textContent = d.text || 'Ok';
            document.body.appendChild(el);
            setTimeout(() => { el.remove(); }, 3000);
        });
    </script>
</div>
