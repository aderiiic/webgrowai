<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'customer_id',
        'period',          // YYYY-MM
        'plan_amount',     // ören
        'addon_amount',    // ören
        'total_amount',    // ören
        'currency',        // SEK
        'due_date',
        'status',          // draft|sent|paid|overdue|void
        'sent_at',
        'paid_at',
        'lines',           // json
    ];

    protected $casts = [
        'plan_amount'   => 'integer',
        'addon_amount'  => 'integer',
        'total_amount'  => 'integer',
        'due_date'      => 'date',
        'sent_at'       => 'datetime',
        'paid_at'       => 'datetime',
        'lines'         => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // Hjälp – belopp i kronor som float (för visning)
    public function totalKr(): float { return ($this->total_amount ?? 0) / 100; }
    public function planKr(): float  { return ($this->plan_amount ?? 0) / 100; }
    public function addonKr(): float { return ($this->addon_amount ?? 0) / 100; }
}
