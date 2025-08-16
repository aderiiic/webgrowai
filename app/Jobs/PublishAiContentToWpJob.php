<?php

namespace App\Jobs;

use App\Models\AiContent;
use App\Models\ContentPublication;
use App\Models\WpIntegration;
use App\Services\WordPressClient;
use App\Support\Usage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Throwable;

class PublishAiContentToWpJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $aiContentId,
        public int $siteId,
        public string $status = 'draft', // draft|publish|future
        public ?string $scheduleAtIso = null, // ISO8601 tid om future
        public ?int $publicationId = null
    ) {
        $this->onQueue('publish');
    }

    public function handle(Usage $usage): void
    {
        $content = AiContent::with('site')->findOrFail($this->aiContentId);
        $integration = WpIntegration::where('site_id', $this->siteId)->firstOrFail();

        // Hämta eller skapa ContentPublication om den saknas
        $pub = $this->publicationId ? ContentPublication::find($this->publicationId) : null;
        if (!$pub) {
            $scheduledAt = null;
            if (!empty($this->scheduleAtIso)) {
                try {
                    $scheduledAt = Carbon::parse($this->scheduleAtIso);
                } catch (\Throwable $e) {
                    $scheduledAt = null; // ogiltig tid ignoreras
                }
            }

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
                ],
            ]);
        }

        // Sätt processing när jobbet startar
        $pub->update(['status' => 'processing', 'message' => null]);

        $client = WordPressClient::for($integration);

        // MD -> block/HTML
        $md = $this->normalizeMd($content->body_md ?? '');
        $blocks = $this->toGutenbergBlocks($md);
        $htmlFallback = Str::of($md)->markdown();

        $payload = [
            'title'   => $content->title ?: Str::limit(strip_tags($htmlFallback), 48, '...'),
            'content' => $blocks !== '' ? $blocks : $htmlFallback,
            'status'  => $this->status,
        ];

        if ($this->status === 'future' && $this->scheduleAtIso) {
            $payload['date'] = $this->scheduleAtIso;
        }

        try {
            $resp = $client->createPost($payload);

            $pub->update([
                'status'      => 'published',
                'external_id' => is_array($resp) ? ($resp['id'] ?? null) : (is_object($resp) ? ($resp->id ?? null) : null),
                'payload'     => $payload,
                'message'     => null,
            ]);

            // Uppdatera state i AI-content
            $content->update([
                'status' => $this->status === 'draft' ? 'ready' : 'published',
            ]);

            // Usage-tracking
            $usage->increment($content->customer_id, 'ai.publish.wp');
        } catch (Throwable $e) {
            $pub->update([
                'status'  => 'failed',
                'message' => $e->getMessage(),
                'payload' => $payload,
            ]);
            throw $e;
        }
    }

    private function normalizeMd(string $md): string
    {
        if ($md === '') {
            return $md;
        }

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
                $minIndent = 0;
                break;
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
        if ($md === '') {
            return '';
        }

        try {
            $html = (string) Str::of($md)->markdown();

            $dom = new \DOMDocument('1.0', 'UTF-8');
            libxml_use_internal_errors(true);
            $dom->loadHTML('<?xml encoding="UTF-8"><body>'.$html.'</body>', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
            libxml_clear_errors();

            $body = $dom->getElementsByTagName('body')->item(0);
            if (!$body) {
                return '';
            }

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
                    case 'h1': $out .= "<!-- wp:heading {\"level\":1} -->{$htmlFrag}<!-- /wp:heading -->"; break;
                    case 'h2': $out .= "<!-- wp:heading {\"level\":2} -->{$htmlFrag}<!-- /wp:heading -->"; break;
                    case 'h3': $out .= "<!-- wp:heading {\"level\":3} -->{$htmlFrag}<!-- /wp:heading -->"; break;
                    case 'h4': $out .= "<!-- wp:heading {\"level\":4} -->{$htmlFrag}<!-- /wp:heading -->"; break;
                    case 'h5': $out .= "<!-- wp:heading {\"level\":5} -->{$htmlFrag}<!-- /wp:heading -->"; break;
                    case 'h6': $out .= "<!-- wp:heading {\"level\":6} -->{$htmlFrag}<!-- /wp:heading -->"; break;

                    case 'p':
                        $out .= "<!-- wp:paragraph -->{$htmlFrag}<!-- /wp:paragraph -->";
                        break;

                    case 'ul':
                        $out .= "<!-- wp:list -->{$htmlFrag}<!-- /wp:list -->";
                        break;

                    case 'ol':
                        $out .= "<!-- wp:list {\"ordered\":true} -->{$htmlFrag}<!-- /wp:list -->";
                        break;

                    case 'blockquote':
                        $out .= "<!-- wp:quote -->{$htmlFrag}<!-- /wp:quote -->";
                        break;

                    case 'pre':
                    case 'code':
                        if ($tag === 'code' && strtolower($node->parentNode?->nodeName) !== 'pre') {
                            $htmlFrag = '<pre><code>'.htmlspecialchars($node->textContent ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'</code></pre>';
                        }
                        $out .= "<!-- wp:code -->{$htmlFrag}<!-- /wp:code -->";
                        break;

                    case 'hr':
                        $out .= "<!-- wp:separator --><hr class=\"wp-block-separator\" /><!-- /wp:separator -->";
                        break;

                    default:
                        $out .= "<!-- wp:html -->{$htmlFrag}<!-- /wp:html -->";
                        break;
                }
            }

            return trim($out);
        } catch (\Throwable $e) {
            return '';
        }
    }
}
