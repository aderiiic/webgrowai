<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'price_monthly',
        'price_yearly',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'price_monthly' => 'integer', // öre
        'price_yearly'  => 'integer', // öre
    ];

    public function features(): HasMany
    {
        return $this->hasMany(PlanFeature::class);
    }

    // Hjälpmetoder

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    /**
     * Hämta kvot/limit för ett feature-key (t.ex. ai.generate).
     * Returnerar null om feature inte finns eller inte är aktiverat.
     */
    public function quota(string $key): ?int
    {
        $feat = $this->features()
            ->where('key', $key)
            ->where('is_enabled', true)
            ->first();

        if (!$feat) {
            return null;
        }

        $val = $feat->limit_value;
        return is_numeric($val) ? (int) $val : null;
    }

    /**
     * Priser i öre (integer). Helpers om du vill formattera i UI:
     */
    public function priceMonthlyKr(): float
    {
        return ($this->price_monthly ?? 0) / 100;
    }

    public function priceYearlyKr(): float
    {
        return ($this->price_yearly ?? 0) / 100;
    }
}
