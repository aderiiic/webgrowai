<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Prenumerationsplaner</h1>
                    <p class="mt-2 text-sm text-gray-600">Hantera alla tillgängliga prenumerationsplaner</p>
                </div>
                <a href="#"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Ny plan
                </a>
            </div>
        </div>

        <!-- Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($plans as $plan)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300 group">
                    <!-- Plan Header -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900">{{ $plan->name }}</h3>
                            @if($plan->is_active)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                                    Aktiv
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    <div class="w-2 h-2 bg-gray-400 rounded-full mr-1"></div>
                                    Inaktiv
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Plan Content -->
                    <div class="p-6 space-y-4">
                        <!-- Pricing -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-700">Månad</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($plan->price_monthly) }} kr</span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="p-2 bg-green-100 rounded-lg">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-1v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-1c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-1"/>
                                        </svg>
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-700">År</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-lg font-bold text-gray-900">{{ number_format($plan->price_yearly) }} kr</span>
                                    @if($plan->price_monthly > 0)
                                        @php
                                            $monthlyTotal = $plan->price_monthly * 12;
                                            $savings = $monthlyTotal - $plan->price_yearly;
                                            $savingsPercent = $monthlyTotal > 0 ? round(($savings / $monthlyTotal) * 100) : 0;
                                        @endphp
                                        @if($savings > 0)
                                            <div class="text-xs text-green-600 font-medium">
                                                Spara {{ $savingsPercent }}%
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Features Count -->
                        <div class="flex items-center p-3 bg-purple-50 rounded-lg">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="ml-3 text-sm text-gray-700">
                                <span class="font-semibold text-gray-900">{{ $plan->features_count }}</span>
                                {{ $plan->features_count == 1 ? 'feature' : 'features' }}
                            </span>
                        </div>
                    </div>

                    <!-- Plan Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <a href="{{ route('admin.plans.edit', $plan) }}"
                           class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 group-hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Redigera plan
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($plans->isEmpty())
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Inga planer skapade ännu</h3>
                <p class="text-gray-500 mb-6">Kom igång genom att skapa din första prenumerationsplan</p>
                <a href="#"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Skapa första planen
                </a>
            </div>
        @endif
    </div>
</div>
