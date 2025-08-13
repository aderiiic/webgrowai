<?php
// app/Models/Customer.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = ['name','contact_email','status'];

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class)->withPivot('role_in_customer');
    }

    public function sites(): HasMany {
        return $this->hasMany(Site::class);
    }
}
