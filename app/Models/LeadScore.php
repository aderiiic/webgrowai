<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadScore extends Model
{
    protected $fillable = ['site_id','lead_id','score_raw','score_norm','breakdown','last_calculated_at'];
    protected $casts = ['breakdown' => 'array', 'last_calculated_at' => 'datetime'];

    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function site(): BelongsTo { return $this->belongsTo(Site::class); }
}
