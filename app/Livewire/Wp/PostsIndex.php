<?php

namespace App\Livewire\Wp;

use App\Models\Site;
use App\Models\WpIntegration;
use App\Services\WordPressClient;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PostsIndex extends Component
{
    public Site $site;
    public array $posts = [];
    public string $status = 'any';

    public function mount(Site $site): void
    {
        $this->site = $site;
        $integration = WpIntegration::where('site_id', $site->id)->firstOrFail();
        $client = WordPressClient::for($integration);
        $this->posts = $client->getPosts(['status' => $this->status, 'per_page' => 10]);
    }

    public function updatedStatus(): void
    {
        $integration = WpIntegration::where('site_id', $this->site->id)->firstOrFail();
        $client = WordPressClient::for($integration);
        $this->posts = $client->getPosts(['status' => $this->status, 'per_page' => 10]);
    }

    public function render()
    {
        return view('livewire.wp.posts-index');
    }
}
