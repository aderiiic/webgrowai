<div>
    @if($show && $news)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50" wire:click="closePopup">
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full mx-auto transform transition-all" wire:click.stop>
                <!-- Stäng-knapp -->
                <button wire:click="closePopup" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <!-- Popup-innehåll -->
                <div class="p-6">
                    <!-- Ikon och typ -->
                    <div class="flex items-center gap-3 mb-4">
                        @php
                            $iconMap = [
                                'feature' => ['class' => 'text-emerald-600', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'bugfix' => ['class' => 'text-rose-600', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-2.694-.833-3.464 0L3.35 16.5c-.77.833.192 2.5 1.732 2.5z'],
                                'info' => ['class' => 'text-blue-600', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ];
                            $config = $iconMap[$news->type] ?? $iconMap['info'];
                        @endphp

                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 {{ $config['class'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $news->title }}</h3>
                            <p class="text-sm text-gray-500">{{ $news->published_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <!-- Innehåll -->
                    <div class="text-gray-700 mb-6 prose prose-sm max-w-none">
                        {!! str($news->body_md)->markdown() !!}
                    </div>

                    <!-- Taggar -->
                    @if($news->tags)
                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach($news->tagsArray as $tag)
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Stäng-knapp -->
                    <div class="flex justify-center">
                        <button wire:click="closePopup" class="px-6 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Förstått
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
