<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $table = 'app_subscriptions';

    protected $fillable = [
        'customer_id',
        'plan_id',
        'status',                // active|trial|paused|cancelled
        'trial_ends_at',
        'billing_cycle',         // monthly|annual
        'current_period_start',
        'current_period_end',
        'last_invoiced_period',  // YYYY-MM
    ];

    protected $casts = [
        'trial_ends_at'         => 'datetime',
        'current_period_start'  => 'datetime',
        'current_period_end'    => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function inTrial(): bool
    {
        return $this->status === 'trial'
            && $this->trial_ends_at !== null
            && now()->lte($this->trial_ends_at);
    }

    public function activeOrTrial(): bool
    {
        return $this->status === 'active' || $this->inTrial();
    }
}
