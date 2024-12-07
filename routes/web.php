<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TestController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VendorController;
use App\Mail\NewMemberNotify;
use App\Http\Controllers\DeliveryController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Media;
use Illuminate\Http\Request;

// Public routes
Route::get('/', [Controller::class, 'home'])->name('home');


// Authentication routes
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'customLogin'])->name('login.custom');
Route::get('register', [LoginController::class, 'registration'])->name('register-user');
Route::post('register', [LoginController::class, 'customRegistration'])->name('register.custom');
Route::get('signout', [LoginController::class, 'signOut'])->name('signout');

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    //Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/update-branch', function(Request $request) {
        DB::table('users')
            ->where('id', Auth::id())
            ->update(['branch_id' => $request->branch_id]);
        
        return redirect()->back()->with('success', 'Preferred branch updated successfully');
    })->name('update.branch');
    Route::get('/dashboard/accountant', [DashboardController::class, 'index'])->name('dashboard.accountant');
    Route::get('/dashboard/purchase_manager', [DashboardController::class, 'index'])->name('dashboard.purchase_manager');
    Route::get('/dashboard/branch_manager', [DashboardController::class, 'index'])->name('dashboard.branch_manager');
    Route::get('/dashboard/librarian', [DashboardController::class, 'index'])->name('dashboard.librarian');
    Route::get('/dashboard/member', [DashboardController::class, 'index'])->name('dashboard.member');
    Route::get('/accountant', [DashboardController::class, 'searchUser'])->name('searchUser');
    
//Route for google chart to display profit of branches
    Route::get('/branch_profits', [DashboardController::class, 'googleLineChart'])->name('branch_profits');


    Route::get('/purchase_manager', [DashboardController::class, 'showProcurementForm'])->name('purchase_manager.view');
    Route::post('/procurement', [DashboardController::class, 'storeProcurement'])->name('procurement.store');
    Route::get('/viewProcurements', [DashboardController::class, 'viewProcurements'])->name('viewProcurements');


    Route::post('/subscription/{id}', [SubscriptionController::class, 'updateSubscription']) -> name('subscription.updateSubscription');
    Route::get('/subscription/{id}/{name}', [SubscriptionController::class, 'showUser']) -> name('subscription.showUser');
    Route::get('/subscription/{id}', [SubscriptionController::class, 'showPastPayments']) ->name('subscription.showPastPayments');

    

    
    Route::post('/media/notify', [MediaController::class, 'notifyManager'])->name('media.notify');
    Route::patch('/dashboard/notifications/{id}/toggle', [DashboardController::class, 'toggleNotification'])->name('notifications.toggle');
    Route::post('/dashboard/notifications/{id}/forward', [DashboardController::class, 'forwardToPurchaseManager'])->name('notifications.forward');

    Route::get('/notifications/{id}', [DashboardController::class, 'showNotification'])->name('notifications.show');
    Route::post('/notifications/{id}/accept', [DashboardController::class, 'acceptRequest'])->name('notifications.accept');
    Route::post('/notifications/{id}/reject', [DashboardController::class, 'rejectRequest'])->name('notifications.reject');

    Route::get('/media/inventory/{mediaId}/{branchId}', [MediaController::class, 'getInventory'])->name('media.inventory');


    Route::get('/returns/pending', [DashboardController::class, 'librarianDashboard'])->name('returns.pending');
    Route::get('/processed-returns', [DashboardController::class, 'viewProcessedReturns'])->name('returns.processed');
    Route::get('/returns/search', [DashboardController::class, 'searchReturns'])->name('returns.search');
    Route::post('/process-return', [DashboardController::class, 'processReturn'])->name('returns.process');
    Route::get('/fines', [DashboardController::class, 'viewFines'])->name('fines');
    Route::post('/return/{id}/process', [MediaController::class, 'processReturn'])->name('return.process');
    Route::post('/return/process', [MediaController::class, 'processReturn'])->name('return.process');
    Route::post('/wishlist/update-priority', [MediaController::class, 'updatePriority'])->name('wishlist.updatePriority');
    Route::post('/wishlist/notifications/update', [MediaController::class, 'updateNotificationPreferences'])->name('wishlist.updateNotifications');

    Route::post('/wishlist/request-media', [MediaController::class, 'requestMedia'])->name('wishlist.requestMedia');


});
    // Media routes
    Route::get('/search', [MediaController::class, 'search'])->name('search');
    Route::get('/browse', [MediaController::class, 'browse'])->name('browse');
    Route::get('/media/{id}', [MediaController::class, 'show'])->name('show');
    Route::get('/borrowed', [MediaController::class, 'borrowed'])->name('borrowed');
    Route::get('/wishlist', [MediaController::class, 'wishlist'])->name('wishlist');
    Route::get('/browse_branch', [MediaController::class, 'browse_branch'])->name('browse_branch');
    Route::get('/branch_media/{branch_id}/{name}', [MediaController::class, 'branch_media'])->name('branch_media');


    // Media actions
    Route::post('/media/{id}/borrow', [MediaController::class, 'borrow'])->name('borrow');
    Route::post('/media/{id}/return', [MediaController::class, 'return'])->name('return');
    Route::post('/media/{id}/wishlist', [MediaController::class, 'addToWishlist'])->name('wishlist.add');
    Route::delete('/media/{id}/wishlist', [MediaController::class, 'removeFromWishlist'])->name('wishlist.remove');


Route::middleware(['auth'])->group(function () {
    // Route to handle the delivery request
    Route::post('/media/{mediaId}/request-delivery', [DeliveryController::class, 'requestDelivery'])->name('delivery.request');
});
// Route for listing vendors
Route::get('/vendors', [VendorController::class, 'index'])->name('vendors.index');

// Route for showing media of a vendor
Route::get('/vendors/{vendor}/media', [VendorController::class, 'showMedia'])->name('vendors.showMedia');

// Route for creating a new vendor
Route::get('/vendors/create', [VendorController::class, 'create'])->name('vendors.create');
Route::post('/vendors', [VendorController::class, 'store'])->name('vendors.store');


