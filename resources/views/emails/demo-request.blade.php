<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ny demo-förfrågan</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #4f46e5;">Ny demo-förfrågan - WebGrow AI</h2>

    <p><strong>Namn:</strong> {{ $name }}</p>
    <p><strong>E-post:</strong> {{ $email }}</p>

    @if($company)
        <p><strong>Företag:</strong> {{ $company }}</p>
    @endif

    @if($notes)
        <p><strong>Meddelande:</strong></p>
        <p style="background: #f8f9fa; padding: 15px; border-radius: 5px;">{{ $notes }}</p>
    @endif

    <hr style="margin: 30px 0;">

    <p style="color: #666; font-size: 14px;">
        Denna förfrågan kom från WebGrow AI:s hemsida.<br>
        Svara direkt på detta mejl för att komma i kontakt med {{ $name }}.
    </p>
</div>
</body>
</html>
