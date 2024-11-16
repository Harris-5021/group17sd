<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'loans';

    protected $fillable = [
        'user_id',
        'media_id',
        'branch_id',
        'borrowed_date',
        'due_date',
        'returned_date',
        'status',
    ];

    // Relationship to Media
    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
