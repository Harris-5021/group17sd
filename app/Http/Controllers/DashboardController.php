<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;  
use App\Models\User;
use App\Models\Media;
use App\Models\Procurement;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        

        // Get borrowed items (loans)
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

        // Redirect to the appropriate dashboard based on user role
        switch ($user->role) {
            case 'accountant':
                return view('dashboard.accountant', compact('user', 'borrowedItems', 'wishlistItems'));
            case 'purchase_manager':
                return view('dashboard.purchase_manager', compact('user', 'borrowedItems', 'wishlistItems'));
            case 'branch_manager':
                return view('dashboard.branch_manager', compact('user', 'borrowedItems', 'wishlistItems'));
            case 'librarian':
                return view('dashboard.librarian', compact('user', 'borrowedItems', 'wishlistItems'));
            case 'member':
                return view('dashboard.member', compact('user', 'borrowedItems', 'wishlistItems'));
            //default:
                // Redirect to a generic user dashboard if no role matches
               // return view('dashboard.generic', compact('user', 'borrowedItems', 'wishlistItems'));
        }
    }

    //search user
    public function searchUser(Request $request)
    {
        $query = $request->input('query');
        
        $userAccount = DB::table('users')
            ->select(
                'users.*'
            )
            ->where(function($q) use ($query) {
                $q->where('users.name', 'LIKE', "%$query%")
                  ->orWhere('users.email', 'LIKE', "%$query%");
                })
            ->get();

        return view('searchresultsAccountant', [
            'users' => $userAccount,
            'query' => $query
        ]);
    }

    public function showProcurementForm()
    {
        // Fetch media items to display in the dropdown/select options
        $mediaItems = Media::all();
        return view('procurement.create', compact('mediaItems'));
    }

    public function storeProcurement(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            
            'procurement_date' => 'required|date',
            'procurement_type' => 'required|in:purchase,license,donation',
            'supplier_name' => 'required|string|max:255',
            'procurement_cost' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,overdue',
        ]);

        // Create a new procurement record
        Procurement::create($request->all());

        // Redirect or return a success message
        return redirect()->route('dashboard.purchase_manager')->with('success', 'Procurement record added successfully.');
    }

}