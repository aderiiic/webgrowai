<?php
// app/Models/Site.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Site extends Model
{
    protected $fillable = [
        'customer_id',
        'name',
        'url',
        'status',
        'public_key',
        'secret',
        'industry',
        'business_description',
        'target_audience',
        'brand_voice',
        'locale',
        'ai_prefs',
    ];

    protected $casts = [
        'ai_prefs' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Site $site) {
            if (empty($site->public_key)) {
                $site->public_key = 'site_' . Str::random(32);
            }
            if (empty($site->secret)) {
                $site->secret = 'sec_' . Str::random(48);
            }
            if (empty($site->locale)) {
                $site->locale = 'sv_SE';
            }
        });
    }

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }

    public function newsletterProducts(): HasMany
    {
        return $this->hasMany(NewsletterProduct::class)->orderBy('sort_order');
    }

    public function integrations(): HasMany
    {
        return $this->hasMany(Integration::class);
    }

    public function aiContextSummary(): string
    {
        $parts = array_filter([
            $this->industry ? "Bransch: {$this->industry}" : null,
            $this->business_description ? "Verksamhet: {$this->business_description}" : null,
            $this->target_audience ? "Målgrupp: {$this->target_audience}" : null,
            $this->brand_voice ? "Ton: {$this->brand_voice}" : null,
        ]);
        return trim(implode(' | ', $parts));
    }
}
