<?php
// app/Models/Vendor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'contact'
    ];

    // Define the relationship with Media (if necessary)
    public function media()
    {
        return $this->hasMany(Media::class);  // Assuming Media has a vendor_id column
    }
}