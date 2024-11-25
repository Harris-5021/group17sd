<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'address', 
        'contact_number',
        'email',
        'opening_hours',
        'half_day',
        'manager_id',
        'purchase_manager_id'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}