<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ga4Integration extends Model
{
    protected $table = 'ga4_integrations';

    protected $fillable = [
        'site_id',
        'provider',
        'property_id',
        'stream_id',
        'hostname',
        'access_token',
        'refresh_token',
        'expires_at',
        'service_account_json',
        'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'site_id'    => 'integer',
    ];

    public function isConnected(): bool
    {
        return ($this->status === 'connected');
    }
}
