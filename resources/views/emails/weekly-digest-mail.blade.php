{{-- resources/views/emails/weekly-digest.blade.php --}}
    <!doctype html>
<html lang="sv">
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; color:#111;">
<h2 style="margin:0 0 16px;">Veckodigest – {{ $customerName }}</h2>
<div style="color:#555; margin-bottom:16px;">Period: {{ $period }}</div>

@foreach($sites as $s)
    <div style="border:1px solid #eee; border-radius:12px; padding:16px; margin-bottom:16px;">
        <h3 style="margin:0 0 12px;">{{ $s['site']['name'] }}</h3>

        <div style="display:flex; gap:16px; flex-wrap:wrap;">
            <div style="flex:1; min-width:220px;">
                <strong>Webb (7d)</strong><br>
                Besökare: {{ $s['website']['visitors_7d'] }}<br>
                Sessions: {{ $s['website']['sessions_7d'] }}<br>
                Trend: {{ $s['website']['trend_pct'] }}%
            </div>
            <div style="flex:1; min-width:220px;">
                <strong>Publiceringar</strong><br>
                Publicerade (30d): {{ $s['publications']['published_30d'] }}<br>
                Misslyckade (30d): {{ $s['publications']['failed_30d'] }}<br>
                Gen/vecka: {{ $s['publications']['avg_per_week'] }}
            </div>
            <div style="flex:1; min-width:220px;">
                <strong>Socialt (7d)</strong><br>
                @foreach(['facebook'=>'FB','instagram'=>'IG','linkedin'=>'LI'] as $k=>$lbl)
                    @php($m = $s['social'][$k] ?? null)
                    {{ $lbl }}:
                    @if($m && $m['connected'])
                        reach {{ $m['reach'] }}, eng {{ $m['engagement'] }}
                    @else
                        ej kopplad
                    @endif
                    <br>
                @endforeach
            </div>
        </div>

        @if(!empty($s['insights']))
            <div style="margin-top:12px;">
                <strong>Insikter</strong>
                <ul>
                    @foreach($s['insights'] as $tip)
                        <li>{{ $tip }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endforeach

<div style="color:#777; font-size:12px;">Tips: Justera veckodigest under Inställningar → Veckodigest.</div>
</body>
</html>
