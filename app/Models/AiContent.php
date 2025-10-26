<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiContent extends Model
{
    protected $fillable = [
        'customer_id',
        'site_id',
        'template_id',
        'bulk_generation_id',
        'title',
        'tone',
        'provider',
        'status',
        'inputs',
        'body_md',
        'error',
        'scheduled_at',
        'placeholders',
        'batch_index',
        'type',
    ];

    protected $casts = [
        'inputs' => 'array',
        'placeholders' => 'array',
        'scheduled_at' => 'datetime',
    ];

    public function template(): BelongsTo { return $this->belongsTo(ContentTemplate::class); }
    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function site(): BelongsTo { return $this->belongsTo(Site::class); }

    public function bulkGeneration(): BelongsTo
    {
        return $this->belongsTo(BulkGeneration::class);
    }

    public function publications(): HasMany
    {
        return $this->hasMany(ContentPublication::class, 'ai_content_id');
    }
}
