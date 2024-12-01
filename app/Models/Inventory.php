<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Inventory extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'inventory';

    protected $fillable = [
        'branch_id',
        'media_id',
        'quantity',
    ];
   
    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function branch()
    {
    return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

}