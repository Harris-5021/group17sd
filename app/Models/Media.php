<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'title', 'author', 'isbn', 'media_type', 'description',
        'publisher', 'publication_year', 'status'
    ];

    public function branchInventories() {
        return $this->hasMany(BranchInventory::class);
    }

    public function loans() {
        return $this->hasMany(Loan::class);
    }

    public function wishlists() {
        return $this->hasMany(Wishlist::class);
    }
}
