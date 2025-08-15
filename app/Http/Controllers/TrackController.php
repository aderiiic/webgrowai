<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadEvent;
use App\Models\LeadScore;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TrackController extends Controller
{
    public function store(Request $request)
    {
        $payload = $request->json()->all();

        $siteKey = (string)($payload['siteKey'] ?? '');
        $vid = (string)($payload['vid'] ?? '');
        $type = (string)($payload['type'] ?? '');
        $url  = (string)($payload['url'] ?? '');
        $tsMs = (int)($payload['ts'] ?? (int)(microtime(true)*1000));

        if ($siteKey === '' || $vid === '' || $type === '') {
            return response()->json(['ok' => false], 422);
        }

        $site = Site::where('public_key', $siteKey)->first();
        if (!$site) {
            return response()->json(['ok' => false], 403);
        }

        // Anonymisera IP/User-Agent
        $ipHash  = hash('sha256', (string)$request->ip().($site->id));
        $uaHash  = hash('sha256', (string)$request->userAgent());

        // Hitta/skap lead
        $lead = Lead::firstOrCreate(
            ['site_id' => $site->id, 'visitor_id' => $vid],
            ['first_seen' => now(), 'last_seen' => now(), 'sessions' => 1, 'last_ip_hash' => $ipHash, 'user_agent_hash' => $uaHash]
        );

        // Uppdatera last_seen, sessions (enkel heuristik: ny session om >30min sedan)
        if ($lead->last_seen === null || $lead->last_seen->diffInMinutes(now()) > 30) {
            $lead->sessions = (int)$lead->sessions + 1;
        }
        $lead->last_seen = now();
        $lead->last_ip_hash = $ipHash;
        $lead->user_agent_hash = $uaHash;

        // Försök associera email från event (om tillåtet)
        $email = trim((string)($payload['email'] ?? ''));
        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $lead->email = $lead->email ?: $email;
        }
        $lead->save();

        // Spara event
        $eventMeta = $payload;
        unset($eventMeta['siteKey'],$eventMeta['vid'],$eventMeta['ts'],$eventMeta['type'],$eventMeta['url']);
        LeadEvent::create([
            'site_id' => $site->id,
            'lead_id' => $lead->id,
            'type' => $type,
            'url'  => Str::limit($url, 1024, ''),
            'meta' => $eventMeta ?: null,
            'occurred_at' => \Carbon\Carbon::createFromTimestampMs($tsMs)->tz(config('app.timezone')),
        ]);

        return response()->json(['ok' => true]);
    }
}
