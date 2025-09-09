{{-- resources/views/emails/contact.blade.php --}}
@component('mail::message')
    # Ny kontaktförfrågan från WebGrow AI

    En ny kontaktförfrågan har kommit in från hemsidan.

    **Från:** {{ $name }} ({{ $email }})
    @if($company)
        **Företag:** {{ $company }}
    @endif
    @if($subject)
        **Ämne:** {{ $subject }}
    @endif

    ## Meddelande:
    {{ $message }}

    ---

    Svara direkt på detta mail för att kontakta personen.

    Tack,<br>
    WebGrow AI System
@endcomponent
