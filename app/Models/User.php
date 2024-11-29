<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'last_login',
        'notification_preferences',
        'branch_id'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'notification_preferences' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_login' => 'datetime'
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class, 'user_id');
    }

    public function branch()
{
    return $this->belongsTo(Branch::class);
}

public function subscription()
{
    return $this->hasOne(Subscription::class);
}
}