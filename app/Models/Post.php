<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body_md',
        'featured_image_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(ImageAsset::class, 'featured_image_id');
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (!$this->featured_image_id) {
            return null;
        }

        $asset = $this->featuredImage;
        if (!$asset) {
            return null;
        }

        // Hämta direkt från S3
        $disk = Storage::disk($asset->disk);
        $path = $asset->thumb_path ?: $asset->path;

        return $disk->url($path);
    }
}
