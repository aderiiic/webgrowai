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
            ->withCount('usersSeen')
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

    public function togglePopup(int $id): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $n = InternalNews::findOrFail($id);

        // Om vi aktiverar popup, inaktivera alla andra först
        if (!$n->show_popup) {
            InternalNews::where('show_popup', true)->update(['show_popup' => false]);
        }

        $n->update(['show_popup' => !$n->show_popup]);

        $message = $n->show_popup ? 'Aktiverade popup för inlägget.' : 'Inaktiverade popup för inlägget.';
        session()->flash('success', $message);
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        InternalNews::whereKey($id)->delete();
        session()->flash('success', 'Tog bort inlägget.');
    }
}
