<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'email', 'password', 'first_name', 'last_name', 'user_type'
    ];

    protected $hidden = [
        'password'
    ];

    public function loans() {
        return $this->hasMany(Loan::class);
    }

    public function wishlist() {
        return $this->hasMany(Wishlist::class);
    }

    public function subscription() {
        return $this->hasOne(Subscription::class);
    }
}
