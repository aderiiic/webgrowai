<?php
// app/Models/Site.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Site extends Model
{
    protected $fillable = ['customer_id','name','url','status','public_key','secret'];

    protected static function booted(): void
    {
        static::creating(function (Site $site) {
            if (empty($site->public_key)) {
                // Unik, URL-säker nyckel
                $site->public_key = 'site_' . Str::random(32);
            }
            if (empty($site->secret_key)) {
                // Hemlig nyckel om ni använder det
                $site->secret = 'sec_' . Str::random(48);
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
}
