<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'plan_type', 'start_date', 'end_date',
        'amount', 'payment_status', 'last_payment_date', 'next_payment_date'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}