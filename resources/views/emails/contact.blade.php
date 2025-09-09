@component('mail::message')
    # Ny kontaktförfrågan från WebGrow AI

    **Från:** {{ $name }} ({{ $email }})
    @if(!empty($company))
        **Företag:** {{ $company }}
    @endif
    @if(!empty($subject))
        **Ämne:** {{ $subject }}
    @endif

    ## Meddelande
    {{ $userMessage }}

    ---

    Svara direkt på detta mail för att kontakta personen.

    Tack,
    WebGrow AI
@endcomponent
