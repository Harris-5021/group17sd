<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Media;
use App\Models\Procurement;
use App\Models\Inventory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class DashboardController extends Controller
{
    use HasFactory;
    public function index()
{
   $user = Auth::user();

   // Get user's branch name
   $userBranch = DB::table('branches')
       ->where('id', $user->branch_id)
       ->first();

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

   // Get all branches for the dropdown
   $branches = DB::table('branches')->get();

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
            // Query for pending returns
            $pendingReturns = DB::table('loans')
                ->join('users', 'loans.user_id', '=', 'users.id')
                ->join('media', 'loans.media_id', '=', 'media.id')
                ->where('loans.status', '=', 'active')
                ->select(
                    'loans.id',
                    'loans.media_id',
                    'loans.due_date',
                    'media.title',
                    'users.name as user_name'
                )
                ->orderBy('loans.borrowed_date', 'desc')
                ->take(10)
                ->get();
        
            // Query for recent fines
            $recentFines = DB::table('fines')
                ->join('users', 'fines.user_id', '=', 'users.id')
                ->select(
                    'fines.loan_id',
                    'fines.amount',
                    'fines.reason',
                    'fines.status',
                    'users.name as user_name'
                )
                ->orderBy('fines.created_at', 'desc')
                ->take(5) // You can adjust the limit
                ->get();
        
            // Query for damaged items
            $damagedItems = DB::table('media')
                ->where('status', 'damaged')
                ->select(
                    'id as media_id',
                    'title',
                    'damage_notes',
                    'updated_at as reported_date',
                    'status'
                )
                ->get();
        
            // Query for transfer requests
            $transferRequests = DB::table('transfer_requests')
                ->join('media', 'transfer_requests.media_id', '=', 'media.id')
                ->join('branches as from_branch', 'transfer_requests.from_branch_id', '=', 'from_branch.id')
                ->join('branches as to_branch', 'transfer_requests.to_branch_id', '=', 'to_branch.id')
                ->select(
                    'transfer_requests.id',
                    'transfer_requests.status',
                    'transfer_requests.created_at',
                    'media.title as media_title',
                    'from_branch.name as from_branch_name',
                    'to_branch.name as to_branch_name'
                )
                ->where(function ($query) use ($user) {
                    // Show transfers where the librarian's branch is either sender or receiver
                    $query->where('from_branch_id', $user->branch_id)
                          ->orWhere('to_branch_id', $user->branch_id);
                })
                ->orderBy('transfer_requests.created_at', 'desc')
                ->take(10)
                ->get();
        
            return view('dashboard.librarian', compact(
                'user',
                'borrowedItems',
                'wishlistItems',
                'pendingReturns',
                'recentFines',
                'damagedItems',
                'transferRequests'
            ));
        
           
       case 'member':
           return view('dashboard.member', compact('user', 'borrowedItems', 'wishlistItems', 'userBranch', 'branches'));
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
    //dd($request->all());

    $branch = DB::table('branches')
        ->where('name', $request->input('branch_location'))  
        ->first();

    // Validate the procurement form data
    $request->validate([
        'media_type' => 'required|in:Book,DVD,Magazine,E-Book,Audio',
        'title' => 'required|string|max:255', 
        'author' => 'nullable|string|max:255',
        'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
        'procurement_date' => 'required|date',
        'procurement_type' => 'required|in:purchase,license,donation',
        'supplier_name' => 'required|string|max:255',
        'procurement_cost' => 'required|numeric|min:0',
        'payment_status' => 'required|in:pending,paid,overdue',
        'branch_location' => 'required|string|max:255',
        'quantity' => 'required|integer|min:1',
    ]);

    try {
        DB::beginTransaction();

        // Check if the supplier name exists in the vendors table
        $vendor = DB::table('vendors')
            ->where('name', $request->input('supplier_name'))
            ->first();

        // If no vendor is found, vendor_id will be set to null
        $vendorId = $vendor ? $vendor->id : null;

        // Check if media already exists
        $existingMedia = DB::table('media')
            ->where('title', $request->input('title'))
            ->where('author', $request->input('author'))
            ->where('type', $request->input('media_type'))
            ->first();

        if ($existingMedia) {
            $mediaId = $existingMedia->id;

            // Update vendor_id to null if no vendor is found
            DB::table('media')
                ->where('id', $mediaId)
                ->update(['vendor_id' => $vendorId]);

            // Check if inventory record exists for this branch
            $existingInventory = DB::table('inventory')
                ->where('media_id', $mediaId)
                ->where('branch_id', $branch->id)
                ->first();

            if ($existingInventory) {
                // Update existing inventory
                DB::table('inventory')
                    ->where('media_id', $mediaId)
                    ->where('branch_id', $branch->id)
                    ->increment('quantity', $request->input('quantity'));
            } else {
                // Create new inventory record for this branch
                Inventory::create([
                    'media_id' => $mediaId,
                    'branch_id' => $branch->id,
                    'quantity' => $request->input('quantity'),
                ]);
            }
        } else {
            // Create new media record if it doesn't exist
            $media = Media::create([
                'title' => $request->input('title'),
                'author' => $request->input('author'),
                'type' => $request->input('media_type'),
                'publication_year' => $request->input('publication_year'),
                'status' => 'available',
                'vendor_id' => $vendorId, // Set vendor_id to null or valid vendor_id
                'procurement_cost' => $request->input('procurement_cost'),
            ]);

            $mediaId = $media->id;

            // Create new inventory record
            Inventory::create([
                'media_id' => $mediaId,
                'branch_id' => $branch->id,
                'quantity' => $request->input('quantity'),
            ]);
        }

        // Create procurement record regardless
        Procurement::create([
            'media_id' => $mediaId,
            'procurement_date' => $request->input('procurement_date'),
            'procurement_type' => $request->input('procurement_type'),
            'supplier_name' => $request->input('supplier_name'),
            'procurement_cost' => $request->input('procurement_cost'),
            'payment_status' => $request->input('payment_status'),
            'branch_location' => $request->input('branch_location'),
            'vendor_id' => $vendorId, // Set vendor_id to null or valid vendor_id
        ]);

        // Check for wishlist users and send notifications
        $wishlistUsers = DB::table('wishlists')
            ->join('users', 'wishlists.user_id', '=', 'users.id')
            ->where('wishlists.media_id', $mediaId)
            ->where('notification_preferences', 'enabled')
            ->get();

        foreach($wishlistUsers as $wishlistUser) {
            Mail::raw("Good news! '{$request->input('title')}' is now available at {$branch->name}.", function ($message) use ($wishlistUser) {
                $message->to($wishlistUser->email)
                    ->subject('Wishlist Item Now Available');
            });
        }

        DB::commit();
        return redirect()->route('purchase_manager.view')->with('success', 'Procurement record added successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to add procurement record: ' . $e->getMessage());
    }
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


public function viewProcessedReturns()
{
    $processedReturns = DB::table('loans')
        ->join('users', 'loans.user_id', '=', 'users.id')
        ->join('media', 'loans.media_id', '=', 'media.id')
        ->whereIn('loans.status', ['returned', 'damaged'])
        ->select(
            'loans.id',
            'loans.media_id',
            'loans.returned_date',
            'loans.status',
            'loans.damaged_notes',
            'media.title',
            'users.name as user_name'
        )
        ->orderBy('loans.returned_date', 'desc')
        ->paginate(15);

    return view('processed-returns', compact('processedReturns'));
}

public function searchReturns(Request $request)
{
    $query = $request->input('query');
    
    $processedReturns = DB::table('loans')
        ->join('users', 'loans.user_id', '=', 'users.id')
        ->join('media', 'loans.media_id', '=', 'media.id')
        ->where(function($q) use ($query) {
            $q->where('media.title', 'LIKE', "%{$query}%")
              ->orWhere('users.name', 'LIKE', "%{$query}%")
              ->orWhere('media.id', 'LIKE', "%{$query}%")
              ->orWhere('users.id', 'LIKE', "%{$query}%");
        })
        ->select(
            'loans.id',
            'media.title',
            'users.name as user_name',
            'loans.returned_date',
            'loans.status',
            'loans.damaged_notes'
        )
        ->orderBy('loans.returned_date', 'desc')
        ->paginate(15);

    return view('processed-returns', compact('processedReturns'));
}

public function viewFines()
{
    // Fetch fines with additional details
    $fines = DB::table('fines')
        ->join('loans', 'fines.loan_id', '=', 'loans.id')
        ->join('users', 'fines.user_id', '=', 'users.id')
        ->join('media', 'loans.media_id', '=', 'media.id')
        ->select(
            'fines.*',
            'media.title',
            'users.name as user_name'
        )
        ->orderBy('fines.created_at', 'desc')
        ->paginate(15);

    // Define recent fines for the view (adjust query if necessary)
    $recentFines = DB::table('fines')
        ->join('users', 'fines.user_id', '=', 'users.id')
        ->select('fines.amount', 'fines.created_at', 'users.name as user_name')
        ->orderBy('fines.created_at', 'desc')
        ->limit(5)
        ->get();

    return view('fines', compact('fines', 'recentFines'));
}


public function processReturn(Request $request, $id)
{
    $request->validate([
        'damage_notes' => 'nullable|string|max:1000',
        'fine_amount' => 'nullable|numeric|min:0',
        'status' => 'required|in:returned,damaged'
    ]);

    try {
        DB::beginTransaction();

        // Use the `$id` passed through the route
        $loan = DB::table('loans')->where('id', $id)->first();
        
        if (!$loan) {
            throw new \Exception('Loan not found.');
        }

        // Update loan status
        DB::table('loans')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'returned_date' => now(),
                'damaged_notes' => $request->status === 'damaged' ? $request->damage_notes : null
            ]);

        // If there's a fine amount, create a fine record
        if ($request->fine_amount > 0) {
            DB::table('fines')->insert([
                'loan_id' => $loan->id,
                'user_id' => $loan->user_id,
                'amount' => $request->fine_amount,
                'reason' => $request->status === 'damaged' ? 'damage' : 'overdue',
                'status' => 'pending',
                'due_date' => now()->addDays(30),
                'created_at' => now()
            ]);
        }

        // Update media inventory
        DB::table('inventory')
            ->where('media_id', $loan->media_id)
            ->where('branch_id', $loan->branch_id)
            ->increment('quantity');

        // If damaged, update media status
        if ($request->status === 'damaged') {
            DB::table('media')
                ->where('id', $loan->media_id)
                ->update(['status' => 'damaged']);
        }

        DB::commit();
        return redirect()->back()->with('success', 'Return processed successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to process return: ' . $e->getMessage());
    }
}

