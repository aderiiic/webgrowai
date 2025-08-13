<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeoAuditItem extends Model
{
    protected $fillable = ['seo_audit_id', 'type', 'page_url', 'message', 'severity', 'data'];

    protected $casts = [
        'data' => 'array',
    ];

    public function audit(): BelongsTo
    {
        return $this->belongsTo(SeoAudit::class, 'seo_audit_id');
    }
}
