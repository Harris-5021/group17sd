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

    public function browse()
    {
        $media = DB::table('media')
            ->join('inventory', 'media.id', '=', 'inventory.media_id')
            ->join('branches', 'inventory.branch_id', '=', 'branches.id')
            ->select(
                'media.*', 
                'inventory.quantity',
                'inventory.branch_id',
                'branches.name as branch_name'
            )
            ->where('inventory.quantity', '>', 0)
            ->paginate(12);
            
        return view('browse', compact('media'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $media = DB::table('media')
            ->join('inventory', 'media.id', '=', 'inventory.media_id')
            ->join('branches', 'inventory.branch_id', '=', 'branches.id')
            ->select(
                'media.*', 
                'inventory.quantity',
                'inventory.branch_id',
                'branches.name as branch_name'
            )
            ->where(function($q) use ($query) {
                $q->where('media.title', 'LIKE', "%$query%")
                  ->orWhere('media.author', 'LIKE', "%$query%")
                  ->orWhere('media.description', 'LIKE', "%$query%");
            })
            ->where('inventory.quantity', '>', 0)
            ->get();

        return view('searchresults', [
            'media' => $media,
            'query' => $query
        ]);
    }

    public function show($id)
    {
        $media = DB::table('media')
            ->join('inventory', 'media.id', '=', 'inventory.media_id')
            ->join('branches', 'inventory.branch_id', '=', 'branches.id')
            ->select(
                'media.*', 
                'inventory.quantity',
                'inventory.branch_id',
                'branches.name as branch_name'
            )
            ->where('media.id', $id)
            ->first();

        if (!$media) {
            abort(404);
        }

        return view('show', compact('media'));
    }

    public function borrowed()
    {
        $activeLoans = DB::table('loans')
            ->join('media', 'loans.media_id', '=', 'media.id')
            ->join('branches', 'loans.branch_id', '=', 'branches.id')
            ->where('loans.user_id', Auth::id())
            ->where('loans.status', 'active')
            ->select(
                'media.*', 
                'loans.borrowed_date', 
                'loans.due_date', 
                'loans.id as loan_id',
                'branches.name as branch_name'
            )
            ->get();
        
        return view('borrowed', compact('activeLoans'));
    }

    public function wishlist()
    {
        $wishlistItems = DB::table('wishlists')
            ->join('media', 'wishlists.media_id', '=', 'media.id')
            ->leftJoin('inventory', 'media.id', '=', 'inventory.media_id')
            ->leftJoin('branches', 'inventory.branch_id', '=', 'branches.id')
            ->where('wishlists.user_id', Auth::id())
            ->select(
                'media.*',
                'inventory.quantity',
                'inventory.branch_id',
                'branches.name as branch_name'
            )
            ->get();
            
        return view('wishlist', compact('wishlistItems'));
    }

    public function borrow($id, Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id'
        ]);

        // Check inventory first
        $inventory = DB::table('inventory')
            ->where('media_id', $id)
            ->where('branch_id', $request->branch_id)
            ->where('quantity', '>', 0)
            ->first();

        if(!$inventory) {
            return redirect()->back()->with('error', 'This item is not available at the selected branch');
        }

        // Check if user already has an active loan for this item
        $existingLoan = DB::table('loans')
            ->where('user_id', Auth::id())
            ->where('media_id', $id)
            ->where('status', 'active')
            ->first();

        if($existingLoan) {
            return redirect()->back()->with('error', 'You already have this item borrowed');
        }

        // Start a transaction
        DB::beginTransaction();

        try {
            // Decrease quantity in inventory
            DB::table('inventory')
                ->where('media_id', $id)
                ->where('branch_id', $request->branch_id)
                ->decrement('quantity');

            // Create loan record
            DB::table('loans')->insert([
                'user_id' => Auth::id(),
                'media_id' => $id,
                'branch_id' => $request->branch_id,
                'borrowed_date' => now(),
                'due_date' => now()->addDays(14),
                'returned_date' => null,
                'status' => 'active'
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Item borrowed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to borrow item');
        }
    }

    public function return($id)
    {
        $loan = DB::table('loans')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();
    
        if(!$loan) {
            return redirect()->back()->with('error', 'Loan record not found');
        }
    
        DB::beginTransaction();

        try {
            // Update loan status
            DB::table('loans')
                ->where('id', $id)
                ->update([
                    'status' => 'returned',
                    'returned_date' => now()
                ]);
    
            // Increment inventory quantity
            DB::table('inventory')
                ->where('media_id', $loan->media_id)
                ->where('branch_id', $loan->branch_id)
                ->increment('quantity');

            DB::commit();
            return redirect()->back()->with('success', 'Item returned successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to return item');
        }
    }

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

    public function removeFromWishlist($id)
    {
        DB::table('wishlists')
            ->where('user_id', Auth::id())
            ->where('media_id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Removed from wishlist');
    }
}