public function librarianDashboard()
{
    $user = Auth::user();

    // Fetch pending returns
    $pendingReturns = DB::table('loans')
        ->join('users', 'loans.user_id', '=', 'users.id')
        ->join('media', 'loans.media_id', '=', 'media.id')
        ->where('loans.status', '=', 'active')
        ->select(
            'loans.id',
            'loans.media_id',
            'loans.due_date',
            'media.title',
            'users.name as user_name'
        )
        ->orderBy('loans.borrowed_date', 'desc')
        ->take(10)
        ->get();

    // Fetch recent fines
    $recentFines = DB::table('fines')
        ->join('users', 'fines.user_id', '=', 'users.id')
        ->select(
            'fines.loan_id',
            'fines.amount',
            'fines.reason',
            'fines.status',
            'users.name as user_name'
        )
        ->orderBy('fines.created_at', 'desc')
        ->take(10)
        ->get();

    // Fetch damaged items
    $damagedItems = DB::table('media')
        ->select(
            'id as media_id',
            'title',
            'damage_notes',
            'updated_at as reported_date',
            'status'
        )
        ->where('status', '=', 'damaged')
        ->get();

    // Fetch transfer requests
    $transferRequests = DB::table('transfer_requests')
        ->join('media', 'transfer_requests.media_id', '=', 'media.id')
        ->join('branches as from_branch', 'transfer_requests.from_branch_id', '=', 'from_branch.id')
        ->join('branches as to_branch', 'transfer_requests.to_branch_id', '=', 'to_branch.id')
        ->select(
            'transfer_requests.id',
            'transfer_requests.status',
            'transfer_requests.created_at',
            'media.title as media_title',
            'from_branch.name as from_branch_name',
            'to_branch.name as to_branch_name'
        )
        ->where(function ($query) use ($user) {
            $query->where('from_branch_id', $user->branch_id)
                  ->orWhere('to_branch_id', $user->branch_id);
        })
        ->orderBy('transfer_requests.created_at', 'desc')
        ->take(10)
        ->get();

        dd($transferRequests); // Add this line

    return view('dashboard.librarian', compact(
        'user',
        'pendingReturns',
        'recentFines',
        'damagedItems',
        'transferRequests'
    ));
}



