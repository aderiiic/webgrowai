<?php
// app/Models/Integration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Integration extends Model
{
    protected $fillable = [
        'site_id',
        'provider',
        'credentials',
        'status',
        'last_error',
        'synced_at',
    ];

    protected $casts = [
        'credentials' => 'array',
        'synced_at'   => 'datetime',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
