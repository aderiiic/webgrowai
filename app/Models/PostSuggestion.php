<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostSuggestion extends Model
{
    protected $fillable = [
        'customer_id','provider','topic','content','recommended_times','expires_at',
    ];

    protected $casts = [
        'recommended_times' => 'array',
        'expires_at' => 'datetime',
    ];
}
