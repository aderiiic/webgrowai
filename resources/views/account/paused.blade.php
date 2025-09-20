@extends('layouts.guest', ['title' => 'Konto pausat – WebGrow AI'])

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-16 text-center">
        <h1 class="text-3xl font-semibold">Konto pausat</h1>
        <p class="mt-3 text-gray-600">Din testperiod har löpt ut. Välj en plan för att fortsätta använda WebGrow AI.</p>
        <div class="mt-6 flex items-center justify-center gap-3">
            <a href="{{ route('billing.pricing') }}" class="px-5 py-3 rounded bg-indigo-600 text-white hover:bg-indigo-700" data-lead-cta="paused_choose_plan">Välj plan</a>
            <a href="{{ route('pricing') }}" class="px-5 py-3 rounded border hover:bg-white" data-lead-cta="paused_book_demo">Boka demo</a>
        </div>
    </div>
@endsection