public function googleLineChart()
{
    $totalEarnings = DB::table('subscriptions')
        ->join('users', 'subscriptions.user_id', '=', 'users.id')
        ->join('branches', 'users.branch_id', '=', 'branches.id')
        ->where('subscriptions.status', 'Active')
        ->select('users.branch_id', 'branches.name AS branch_name', DB::raw('SUM(subscriptions.amount) AS total_earnings'))
        ->groupBy('users.branch_id', 'branches.name')
        ->get();

    

    return view('branch_profits', ['totalEarnings'=>$totalEarnings]);
}

public function processTransfer(Request $request)
{
    $request->validate([
        'transfer_id' => 'required|exists:transfer_requests,id',
        'action' => 'required|in:confirm,reject',
        'transfer_notes' => 'nullable|string|max:1000',
    ]);

    try {
        DB::beginTransaction();

        $transfer = DB::table('transfer_requests')->where('id', $request->transfer_id)->first();

        if (!$transfer) {
            throw new \Exception('Transfer request not found.');
        }

        // Update the status of the transfer request based on the action
        $status = $request->action === 'confirm' ? 'approved' : 'rejected';

        DB::table('transfer_requests')
            ->where('id', $request->transfer_id)
            ->update([
                'status' => $status,
                'notes' => $request->transfer_notes,
                'updated_at' => now(),
            ]);

        DB::commit();

        return redirect()->back()->with('success', 'Transfer request processed successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to process transfer request: ' . $e->getMessage());
    }
}


}
