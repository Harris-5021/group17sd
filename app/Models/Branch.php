<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'branch_name', 'address', 'city', 'post_code',
        'phone', 'email', 'operating_hours'
    ];

    public function inventory() {
        return $this->hasMany(BranchInventory::class);
    }
}