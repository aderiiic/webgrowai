<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    protected $fillable = [
        'site_id','visitor_id','email','first_seen','last_seen','sessions','last_ip_hash','user_agent_hash'
    ];

    protected $casts = [
        'first_seen' => 'datetime',
        'last_seen'  => 'datetime',
        'sessions'   => 'integer',
    ];

    public function site(): BelongsTo { return $this->belongsTo(Site::class); }
    public function events(): HasMany { return $this->hasMany(LeadEvent::class); }
    public function score(): HasMany { return $this->hasMany(LeadScore::class); }
}
