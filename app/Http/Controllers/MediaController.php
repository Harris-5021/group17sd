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

   // Show all media
   public function index()
   {
       $media = Media::all();
       return view('media.index', compact('media'));
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
       return view('media.show', compact('media'));
   }

   // Show borrowed media
   public function borrowed()
   {
       $borrowedMedia = DB::table('media')
           ->join('loans', 'media.id', '=', 'loans.media_id')
           ->where('loans.user_id', Auth::id())
           ->where('loans.status', 'active')
           ->select('media.*', 'loans.due_date')
           ->get();
       
       return view('media.borrowed', compact('borrowedMedia'));
   }

   // Borrow media
   public function borrow($id)
   {
       $media = Media::findOrFail($id);
       
       // Check if media is available
       if($media->status !== 'available') {
           return redirect()->back()->with('error', 'This item is not available for borrowing');
       }

       // Create loan record
       DB::table('loans')->insert([
           'user_id' => Auth::id(),
           'media_id' => $id,
           'loan_date' => now(),
           'due_date' => now()->addDays(14), // 2 weeks loan period
           'status' => 'active',
           'created_at' => now()
       ]);

       // Update media status
       $media->status = 'borrowed';
       $media->save();

       return redirect()->back()->with('success', 'Item borrowed successfully');
   }

   // Return media
   public function return($id)
   {
       $loan = DB::table('loans')
           ->where('media_id', $id)
           ->where('user_id', Auth::id())
           ->where('status', 'active')
           ->first();

       if(!$loan) {
           return redirect()->back()->with('error', 'Loan record not found');
       }

       // Update loan record
       DB::table('loans')
           ->where('id', $loan->id)
           ->update([
               'status' => 'returned',
               'return_date' => now(),
               'updated_at' => now()
           ]);

       // Update media status
       Media::where('id', $id)->update([
           'status' => 'available'
       ]);

       return redirect()->back()->with('success', 'Item returned successfully');
   }

   // Add to wishlist
   public function addToWishlist($id)
   {
       // Check if already in wishlist
       $exists = DB::table('wishlists')
           ->where('user_id', Auth::id())
           ->where('media_id', $id)
           ->exists();

       if($exists) {
           return redirect()->back()->with('error', 'Item already in wishlist');
       }

       // Add to wishlist
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