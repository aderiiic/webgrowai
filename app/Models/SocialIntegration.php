<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialIntegration extends Model
{
    protected $fillable = [
        'customer_id','provider','page_id','ig_user_id','access_token','status',
    ];

    protected $casts = [
        // Kryptera tokens i databasen
        'access_token' => 'encrypted',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
