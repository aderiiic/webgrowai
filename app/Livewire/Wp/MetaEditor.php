<?php

namespace App\Livewire\Wp;

use App\Models\Site;
use App\Models\WpIntegration;
use App\Services\WordPressClient;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
class MetaEditor extends Component
{
    public Site $site;
    public int $postId;

    public string $currentTitle = '';
    public string $currentExcerpt = '';
    public string $rawContent = '';

    public string $suggestedTitle = '';
    public string $suggestedMeta = '';

    public string $title = '';
    public string $meta = '';

    public function mount(Site $site, int $postId): void
    {
        $this->site = $site;
        $this->postId = $postId;

        $client = WordPressClient::for(WpIntegration::where('site_id', $site->id)->firstOrFail());
        $post = $client->getPost($postId);

        $this->currentTitle = trim(strip_tags($post['title']['rendered'] ?? ''));
        $this->rawContent = trim(strip_tags($post['content']['rendered'] ?? ''));
        $this->currentExcerpt = trim(strip_tags($post['excerpt']['rendered'] ?? ''));

        $this->generateSuggestions();
        $this->title = $this->suggestedTitle ?: $this->currentTitle;
        $this->meta = $this->suggestedMeta ?: $this->currentExcerpt;
    }

    public function generateSuggestions(): void
    {
        // Titel
        $title = $this->currentTitle;
        if ($title === '') {
            // Ta de första orden från content
            $title = Str::title(Str::limit($this->rawContent, 60, ''));
        }
        // Ta bort " | Brand" när title är för lång
        if (Str::length($title) > 60 && Str::contains($title, ' | ')) {
            $title = explode(' | ', $title)[0];
        }
        $title = trim(Str::limit($title, 60, ''));

        // Meta description
        $source = $this->currentExcerpt !== '' ? $this->currentExcerpt : $this->rawContent;
        $source = preg_replace('/\s+/', ' ', $source ?? '');
        $meta = trim($source);

        if ($meta === '') {
            $meta = 'Läs mer om ämnet i detta inlägg och få praktiska tips för att komma igång.';
        }
        if (Str::length($meta) > 155) {
            $cut = Str::substr($meta, 0, 155);
            // backa till senaste mellanslag för att undvika ordkapning
            $lastSpace = strrpos($cut, ' ');
            if ($lastSpace !== false) {
                $cut = substr($cut, 0, $lastSpace);
            }
            $meta = rtrim($cut, ' .,:;').'.';
        }

        $this->suggestedTitle = $title;
        $this->suggestedMeta = $meta;
    }

    public function apply(): void
    {
        $this->validate([
            'title' => 'required|string|max:120',
            'meta' => 'nullable|string|max:200',
        ]);

        $client = WordPressClient::for(WpIntegration::where('site_id', $this->site->id)->firstOrFail());

        $payload = [
            'title' => $this->title,
            // WP excerpt används för meta description om inget SEO-plugin används
            'excerpt' => $this->meta,
            // Försök uppdatera Yoast-fält om exponerade via REST
            'meta' => [
                '_yoast_wpseo_title' => $this->title,
                '_yoast_wpseo_metadesc' => $this->meta,
            ],
        ];

        try {
            $client->updatePost($this->postId, $payload);
            session()->flash('success', 'Titel och meta uppdaterade.');
            $this->redirectRoute('wp.posts.index', $this->site);
        } catch (\Throwable $e) {
            $this->addError('meta', 'Kunde inte uppdatera i WordPress: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.wp.meta-editor');
    }
}
