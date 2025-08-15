<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversionSuggestion extends Model
{
    protected $fillable = [
        'site_id','wp_post_id','wp_type','url','insights','suggestions','status','applied_at'
    ];

    protected $casts = [
        'insights' => 'array',
        'suggestions' => 'array',
        'applied_at' => 'datetime',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
