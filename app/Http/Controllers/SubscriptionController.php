<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Subscription;
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
            // Find the subscription by ID
            $subscription = Subscription::findOrFail($id);
    
            // Update the fields based on the form inputs
            if ($request->has('plan_type')) {
                $subscription->plan_type = $request->plan_type;
            }
            if ($request->has('amount')) {
                $subscription->amount = $request->amount;
            }
            if ($request->has('fee_paid')) {
                $subscription->fee_paid = $request->fee_paid;
            }
    
            if($request['fee_paid'] == 1)
            {
                $status = 'Active';
            } else
            {
                $status = 'Suspended';
            }
            // Save the changes to the database
            $subscription->save();
    
            return redirect()->back()->with('success', 'Subscription updated successfully.');
        }
      

   
    }




