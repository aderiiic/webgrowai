<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WpIntegration extends Model
{
    protected $fillable = [
        'site_id', 'wp_url', 'wp_username', 'wp_app_password', 'status', 'last_error',
    ];

    protected $casts = [
        'last_error' => 'string',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
