<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Browse/show all available media
    public function browse()
    {
        $media = DB::table('media')
            ->where('status', 'available')
            ->paginate(12);
            
        return view('browse', compact('media'));
    }

    // Handle search
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $media = DB::table('media')
            ->where('title', 'LIKE', "%$query%")
            ->orWhere('author', 'LIKE', "%$query%")
            ->orWhere('description', 'LIKE', "%$query%")
            ->get();

        return view('searchresults', [
            'media' => $media,
            'query' => $query
        ]);
    }

    // Show single media details
    public function show($id)
    {
        $media = Media::findOrFail($id);
        return view('show', compact('media'));
    }

    // Show borrowed media
    public function borrowed()
    {
        $activeLoans = DB::table('loans')
            ->join('media', 'loans.media_id', '=', 'media.id')
            ->where('loans.user_id', Auth::id())
            ->where('loans.status', 'active')
            ->select('media.*', 'loans.borrowed_date', 'loans.due_date', 'loans.id as loan_id')
            ->get();
        
        return view('borrowed', compact('activeLoans'));
    }

    // Show wishlist
    public function wishlist()
    {
        $wishlistItems = DB::table('wishlists')
            ->join('media', 'wishlists.media_id', '=', 'media.id')
            ->where('wishlists.user_id', Auth::id())
            ->select('media.*')
            ->get();
            
        return view('wishlist', compact('wishlistItems'));
    }

    // Borrow media
    public function borrow($id, Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id'
        ]);

        $media = Media::findOrFail($id);
        
        if($media->status !== 'available') {
            return redirect()->back()->with('error', 'This item is not available for borrowing');
        }

        DB::table('loans')->insert([
            'user_id' => Auth::id(),
            'media_id' => $id,
            'branch_id' => $request->branch_id,
            'borrowed_date' => now(),
            'due_date' => now()->addDays(14),
            'returned_date' => null,
            'status' => 'active'
        ]);

        $media->status = 'borrowed';
        $media->save();

        return redirect()->back()->with('success', 'Item borrowed successfully');
    }

    // Return media
    public function return($id)
    {
        // First find the loan
        $loan = DB::table('loans')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();
    
        if(!$loan) {
            return redirect()->back()->with('error', 'Loan record not found');
        }
    
        // Update loan status
        DB::table('loans')
            ->where('id', $id)
            ->update([
                'status' => 'returned',
                'returned_date' => now()
            ]);
    
        // Update media availability status
        DB::table('media')
            ->where('id', $loan->media_id)
            ->update([
                'status' => 'available'
            ]);
    
        return redirect()->back()->with('success', 'Item returned successfully');
    }

    // Add to wishlist
    public function addToWishlist($id)
    {
        $exists = DB::table('wishlists')
            ->where('user_id', Auth::id())
            ->where('media_id', $id)
            ->exists();

        if($exists) {
            return redirect()->back()->with('error', 'Item already in wishlist');
        }

        DB::table('wishlists')->insert([
            'user_id' => Auth::id(),
            'media_id' => $id,
            'created_at' => now()
        ]);

        return redirect()->back()->with('success', 'Added to wishlist');
    }

    // Remove from wishlist
    public function removeFromWishlist($id)
    {
        DB::table('wishlists')
            ->where('user_id', Auth::id())
            ->where('media_id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Removed from wishlist');
    }
}