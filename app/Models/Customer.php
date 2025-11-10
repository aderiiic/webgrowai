<?php
// app/Models/Customer.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'contact_email',
        'status',
        'weekly_recipients',
        'weekly_brand_voice',
        'weekly_audience',
        'weekly_goal',
        'weekly_keywords',
        'mailchimp_api_key',
        'mailchimp_audience_id',
        'mailchimp_from_name',
        'mailchimp_reply_to',
        'company_name',
        'org_nr',
        'vat_nr',
        'contact_name',
        'contact_phone',
        'billing_email',
        'billing_address',
        'billing_zip',
        'billing_city',
        'billing_country',
    ];

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class)->withPivot('role_in_customer');
    }

    public function sites(): HasMany {
        return $this->hasMany(Site::class);
    }

    public function newsletterProducts(): HasManyThrough
    {
        return $this->hasManyThrough(
            NewsletterProduct::class,
            Site::class,
            'customer_id',
            'site_id',
            'id',
            'id'
        )->orderBy('newsletter_products.sort_order');
    }

    public function subscription(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }
}
