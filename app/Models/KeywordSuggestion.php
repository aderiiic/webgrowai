<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeywordSuggestion extends Model
{
    protected $fillable = [
        'site_id','wp_post_id','wp_type','url','current','suggested','insights','status','applied_at'
    ];

    protected $casts = [
        'current' => 'array',
        'suggested' => 'array',
        'insights' => 'array',
        'applied_at' => 'datetime',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
