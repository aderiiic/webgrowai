<?php

namespace App\Livewire\Wp;

use App\Models\Site;
use App\Models\WpIntegration;
use App\Services\WordPressClient;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PostEditor extends Component
{
    public Site $site;
    public ?int $postId = null;
    public string $title = '';
    public string $content = '';
    public string $status = 'draft';

    public function mount(Site $site, ?int $postId = null): void
    {
        $this->site = $site;
        $this->postId = $postId;

        if ($postId) {
            $client = WordPressClient::for(WpIntegration::where('site_id', $site->id)->firstOrFail());
            $post = $client->getPost($postId);
            $this->title = $post['title']['rendered'] ?? '';
            $this->content = $post['content']['raw'] ?? $post['content']['rendered'] ?? '';
            $this->status = $post['status'] ?? 'draft';
        }
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|min:2',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,publish,future,pending',
        ]);

        $client = WordPressClient::for(WpIntegration::where('site_id', $this->site->id)->firstOrFail());

        $payload = [
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
        ];

        if ($this->postId) {
            $client->updatePost($this->postId, $payload);
            session()->flash('success', 'Inlägg uppdaterat.');
        } else {
            $new = $client->createPost($payload);
            $this->postId = $new['id'] ?? null;
            session()->flash('success', 'Inlägg skapat.');
        }

        $this->redirectRoute('wp.posts.index', $this->site);
    }

    public function render()
    {
        return view('livewire.wp.post-editor');
    }
}
