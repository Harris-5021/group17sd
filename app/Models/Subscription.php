<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public $timestamps = false;

    protected $fillable = ['plan_type', 'amount', 'fee_paid', 'start_date', 'end_date', 'next_billing_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}   
