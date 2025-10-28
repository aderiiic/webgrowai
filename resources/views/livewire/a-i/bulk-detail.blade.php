<div @if($isPublishing) wire:poll.2s="refreshData" @else wire:poll.5s="refreshData" @endif>
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

        <!-- Publishing Progress Banner -->
        @if($isPublishing)
            @php
                $total = count($publishingProgress);
                $completed = count(array_filter($publishingProgress, fn($s) => in_array($s, ['published', 'failed'])));
                $published = count(array_filter($publishingProgress, fn($s) => $s === 'published'));
                $failed = count(array_filter($publishingProgress, fn($s) => $s === 'failed'));
                $progressPercent = $total > 0 ? round(($completed / $total) * 100) : 0;
            @endphp
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white mr-4"></div>
                        <div>
                            <h3 class="text-xl font-bold">Publicering pågår...</h3>
                            <p class="text-blue-100 text-sm mt-1">
                                {{ $published }} publicerade • {{ $failed }} misslyckades • {{ $total - $completed }} kvar
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold">{{ $progressPercent }}%</div>
                        <div class="text-blue-100 text-sm">{{ $completed }}/{{ $total }}</div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-blue-400 bg-opacity-30 rounded-full h-3">
                    <div
                        class="bg-white h-3 rounded-full transition-all duration-500 ease-out"
                        style="width: {{ $progressPercent }}%"
                    ></div>
                </div>
            </div>
        @endif

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
                    Texterna genereras i bakgrunden. Sidan uppdateras automatiskt.
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

        <!-- Publish Controls -->
        @if($bulk->status === 'done' && $bulk->contents->filter(fn($c) => in_array($c->status, ['ready', 'completed']))->isNotEmpty() && !$isPublishing)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Publicera texter</h3>

                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
                    <!-- Selection Controls -->
                    <div class="flex items-center space-x-4">
                        <button
                            wire:click="selectAll"
                            class="text-sm font-medium text-indigo-600 hover:text-indigo-700"
                        >
                            Markera alla
                        </button>
                        <span class="text-gray-300">|</span>
                        <button
                            wire:click="deselectAll"
                            class="text-sm font-medium text-gray-600 hover:text-gray-700"
                        >
                            Avmarkera alla
                        </button>
                        @if(count($selectedContents) > 0)
                            <span class="text-sm text-gray-600">
                                ({{ count($selectedContents) }} markerade)
                            </span>
                        @endif
                    </div>

                    <!-- Publish Status Selection -->
                    <div class="flex items-center space-x-3">
                        <label class="inline-flex items-center cursor-pointer">
                            <input
                                type="radio"
                                wire:model="publishStatus"
                                value="draft"
                                class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                            >
                            <span class="ml-2 text-sm text-gray-700">Spara som utkast</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input
                                type="radio"
                                wire:model="publishStatus"
                                value="publish"
                                class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                            >
                            <span class="ml-2 text-sm text-gray-700">Publicera direkt</span>
                        </label>
                    </div>
                </div>

                <!-- Publish Button -->
                @if(count($selectedContents) > 0)
                    <button
                        wire:click="openConfirmModal"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Publicera ({{ count($selectedContents) }})
                    </button>
                @endif
            </div>
        @endif

        <!-- Generated Contents -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Genererade texter ({{ $bulk->contents->count() }})</h3>

            @if($bulk->contents->isEmpty())
                <p class="text-gray-600 text-center py-8">Inga texter har genererats än...</p>
            @else
                <div class="space-y-3">
                    @foreach($bulk->contents as $content)
                        @php
                            // Kolla om denna text håller på att publiceras
                            $isBeingPublished = isset($publishingProgress[$content->id]);
                            $publishProgress = $isBeingPublished ? $publishingProgress[$content->id] : null;

                            // Kolla om texten redan har publicerats tidigare
                            $hasPublication = $content->publications()->exists();
                            $latestPublication = $hasPublication ? $content->publications()->latest()->first() : null;
                        @endphp

                        <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-indigo-300 transition {{ in_array($content->id, $selectedContents) ? 'bg-indigo-50 border-indigo-400' : '' }}">
                            <!-- Checkbox -->
                            @if(in_array($content->status, ['ready', 'completed']) && !$isBeingPublished)
                                <div class="flex-shrink-0 mr-4">
                                    <input
                                        type="checkbox"
                                        wire:click="toggleSelection({{ $content->id }})"
                                        @checked(in_array($content->id, $selectedContents))
                                        class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer"
                                    />
                                </div>
                            @else
                                <div class="flex-shrink-0 mr-4 w-5"></div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 truncate">
                                    {{ $content->title }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    Variabler: {{ json_encode($content->placeholders, JSON_UNESCAPED_UNICODE) }}
                                </p>
                            </div>

                            <div class="flex items-center space-x-3 ml-4">
                                <!-- Publishing Progress Status -->
                                @if($isBeingPublished)
                                    @if($publishProgress === 'queued')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 animate-pulse">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <circle cx="10" cy="10" r="8"/>
                                            </svg>
                                            Köad
                                        </span>
                                    @elseif($publishProgress === 'processing')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="animate-spin w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Publicerar...
                                        </span>
                                    @elseif($publishProgress === 'published')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Publicerad!
                                        </span>
                                    @elseif($publishProgress === 'failed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Misslyckades
                                        </span>
                                    @endif
                                @elseif($hasPublication)
                                    <!-- Visa tidigare publikationsstatus -->
                                    @if($latestPublication->status === 'published')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Tidigare publicerad
                                        </span>
                                    @elseif($latestPublication->status === 'failed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Tidigare misslyckad
                                        </span>
                                    @endif
                                @else
                                    <!-- Regular status -->
                                    @if($content->status === 'queued')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Genereras
                                        </span>
                                    @elseif($content->status === 'processing')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Processar
                                        </span>
                                    @elseif($content->status === 'ready' || $content->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Klar att publicera
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($content->status) }}
                                        </span>
                                    @endif
                                @endif

                                @if(in_array($content->status, ['ready', 'published', 'completed']))
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

    <!-- Confirmation Modal -->
    @if($showConfirmModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    wire:click="$set('showConfirmModal', false)"
                ></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                <h3 class="text-lg leading-6 font-semibold text-gray-900" id="modal-title">
                                    Bekräfta publicering
                                </h3>
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600">
                                        Vill du
                                        <strong class="text-gray-900">
                                            {{ $publishStatus === 'publish' ? 'publicera direkt' : 'spara som utkast' }}
                                        </strong>
                                        {{ count($selectedContents) }}
                                        {{ count($selectedContents) === 1 ? 'inlägg' : 'inlägg' }}?
                                    </p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        {{ $publishStatus === 'publish'
                                            ? 'Inläggen kommer att publiceras direkt på din WordPress-webbplats.'
                                            : 'Inläggen kommer att sparas som utkast i WordPress.'
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3">
                        <button
                            type="button"
                            wire:click="confirmPublish"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-base font-semibold text-white hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:w-auto sm:text-sm"
                        >
                            Ja, {{ $publishStatus === 'publish' ? 'publicera' : 'spara som utkast' }}
                        </button>
                        <button
                            type="button"
                            wire:click="$set('showConfirmModal', false)"
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
                        >
                            Avbryt
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
