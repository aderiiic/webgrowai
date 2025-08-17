<?php

namespace App\Jobs;

use App\Models\ContentPublication;
use App\Models\SocialIntegration;
use App\Services\Social\LinkedInService;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishToLinkedInJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $publicationId) {}

    public function handle(LinkedInService $li): void
    {
        $pub = ContentPublication::findOrFail($this->publicationId);
        if ($pub->status !== 'queued') {
            return;
        }

        $pub->update(['status' => 'processing']);

        // Hämta kund via AiContent-relation eller annan koppling ni har
        $content = $pub->aiContent()->first();
        $customerId = $content?->customer_id;
        abort_unless($customerId, 422);

        // Hämta LinkedIn-integration
        $si = SocialIntegration::where('customer_id', $customerId)->where('provider', 'linkedin')->firstOrFail();
        $accessToken = $si->access_token;
        $ownerUrn = $si->page_id ?: ''; // lagrar owner URN i page_id fältet

        $payload = $pub->payload ?? [];
        $text = $payload['text'] ?? $content?->title ?? 'Inlägg';
        $imagePrompt = $payload['image_prompt'] ?? null;
        $assetUrn = null;

        // Valfri bild (endast om image_prompt finns i payload). Generera i minne och ladda upp direkt.
        if ($imagePrompt) {
            // Anropa ert befintliga AI-bildflöde (kan bytas mot er service)
            $openaiKey = config('services.openai.key');
            if ($openaiKey) {
                $http = new Client(['base_uri' => 'https://api.openai.com/v1/', 'timeout' => 60]);
                $res = $http->post('images/generations', [
                    'headers' => [
                        'Authorization' => 'Bearer '.$openaiKey,
                        'Content-Type'  => 'application/json',
                    ],
                    'json' => [
                        'model' => 'gpt-image-1',
                        'prompt' => $imagePrompt,
                        'size' => '1024x1024',
                        'response_format' => 'b64_json',
                    ],
                ]);
                $data = json_decode((string)$res->getBody(), true);
                $b64 = $data['data'][0]['b64_json'] ?? null;
                if ($b64) {
                    $bytes = base64_decode($b64);
                    $reg = $li->registerImageUpload($accessToken, $ownerUrn);
                    if (!empty($reg['uploadUrl']) && !empty($reg['asset'])) {
                        $li->uploadToLinkedInUrl($reg['uploadUrl'], $bytes, 'image/png');
                        $assetUrn = $reg['asset'];
                    }
                }
            }
        }

        // Publicera
        $resp = $li->publishPost($accessToken, $ownerUrn, $text, $assetUrn);

        $pub->update([
            'status'      => 'published',
            'external_id' => $resp['id'] ?? ($resp['ugcPost'] ?? null),
            'message'     => 'Publicerat till LinkedIn',
            'payload'     => array_merge($payload, ['asset_urn' => $assetUrn]),
        ]);
    }
}
