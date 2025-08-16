<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Fakturor</h1>
                    <p class="mt-2 text-sm text-gray-600">Hantera och övervaka alla kundfakturor</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Status Statistics -->
                    <div class="hidden md:flex items-center space-x-3">
                        @php
                            $statusCounts = collect($rows->items())->groupBy('status')->map->count();
                        @endphp
                        <div class="inline-flex items-center px-3 py-2 bg-yellow-50 text-yellow-700 text-sm font-medium rounded-lg">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                            {{ $statusCounts->get('draft', 0) }} Draft
                        </div>
                        <div class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-700 text-sm font-medium rounded-lg">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                            {{ $statusCounts->get('sent', 0) }} Skickade
                        </div>
                        <div class="inline-flex items-center px-3 py-2 bg-green-50 text-green-700 text-sm font-medium rounded-lg">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            {{ $statusCounts->get('paid', 0) }} Betalda
                        </div>
                    </div>
                    <a href="#"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Ny faktura
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-64">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sök kund</label>
                    <div class="relative">
                        <input type="text" wire:model.live="q"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                               placeholder="Sök efter kundnamn...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status filter</label>
                    <select wire:model.live="status"
                            class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-white">
                        <option value="all">📄 Alla status</option>
                        <option value="draft">📝 Draft</option>
                        <option value="sent">📤 Skickade</option>
                        <option value="paid">💰 Betalda</option>
                        <option value="overdue">⚠️ Förfallna</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kund</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Belopp</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Åtgärder</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($rows as $inv)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">
                                                {{ substr($inv->customer_name, 0, 1) }}
                                            </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $inv->customer_name }}</div>
                                        <div class="text-xs text-gray-500">ID: #{{ $inv->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="p-2 bg-gray-100 rounded-lg">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-900">{{ $inv->period }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="p-2 bg-green-100 rounded-lg">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ number_format($inv->total_amount / 100, 2, ',', ' ') }} kr
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $inv->currency ?? 'SEK' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($inv->status)
                                    @case('draft')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                                Draft
                                            </span>
                                        @break
                                    @case('sent')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                                Skickad
                                            </span>
                                        @break
                                    @case('paid')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                Betald
                                            </span>
                                        @break
                                    @case('overdue')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></div>
                                                Förfallen
                                            </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <div class="w-2 h-2 bg-gray-500 rounded-full mr-2"></div>
                                                {{ ucfirst($inv->status) }}
                                            </span>
                                @endswitch
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.invoices.show', $inv->id) }}"
                                   class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Öppna
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h3 class="text-sm font-medium text-gray-900 mb-1">Inga fakturor hittades</h3>
                                    <p class="text-sm text-gray-500">Det finns inga fakturor som matchar dina filter</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($rows->hasPages())
                <div class="bg-white px-6 py-4 border-t border-gray-200">
                    {{ $rows->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
