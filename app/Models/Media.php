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
        'publisher',
        'description',
        'status',
        'damaged_notes',
        'replacement_cost'
    ];

    // Since your table has created_at but no updated_at
    const UPDATED_AT = null;
}
