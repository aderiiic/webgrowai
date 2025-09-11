<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteInsight extends Model
{
    protected $fillable = [
        'site_id',
        'week_start',
        'payload',
        'model',
        'generated_at',
    ];

    protected $casts = [
        'week_start'   => 'date',
        'generated_at' => 'datetime',
        'payload'      => 'array',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
