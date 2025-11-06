<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BulkGeneration extends Model
{
    protected $fillable = [
        'customer_id',
        'template_text',
        'custom_title_template',
        'variables',
        'status',
        'total_count',
        'completed_count',
        'content_type',
        'tone',
        'site_id',
    ];

    protected $casts = [
        'variables' => 'array',
        'total_count' => 'integer',
        'completed_count' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function contents(): HasMany
    {
        return $this->hasMany(AiContent::class);
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentage(): int
    {
        if ($this->total_count === 0) {
            return 0;
        }

        return (int) round(($this->completed_count / $this->total_count) * 100);
    }

    /**
     * Check if generation is complete
     */
    public function isComplete(): bool
    {
        return $this->status === 'done' || $this->completed_count >= $this->total_count;
    }
}
