<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ContentPublication extends Model
{
    protected $fillable = [
        'ai_content_id',
        'target',
        'status',
        'scheduled_at',
        'external_id',
        'external_url',
        'message',
        'payload',
        'queue_ref',
        'cancelled_at',
        'metrics',
        'metrics_refreshed_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'metrics_refreshed_at' => 'datetime',
        'payload'      => 'array',
        'metrics'      => 'array',
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(AiContent::class, 'ai_content_id');
    }
}
