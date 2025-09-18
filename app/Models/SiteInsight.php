<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteInsight extends Model
{
    protected $fillable = [
        'site_id',
        'week_start',
        'payload',
        'model',
        'trends_data',
        'generated_at',
    ];

    protected $casts = [
        'week_start'   => 'date',
        'generated_at' => 'datetime',
        'payload'      => 'array',
        'trends_data'  => 'array',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function getTopicsAttribute(): array
    {
        return $this->payload['topics'] ?? [];
    }

    public function getTimeslotsAttribute(): array
    {
        return $this->payload['timeslots'] ?? [];
    }

    public function getActionsAttribute(): array
    {
        return $this->payload['actions'] ?? [];
    }

    public function getTrendingTopicsAttribute(): array
    {
        return $this->trends_data['trending_topics'] ?? [];
    }

    public function getIndustryTrendsAttribute(): array
    {
        return $this->trends_data['industry_trends'] ?? [];
    }

    public function getPopularContentAttribute(): array
    {
        return $this->trends_data['popular_content'] ?? [];
    }

    public function getRecommendedHashtagsAttribute(): array
    {
        return $this->trends_data['recommended_hashtags'] ?? [];
    }
}
