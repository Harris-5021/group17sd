<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TestController;

Route::get(uri: '/', action: [Controller::class, 'home'])->name(name: 'home');
Route::get(uri: '/test', action: [TestController::class, 'test'])->name(name: 'test');


 