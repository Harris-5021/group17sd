<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Media;
class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Browse media items
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

    public function browse_branch()
    {
        $branches = DB::table('branches')
        ->select('branches.*')
        ->get();

        return view('browse_branch', ['branches' => $branches]);
    }

    public function branch_media($branch_id)
    {
        $branch_media = DB::table('media')
        ->join('inventory', 'media.id', '=', 'inventory.media_id')
        ->join('branches', 'inventory.branch_id', '=', 'branches.id')
        ->select('media.*', 'inventory.quantity')
        ->where('inventory.branch_id', $branch_id)
        ->get();


        return view('branch_media', ['branch_media'=> $branch_media]);
    }

    // Search for media items
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
            ->where(function ($q) use ($query) {
                $q->where('media.title', 'LIKE', "%$query%")
                  ->orWhere('media.author', 'LIKE', "%$query%")
                  ->orWhere('media.description', 'LIKE', "%$query%");
            })
            ->where('inventory.quantity', '>', 0)
            ->get();

        return view('searchresults', compact('media', 'query'));
    }

    // Show details of a specific media item
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

    // View borrowed items
    public function borrowed()
{
    $activeLoans = DB::table('loans')
        ->join('media', 'loans.media_id', '=', 'media.id') // Ensure the media table is joined
        ->join('branches', 'loans.branch_id', '=', 'branches.id')
        ->where('loans.user_id', Auth::id())
        ->where('loans.status', 'active') // Fetch only active loans
        ->select(
            'media.id as media_id', 
            'media.title', 
            'media.author',
            'loans.borrowed_date', 
            'loans.due_date', 
            'loans.id as loan_id',
            'branches.name as branch_name'
        )
        ->get();

    return view('borrowed', compact('activeLoans'));
}


    // Wishlist functionality
   // Update your wishlist method in MediaController
public function wishlist()
{
    $wishlistItems = DB::table('wishlists')
        ->join('media', 'wishlists.media_id', '=', 'media.id')
        ->leftJoin('inventory', 'media.id', '=', 'inventory.media_id')
        ->leftJoin('branches', 'inventory.branch_id', '=', 'branches.id')
        ->where('wishlists.user_id', Auth::id())
        ->select(
            'wishlists.id',
            'wishlists.priority',
            'wishlists.notification_preferences', // Add this line
            'media.*',
            'inventory.quantity',
            'inventory.branch_id',
            'branches.name as branch_name'
        )
        ->orderBy('wishlists.priority', 'asc')
        ->get();
        
    $branches = DB::table('branches')
        ->select('id', 'name')
        ->get();
        
    return view('wishlist', compact('wishlistItems', 'branches'));
}

    // Borrow media items
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

        if (!$inventory) {
            return redirect()->back()->with('error', 'This item is not available at the selected branch');
        }

        // Check if user already has an active loan for this item
        $existingLoan = DB::table('loans')
            ->where('user_id', Auth::id())
            ->where('media_id', $id)
            ->where('status', 'active')
            ->first();

        if ($existingLoan) {
            return redirect()->back()->with('error', 'You already have this item borrowed');
        }

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

    // Return borrowed media items
   
// In MediaController.php

public function return($id)
{
    $loan = DB::table('loans')
        ->where('id', $id)
        ->where('user_id', Auth::id())
        ->where('status', 'active')
        ->first();

    if (!$loan) {
        return redirect()->back()->with('error', 'Loan record not found');
    }

    DB::beginTransaction();

    try {
        // Update loan status
        DB::table('loans')
            ->where('id', $id)
            ->update([
                'status' => 'returned',
                'returned_date' => now(),
            ]);

        // Increment inventory quantity
        DB::table('inventory')
            ->where('media_id', $loan->media_id)
            ->where('branch_id', $loan->branch_id)
            ->increment('quantity');

        // Add the new code HERE, after incrementing inventory
        $updatedQuantity = DB::table('inventory')
            ->where('media_id', $loan->media_id)
            ->where('branch_id', $loan->branch_id)
            ->value('quantity');

        if($updatedQuantity > 0) {  // If item is now in stock
            // Get the media title
            $media = DB::table('media')->where('id', $loan->media_id)->first();
            
            // Check for users who want to be notified
            $wishlistUsers = DB::table('wishlists')
                ->join('users', 'wishlists.user_id', '=', 'users.id')
                ->where('wishlists.media_id', $loan->media_id)
                ->where('notification_preferences', 'enabled')
                ->get();

            foreach($wishlistUsers as $wishlistUser) {
                Mail::raw("Good news! '{$media->title}' is now available at the library.", function ($message) use ($wishlistUser) {
                    $message->to($wishlistUser->email)
                            ->subject('Wishlist Item Now Available');
                });
            }
        }

        // Send email to the user who returned the item
        $user = Auth::user();
        Mail::raw('Your book has been successfully returned.', function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Book Return Confirmation');
        });

        DB::commit();
        return redirect()->back()->with('success', 'Item returned successfully, and an email confirmation has been sent.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to return item');
    }
}


