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

