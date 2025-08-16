
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Faktura #{{ $invoice->id }}</h1>
                    <p class="mt-2 text-sm text-gray-600">Hantera fakturadetaljer och status</p>
                </div>
                <a href="{{ route('admin.invoices.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Tillbaka
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="space-y-8">
            <!-- Invoice Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h2 class="ml-3 text-lg font-semibold text-gray-900">Fakturadetaljer</h2>
                        </div>
                        @switch($invoice->status)
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
                                    {{ ucfirst($invoice->status) }}
                                </span>
                        @endswitch
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Customer Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-lg font-bold">
                                    {{ substr($invoice->customer->name ?? '#'.$invoice->customer_id, 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ $invoice->customer->name ?? '#'.$invoice->customer_id }}
                                </h3>
                                <p class="text-sm text-gray-500">Kund ID: {{ $invoice->customer_id }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Details Grid -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-purple-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-700">Period</div>
                                    <div class="text-lg font-bold text-gray-900">{{ $invoice->period }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-700">Status</div>
                                    <div class="text-lg font-bold text-gray-900">{{ strtoupper($invoice->status) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Amount Breakdown -->
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-6 border border-green-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Kostnadsfördelning</h3>
                        <div class="grid md:grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ number_format($invoice->plan_amount/100, 2, ',', ' ') }}
                                </div>
                                <div class="text-sm text-gray-600 mt-1">Planbelopp (kr)</div>
                            </div>
                            <div class="text-center p-4 bg-white rounded-lg shadow-sm">
                                <div class="text-2xl font-bold text-purple-600">
                                    {{ number_format($invoice->addon_amount/100, 2, ',', ' ') }}
                                </div>
                                <div class="text-sm text-gray-600 mt-1">Tillägg (kr)</div>
                            </div>
                            <div class="text-center p-4 bg-white rounded-lg shadow-sm border-2 border-green-200">
                                <div class="text-3xl font-bold text-green-600">
                                    {{ number_format($invoice->total_amount/100, 2, ',', ' ') }}
                                </div>
                                <div class="text-sm text-gray-600 mt-1">Totalt (kr)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Åtgärder</h3>
                <div class="flex flex-wrap gap-3">
                    <button wire:click="recalc"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Beräkna belopp
                    </button>

                    <button wire:click="send"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Skicka via e‑post
                    </button>

                    @if(in_array($invoice->status, ['draft','sent']))
                        <button wire:click="markPaid"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Markera betald
                        </button>
                    @endif
                </div>
            </div>

            <!-- Invoice Lines (if exists) -->
            @if(!empty($invoice->lines))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="p-2 bg-gray-200 rounded-lg">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-semibold text-gray-900">Fakturarader</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="bg-gray-50 rounded-lg p-4 overflow-auto">
                            <pre class="text-xs font-mono text-gray-800">{{ json_encode($invoice->lines, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
