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
use App\Mail\NewMemberNotify;
use App\Http\Controllers\DeliveryController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Media;
use Illuminate\Http\Request;
// Public routes
Route::get('/', [Controller::class, 'home'])->name('home');
Route::get('/test', [TestController::class, 'test'])->name('test');

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

    Route::get('/purchase_manager', [DashboardController::class, 'showProcurementForm'])->name('purchase_manager.view');
    Route::post('/procurement', [DashboardController::class, 'storeProcurement'])->name('procurement.store');
    Route::get('/viewProcurements', [DashboardController::class, 'viewProcurements'])->name('viewProcurements');

    //gets user id passed when form is submitted
    Route::get('/subscription/{id}/{name}', [SubscriptionController::class, 'showUser']) -> name('subscription.showUser');
    Route::post('/subscription/{id}', [SubscriptionController::class, 'updateSubscription']) -> name('subscription.updateSubscription');

    
    Route::post('/media/notify', [MediaController::class, 'notifyManager'])->name('media.notify');
    Route::patch('/dashboard/notifications/{id}/toggle', [DashboardController::class, 'toggleNotification'])->name('notifications.toggle');
    Route::post('/dashboard/notifications/{id}/forward', [DashboardController::class, 'forwardToPurchaseManager'])->name('notifications.forward');

    Route::get('/notifications/{id}', [DashboardController::class, 'showNotification'])->name('notifications.show');
    Route::post('/notifications/{id}/accept', [DashboardController::class, 'acceptRequest'])->name('notifications.accept');
    Route::post('/notifications/{id}/reject', [DashboardController::class, 'rejectRequest'])->name('notifications.reject');

    Route::get('/media/inventory/{mediaId}/{branchId}', [MediaController::class, 'getInventory'])->name('media.inventory');
});
    // Media routes
    Route::get('/search', [MediaController::class, 'search'])->name('search');
    Route::get('/browse', [MediaController::class, 'browse'])->name('browse');
    Route::get('/media/{id}', [MediaController::class, 'show'])->name('show');
    Route::get('/borrowed', [MediaController::class, 'borrowed'])->name('borrowed');
    Route::get('/wishlist', [MediaController::class, 'wishlist'])->name('wishlist');
    
    // Media actions
    Route::post('/media/{id}/borrow', [MediaController::class, 'borrow'])->name('borrow');
    Route::post('/media/{id}/return', [MediaController::class, 'return'])->name('return');
    Route::post('/media/{id}/wishlist', [MediaController::class, 'addToWishlist'])->name('wishlist.add');
    Route::delete('/media/{id}/wishlist', [MediaController::class, 'removeFromWishlist'])->name('wishlist.remove');


   


Route::get('/send-test-email', function () {
    $testEmail = 'harrisfiaz3@gmail.com'; // Replace with your actual email
    $userName = 'harris fiaz'; // Optional name for personalization
    
    // Send the email using the mailable
    Mail::to($testEmail)->send(new NewMemberNotify($testEmail, $userName));
    
    return 'Test email sent to ' . $testEmail;
});
Route::middleware(['auth'])->group(function () {
    // Route to handle the delivery request
    Route::post('/media/{mediaId}/request-delivery', [DeliveryController::class, 'requestDelivery'])->name('delivery.request');
});

