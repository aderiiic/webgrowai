<?php

namespace App\Livewire\Admin\Blog;

use App\Models\Post;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Edit extends Component
{
    public ?Post $post = null;
    public int $idParam = 0;

    public string $title = '';
    public string $slug = '';
    public string $excerpt = '';
    public string $body_md = '';

    public function mount(int $id = 0): void
    {
        $this->idParam = $id;
        if ($id > 0) {
            $this->post = Post::findOrFail($id);
            $this->title = $this->post->title;
            $this->slug = $this->post->slug;
            $this->excerpt = (string) ($this->post->excerpt ?? '');
            $this->body_md = (string) ($this->post->body_md ?? '');
        }
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:160',
            'slug' => 'nullable|string|max:180',
            'excerpt' => 'nullable|string|max:300',
            'body_md' => 'nullable|string',
        ]);

        $slug = $this->slug !== '' ? Str::slug($this->slug) : Str::slug($this->title);

        $data = [
            'title' => $this->title,
            'slug' => $slug,
            'excerpt' => $this->excerpt ?: null,
            'body_md' => $this->body_md ?: null,
        ];

        if ($this->post) {
            $this->post->update($data);
        } else {
            $this->post = Post::create($data);
        }

        session()->flash('success', 'Sparat.');
        redirect()->route('admin.blog.index');
    }

    public function publish(): void
    {
        if (!$this->post) {
            $this->save();
        }
        $this->post->update(['published_at' => now()]);
        session()->flash('success', 'Publicerat.');
        redirect()->route('admin.blog.index');
    }

    public function render()
    {
        return view('livewire.admin.blog.edit');
    }
}
