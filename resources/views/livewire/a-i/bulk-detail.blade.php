<div @if($bulk->status === 'done') @else wire:poll.3s="refreshData" @endif>
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Massgenerering #{{ $bulk->id }}</h1>
                <p class="mt-2 text-gray-600">
                    Status:
                    @if($bulk->status === 'pending')
                        <span class="text-yellow-600 font-medium">Väntar</span>
                    @elseif($bulk->status === 'processing')
                        <span class="text-blue-600 font-medium">Processar...</span>
                    @elseif($bulk->status === 'done')
                        <span class="text-green-600 font-medium">Klar</span>
                    @else
                        <span class="text-red-600 font-medium">Misslyckad</span>
                    @endif
                </p>
            </div>
            <a href="{{ route('ai.list') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                ← Tillbaka till översikt
            </a>
        </div>

        <!-- Progress -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-900">
                    Framsteg: {{ $bulk->completed_count }} / {{ $bulk->total_count }}
                </span>
                <span class="text-sm text-gray-600">{{ $bulk->getProgressPercentage() }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div
                    class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-500"
                    style="width: {{ $bulk->getProgressPercentage() }}%"
                ></div>
            </div>

            @if($bulk->status === 'processing')
                <p class="mt-3 text-sm text-gray-600">
                    Texterna genereras i bakgrunden. Uppdatera sidan för att se framsteg.
                </p>
            @endif
        </div>

        <!-- Template Info -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-indigo-200 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Textmall</h3>
            <p class="text-gray-800 font-mono">{{ $bulk->template_text }}</p>

            <div class="mt-4 grid grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Typ:</span>
                    <span class="ml-2 font-medium text-gray-900">{{ ucfirst($bulk->content_type) }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Längd:</span>
                    <span class="ml-2 font-medium text-gray-900">{{ $bulk->tone === 'short' ? 'Kort' : 'Lång' }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Webbplats:</span>
                    <span class="ml-2 font-medium text-gray-900">{{ $bulk->site?->name ?? 'Ingen' }}</span>
                </div>
            </div>
        </div>

        <!-- Generated Contents -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Genererade texter ({{ $bulk->contents->count() }})</h3>

            @if($bulk->contents->isEmpty())
                <p class="text-gray-600 text-center py-8">Inga texter har genererats än...</p>
            @else
                <div class="space-y-3">
                    @foreach($bulk->contents as $content)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-indigo-300 transition">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 truncate">
                                    {{ $content->title }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    Variabler: {{ json_encode($content->placeholders, JSON_UNESCAPED_UNICODE) }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-3 ml-4">
                                @if($content->status === 'queued')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Köad
                                    </span>
                                @elseif($content->status === 'processing')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Processar
                                    </span>
                                @elseif($content->status === 'ready')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Klar
                                    </span>
                                @elseif($content->status === 'published')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        Publicerad
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Misslyckad
                                    </span>
                                @endif

                                @if(in_array($content->status, ['ready', 'published']))
                                    <a
                                        href="{{ route('ai.detail', $content->id) }}"
                                        class="text-indigo-600 hover:text-indigo-700 text-sm font-medium"
                                    >
                                        Visa →
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
