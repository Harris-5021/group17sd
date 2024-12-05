<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


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
            $subscription = Subscription::findOrFail($id);
    
            if ($request->has('plan_type')) {
                $subscription->plan_type = $request->plan_type;
            }
            if ($request->has('fee_paid')) {
                if($request['fee_paid'] == 1)
                {
                    $subscription->fee_paid = 1;
                    $subscription->status = 'Active';
                } else
                {
                    $subscription->fee_paid = 0;
                    $subscription->status = 'Suspended';
                }    
            }
            if($request->has('start_date'))
            {
                $subscription->start_date = $request->start_date;
                $startDate = Carbon::parse($request->input('start_date'));
                $endDate = $startDate->copy()->addMonth();
                $subscription->end_date = $endDate;
            }
           
            if($request['plan_type'] == 'Basic')
            {
                $subscription->amount = 25.99;
            }
            elseif($request['plan_type'] == 'Student')
            {
                $subscription->amount = 15.99;
            }
            elseif($request['plan_type'] == 'Premium')
            {
                $subscription->amount = 35.99;
            }

            $subscription->save();
    
            return redirect()->back()->with('success', 'Subscription updated successfully.');
        }
      

     
   
    }




