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
        'notification_preferences'
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

}