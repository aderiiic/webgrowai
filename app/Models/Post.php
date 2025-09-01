<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return $this->featured_image_id ? route('assets.thumb', $this->featured_image_id) : null;
    }
}
