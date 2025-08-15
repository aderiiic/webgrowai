<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadEvent extends Model
{
    protected $fillable = ['site_id','lead_id','type','url','meta','occurred_at'];
    protected $casts = ['meta' => 'array', 'occurred_at' => 'datetime'];

    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function site(): BelongsTo { return $this->belongsTo(Site::class); }
}
