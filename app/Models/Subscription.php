<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['plan_type', 'amount', 'fee_paid', 'start_date', 'end_date', 'next_billing_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}   
