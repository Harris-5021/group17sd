<?php
namespace App\Http\Controllers;
use App\Models\Procurement;
use Illuminate\Http\Request;

class ProcurementController extends Controller
{
    public function updateStatus(Request $request)
{
    // Debugging: log incoming data
    \Log::info('Update Payment Status Request:', $request->all());
    //dd($request->all());
    // Validate incoming data
    $request->validate([
        'procurement_id' => 'required|exists:procurements,procurement_id', // Update this line to match column name
        'payment_status' => 'required|in:pending,paid',
    ]);

    try {
        // Find the procurement by its unique procurement_id
        $procurement = Procurement::where('procurement_id', $request->input('procurement_id'))->first();

        if (!$procurement) {
            return redirect()->back()->with('error', 'Procurement not found.');
        }

        // Update the payment status
        $procurement->payment_status = $request->input('payment_status');
        $procurement->save();

        // Debugging: log success
        \Log::info('Payment status updated for procurement_id:', ['procurement_id' => $procurement->procurement_id]);

        return redirect()->back()->with('success', 'Payment status updated successfully.');
    } catch (\Exception $e) {
        // Debugging: log error
        \Log::error('Failed to update payment status:', ['error' => $e->getMessage()]);

        return redirect()->back()->with('error', 'Failed to update payment status.');
    }

    }
    
}
