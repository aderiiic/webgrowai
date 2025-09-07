<?php

namespace App\Livewire\Admin\News;

use App\Models\InternalNews;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Edit extends Component
{
    public ?InternalNews $news = null;

    public string $title = '';
    public string $body_md = '';
    public string $tags = '';
    public string $type = 'info';
    public bool $is_pinned = false;
    public bool $is_public = false;
    public ?string $published_at = null;

    public function mount(?int $id = null): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        if ($id) {
            $this->news = InternalNews::findOrFail($id);
            $this->title        = (string) $this->news->title;
            $this->body_md      = (string) $this->news->body_md;
            $this->tags         = (string) $this->news->tags;
            $this->type         = (string) $this->news->type;
            $this->is_pinned    = (bool) $this->news->is_pinned;
            $this->is_public    = (bool) $this->news->is_public;
            $this->published_at = optional($this->news->published_at)->format('Y-m-d\TH:i');
        } else {
            $this->published_at = now()->format('Y-m-d\TH:i');
        }
    }

    public function save(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $this->validate([
            'title' => 'required|string|max:200',
            'body_md' => 'nullable|string|max:20000',
            'tags' => 'nullable|string|max:500',
            'type' => 'required|in:bugfix,feature,info',
            'is_pinned' => 'boolean',
            'is_public' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $data = [
            'title'        => $this->title,
            'body_md'      => $this->body_md ?: null,
            'tags'         => $this->tags ?: null,
            'type'         => $this->type,
            'is_pinned'    => $this->is_pinned,
            'is_public'    => $this->is_public,
            'published_at' => $this->published_at ? \Carbon\Carbon::parse($this->published_at) : null,
            'created_by'   => auth()->id(),
        ];

        if ($this->news) {
            $this->news->update($data);
        } else {
            $this->news = InternalNews::create($data);
        }

        session()->flash('success', 'Nyheten sparades.');
        redirect()->route('admin.news.index');
    }

    public function render()
    {
        return view('livewire.admin.news.edit');
    }
}
