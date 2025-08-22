<?php
namespace App\Services\Sites;

use App\Models\Integration;
use App\Services\Sites\Providers\WordPressAdapter;
use App\Services\Sites\Providers\ShopifyAdapter;
use App\Services\Sites\Providers\CustomAdapter;
use RuntimeException;

class IntegrationManager
{
    public function forSite(int $siteId): SiteIntegrationClient
    {
        $integration = Integration::where('site_id', $siteId)->first();
        if (!$integration) {
            throw new RuntimeException('Ingen integration kopplad till site');
        }
        return $this->forIntegration($integration);
    }

    public function forIntegration(Integration $integration): SiteIntegrationClient
    {
        return match ($integration->provider) {
            'wordpress' => new WordPressAdapter($integration),
            'shopify'   => new ShopifyAdapter($integration),
            'custom'    => new CustomAdapter($integration),
            default     => throw new RuntimeException('Okänd provider: '.$integration->provider),
        };
    }
}
