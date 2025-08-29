<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ImageAsset extends Model
{
    protected $fillable = [
        'customer_id','uploaded_by','disk','path','thumb_path','original_name',
        'mime','size_bytes','width','height','sha256','usage_count','last_used_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
    ];

    public function publications(): BelongsToMany
    {
        return $this->belongsToMany(ContentPublication::class, 'content_publication_image')
            ->withPivot('used_at');
    }

    public static function markUsed(int $imageId, int $publicationId): void
    {
        /** @var self $img */
        $img = self::find($imageId);
        if (!$img) return;

        $now = now();
        \DB::table('content_publication_image')->updateOrInsert(
            ['content_publication_id' => $publicationId, 'image_asset_id' => $imageId],
            ['used_at' => $now]
        );

        $img->increment('usage_count');
        $img->forceFill(['last_used_at' => $now])->save();
    }
}
