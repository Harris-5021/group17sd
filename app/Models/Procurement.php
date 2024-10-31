<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    protected $fillable = [
        'media_id', 'branch_id', 'requested_by', 'quantity',
        'status', 'vendor_name', 'estimated_delivery_date',
        'actual_delivery_date', 'cost', 'purchase_order_number'
    ];

    public function media() {
        return $this->belongsTo(Media::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function requestedByUser() {
        return $this->belongsTo(User::class, 'requested_by');
    }
}