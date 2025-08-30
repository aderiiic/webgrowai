<?php

namespace App\Jobs;

use App\Models\AiContent;
use App\Models\ContentPublication;
use App\Models\ImageAsset;
use App\Models\WpIntegration;
use App\Services\ImageGenerator;
use App\Services\WordPressClient;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class PublishAiContentToWpJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $aiContentId,
        public int $siteId,
        public string $status = 'draft',
        public ?string $scheduleAtIso = null,
        public ?int $publicationId = null
    ) {
        $this->onQueue('publish');
    }

    public function handle(Usage $usage, ImageGenerator $images): void
    {
        $content = AiContent::with('site')->findOrFail($this->aiContentId);
        $integration = WpIntegration::where('site_id', $this->siteId)->firstOrFail();

        $pub = $this->publicationId ? ContentPublication::find($this->publicationId) : null;
        if (!$pub) {
            $scheduledAt = null;
            if (!empty($this->scheduleAtIso)) {
                try { $scheduledAt = Carbon::parse($this->scheduleAtIso); } catch (\Throwable) {}
            }

            $imgPrefs = $content->inputs['image'] ?? ['generate' => false, 'mode' => 'auto', 'prompt' => null];

            $pub = ContentPublication::create([
                'ai_content_id' => $content->id,
                'target'        => 'wp',
                'status'        => 'queued',
                'scheduled_at'  => $scheduledAt,
                'message'       => null,
                'payload'       => [
                    'site_id' => $this->siteId,
                    'status'  => $this->status,
                    'date'    => $this->scheduleAtIso,
                    'image'   => $imgPrefs,
                ],
            ]);
        }

        $pub->update(['status' => 'processing', 'message' => null]);
        $client = WordPressClient::for($integration);

        try {
            $me = $client->getMe();
            \Log::debug('[WP Publish] Auth OK', ['user' => $me['name'] ?? $me['slug'] ?? null, 'id' => $me['id'] ?? null]);
        } catch (\Throwable $e) {
            $pub->update(['status' => 'failed', 'message' => 'WP-auth misslyckades: '.$e->getMessage()]);
            throw $e;
        }

        $md = $this->normalizeMd($content->body_md ?? '');
        $blocks = $this->toGutenbergBlocks($md);
        $htmlFallback = \Illuminate\Support\Str::of($md)->markdown();

        $payload = [
            'title'   => $content->title ?: \Illuminate\Support\Str::limit(strip_tags($htmlFallback), 48, '...'),
            'content' => $blocks !== '' ? $blocks : $htmlFallback,
            'status'  => $this->status,
        ];
        if ($this->status === 'future' && $this->scheduleAtIso) {
            $payload['date'] = $this->scheduleAtIso;
        }

        $featuredMediaId = null;
        $imagesEnabled = config('features.image_generation', false);

        try {
            $pubPayload = $pub->payload ?? [];
            $inputs     = $content->inputs ?? [];

            $imageAssetId = $pubPayload['image_asset_id'] ?? null;

            if ($imageAssetId) {
                $asset = ImageAsset::findOrFail((int)$imageAssetId);
                if ((int)$asset->customer_id !== (int)$content->customer_id) {
                    throw new \RuntimeException('Otillåten bild.');
                }
                $bytes    = Storage::disk($asset->disk)->get($asset->path);
                $filename = basename($asset->path);
                $media    = $client->uploadMedia($bytes, $filename, $asset->mime ?: 'image/jpeg');
                $featuredMediaId = $media['id'] ?? null;
                $mediaUrl = $media['source_url'] ?? ($media['guid']['rendered'] ?? null) ?? null;

                $pubPayload['image_asset_id']  = (int)$imageAssetId;
                $pubPayload['image_bank_path'] = $asset->path;

                if ($featuredMediaId) {
                    $payload['featured_media'] = $featuredMediaId;

                    if ($mediaUrl) {
//                        $imgBlock = "<!-- wp:image {\"id\":{$featuredMediaId},\"sizeSlug\":\"full\",\"linkDestination\":\"none\"} -->
//<figure class=\"wp-block-image size-full\"><img src=\"{$mediaUrl}\" alt=\"\" class=\"wp-image-{$featuredMediaId}\"/></figure>
//<!-- /wp:image -->";
//                        $payload['content'] = $imgBlock . "\n\n" . $payload['content'];
                    }
                }
            } else {
                if ($imagesEnabled) {
                    $want = (bool)($pubPayload['image']['generate'] ?? $inputs['image']['generate'] ?? false);
                    $want = $imagesEnabled && $want;

                    $prompt = $pubPayload['image_prompt']
                        ?? $pubPayload['image']['prompt']
                        ?? $inputs['image']['prompt']
                        ?? null;

                    if ($want && !$prompt) {
                        $prompt = $this->buildAutoPrompt($content->title, $inputs);
                    }

                    if ($want && $prompt) {
                        $bytes    = $images->generateJpeg($prompt, '1024x1024', 85);
                        $filename = 'ai-image-' . \Illuminate\Support\Str::uuid() . '.jpg';

                        $media    = $client->uploadMedia($bytes, $filename, 'image/jpeg');
                        $featuredMediaId = $media['id'] ?? null;
                        $mediaUrl = $media['source_url'] ?? ($media['guid']['rendered'] ?? null) ?? null;

                        \Log::debug('[WP Publish] Media upload result', [
                            'id'  => $featuredMediaId,
                            'url' => $mediaUrl,
                        ]);

                        if ($featuredMediaId) {
                            $payload['featured_media'] = $featuredMediaId;

                            if ($mediaUrl) {
                                $imgBlock = "<!-- wp:image {\"id\":{$featuredMediaId},\"sizeSlug\":\"full\",\"linkDestination\":\"none\"} -->
<figure class=\"wp-block-image size-full\"><img src=\"{$mediaUrl}\" alt=\"\" class=\"wp-image-{$featuredMediaId}\"/></figure>
<!-- /wp:image -->";
                                $payload['content'] = $imgBlock . "\n\n" . $payload['content'];
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('[WP Publish] Bilddel misslyckades – fortsätter utan bild', ['err' => $e->getMessage()]);
        }

        try {
            $resp = $client->createPost($payload);
            $postId = is_array($resp) ? ($resp['id'] ?? null) : (is_object($resp) ? ($resp->id ?? null) : null);

            if ($postId && $featuredMediaId && (empty($resp['featured_media']) || (int)$resp['featured_media'] !== (int)$featuredMediaId)) {
                $client->updatePost($postId, ['featured_media' => $featuredMediaId]);
            }

            $pub->update([
                'status'      => 'published',
                'external_id' => $postId,
                'payload'     => $pubPayload ?: $payload, // spara ev. image_asset_id m.m.
                'message'     => null,
            ]);

            if (!empty($pubPayload['image_asset_id'])) {
                ImageAsset::markUsed((int)$pubPayload['image_asset_id'], $pub->id);
            }

            $content->update([
                'status' => $this->status === 'draft' ? 'ready' : 'published',
            ]);

            $usage->increment($content->customer_id, 'ai.publish.wp');
            $usage->increment($content->customer_id, 'ai.publish');
        } catch (Throwable $e) {
            $pub->update(['status' => 'failed', 'message' => $e->getMessage(), 'payload' => $payload]);
            throw $e;
        }
    }

    private function buildAutoPrompt(?string $title, array $inputs): string
    {
        $kw    = implode(', ', $inputs['keywords'] ?? []);
        $voice = $inputs['brand']['voice'] ?? null;
        $aud   = $inputs['audience'] ?? null;

        return trim("Create a clean blog featured image.
Title: {$title}
Keywords: {$kw}
Audience: {$aud}
Brand voice: {$voice}
Style: modern, photographic, 16:9, minimal, no text overlays.");
    }

    private function normalizeMd(string $md): string
    {
        if ($md === '') return $md;
        $md = str_replace(["\r\n", "\r"], "\n", $md);
        $trimmed = trim($md);
        if (str_starts_with($trimmed, '```') && str_ends_with($trimmed, '```')) {
            $trimmed = preg_replace('/^```[a-zA-Z0-9_-]*\n?/','', $trimmed);
            $trimmed = preg_replace("/\n?```$/", '', $trimmed);
            $md = $trimmed;
        }
        $lines = explode("\n", $md);
        $minIndent = null;
        foreach ($lines as $line) {
            if (trim($line) === '') continue;
            preg_match('/^( +|\t+)/', $line, $m);
            if (!empty($m[0])) {
                $len = strlen(str_replace("\t", '    ', $m[0]));
                $minIndent = $minIndent === null ? $len : min($minIndent, $len);
            } else {
                $minIndent = 0; break;
            }
        }
        if ($minIndent && $minIndent > 0) {
            $md = implode("\n", array_map(function ($line) use ($minIndent) {
                $line = str_replace("\t", '    ', $line);
                return preg_replace('/^ {0,' . $minIndent . '}/', '', $line);
            }, $lines));
        }
        return trim($md);
    }

    private function toGutenbergBlocks(string $md): string
    {
        if ($md === '') return '';
        try {
            $html = (string) \Illuminate\Support\Str::of($md)->markdown();

            $dom = new \DOMDocument('1.0', 'UTF-8');
            libxml_use_internal_errors(true);
            $dom->loadHTML('<?xml encoding="UTF-8"><body>'.$html.'</body>', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
            libxml_clear_errors();

            $body = $dom->getElementsByTagName('body')->item(0);
            if (!$body) return '';

            $out = '';
            foreach (iterator_to_array($body->childNodes) as $node) {
                if ($node->nodeType !== XML_ELEMENT_NODE) {
                    $text = trim($node->textContent ?? '');
                    if ($text !== '') {
                        $out .= "<!-- wp:paragraph --><p>".htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')."</p><!-- /wp:paragraph -->";
                    }
                    continue;
                }

                $tag = strtolower($node->nodeName);
                $htmlFrag = $dom->saveHTML($node);

                switch ($tag) {
                    case 'h1': break;
                    case 'h2': $out .= "<!-- wp:heading {\"level\":2} -->{$htmlFrag}<!-- /wp:heading -->"; break;
                    case 'h3': $out .= "<!-- wp:heading {\"level\":3} -->{$htmlFrag}<!-- /wp:heading -->"; break;
                    case 'h4': $out .= "<!-- wp:heading {\"level\":4} -->{$htmlFrag}<!-- /wp:heading -->"; break;
                    case 'h5': $out .= "<!-- wp:heading {\"level\":5} -->{$htmlFrag}<!-- /wp:heading -->"; break;
                    case 'h6': $out .= "<!-- wp:heading {\"level\":6} -->{$htmlFrag}<!-- /wp:heading -->"; break;
                    case 'p':  $out .= "<!-- wp:paragraph -->{$htmlFrag}<!-- /wp:paragraph -->"; break;
                    case 'ul': $out .= "<!-- wp:list -->{$htmlFrag}<!-- /wp:list -->"; break;
                    case 'ol': $out .= "<!-- wp:list {\"ordered\":true} -->{$htmlFrag}<!-- /wp:list -->"; break;
                    case 'blockquote': $out .= "<!-- wp:quote -->{$htmlFrag}<!-- /wp:quote -->"; break;
                    case 'pre':
                    case 'code':
                        if ($tag === 'code' && strtolower($node->parentNode?->nodeName) !== 'pre') {
                            $htmlFrag = '<pre><code>'.htmlspecialchars($node->textContent ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'</code></pre>';
                        }
                        $out .= "<!-- wp:code -->{$htmlFrag}<!-- /wp:code -->"; break;
                    case 'hr': $out .= "<!-- wp:separator --><hr class=\"wp-block-separator\" /><!-- /wp:separator -->"; break;
                    default:   $out .= "<!-- wp:html -->{$htmlFrag}<!-- /wp:html -->"; break;
                }
            }

            return trim($out);
        } catch (\Throwable) {
            return '';
        }
    }
}
