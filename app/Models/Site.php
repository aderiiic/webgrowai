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
        'weekly_brand_voice',
        'weekly_audience',
        'weekly_goal',
        'weekly_keywords',   // json
        'weekly_recipients',
    ];

    protected $casts = [
        'ai_prefs' => 'array',
        'weekly_keywords' => 'array',
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

    public function effectiveBrandVoice(): ?string
    {
        return $this->weekly_brand_voice ?: ($this->customer?->weekly_brand_voice ?? null);
    }

    public function effectiveAudience(): ?string
    {
        return $this->weekly_audience ?: ($this->customer?->weekly_audience ?? null);
    }

    public function effectiveGoal(): ?string
    {
        return $this->weekly_goal ?: ($this->customer?->weekly_goal ?? null);
    }

    public function effectiveKeywords(): array
    {
        if (!empty($this->weekly_keywords) && is_array($this->weekly_keywords)) {
            return $this->weekly_keywords;
        }
        $cust = $this->customer?->weekly_keywords;
        return $cust ? (array) json_decode($cust, true) : [];
    }

    public function effectiveRecipients(): array
    {
        $raw = $this->weekly_recipients ?: ($this->customer?->weekly_recipients ?? '');
        $emails = preg_split('/\s*,\s*/', (string) $raw, -1, PREG_SPLIT_NO_EMPTY);
        return collect($emails)
            ->filter(fn ($e) => filter_var($e, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();
    }
}
