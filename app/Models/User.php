<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable; // lägg till
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

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
