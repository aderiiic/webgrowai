<?php
namespace App\Services\Sites\Providers;

use App\Models\Integration;
use App\Services\Sites\SiteIntegrationClient;
use App\Services\WordPressClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
        // Bygg WP-klienten från $this->integration->credentials
        return WordPressClient::forLegacyArray($this->integration->credentials);
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
        $resp = $wp->createPost($payload);
        return is_array($resp) ? $resp : ['id' => null];
    }
}
