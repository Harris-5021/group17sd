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

Route::get('/', [Controller::class, 'home'])->name('home');
Route::get('/test', [TestController::class, 'test'])->name('test');

// Auth routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Login routes
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'customLogin'])->name('login.custom');

// Registration routes
Route::get('register', [LoginController::class, 'registration'])->name('register-user');
Route::post('register', [LoginController::class, 'customRegistration'])->name('register.custom');

// Logout route
Route::get('signout', [LoginController::class, 'signOut'])->name('signout');


Route::get('/search', [MediaController::class, 'search'])->name('media.search');
