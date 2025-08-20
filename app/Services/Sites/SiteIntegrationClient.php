<?php
namespace App\Services\Sites;

interface SiteIntegrationClient
{
    // Metadata/kapabiliteter
    public function provider(): string; // 'wordpress' | 'shopify' | 'custom'
    public function supports(string $capability): bool; // t.ex. 'list', 'fetch', 'update_meta', 'publish'

    // Listning av innehåll att analysera
    public function listDocuments(array $opts = []): array; // [ [id,type,url,title,excerpt], ... ]

    // Hämtning av fulltext för analys
    public function getDocument(int|string $id, string $type): array; // ['id','type','url','title','excerpt','html']

    // Uppdateringar (titel/meta etc.)
    public function updateDocument(int|string $id, string $type, array $payload): void;

    // Publicering av nytt innehåll
    public function publish(array $payload): array; // returnerar t.ex. ['id'=>..,'url'=>..]
}
