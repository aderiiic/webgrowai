<?php

namespace App\Livewire\Admin\News;

use App\Models\InternalNews;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $q = '';

    public function render()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $items = InternalNews::query()
            ->when($this->q !== '', fn($q) =>
            $q->where('title','like',"%{$this->q}%")
                ->orWhere('tags','like',"%{$this->q}%")
            )
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.admin.news.index', compact('items'));
    }

    public function pin(int $id): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $n = InternalNews::findOrFail($id);
        $n->update(['is_pinned' => !$n->is_pinned]);
        session()->flash('success', 'Uppdaterade fästning av inlägget.');
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        InternalNews::whereKey($id)->delete();
        session()->flash('success', 'Tog bort inlägget.');
    }
}
