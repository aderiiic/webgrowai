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

    public function features(): HasMany
    {
        return $this->hasMany(PlanFeature::class);
    }
}
