<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Alla Sajter</h1>
                    <p class="mt-2 text-sm text-gray-600">Hantera och sök bland alla kundsajter</p>
                </div>
            </div>
        </div>

        <!-- Search Section -->
        <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       placeholder="Sök efter sajtnamn, domän eller kundnamn...">
            </div>
        </div>

        <!-- Sites Grid -->
        @if($sites->count() > 0)
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($sites as $site)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <div class="p-6">
                            <!-- Site Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $site->name }}</h3>
                                    @if($site->domain)
                                        <a href="https://{{ $site->domain }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center">
                                            {{ $site->domain }}
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Customer Info -->
                            <div class="mb-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-blue-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">
                                            {{ substr($site->customer->name ?? '#'.$site->customer_id, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $site->customer->name ?? 'Okänd kund' }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $site->customer_id }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Integration Status -->
                            @if($site->integrations->count() > 0)
                                <div class="mb-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($site->integrations as $integration)
                                            @php
                                                $statusColors = [
                                                    'connected' => 'bg-green-100 text-green-800',
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'error' => 'bg-red-100 text-red-800',
                                                ];
                                                $colorClass = $statusColors[$integration->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                                {{ ucfirst($integration->provider) }}: {{ ucfirst($integration->status) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Site Meta -->
                            <div class="text-xs text-gray-500 mb-4">
                                Skapad: {{ $site->created_at->format('Y-m-d H:i') }}
                            </div>

                            <!-- Action Button -->
                            <div class="flex justify-end">
                                <a href="{{ route('admin.customers.show', $site->customer_id) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Visa kund
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $sites->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Inga sajter hittades</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($search)
                        Inga sajter matchar sökningen "{{ $search }}".
                    @else
                        Det finns inga sajter registrerade ännu.
                    @endif
                </p>
                @if($search)
                    <div class="mt-6">
                        <button wire:click="$set('search', '')"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Rensa sökning
                        </button>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