public function showReturnForm($id)
{
    $loan = DB::table('loans')
        ->join('users', 'loans.user_id', '=', 'users.id')
        ->join('media', 'loans.media_id', '=', 'media.id')
        ->where('loans.id', $id)
        ->where('loans.status', 'active')
        ->select('loans.*', 'media.title', 'users.name as user_name')
        ->first();

    if (!$loan) {
        return redirect()->back()->with('error', 'Loan record not found');
    }

    return view('return-form', compact('loan'));
}


public function processReturn(Request $request)
{
    // Verify user is a librarian
    if (Auth::user()->role !== 'librarian') {
        return redirect()->back()->with('error', 'Unauthorized access');
    }

    $loan = DB::table('loans')
        ->where('id', $request->loan_id)
        ->where('status', 'returned')  // Only process items that have been returned
        ->first();

    if (!$loan) {
        return redirect()->back()->with('error', 'Loan record not found');
    }

    DB::beginTransaction();

    try {
        // Update to damaged if damage notes provided
        if ($request->has('damage_notes')) {
            DB::table('loans')
                ->where('id', $request->loan_id)
                ->update([
                    'status' => 'damaged',
                    'damaged_notes' => $request->damage_notes
                ]);
        }

        // Create fine if specified
        if ($request->filled('fine_amount') && $request->fine_amount > 0) {
            DB::table('fines')->insert([
                'loan_id' => $loan->id,
                'user_id' => $loan->user_id,
                'amount' => $request->fine_amount,
                'reason' => $request->has('damage_notes') ? 'damage' : 'overdue',
                'status' => 'pending',
                'due_date' => now()->addDays(30),
                'created_at' => now()
            ]);

            // Notify user of fine
            $user = DB::table('users')->find($loan->user_id);
            Mail::raw('A fine has been added to your account for returned item.', function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Library Fine Notice');
            });
        }

        DB::commit();
        return redirect()->back()->with('success', 'Return processed successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to process return: ' . $e->getMessage());
    }
}
    // Add media to wishlist
    public function addToWishlist($id)
{
    // Check if the item is already in the wishlist
    $exists = DB::table('wishlists')
        ->where('user_id', Auth::id())
        ->where('media_id', $id)
        ->exists();

    if ($exists) {
        return redirect()->back()->with('error', 'Item already in wishlist');
    }

    // Add the item to the wishlist
    DB::table('wishlists')->insert([
        'user_id' => Auth::id(),
        'media_id' => $id,
        'priority' => null,
        'created_at' => now(),
    ]);

    // Get more detailed media information
    $media = DB::table('media')->where('id', $id)->first();
    $user = Auth::user();
    $branchManager = DB::table('branches')
        ->where('id', Auth::user()->branch_id)
        ->first();

    if ($branchManager && $branchManager->manager_id) {
        // Create a more detailed notification message
        $notificationMessage = sprintf(
            "User: %s\nMedia Details:\n- Title: %s\n- Author: %s\n- Type: %s\n- Publication Year: %s\nRequested for Branch: %s",
            $user->name,
            $media->title,
            $media->author,
            $media->type,
            $media->publication_year,
            $branchManager->name
        );

        DB::table('notifications')->insert([
            'user_id' => $branchManager->manager_id,
            'type' => 'wishlist',
            'title' => 'New Wishlist Request',
            'message' => $notificationMessage,
            'status' => 'unread',
            'created_at' => now(),
        ]);
    }

    return redirect()->back()->with('success', 'Item added to wishlist. The branch manager has been notified.');
}
    

    // Remove media from wishlist
    public function removeFromWishlist($id)
    {
        DB::table('wishlists')
            ->where('user_id', Auth::id())
            ->where('media_id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Removed from wishlist');
    }

    // Notify manager about media requests
    public function notifyManager(Request $request)
    {
        $request->validate([
            'media_id' => 'required|exists:media,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        try {
            $media = DB::table('media')->where('id', $request->media_id)->first();

            $branchManager = DB::table('branches')
                ->where('id', $request->branch_id)
                ->first();

            if (!$branchManager || !$branchManager->manager_id) {
                return back()->with('error', 'Branch manager not found.');
            }

            DB::table('notifications')->insert([
                'user_id' => $branchManager->manager_id,
                'type' => 'procurement',
                'title' => 'Media Request',
                'message' => 'A user has requested the media "' . $media->title . '" to be added to your branch.',
                'status' => 'unread',
                'created_at' => now()
            ]);

            return back()->with('success', 'Branch manager has been notified about your request.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while sending the notification.');
        }
    }

    // Get inventory for a specific media and branch
    public function getInventory($mediaId, $branchId)
    {
        $inventory = DB::table('inventory')
            ->where('media_id', $mediaId)
            ->where('branch_id', $branchId)
            ->first();
        
        return response()->json([
            'quantity' => $inventory ? $inventory->quantity : 0
        ]);
    }

 
    public function updatePriority(Request $request)
{
    $order = $request->input('order');
    
    DB::beginTransaction();
    try {
        foreach ($order as $item) {
            DB::table('wishlists')
                ->where('id', $item['id'])
                ->where('user_id', Auth::id()) // Add security check
                ->update(['priority' => $item['priority']]);
        }
        DB::commit();
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

public function updateNotificationPreferences(Request $request)
{
    $request->validate([
        'wishlist_id' => 'required|exists:wishlists,id',
        'notifications_enabled' => 'required|boolean'
    ]);

    try {
        DB::table('wishlists')
            ->where('id', $request->wishlist_id)
            ->where('user_id', Auth::id())
            ->update([
                'notification_preferences' => $request->notifications_enabled ? 'enabled' : null
            ]);

        // Send confirmation email
        $user = Auth::user();
        $wishlistItem = DB::table('wishlists')
            ->join('media', 'wishlists.media_id', '=', 'media.id')
            ->where('wishlists.id', $request->wishlist_id)
            ->select('media.title')
            ->first();

        $message = $request->notifications_enabled 
            ? "You will now receive email notifications when '{$wishlistItem->title}' becomes available."
            : "You have disabled email notifications for '{$wishlistItem->title}'.";

        Mail::raw($message, function ($mail) use ($user) {
            $mail->to($user->email)
                 ->subject('Wishlist Notification Settings Updated');
        });

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}
public function requestMedia(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'media_type' => 'required|in:Book,DVD,Magazine,E-Book,Audio',
        'additional_notes' => 'nullable|string|max:1000'
    ]);

    DB::beginTransaction();

    try {
        // Create a 'pending' media record
        $media = Media::create([
            'title' => $request->title,
            'author' => $request->author,
            'type' => $request->media_type,
            'status' => 'pending'  // Special status for requested items
        ]);

        // Add to user's wishlist
        DB::table('wishlists')->insert([
            'user_id' => Auth::id(),
            'media_id' => $media->id,
            'notification_preferences' => 'enabled',  // Auto-enable notifications
            'created_at' => now()
        ]);

        // Notify branch manager
        $branchManager = DB::table('branches')
            ->where('id', Auth::user()->branch_id)
            ->first();

        if ($branchManager && $branchManager->manager_id) {
            $notificationMessage = sprintf(
                "New Media Request:\n- Title: %s\n- Author: %s\n- Type: %s\n- Requested by: %s\n- Additional Notes: %s",
                $request->title,
                $request->author,
                $request->media_type,
                Auth::user()->name,
                $request->additional_notes ?? 'None'
            );

            DB::table('notifications')->insert([
                'user_id' => $branchManager->manager_id,
                'type' => 'media_request',
                'title' => 'New Media Request',
                'message' => $notificationMessage,
                'status' => 'unread',
                'created_at' => now()
            ]);
        }

        DB::commit();
        return redirect()->back()->with('success', 'Media request submitted successfully. You will be notified when it becomes available.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Failed to submit media request. Please try again.');
    }
}










}
