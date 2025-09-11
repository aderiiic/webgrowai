<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'show_popup',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_pinned'    => 'boolean',
        'is_public'    => 'boolean',
        'show_popup'   => 'boolean',
    ];

    protected function tagsArray(): Attribute
    {
        return Attribute::get(fn () => array_values(array_filter(array_map('trim', explode(',', (string) $this->tags)))));
    }

    public function usersSeen(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_news_popups_seen')
            ->withPivot('seen_at')
            ->withTimestamps();
    }

    public function hasBeenSeenBy($userId): bool
    {
        return $this->usersSeen()->where('user_id', $userId)->exists();
    }

    public function markAsSeenBy($userId): void
    {
        $this->usersSeen()->syncWithoutDetaching([
            $userId => ['seen_at' => now()]
        ]);
    }

    public function getSeenCount(): int
    {
        return $this->usersSeen()->count();
    }

    public static function getPopupForUser($userId): ?self
    {
        return static::query()
            ->where('show_popup', true)
            ->where('published_at', '<=', now())
            ->whereDoesntHave('usersSeen', fn($q) => $q->where('user_id', $userId))
            ->orderByDesc('published_at')
            ->first();
    }
}
