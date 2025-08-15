<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentPublication extends Model
{
    protected $fillable = [
        'ai_content_id','target','status','scheduled_at','external_id','message','payload',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'payload' => 'array',
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(AiContent::class, 'ai_content_id');
    }
}
