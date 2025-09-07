<!doctype html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <title>Veckosammanställning</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Enkel, responsiv, ”system-neutral” styling utan beroenden */
        body { margin:0; padding:0; background:#f6f7fb; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; color:#0f172a; }
        .container { max-width:640px; margin:0 auto; padding:24px 12px; }
        .card { background:#ffffff; border-radius:16px; box-shadow:0 8px 28px rgba(2,6,23,0.08); overflow:hidden; }
        .header { background:linear-gradient(135deg,#eef2ff,#f5f3ff); padding:24px; }
        .header h1 { margin:0; font-size:20px; color:#1e293b; }
        .meta { font-size:13px; color:#475569; margin-top:4px; }
        .content { padding:20px 24px; }
        .site { border:1px solid #e2e8f0; border-radius:12px; padding:12px 14px; margin-bottom:10px; }
        .site h3 { margin:0 0 6px 0; font-size:15px; color:#0f172a; }
        .chips { display:flex; flex-wrap:wrap; gap:6px; }
        .chip { font-size:12px; padding:4px 8px; border-radius:999px; background:#f1f5f9; color:#334155; }
        .cta { text-align:center; padding:20px 24px 28px; }
        .btn { display:inline-block; background:#4f46e5; color:#fff !important; text-decoration:none; padding:12px 18px; border-radius:10px; font-weight:600; }
        .footer { text-align:center; font-size:12px; color:#64748b; padding:14px; }
        @media (prefers-color-scheme: dark) {
            body { background:#0b1220; color:#e2e8f0; }
            .card { background:#0f172a; }
            .header { background:linear-gradient(135deg,#1e293b,#0f172a); }
            .header h1 { color:#e2e8f0; }
            .meta { color:#94a3b8; }
            .site { border-color:#243242; }
            .chip { background:#1e293b; color:#cbd5e1; }
            .footer { color:#94a3b8; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="header">
            <h1>Ny veckosammanställning – {{ $customer->name }}</h1>
            <div class="meta">
                @php
                    $tag = $runTag === 'friday' ? 'Fredag' : 'Måndag';
                @endphp
                {{ $tag }} · {{ $summary['date'] ?? now()->format('Y-m-d') }}
            </div>
        </div>

        <div class="content">
            <p>Vi har genererat en färsk sammanställning för dina sajter. Här är en snabb överblick:</p>

            @foreach(($summary['sites'] ?? []) as $s)
                <div class="site">
                    <h3>{{ $s['site_name'] ?? 'Sajt' }}</h3>
                    @if(!empty($s['sections']))
                        <div class="chips">
                            @foreach($s['sections'] as $sec)
                                <span class="chip">{{ $sec }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="chips"><span class="chip">Uppdaterad</span></div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="cta">
            <a class="btn" href="{{ $summary['cta_url'] ?? url('/') }}" target="_blank" rel="noopener">
                {{ $summary['cta_text'] ?? 'Logga in för att se mer' }}
            </a>
        </div>
    </div>

    <div class="footer">
        Du får detta mejl eftersom veckosammanställningar är aktiverade. Vill du ändra mottagare? Uppdatera under Inställningar → Veckorapporter.
    </div>
</div>
</body>
</html>
