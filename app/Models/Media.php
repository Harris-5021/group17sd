<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'title',
        'author',
        'type',
        'publication_year',
        'status',
        'description',
        'damage_notes',
        'replacement_cost',
        'vendor_id',
        'procurement_cost',
    ];
    const UPDATED_AT = null;

}
