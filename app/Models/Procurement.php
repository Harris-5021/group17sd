<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    use HasFactory;
    protected $table = 'dbo.procurements';
    protected $fillable = [
         'procurement_date', 'procurement_type', 'supplier_name', 'procurement_cost',  'payment_status'
    ];

    // Define the relationship with Media
    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
