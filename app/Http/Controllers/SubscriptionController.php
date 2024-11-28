<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller 
{
    // Show subscription form
    public function index()
    {
        return view('subscription');
    }

    public function showUser($user_id, $name)
    {

        $UserSubscription = DB::table('subscriptions')
        ->select('subscriptions.*')
        ->where('user_id', $user_id)
        ->get();

        return view('subscription', 
        ['subscriptions' => $UserSubscription, 'name' =>$name]
    );

    }

    public function updateSubscription(Request $request, $id)
    {
        if($request['fee_paid'] == 1)
        {
            $status = 'Active';
        } else
        {
            $status = 'Suspended';
        }

        DB::updateSubscription(
            'UPDATE subscriptions
            SET plan_type = :plan_type, amount = :amount, status = :status, fee_paid = :fee_paid
            WHERE id = :id',
            [
                'plan_type' => $request['plan_type'],
                'amount' => $request['amount'],
                'status' => $status,
                'fee_paid' => $request['fee_paid'],
                'id' => $id,
            ]
            );
            return redirect()->back()->with('success', 'Subscription updated successfully!');

    }


}

