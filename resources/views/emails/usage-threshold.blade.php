<!doctype html>
<html lang="sv">
<body style="font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; color:#111827;">
<p>Hej {{ $customerName }},</p>

@if($level === 'stop')
    <p><strong>Viktigt:</strong> du har nått kvotgränsen för {{ $metricLabel }} ({{ $used }} / {{ $quota }}).</p>
    <p>Uppgradera planen eller begär extraanvändning för att fortsätta.</p>
@else
    <p>Notis: du har förbrukat {{ $used }} av {{ $quota }} för {{ $metricLabel }}.</p>
    <p>Du närmar dig kvotgränsen. Överväg att uppgradera eller hålla nere förbrukningen.</p>
@endif

<p>
    Snabba länkar:<br>
    • Uppgradera plan: {{ url('/account/upgrade') }}<br>
    • Visa förbrukning: {{ url('/account/usage') }}
</p>

<p>Vänliga hälsningar<br>WebGrow AI</p>
</body>
</html>
