<?php

namespace App\Livewire\Admin\Blog;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $q = '';

    public function publish(int $id): void
    {
        $p = Post::findOrFail($id);
        $p->update(['published_at' => now()]);
        session()->flash('success', 'Publicerat.');
    }

    public function unpublish(int $id): void
    {
        $p = Post::findOrFail($id);
        $p->update(['published_at' => null]);
        session()->flash('success', 'Avpublicerat.');
    }

    public function delete(int $id): void
    {
        Post::findOrFail($id)->delete();
        session()->flash('success', 'Raderat.');
    }

    public function render()
    {
        $q = Post::query()
            ->when($this->q !== '', fn($x) => $x->where('title','like','%'.$this->q.'%'))
            ->latest('created_at')
            ->paginate(12);

        return view('livewire.admin.blog.index', ['posts' => $q]);
    }
}
