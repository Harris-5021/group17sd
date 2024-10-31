<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BranchInventory extends Model
{
    protected $fillable = [
        'branch_id', 'media_id', 'quantity', 'location'
    ];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function media() {
        return $this->belongsTo(Media::class);
    }
}