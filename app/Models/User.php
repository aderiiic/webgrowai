<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'role', 'onboarding_step',
    ];

    protected $hidden = ['password','remember_token'];

    public function customers(): BelongsToMany {
        return $this->belongsToMany(Customer::class)->withPivot('role_in_customer');
    }

    public function isAdmin(): bool {
        return $this->role === 'admin';
    }
}
