<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use App\Models\ImageAsset;
use App\Models\SocialIntegration;
use App\Services\Social\FacebookClient;
use App\Support\Usage;
use Carbon\CarbonImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class PublishToFacebookJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    /**
     * Totalt antal försök innan jobb klassas som misslyckat.
     */
    public int $tries = 5;

    /**
     * Exponentiell backoff för retries (sekunder).
     */
    public function backoff(): array
    {
        return [60, 120, 300, 600, 900];
    }

    /**
     * Absolut tidsgräns för retries.
     */
    public function retryUntil(): \DateTimeInterface
    {
        return now()->addHours(6);
    }

    public function __construct(public int $publicationId)
    {
        $this->onQueue('social');
        // Initial liten delay för att undvika race med skapande/commit
        $this->delay(now()->addSeconds(10));
    }

    /**
     * Skydda mot parallell körning för samma publicationId + enkel rate limiting.
     */
    public function middleware(): array
    {
        return [
            // Låser per publikation så att inte två workers kör samma jobb samtidigt
            new WithoutOverlapping("fb-pub-{$this->publicationId}"),
            // Namngiven rate limiter (konfigureras i app/Providers/RouteServiceProvider eller RateLimiter)
            // Skapar mjuk throttle mot Facebook Graph API.
            (new RateLimited('facebook-api'))->dontRelease(),
        ];
    }

    public function handle(Usage $usage): void
    {
        Log::info('[Facebook] Start', ['pub_id' => $this->publicationId]);

        // Läs med lås för att undvika race mellan flera instanser
        $pub = ContentPublication::with('content')->find($this->publicationId);
        if (!$pub) {
            Log::warning('[Facebook] Publication saknas', ['pub_id' => $this->publicationId]);
            return;
        }

        if ($pub->status === 'cancelled') {
            Log::info('[FB] Avbruten – ingen publicering', ['publication_id' => $pub->id]);
            return;
        }

        if ($pub->scheduled_at) {
            $nowTs = Carbon::now()->getTimestamp();
            $schedTs = $pub->scheduled_at->getTimestamp();
            $delay = max(0, $schedTs - $nowTs);
            if ($delay > 20) {
                Log::info('[FB] För tidigt – release till schematid', [
                    'publication_id' => $pub->id,
                    'delay' => $delay,
                    'scheduled_at' => $pub->scheduled_at->toIso8601String(),
                ]);
                $this->release($delay);
                return;
            }
        }

        // Endast bearbeta inlägg för Facebook
        if (!in_array($pub->target, ['facebook', 'fb'], true)) {
            Log::info('[Facebook] Hoppar över – fel target', ['pub_id' => $pub->id, 'target' => $pub->target]);
            return;
        }

        // Skydda mot omkörning om redan publicerad
        if ($pub->status === 'published' && !empty($pub->external_id)) {
            Log::info('[Facebook] Redan publicerad – ingen åtgärd', ['pub_id' => $pub->id]);
            return;
        }

        // Statusgate
        if (!in_array($pub->status, ['queued', 'processing', 'scheduled'], true)) {
            Log::info('[Facebook] Hoppar över pga status', ['pub_id' => $pub->id, 'status' => $pub->status]);
            return;
        }

        // Försök att ta "processing" atomiskt
        if ($pub->status === 'queued') {
            $updated = DB::transaction(function () use ($pub) {
                $fresh = ContentPublication::lockForUpdate()->find($pub->id);
                if (!$fresh) {
                    return false;
                }
                if ($fresh->status !== 'queued') {
                    return false;
                }
                $fresh->update(['status' => 'processing']);
                return true;
            });
            if (!$updated) {
                // Någon annan jobbnod tog den – sluta
                Log::info('[Facebook] Kunde ej ta processing-lås', ['pub_id' => $pub->id]);
                return;
            }
            // Refresh lokalt objekt
            $pub->refresh();
        }

        $content = $pub->content;
        if (!$content) {
            $this->markFailed($pub, 'Inget innehåll kopplat till publiceringen.');
            return;
        }

        // Hämta och validera integration
        $integration = SocialIntegration::where('site_id', $content->site_id)
            ->where('provider', 'facebook')
            ->first();

        if (!$integration || empty($integration->access_token) || empty($integration->page_id)) {
            $this->markFailed($pub, 'Facebook-integration saknas eller är ofullständig (access_token/page_id).');
            return;
        }

        // Bygg och sanera meddelandet
        $payload = (array) ($pub->payload ?? []);
        $raw = $payload['text']
            ?? trim(($content->title ? $content->title . "\n\n" : '') . (string) ($content->body_md ?? ''));

        $message = $this->buildFacebookMessage($raw);

        // Validera att vi faktiskt har något att posta
        if ($message === '' && empty($payload['image_asset_id'])) {
            $this->markFailed($pub, 'Varken text eller bild angiven för Facebook-inlägget.');
            return;
        }

        // Hantera schemaläggning:
        // - Om > 15 minuter i framtiden: använd Facebooks scheduled_publish_time
        // - Om i framtiden men nära: försök att vänta tills tidpunkten
        $scheduledAt = $pub->scheduled_at ? CarbonImmutable::parse($pub->scheduled_at) : null;
        $now = CarbonImmutable::now();

        try {
            $client = new FacebookClient($integration->access_token);

            // Om vi redan har external_id (t.ex. schemalagt i tidigare försök), avbryt säkert
            if (!empty($pub->external_id)) {
                Log::info('[Facebook] external_id finns redan – ingen ny publicering', [
                    'pub_id' => $pub->id,
                    'external_id' => $pub->external_id,
                ]);
                return;
            }

            $resp = null;
            $usedImageAssetId = null;

            // Om bilden finns i payload -> skicka som bildinlägg
            if (!empty($payload['image_asset_id'])) {
                $asset = ImageAsset::find((int) $payload['image_asset_id']);
                if (!$asset) {
                    $this->markFailed($pub, 'Angiven bild finns inte.');
                    return;
                }
                // Säkerställ ägarskap
                if ((int) $asset->customer_id !== (int) $content->customer_id) {
                    $this->markFailed($pub, 'Otillåten bild för denna kund.');
                    return;
                }

                // Läs bytes
                try {
                    $bytes = Storage::disk($asset->disk)->get($asset->path);
                } catch (Throwable $e) {
                    $this->markFailed($pub, 'Kunde inte läsa bild från lagring.');
                    return;
                }

                $filename = basename($asset->path) ?: (Str::uuid()->toString() . '.jpg');

                // Facebooks photos endpoint saknar stöd för scheduled_publish_time i samma grad som feed-text,
                // så om det är långt fram – posta text schemalagt istället. Annars: posta direkt som foto.
                if ($scheduledAt && $scheduledAt->isFuture()) {
                    $seconds = $now->diffInSeconds($scheduledAt, false);

                    if ($seconds > 900) {
                        // Schemalägg som textinlägg istället (alternativ: ladda upp unpublished photo + dela via post)
                        $resp = $client->schedulePagePost(
                            $integration->page_id,
                            $message !== '' ? $message : '(Bild)', // fallback caption
                            $scheduledAt->getTimestamp()
                        );
                        $this->finalizeScheduled($pub, $payload, $resp, note: 'Schemalagd på Facebook (text, vald bild kunde ej schemaläggas som foto).');
                        return;
                    }

                    if ($seconds > 5) {
                        // Vänta tills nära tidpunkt
                        $this->release($seconds);
                        Log::info('[Facebook] För tidigt – release till schematid', [
                            'pub_id' => $pub->id,
                            'delay_s' => $seconds,
                        ]);
                        return;
                    }
                }

                // Publicera som foto nu
                $resp = $client->createPagePhoto($integration->page_id, $bytes, $filename, $message);
                $usedImageAssetId = (int) $payload['image_asset_id'];
                // Berika payload
                $payload['image_asset_id'] = $usedImageAssetId;
                $payload['image_bank_path'] = $asset->path;
            } else {
                // Inget foto: textinlägg
                if ($scheduledAt && $scheduledAt->isFuture()) {
                    $seconds = $now->diffInSeconds($scheduledAt, false);

                    if ($seconds > 900) {
                        // Schemalägg via Facebook
                        $resp = $client->schedulePagePost($integration->page_id, $message, $scheduledAt->getTimestamp());
                        $this->finalizeScheduled($pub, $payload, $resp, note: 'Schemalagd på Facebook.');
                        return;
                    }

                    if ($seconds > 5) {
                        // Vänta tills nära tidpunkt
                        $this->release($seconds);
                        Log::info('[Facebook] För tidigt – release till schematid', [
                            'pub_id' => $pub->id,
                            'delay_s' => $seconds,
                        ]);
                        return;
                    }
                }

                // Publicera direkt
                $resp = $client->createPagePost($integration->page_id, $message);
            }

            if (empty($resp) || empty($resp['id'])) {
                $this->markFailed($pub, 'Tomt eller ogiltigt svar från Facebook API (saknar id).');
                return;
            }

            // Uppdatera publicering som klar (publicerad)
            $pub->update([
                'status'      => 'published',
                'external_id' => $resp['id'],
                'message'     => !empty($usedImageAssetId) ? 'Publicerat (photo)' : 'Publicerat (text)',
                'payload'     => $payload,
            ]);

            dispatch(new \App\Jobs\RefreshPublicationMetricsJob($pub->id))
                ->onQueue('metrics')
                ->delay(now()->addSeconds(60))
                ->afterCommit();

            // Markera bild använd om tillgänglig
            if (!empty($usedImageAssetId) && method_exists(ImageAsset::class, 'markUsed')) {
                ImageAsset::markUsed($usedImageAssetId, $pub->id);
            }

            // Usage counters
            $usage->increment($content->customer_id, 'ai.publish.facebook');
            $usage->increment($content->customer_id, 'ai.publish');

            Log::info('[Facebook] Klart', [
                'pub_id' => $pub->id,
                'fb_id'  => $resp['id'],
            ]);
        } catch (Throwable $e) {
            // Sätt endast diagnostiskt meddelande; låt retries hantera tillfälliga fel
            $pub->update(['message' => $this->safeError($e)]);
            Log::error('[Facebook] Misslyckades (transient)', [
                'pub_id' => $pub->id,
                'error'  => $this->safeError($e),
            ]);
            throw $e; // behåll retry-beteende
        }
    }

    /**
     * Körs av Laravel när jobbet slutligen anses misslyckat efter alla försök.
     */
    public function failed(Throwable $e): void
    {
        try {
            $pub = ContentPublication::find($this->publicationId);
            if ($pub && $pub->status !== 'published') {
                $pub->update([
                    'status'  => 'failed',
                    'message' => $this->safeError($e),
                ]);
            }
        } catch (Throwable $inner) {
            Log::error('[Facebook] Kunde inte markera failed', [
                'pub_id' => $this->publicationId,
                'error'  => $inner->getMessage(),
            ]);
        }
    }

    /**
     * Slutbehandlar schemalagda inlägg via Facebook (betraktas som "klar" i vår pipeline).
     */
    private function finalizeScheduled(ContentPublication $pub, array $payload, array $resp, string $note): void
    {
        if (empty($resp) || empty($resp['id'])) {
            $this->markFailed($pub, 'Schemaläggning misslyckades – saknar id i svar.');
            return;
        }

        $pub->update([
            'status'      => 'published', // I vår domän betyder "klart/hanterat"; själva FB-publiceringen sker senare
            'external_id' => $resp['id'],
            'message'     => $note,
            'payload'     => $payload,
        ]);

        dispatch(new \App\Jobs\RefreshPublicationMetricsJob($pub->id))
            ->onQueue('metrics')
            ->delay(now()->addSeconds(60))
            ->afterCommit();

        Log::info('[Facebook] Schemalagd på Facebook', [
            'pub_id' => $pub->id,
            'fb_id'  => $resp['id'],
            'note'   => $note,
        ]);
    }

    /**
     * Markerar publikation som permanent misslyckad (utan retries).
     */
    private function markFailed(ContentPublication $pub, string $reason): void
    {
        $pub->update([
            'status'  => 'failed',
            'message' => $reason,
        ]);
        Log::warning('[Facebook] Permanent fel', [
            'pub_id' => $pub->id,
            'reason' => $reason,
        ]);
    }

    /**
     * Bygger och sanerar text för Facebook och klipper till 5 000 tecken.
     */
    private function buildFacebookMessage(string $input): string
    {
        // Skydda mot orimligt stora strängar
        if (mb_strlen($input) > 20000) {
            $input = mb_substr($input, 0, 20000);
        }

        // Normalisera radbrytningar och ta bort ev. kodstaket
        $t = str_replace(["\r\n", "\r"], "\n", (string) $input);
        $t = $this->stripCodeFences($t);

        // Ta bort rubriker, bilder och metadata
        $t = preg_replace('/^#{1,6}\s.*$/m', '', $t);
        $t = preg_replace('/!\[.*?\]\([^)]*\)/s', '', $t);
        $t = preg_replace('/<img[^>]*>/i', '', $t);
        $t = preg_replace(
            '/^\s*(Nyckelord|Keywords|Stil|Style|CTA|Målgrupp|Audience|Brand voice)\s*:\s*.*$/im',
            '',
            $t
        );

        // Normalisera listor, trimma radslut; bevara radbrytningar
        $t = preg_replace('/^\s*[\*\-]\s+/m', '- ', $t);
        $t = preg_replace('/[ \t]+$/m', '', $t);     // trimma spaces i slutet av rader
        $t = preg_replace('/\n{3,}/', "\n\n", $t);   // max två radbrytningar i rad

        // Ta bort osynliga tecken (ej radbrytningar)
        $t = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $t);
        $t = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $t);

        $t = trim($t);

        // 1) Samla befintliga hashtags (case-insensitivt) inklusive position för relevans
        $allTags = [];
        if (preg_match_all('/(^|\s)(#[\p{L}\p{N}_-]+)/u', $t, $m, PREG_OFFSET_CAPTURE)) {
            foreach ($m[2] as $match) {
                $tag = $match[0];         // ex: "#webbsida"
                $pos = $match[1];         // index i texten
                $key = mb_strtolower($tag);
                if (!isset($allTags[$key])) {
                    $allTags[$key] = ['tag' => $tag, 'count' => 0, 'first' => $pos];
                }
                $allTags[$key]['count']++;
            }
        }

        // 2) Ta bort existerande avslutande hashtag-rad (utan att ta bort föregående newline)
        $t = preg_replace('/(?:^|\n)\s*(?:#[\p{L}\p{N}_-]+(?:\s+#[\p{L}\p{N}_-]+)*)\s*$/u', '', $t);

        // 3) Plocka nakna tagg-kluster i slutet av rader (t.ex. "webbsida företag försäljning")
        //    - Vi samlar upp till ~6 ord i slutet av raden, bara bokstäver/siffror/_/-
        //    - Vi tar bort dem från raden men bevarar radbrytningar
        $lines = explode("\n", $t);
        foreach ($lines as &$line) {
            $orig = $line;

            // Hitta kluster i slutet: ett eller flera "ord" separerade med mellanslag
            if (preg_match('/\s((?:[\p{L}\p{N}_-]{2,})(?:\s+[\p{L}\p{N}_-]{2,}){0,7})\s*$/u', $line, $m)) {
                $cluster = $m[1];

                // Heuristik: ta bara detta som taggkluster om
                // - föregående tecken före klustret inte är en bokstav/siffra (dvs slutet av meningen eller emoji),
                // - och klustret inte innehåller tydlig meningsinterpunktion
                $prefixOk = !preg_match('/[\p{L}\p{N}]$/u', mb_substr($line, 0, -mb_strlen($m[0])));
                $hasPunct = preg_match('/[.,!?;:]/u', $cluster);

                if ($prefixOk && !$hasPunct) {
                    $tokens = preg_split('/\s+/', trim($cluster));
                    if ($tokens && count($tokens) >= 2) {
                        foreach ($tokens as $w) {
                            // Skippa om ordet ser ut som en riktig mening (börjar med versal och är långt), annars ta som tagg
                            if (preg_match('/^[\p{L}\p{N}_-]{2,}$/u', $w)) {
                                $tag = '#' . mb_strtolower($w);
                                $key = mb_strtolower($tag);
                                if (!isset($allTags[$key])) {
                                    $allTags[$key] = ['tag' => $tag, 'count' => 0, 'first' => mb_strlen(implode("\n", $lines))];
                                }
                                $allTags[$key]['count']++;
                            }
                        }
                        // Ta bort klustret från raden (ersätt med ingenting, men bevara resten av raden)
                        $line = rtrim(mb_substr($line, 0, -mb_strlen($m[0])));
                    }
                }
            }

            // Om inga ändringar, lämna raden som den är
            if ($line === null) {
                $line = $orig;
            }
        }
        unset($line);

        // 4) Ta bort inline-hashtags men bevara radbrytningar (ersätt "␠#tag" med "␠")
        $lines = array_map(function ($line) {
            $line = preg_replace('/(^|[^\S\r\n])#[\p{L}\p{N}_-]+/u', '$1', $line);
            // Komprimera enbart multipla mellanslag på raden (inte radbrytningar)
            $line = preg_replace('/[ \t]{2,}/', ' ', $line);
            return rtrim($line);
        }, $lines);

        // 5) Se till att numrerade punkter har en tomrad före (för läsbarhet)
        //    Om en rad börjar med "N. " och föregående rad inte är tom, sätt in tomrad
        $normalized = [];
        foreach ($lines as $idx => $line) {
            $isNumbered = preg_match('/^\d+\.\s/', $line);
            if ($isNumbered && !empty($normalized) && trim(end($normalized)) !== '') {
                $normalized[] = ''; // tom rad
            }
            $normalized[] = $line;
        }

        // Ta bort överflödiga tomrader (max två i rad) och trimma
        $t = implode("\n", $normalized);
        $t = preg_replace('/\n{3,}/', "\n\n", $t);
        $t = trim($t);

        // 6) Välj de mest relevanta hashtagsen
        $maxTags = 6;
        if (!empty($allTags) && $maxTags > 0) {
            // Sortera: flest förekomster först, sedan tidigast förekomst
            uasort($allTags, function ($a, $b) {
                if ($a['count'] === $b['count']) {
                    return $a['first'] <=> $b['first'];
                }
                return $b['count'] <=> $a['count'];
            });

            $selected = array_slice(array_column($allTags, 'tag'), 0, max(1, $maxTags));

            if (!empty($selected)) {
                $t = rtrim($t) . "\n\n" . implode(' ', $selected);
            }
        }

        // 7) Facebook maxlängd
        if (mb_strlen($t) > 5000) {
            $t = mb_substr($t, 0, 4996) . ' ...';
        }

        return $t;
    }

    private function stripCodeFences(string $t): string
    {
        $trim = trim($t);
        if (str_starts_with($trim, '```') && str_ends_with($trim, '```')) {
            $t = preg_replace('/^```[a-zA-Z0-9_-]*\n?/', '', $trim);
            $t = preg_replace("/\n?```$/", '', (string) $t);
        }
        return (string) $t;
    }

    /**
     * Returnerar ett säkert felmeddelande för logg/UI (inga känsliga data).
     */
    private function safeError(Throwable $e): string
    {
        $msg = $e->getMessage() ?: 'Okänt fel';
        // Rensa eventuella access tokens eller query-strängar
        $msg = preg_replace('/access_token=[^&\s]+/i', 'access_token=[REDACTED]', $msg);
        return $msg;
    }
}
