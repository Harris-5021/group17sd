<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TestController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ProcurementController;

Route::get(uri: '/', action: [Controller::class, 'home'])->name(name: 'home');
Route::get(uri: '/test', action: [TestController::class, 'test'])->name(name: 'test');

// Media Availability Routes
Route::get('/media', [MediaController::class, 'index'])->name('media.index');
Route::get('/media/{media}', [MediaController::class, 'show'])->name('media.show');
Route::get('/media/branch/{branch}', [MediaController::class, 'byBranch'])->name('media.branch');
Route::get('/media/{media}/availability', [MediaController::class, 'checkAvailability'])->name('media.availability');

// Media Borrowing Routes
Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
Route::put('/loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');

// Wishlist Routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

// Subscription Management Routes
Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
Route::put('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
Route::get('/subscriptions/{subscription}/history', [SubscriptionController::class, 'history'])->name('subscriptions.history');

// Procurement Routes
Route::get('/procurement', [ProcurementController::class, 'index'])->name('procurement.index');
Route::post('/procurement', [ProcurementController::class, 'store'])->name('procurement.store');
Route::get('/procurement/{procurement}', [ProcurementController::class, 'show'])->name('procurement.show');
 