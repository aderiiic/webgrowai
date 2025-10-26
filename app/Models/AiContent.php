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
        'archived',
    ];

    protected $casts = [
        'inputs' => 'array',
        'placeholders' => 'array',
        'scheduled_at' => 'datetime',
        'archived' => 'boolean',
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

    public function getTypeDisplay(): array
    {
        // Determine actual type from inputs if type is generic
        $actualType = $this->type;

        if ($actualType === 'generic' && $this->inputs) {
            $channel = $this->inputs['channel'] ?? null;
            $actualType = match ($channel) {
                'facebook', 'instagram', 'linkedin' => 'social',
                'blog' => 'blog',
                'seo' => 'seo',
                'product' => 'product',
                default => 'generic',
            };
        }

        return match ($actualType) {
            'social' => [
                'label' => 'Social',
                'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                'bg' => 'bg-blue-100',
                'text' => 'text-blue-700',
                'border' => 'border-blue-200',
            ],
            'blog' => [
                'label' => 'Blogg',
                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'bg' => 'bg-emerald-100',
                'text' => 'text-emerald-700',
                'border' => 'border-emerald-200',
            ],
            'seo' => [
                'label' => 'SEO',
                'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
                'bg' => 'bg-purple-100',
                'text' => 'text-purple-700',
                'border' => 'border-purple-200',
            ],
            'product' => [
                'label' => 'Produkt',
                'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                'bg' => 'bg-amber-100',
                'text' => 'text-amber-700',
                'border' => 'border-amber-200',
            ],
            'newsletter' => [
                'label' => 'Nyhetsbrev',
                'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                'bg' => 'bg-indigo-100',
                'text' => 'text-indigo-700',
                'border' => 'border-indigo-200',
            ],
            'multi' => [
                'label' => 'Multi',
                'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                'bg' => 'bg-orange-100',
                'text' => 'text-orange-700',
                'border' => 'border-orange-200',
            ],
            default => [
                'label' => 'Generisk',
                'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
                'bg' => 'bg-gray-100',
                'text' => 'text-gray-700',
                'border' => 'border-gray-200',
            ],
        };
    }

    /**
     * Scope for non-archived content
     */
    public function scopeActive($query)
    {
        return $query->where('archived', false);
    }

    /**
     * Scope for archived content
     */
    public function scopeArchived($query)
    {
        return $query->where('archived', true);
    }
}
