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
        // Välj helst en "connected" integration om sådan finns
        $integration = Integration::where('site_id', $siteId)
            ->orderByRaw("CASE WHEN status = 'connected' THEN 0 ELSE 1 END")
            ->first();

        if (!$integration) {
            throw new RuntimeException('Ingen integration kopplad till site');
        }
        return $this->forIntegration($integration);
    }

    public function forSiteWithProvider(int $siteId, ?string $preferred = null): SiteIntegrationClient
    {
        $q = Integration::where('site_id', $siteId);
        if ($preferred) {
            $q->where('provider', $preferred);
        }
        $integration = $q->orderByRaw("CASE WHEN status = 'connected' THEN 0 ELSE 1 END")->first();

        // Om preferred inte hittades, fallback till valfri connected integration
        if (!$integration) {
            return $this->forSite($siteId);
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
