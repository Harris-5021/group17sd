<?php
namespace App\Http\Controllers;
use App\Models\Procurement;
use Illuminate\Http\Request;

class ProcurementController extends Controller
{
    public function updateStatus(Request $request)
    {
        \Log::info('Incoming data for updating statuses:', $request->all());
    
        // Validate incoming data
        $request->validate([
            'procurement_id' => 'required|exists:procurements,procurement_id',
            'payment_status' => 'nullable|in:pending,paid',
            'updateStatus' => 'nullable|in:pending,dispatched,delivering,delivered',
        ]);
    
        try {
            // Fetch procurement by procurement_id
            $procurement = Procurement::where('procurement_id', $request->input('procurement_id'))->first();
    
            if (!$procurement) {
                \Log::error('Procurement not found:', ['procurement_id' => $request->input('procurement_id')]);
                return redirect()->back()->with('error', 'Procurement not found.');
            }
    
            // Update payment_status if provided
            if ($request->has('payment_status')) {
                $procurement->payment_status = $request->input('payment_status');
            }
    
            // Update updateStatus if provided
            if ($request->has('updateStatus')) {
                $procurement->updateStatus = $request->input('updateStatus');
            }
    
            // Save the updated procurement record
            $procurement->save();
    
            \Log::info('Procurement statuses updated successfully:', ['procurement_id' => $procurement->procurement_id]);
    
            return redirect()->back()->with('success', 'Statuses updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating statuses:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update statuses.');
        }
    }
    
    
}
