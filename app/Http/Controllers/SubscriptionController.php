<?php
namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $subscriptions = Subscription::with('user')
            ->when(Auth::user()->user_type !== 'accountant', function ($query) {
                return $query->where('user_id', Auth::user()->id);
            })
            ->paginate(10);
            
        return view('subscriptions.index', compact('subscriptions'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        if (Auth::user()->user_type !== 'accountant') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:paid,pending,overdue',
            'next_payment_date' => 'required|date'
        ]);

        $subscription->update($request->all());

        return redirect()->route('subscriptions.index')
                        ->with('success', 'Subscription updated successfully');
    }

    public function history(Subscription $subscription)
    {
        // Check if user is authorized to view this subscription
        if (Auth::user()->user_type !== 'accountant' && Auth::user()->id !== $subscription->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $payments = $subscription->payments()->orderBy('created_at', 'desc')->get();
        return view('subscriptions.history', compact('subscription', 'payments'));
    }
}