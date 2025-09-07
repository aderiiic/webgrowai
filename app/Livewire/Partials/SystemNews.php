<?php

namespace App\Livewire\Partials;

use App\Models\InternalNews;
use Livewire\Component;

class SystemNews extends Component
{
    public int $limit = 4;

    // Modal-state
    public bool $show = false;
    public ?int $activeId = null;
    public ?array $active = null; // ['title'=>..., 'body_md'=>..., 'type'=>..., 'published_at'=>...]

    public function open(int $id): void
    {
        $n = InternalNews::findOrFail($id);
        $this->activeId = $n->id;
        $this->active = [
            'title'        => $n->title,
            'body_md'      => $n->body_md,
            'type'         => $n->type,
            'published_at' => optional($n->published_at)?->format('Y-m-d H:i'),
            'tags'         => $n->tags,
            'is_pinned'    => (bool) $n->is_pinned,
        ];
        $this->show = true;
    }

    public function close(): void
    {
        $this->show = false;
        $this->activeId = null;
        $this->active = null;
    }

    public function render()
    {
        $items = InternalNews::query()
            ->whereNotNull('published_at')
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit($this->limit)
            ->get();

        return view('livewire.partials.system-news', compact('items'));
    }
}
