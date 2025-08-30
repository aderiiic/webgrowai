<?php

namespace App\Livewire;

use App\Models\ImageAsset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Laravel\Facades\Image as ImageManager;
use Illuminate\Support\Str;

class MediaPicker extends Component
{
    use WithFileUploads;

    #[Modelable]
    public int $selectedId = 0;

    public string $search = '';
    public array $items = [];
    public int $page = 1;
    public bool $open = false;

    public $upload;

    public function mount(int $selectedId = 0): void
    {
        $this->selectedId = $selectedId;
    }

    #[On('media-picker:open')]
    public function open(): void
    {
        $this->open = true;
        $this->load();
    }

    public function updatedSearch(): void
    {
        $this->page = 1;
        $this->load();
    }

    public function next(): void
    {
        $this->page++;
        $this->load();
    }

    public function prev(): void
    {
        if ($this->page > 1) {
            $this->page--;
            $this->load();
        }
    }

    public function pick(int $id): void
    {
        // Sätt bara modellen – låt bekräftelsen skicka eventet
        $this->selectedId = $id;
    }

    public function confirmSelection(): void
    {
        if ($this->selectedId > 0) {
            // Skicka eventet först vid bekräftelse
            $this->dispatch('media-selected', id: $this->selectedId);
            $this->open = false;
        }
    }

    public function load(): void
    {
        $user = Auth::user();

        $customerId = (int) (app(\App\Support\CurrentCustomer::class)->get()?->id ?? ($user?->customer_id ?? 0));
        if ($customerId <= 0) {
            $this->items = [];
            return;
        }

        $perPage = 24;
        $q = ImageAsset::query()->where('customer_id', $customerId);

        if (strlen(trim($this->search)) > 0) {
            $q->where('original_name', 'like', '%' . trim($this->search) . '%');
        }

        $assets = $q->orderByDesc('created_at')
            ->skip(($this->page - 1) * $perPage)
            ->take($perPage)
            ->get(['id', 'original_name', 'last_used_at']);

        $this->items = $assets->map(fn(ImageAsset $a) => [
            'id'            => $a->id,
            'original_name' => $a->original_name,
            'last_used_at'  => optional($a->last_used_at)?->toISOString(),
        ])->all();
    }

    public function updatedUpload(): void
    {
        if (!$this->upload) return;

        $this->validate([
            'upload' => 'file|mimes:jpeg,jpg,png,webp|max:3072',
        ]);

        $user = Auth::user();
        $customerId = (int) (app(\App\Support\CurrentCustomer::class)->get()?->id ?? ($user?->customer_id ?? 0));
        if ($customerId <= 0) {
            $this->addError('upload', 'Ingen kundkontext tillgänglig.');
            $this->reset('upload');
            return;
        }

        $file = $this->upload;
        $uuid = (string) Str::uuid();
        $ext  = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $baseDir = "customers/{$customerId}/images/" . now()->format('Y/m');
        $filename = "{$uuid}.{$ext}";
        $thumbFilename = "{$uuid}.webp";
        $thumbDir = "customers/{$customerId}/images/thumbs/" . now()->format('Y/m');

        try {
            $storedPath = $file->storeAs($baseDir, $filename, [
                'disk' => 's3',
                'visibility' => 'private',
            ]);

            $origBytes = Storage::disk('s3')->get($storedPath);
            $mime = $file->getMimeType() ?: 'application/octet-stream';
            $size = $file->getSize() ?: strlen($origBytes);

            $width = $height = null;
            if (function_exists('getimagesizefromstring')) {
                $info = @getimagesizefromstring($origBytes);
                $width  = $info[0] ?? null;
                $height = $info[1] ?? null;
            }

            $thumbPath = null;
            try {
                $thumbImg = ImageManager::read($origBytes)
                    ->cover(256, 256)
                    ->toWebp(85);
                $thumbStored = $thumbDir . '/' . $thumbFilename;
                Storage::disk('s3')->put($thumbStored, (string)$thumbImg, [
                    'visibility'  => 'private',
                    'ContentType' => 'image/webp',
                ]);
                $thumbPath = $thumbStored;
            } catch (\Throwable) {
                // Ignorera thumb-fel
            }

            ImageAsset::create([
                'customer_id'   => $customerId,
                'uploaded_by'   => $user?->id,
                'disk'          => 's3',
                'path'          => $storedPath,
                'thumb_path'    => $thumbPath,
                'original_name' => $file->getClientOriginalName(),
                'mime'          => $mime,
                'size_bytes'    => $size,
                'width'         => $width,
                'height'        => $height,
                'sha256'        => hash('sha256', $origBytes),
            ]);

            $this->reset('upload');
            $this->page = 1;
            $this->load();
        } catch (\Throwable $e) {
            $this->addError('upload', 'Uppladdning misslyckades: ' . $e->getMessage());
        } finally {
            if ($this->upload) {
                $this->reset('upload');
            }
        }
    }

    public function render()
    {
        return view('livewire.media-picker');
    }
}
