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
                // Pass procurement form data for the purchase manager
                $mediaItems = Media::all();
                return view('dashboard.purchase_manager', compact('user', 'borrowedItems', 'wishlistItems', 'mediaItems'));
            case 'branch_manager':
                return view('dashboard.branch_manager', compact('user', 'borrowedItems', 'wishlistItems'));
            case 'librarian':
                return view('dashboard.librarian', compact('user', 'borrowedItems', 'wishlistItems'));
            case 'member':
                return view('dashboard.member', compact('user', 'borrowedItems', 'wishlistItems'));
            default:
                // Redirect to a generic dashboard if no role matches
                return view('dashboard.generic', compact('user', 'borrowedItems', 'wishlistItems'));
        }
    }

    public function searchUser(Request $request)
    {
        $query = $request->input('query');

        $userAccount = DB::table('users')
            ->select('users.*')
            ->where(function ($q) use ($query) {
                $q->where('users.name', 'LIKE', "%$query%")
                    ->orWhere('users.email', 'LIKE', "%$query%");
            })
            ->get();

        return view('searchresultsAccountant', [
            'users' => $userAccount,
            'query' => $query,
        ]);
    }

    public function showProcurementForm()
    {
        // Fetch media items for the procurement dropdown
        $mediaItems = Media::all();
    
        // Fetch the authenticated user
        $user = Auth::user();
    
        // Fetch wishlist items for the user
        $wishlistItems = DB::table('wishlists')
            ->join('media', 'wishlists.media_id', '=', 'media.id')
            ->where('wishlists.user_id', $user->id)
            ->select('media.title', 'media.author')
            ->get();
    
        // Pass variables to the view
        return view('dashboard.purchase_manager', compact('mediaItems', 'user', 'wishlistItems'));
    }
    
    

    public function storeProcurement(Request $request)
{
    // Validate the procurement form data
    $request->validate([
        'media_type' => 'required|in:Book,DVD,Magazine,E-Book,Audio',
        'title' => 'required|string|max:255', // Title for the media item
        'author' => 'nullable|string|max:255',
        'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
        'procurement_date' => 'required|date',
        'procurement_type' => 'required|in:purchase,license,donation',
        'supplier_name' => 'required|string|max:255',
        'procurement_cost' => 'nullable|numeric|min:0',
        'payment_status' => 'required|in:pending,paid,overdue',
    ]);

    // Step 1: Insert into the media table
    $media = Media::create([
        'title' => $request->input('title'),
        'author' => $request->input('author'),
        'type' => $request->input('media_type'),
        'publication_year' => $request->input('publication_year'),
        'status' => 'available', // Default status for new media
    ]);

    // Step 2: Insert into the procurements table using the new media_id
    Procurement::create([
        'media_id' => $media->id, // Use the ID of the newly created media
        'procurement_date' => $request->input('procurement_date'),
        'procurement_type' => $request->input('procurement_type'),
        'supplier_name' => $request->input('supplier_name'),
        'procurement_cost' => $request->input('procurement_cost'),
        'payment_status' => $request->input('payment_status'),
    ]);

    // Redirect back with a success message
    return redirect()->route('purchase_manager.view')->with('success', 'Procurement record added successfully.');
}


}
