<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;  
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get borrowed books
        $borrowedItems = DB::table('loans')
            ->join('media', 'loans.media_id', '=', 'media.id')
            ->where('loans.user_id', Auth::id())
            ->where('loans.status', 'active')
            ->select('media.title', 'media.author', 'loans.borrowed_date', 'loans.due_date')
            ->get();

        // Get wishlist items
        $wishlistItems = DB::table('wishlists')
            ->join('media', 'wishlists.media_id', '=', 'media.id')
            ->where('wishlists.user_id', Auth::id())
            ->select('media.title', 'media.author')
            ->get();

        return view('dashboard', compact('user', 'borrowedItems', 'wishlistItems'));
    }
}