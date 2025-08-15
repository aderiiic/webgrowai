<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OveragePermission extends Model
{
    protected $fillable = ['customer_id','period','approved','approved_at','note'];

    protected $casts = [
        'approved'    => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
