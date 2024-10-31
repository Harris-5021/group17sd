<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id', 'media_id', 'branch_id', 'borrow_date',
        'due_date', 'return_date', 'status', 'fine_amount'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function media() {
        return $this->belongsTo(Media::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}