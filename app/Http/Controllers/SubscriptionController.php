<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller 
{
    // Show login form
    public function index()
    {
        return view('subscription');
    }

    public function showUser($id)
    {
        $User = DB::table('subscriptions')
        ->select('subscriptions.*')
        ->where('user_id', $id)
        ->get();


    }

}

