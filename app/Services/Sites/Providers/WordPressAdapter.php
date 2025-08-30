<?php
namespace App\Services\Sites\Providers;

use App\Models\Integration;
use App\Models\ImageAsset;
use App\Services\Sites\SiteIntegrationClient;
use App\Services\WordPressClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class WordPressAdapter implements SiteIntegrationClient
{
    public function __construct(private Integration $integration) {}

    public function provider(): string { return 'wordpress'; }

    public function supports(string $capability): bool
    {
        return in_array($capability, ['list','fetch','update_meta','publish'], true);
    }

    private function wp(): WordPressClient
    {
        $creds = (array)($this->integration->credentials ?? []);

        $baseUrl = rtrim((string)($creds['wp_url'] ?? $creds['url'] ?? ''), '/');
        $username = (string)($creds['wp_username'] ?? $creds['username'] ?? '');
        $rawPass  = (string)($creds['wp_app_password'] ?? $creds['app_password'] ?? '');

        if ($baseUrl === '' || $username === '' || $rawPass === '') {
            throw new \RuntimeException('Ogiltiga WordPress-uppgifter (url/användare/lösen saknas).');
        }

        try {
            $appPassword = Crypt::decryptString($rawPass);
        } catch (\Throwable) {
            $appPassword = $rawPass;
        }

        return new WordPressClient($baseUrl, $username, $appPassword);
    }

    public function listDocuments(array $opts = []): array
    {
        $wp = $this->wp();
        $limit = (int)($opts['limit'] ?? 10);

        $pages = $wp->getPages(['per_page' => min($limit,100), 'orderby'=>'modified','order'=>'desc','status'=>'publish']);
        $remaining = max(0, $limit - count($pages));
        $posts = $remaining ? $wp->getPosts(['per_page' => min($remaining,100),'orderby'=>'modified','order'=>'desc','status'=>'publish']) : [];

        $map = function ($it) {
            return [
                'id'     => (int)$it['id'],
                'type'   => (string)($it['type'] ?? 'page'),
                'url'    => (string)($it['link'] ?? ''),
                'title'  => trim(strip_tags($it['title']['rendered'] ?? '')),
                'excerpt'=> trim(strip_tags($it['excerpt']['rendered'] ?? '')),
            ];
        };

        return array_map($map, array_merge($pages, $posts));
    }

    public function getDocument(int|string $id, string $type): array
    {
        $wp = $this->wp();
        $data = $type === 'post' ? $wp->getPost((int)$id) : $wp->getPage((int)$id);

        $html = (string)Arr::get($data, 'content.rendered', '');
        return [
            'id'     => (int)($data['id'] ?? $id),
            'type'   => $type,
            'url'    => (string)($data['link'] ?? ''),
            'title'  => trim(strip_tags($data['title']['rendered'] ?? '')),
            'excerpt'=> trim(strip_tags($data['excerpt']['rendered'] ?? '')),
            'html'   => $html,
        ];
    }

    public function updateDocument(int|string $id, string $type, array $payload): void
    {
        $wp = $this->wp();
        $mapped = [];
        if (isset($payload['title']))   $mapped['title'] = $payload['title'];
        if (isset($payload['meta']))    $mapped['excerpt'] = $payload['meta'];
        if (isset($payload['content'])) $mapped['content'] = $payload['content'];
        if (isset($payload['status']))  $mapped['status']  = $payload['status'];
        if (isset($payload['date']))    $mapped['date']    = $payload['date'];

        $type === 'post' ? $wp->updatePost((int)$id, $mapped) : $wp->updatePage((int)$id, $mapped);
    }

    public function publish(array $payload): array
    {
        $wp = $this->wp();

        // 1) Om bild valts: ladda upp till WP Media med uploadMedia
        $mediaId = null;
        if (!empty($payload['image_asset_id'])) {
            $asset = ImageAsset::find((int)$payload['image_asset_id']);
            if ($asset) {
                try {
                    if (!Storage::disk($asset->disk)->exists($asset->path)) {
                        throw new \RuntimeException("Bildfil hittades inte: {$asset->path}");
                    }

                    $bytes = Storage::disk($asset->disk)->get($asset->path);
                    if (empty($bytes)) {
                        throw new \RuntimeException("Bildfilen är tom eller kunde inte läsas");
                    }

                    $filename = $asset->original_name ?: basename($asset->path) ?: ('image_'.$asset->id.'.jpg');
                    $mime = $asset->mime ?: 'image/jpeg';

                    $media = $wp->uploadMedia($bytes, $filename, $mime);
                    $mediaId = (int)($media['id'] ?? 0);

                } catch (\Throwable $e) {
                    \Log::error('Misslyckades att ladda upp bild till WordPress', [
                        'error' => $e->getMessage(),
                        'asset_id' => $asset->id,
                        'filename' => $filename ?? 'okänd'
                    ]);
                    $mediaId = null;
                }
            }
            unset($payload['image_asset_id']); // okänt fält för WP
        }

        // 2) Rensa bort eventuell titel-duplicering från innehållet
        if (!empty($payload['content']) && !empty($payload['title'])) {
            $content = $payload['content'];
            $title = $payload['title'];

            // Ta bort titel från början av innehållet om den finns där
            $content = preg_replace('/^#\s*' . preg_quote($title, '/') . '\s*\n*/i', '', $content);
            $content = preg_replace('/^' . preg_quote($title, '/') . '\s*\n*/i', '', $content);

            $payload['content'] = trim($content);
        }

        // 3) Ta bort eventuella bildreferenser från innehållet (om featured image används)
        if ($mediaId && !empty($payload['content'])) {
            // Ta bort markdown bildreferenser
            $payload['content'] = preg_replace('/!\[.*?\]\(.*?\)/', '', $payload['content']);
            // Ta bort HTML img tags
            $payload['content'] = preg_replace('/<img[^>]*>/i', '', $payload['content']);
            $payload['content'] = trim($payload['content']);
        }

        // 4) Skapa inlägget (utan featured_media)
        $resp = $wp->createPost($payload);
        if (!is_array($resp) || empty($resp['id'])) {
            return ['id' => null];
        }

        // 5) Sätt featured_media i efterhand med en liten retry-loop
        if ($mediaId) {
            $postId = (int)$resp['id'];
            $attempts = 0;
            $ok = false;

            while ($attempts < 3 && !$ok) {
                try {
                    $wp->updatePost($postId, ['featured_media' => $mediaId]);
                    $ok = true;
                    \Log::info('Featured media satt för WordPress post', [
                        'post_id' => $postId,
                        'media_id' => $mediaId
                    ]);
                } catch (\Throwable $e) {
                    \Log::warning('Försök att sätta featured media misslyckades', [
                        'attempt' => $attempts + 1,
                        'post_id' => $postId,
                        'media_id' => $mediaId,
                        'error' => $e->getMessage()
                    ]);

                    usleep(500_000); // 500 ms
                    $attempts++;
                    if ($attempts >= 3) {
                        \Log::error('Kunde inte sätta featured media efter 3 försök', [
                            'post_id' => $postId,
                            'media_id' => $mediaId
                        ]);
                    }
                }
            }
        }

        return $resp;
    }
}
