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
                    $notifications = DB::table('notifications')
                        ->where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();
                                         
                    return view('dashboard.purchase_manager', compact('user', 'borrowedItems', 'wishlistItems', 'mediaItems', 'notifications'));
                case 'branch_manager':
                    // Get notifications for the branch manager
                    $notifications = DB::table('notifications')
                        ->where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();
                
                    return view('dashboard.branch_manager', compact('user', 'borrowedItems', 'wishlistItems', 'notifications'));
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
        $notifications = DB::table('notifications')
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();
    
        // Pass variables to the view
        return view('dashboard.purchase_manager', compact('mediaItems', 'user', 'wishlistItems', 'notifications'));
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
        'branch_location' => 'required|string|max:255',
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
        'branch_location' => $request->input('branch_location'),
    ]);
    

    // Redirect back with a success message
    return redirect()->route('purchase_manager.view')->with('success', 'Procurement record added successfully.');
}
public function viewProcurements()
{
    // Fetch all procurements from the database
    $procurements = Procurement::with('media')->get(); // Including media details

    return view('viewProcurements', compact('procurements',));
}

public function toggleNotification($id)
{
    $notification = DB::table('notifications')
        ->where('id', $id)
        ->where('user_id', Auth::id())
        ->first();

    if (!$notification) {
        return back()->with('error', 'Notification not found.');
    }

    $newStatus = $notification->status === 'read' ? 'unread' : 'read';

    DB::table('notifications')
        ->where('id', $id)
        ->update([
            'status' => $newStatus,
            'read_at' => $newStatus === 'read' ? now() : null
        ]);

    return back()->with('success', 'Notification updated.');
}
public function forwardToPurchaseManager($id, Request $request)
{
    $request->validate([
        'media_type' => 'required|in:Book,DVD,Magazine,E-Book,Audio',
        'quantity' => 'required|integer|min:1',
        'supplier_name' => 'nullable|string',
        'additional_notes' => 'nullable|string',
        'estimated_cost' => 'nullable|numeric|min:0'
    ]);

    $notification = DB::table('notifications')->where('id', $id)->first();
    $branch = DB::table('branches')->where('manager_id', Auth::id())->first();

    if ($branch && $branch->purchase_manager_id) {
        $messageData = [
            'media_type' => $request->media_type,
            'quantity' => $request->quantity,
            'supplier_name' => $request->supplier_name,
            'estimated_cost' => $request->estimated_cost,
            'additional_notes' => $request->additional_notes,
            'original_request' => $notification->message,
            'branch_name' => $branch->name
        ];

        DB::table('notifications')->insert([
            'user_id' => $branch->purchase_manager_id,
            'type' => 'procurement',
            'title' => 'Procurement Request',
            'message' => json_encode($messageData),
            'status' => 'unread',
            'created_at' => now()
        ]);

        return back()->with('success', 'Request forwarded to purchase manager');
    }

    return back()->with('error', 'No purchase manager assigned to this branch');
}

public function showNotification($id)
{
    $notification = DB::table('notifications')
        ->where('id', $id)
        ->where('user_id', Auth::id())
        ->first();

    if (!$notification) {
        return redirect()->back()->with('error', 'Notification not found.');
    }

    $message = $notification->message;
    $details = [];

    // Check if the message is JSON or a plain string
    if ($this->isJson($message)) {
        $details = json_decode($message, true);
    } else {
        // Parse legacy message format
        preg_match('/Type: (.*?) Quantity:/', $message, $type);
        preg_match('/Quantity: (.*?) Supplier:/', $message, $quantity);
        preg_match('/Supplier: (.*?) Est\. Cost:/', $message, $supplier);
        preg_match('/Est\. Cost: £(.*?) Notes:/', $message, $cost);
        preg_match('/Notes: (.*?)$/', $message, $notes);

        $details = [
            'media_type' => trim($type[1] ?? 'N/A'),
            'quantity' => trim($quantity[1] ?? 'N/A'),
            'supplier_name' => trim($supplier[1] ?? 'N/A'),
            'estimated_cost' => trim($cost[1] ?? 'N/A'),
            'additional_notes' => trim($notes[1] ?? 'N/A')
        ];
    }

    return view('notification.notification-details', compact('notification', 'details'));
}

private function isJson($string)
{
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}

public function acceptRequest($id)
{
    $originalNotification = DB::table('notifications')->find($id);
    $branchManager = DB::table('users')
        ->join('branches', 'users.id', '=', 'branches.manager_id')
        ->where('branches.purchase_manager_id', Auth::id())
        ->first();

    $purchaseManager = DB::table('users')
        ->where('id', Auth::id())
        ->first();

    // Create notification for branch manager
    DB::table('notifications')->insert([
        'user_id' => $branchManager->id,
        'type' => 'procurement',
        'title' => 'Procurement Request Accepted',
        'message' => "Your procurement request has been accepted and will be processed. For follow-up, contact: {$purchaseManager->email}",
        'status' => 'unread',
        'created_at' => now()
    ]);

    return redirect()->route('dashboard.purchase_manager')->with('success', 'Response sent to branch manager');
}

public function rejectRequest($id)
{
    $originalNotification = DB::table('notifications')->find($id);
    $branchManager = DB::table('users')
        ->join('branches', 'users.id', '=', 'branches.manager_id')
        ->where('branches.purchase_manager_id', Auth::id())
        ->first();

    $purchaseManager = DB::table('users')
        ->where('id', Auth::id())
        ->first();

    // Create notification for branch manager
    DB::table('notifications')->insert([
        'user_id' => $branchManager->id,
        'type' => 'procurement',
        'title' => 'Procurement Request Rejected',
        'message' => "Your procurement request could not be processed. Please email {$purchaseManager->email} to discuss further.",
        'status' => 'unread',
        'created_at' => now()
    ]);

    return redirect()->route('dashboard.purchase_manager')->with('success', 'Response sent to branch manager');
}
}
