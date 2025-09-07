<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class InternalNews extends Model
{
    protected $fillable = [
        'title',
        'body_md',
        'tags',         // kommaseparerade taggar, valfritt
        'type',         // bugfix|feature|info
        'published_at',
        'created_by',
        'is_pinned',    // lyft upp i listan
        'is_public',    // ev. framtida publik vy
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_pinned'    => 'boolean',
        'is_public'    => 'boolean',
    ];

    protected function tagsArray(): Attribute
    {
        return Attribute::get(fn () => array_values(array_filter(array_map('trim', explode(',', (string) $this->tags)))));
    }
}
