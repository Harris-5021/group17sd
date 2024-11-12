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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
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
});