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
        'damaged_notes',
        'replacement_cost',
    ];
    const UPDATED_AT = null;

}
