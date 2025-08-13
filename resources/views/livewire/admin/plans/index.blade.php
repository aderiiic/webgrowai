<div class="max-w-5xl mx-auto py-10 space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Planer</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($plans as $plan)
            <div class="border rounded p-4 space-y-2">
                <div class="font-medium">{{ $plan->name }}</div>
                <div class="text-sm text-gray-600">
                    Mån: {{ $plan->price_monthly }} kr<br>
                    År: {{ $plan->price_yearly }} kr
                </div>
                <div class="text-xs text-gray-500">Features: {{ $plan->features_count }}</div>
                <a class="btn btn-sm mt-2" href="{{ route('admin.plans.edit', $plan) }}">Redigera</a>
            </div>
        @endforeach
    </div>
</div>
