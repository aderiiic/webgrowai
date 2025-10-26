<?php

namespace App\Livewire\AI;

use App\Services\Billing\FeatureGuard;
use App\Support\CurrentCustomer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ContentTypeSelector extends Component
{
    public function render(CurrentCustomer $current): View
    {
        $customer = $current->get();
        $featureGuard = app(FeatureGuard::class);

        $features = [
            'social' => $customer ? $featureGuard->canUseFeature($customer, FeatureGuard::FEATURE_SOCIAL_MEDIA) : false,
            'blog' => $customer ? $featureGuard->canUseFeature($customer, FeatureGuard::FEATURE_BLOG) : false,
            'seo' => $customer ? $featureGuard->canUseFeature($customer, FeatureGuard::FEATURE_SEO_OPTIMIZE) : false,
            'product' => $customer ? $featureGuard->canUseFeature($customer, FeatureGuard::FEATURE_PRODUCT) : false,
            'bulk' => $customer ? $featureGuard->canUseFeature($customer, FeatureGuard::FEATURE_BULK_GENERATE) : false,
        ];

        return view('livewire.a-i.content-type-selector', compact('features'));
    }
}
