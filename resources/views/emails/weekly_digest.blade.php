@component('mail::message')
    # Veckodigest – {{ $customer->name }}

    @isset($sections['campaigns'])
        ## Kampanjförslag
        {!! \Illuminate\Support\Str::of($sections['campaigns'])->markdown() !!}
    @endisset

    @isset($sections['topics'])
        ## Aktuella ämnen
        {!! \Illuminate\Support\Str::of($sections['topics'])->markdown() !!}
    @endisset

    @isset($sections['next_week'])
        ## Nästa veckas plan
        {!! \Illuminate\Support\Str::of($sections['next_week'])->markdown() !!}
    @endisset

    @component('mail::panel')
        Behöver du hjälp att publicera? Logga in och välj “Publicera till WordPress”.
    @endcomponent

    Hälsningar,
    {{ config('app.name') }}
@endcomponent
