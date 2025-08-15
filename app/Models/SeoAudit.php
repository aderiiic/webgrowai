<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeoAudit extends Model
{
    protected $fillable = [
        'site_id',
        'lighthouse_performance',
        'lighthouse_accessibility',
        'lighthouse_best_practices',
        'lighthouse_seo',
        'title_issues',
        'meta_issues',
        'summary',
    ];

    protected $casts = [
        'summary' => 'array',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SeoAuditItem::class);
    }
}
