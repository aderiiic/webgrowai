<?php

namespace App\Livewire\Admin\Blog;

use App\Models\Post;
use App\Models\ImageAsset;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
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
    public ?int $featured_image_id = null;

    public function mount(int $id = 0): void
    {
        $this->idParam = $id;
        if ($id > 0) {
            $this->post = Post::findOrFail($id);
            $this->title = $this->post->title;
            $this->slug = $this->post->slug;
            $this->excerpt = (string) ($this->post->excerpt ?? '');
            $this->body_md = (string) ($this->post->body_md ?? '');
            $this->featured_image_id = $this->post->featured_image_id;
        }
    }

    #[On('media-selected')]
    public function onMediaSelected(int $id): void
    {
        $this->featured_image_id = $id;
    }

    public function removeFeaturedImage(): void
    {
        $this->featured_image_id = null;
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:160',
            'slug' => 'nullable|string|max:180',
            'excerpt' => 'nullable|string|max:300',
            'body_md' => 'nullable|string',
            'featured_image_id' => 'nullable|exists:image_assets,id',
        ]);

        $slug = $this->slug !== '' ? Str::slug($this->slug) : Str::slug($this->title);

        $data = [
            'title' => $this->title,
            'slug' => $slug,
            'excerpt' => $this->excerpt ?: null,
            'body_md' => $this->body_md ?: null,
            'featured_image_id' => $this->featured_image_id,
        ];

        if ($this->post) {
            $this->post->update($data);
        } else {
            $this->post = Post::create($data);
        }

        session()->flash('success', 'Inlägg sparat.');
        $this->redirectRoute('admin.blog.index');
    }

    public function publish(): void
    {
        if (!$this->post) {
            $this->save();
        }

        $this->post->update([
            'published_at' => now(),
            'featured_image_id' => $this->featured_image_id,
        ]);

        session()->flash('success', 'Inlägg publicerat.');
        $this->redirectRoute('admin.blog.index');
    }

    public function getFeaturedImageProperty(): ?ImageAsset
    {
        return $this->featured_image_id ? ImageAsset::find($this->featured_image_id) : null;
    }

    public function render()
    {
        return view('livewire.admin.blog.edit');
    }
}
