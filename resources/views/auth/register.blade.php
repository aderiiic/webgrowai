@extends('layouts.guest', ['title' => 'Registrera – WebGrow AI'])

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-12">
        <h1 class="text-2xl font-semibold mb-6">Skapa konto</h1>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div class="border rounded p-4 bg-white space-y-3">
                <div>
                    <label class="block text-sm text-gray-600">Namn</label>
                    <input name="name" type="text" class="input input-bordered w-full" value="{{ old('name') }}" required>
                    @error('name')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-600">E‑post</label>
                    <input name="email" type="email" class="input input-bordered w-full" value="{{ old('email') }}" required>
                    @error('email')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                </div>
                <div class="grid md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm text-gray-600">Lösenord</label>
                        <input name="password" type="password" class="input input-bordered w-full" required>
                        @error('password')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Bekräfta lösenord</label>
                        <input name="password_confirmation" type="password" class="input input-bordered w-full" required>
                    </div>
                </div>
            </div>

            <div class="border rounded p-4 bg-white space-y-3">
                <h2 class="text-sm font-medium text-gray-700">Företagsuppgifter</h2>
                <div>
                    <label class="block text-sm text-gray-600">Företagsnamn</label>
                    <input name="company_name" type="text" class="input input-bordered w-full" value="{{ old('company_name') }}" required>
                    @error('company_name')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                </div>
                <div class="grid md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm text-gray-600">Org.nr (valfritt)</label>
                        <input name="org_nr" type="text" class="input input-bordered w-full" value="{{ old('org_nr') }}">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Momsnr / VAT (valfritt)</label>
                        <input name="vat_nr" type="text" class="input input-bordered w-full" value="{{ old('vat_nr') }}">
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm text-gray-600">Kontaktperson</label>
                        <input name="contact_name" type="text" class="input input-bordered w-full" value="{{ old('contact_name') }}" required>
                        @error('contact_name')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Telefon (valfritt)</label>
                        <input name="contact_phone" type="text" class="input input-bordered w-full" value="{{ old('contact_phone') }}">
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-gray-600">Faktura e‑post</label>
                    <input name="billing_email" type="email" class="input input-bordered w-full" value="{{ old('billing_email', old('email')) }}" required>
                    @error('billing_email')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                </div>
                <div class="grid md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm text-gray-600">Fakturaadress</label>
                        <input name="billing_address" type="text" class="input input-bordered w-full" value="{{ old('billing_address') }}" required>
                        @error('billing_address')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Postnummer</label>
                        <input name="billing_zip" type="text" class="input input-bordered w-full" value="{{ old('billing_zip') }}" required>
                        @error('billing_zip')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm text-gray-600">Ort</label>
                        <input name="billing_city" type="text" class="input input-bordered w-full" value="{{ old('billing_city') }}" required>
                        @error('billing_city')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Land (ISO‑2)</label>
                        <input name="billing_country" type="text" maxlength="2" class="input input-bordered w-full" value="{{ old('billing_country','SE') }}" required>
                        @error('billing_country')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button class="btn btn-primary">Skapa konto</button>
            </div>
        </form>
    </div>
@endsection
