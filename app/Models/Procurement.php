<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    use HasFactory;

    protected $table = 'procurements';
    protected $primaryKey = 'procurement_id';

    protected $fillable = [
        'media_id', // Reference to the media table
        'procurement_date',
        'procurement_type',
        'supplier_name',
        'procurement_cost',
        'payment_status',
        'branch_location',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}


