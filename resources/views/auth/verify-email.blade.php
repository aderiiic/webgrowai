@extends('layouts.guest', ['title' => 'Verifiera din e‑post'])

@section('content')
    <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl shadow p-8 border">
            <h1 class="text-2xl font-bold mb-2">Verifiera din e‑post</h1>
            <p class="text-gray-600">
                Vi har skickat en verifieringslänk till din e‑postadress. Följ länken för att komma vidare.
                Om du inte fått mailet kan du skicka en ny länk nedan.
            </p>

            @if (session('status') === 'verification-link-sent'))
            <div class="mt-4 p-3 bg-emerald-50 text-emerald-700 rounded-md">
                Ny verifieringslänk har skickats.
            </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
                @csrf
                <button class="px-5 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">
                    Skicka ny verifieringslänk
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button class="text-sm text-gray-600 underline">Logga ut</button>
            </form>
        </div>
    </div>
@endsection
