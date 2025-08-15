<?php
// app/Models/Site.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Site extends Model
{
    protected $fillable = ['customer_id','name','url','status','public_key','secret'];

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }
}
