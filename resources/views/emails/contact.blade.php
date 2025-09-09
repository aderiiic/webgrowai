<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <title>Ny kontaktförfrågan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin:0; padding:0; background-color:#f6f7fb;">
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#f6f7fb;">
    <tr>
        <td align="center" style="padding:24px;">
            <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="max-width:600px; background:#ffffff; border-radius:12px; overflow:hidden;">
                <tr>
                    <td style="padding:24px; text-align:center; background:linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff;">
                        <h1 style="margin:0; font-family:Arial,Helvetica,sans-serif; font-size:20px; line-height:1.3;">
                            Ny kontaktförfrågan från WebGrow AI
                        </h1>
                    </td>
                </tr>

                <tr>
                    <td style="padding:24px; font-family:Arial,Helvetica,sans-serif; color:#111827; font-size:14px; line-height:1.6;">
                        <p style="margin:0 0 12px;">
                            <strong>Från:</strong> {{ $name }} &lt;{{ $email }}&gt;
                        </p>
                        @if(!empty($company))
                            <p style="margin:0 0 12px;">
                                <strong>Företag:</strong> {{ $company }}
                            </p>
                        @endif
                        @if(!empty($subject))
                            <p style="margin:0 0 12px;">
                                <strong>Ämne:</strong> {{ $subject }}
                            </p>
                        @endif>

                        <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:16px; margin:16px 0;">
                            <h3 style="margin:0 0 8px; font-size:14px; color:#111827;">Meddelande</h3>
                            <p style="margin:0; white-space:pre-wrap; color:#374151;">
                                {{ $userMessage }}
                            </p>
                        </div>

                        <p style="margin:16px 0 0; color:#374151;">
                            Du kan svara direkt på detta e‑postmeddelande för att kontakta avsändaren.
                        </p>

                        <hr style="border:none; border-top:1px solid #e5e7eb; margin:20px 0;">

                        <p style="margin:0; font-size:12px; color:#6b7280;">
                            Detta meddelande skickades automatiskt från WebGrow AI.
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:12px; text-align:center; background:#f9fafb; color:#6b7280; font-family:Arial,Helvetica,sans-serif; font-size:12px;">
                        © {{ date('Y') }} {{ config('app.name') }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
