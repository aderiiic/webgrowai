<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyPlan extends Model
{
    protected $fillable = [
        'customer_id',
        'site_id',
        'run_date',
        'run_tag',
        'type',
        'title',
        'content_md',
        'emailed_at',
    ];

    protected $casts = [
        'run_date' => 'date',
        'emailed_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
