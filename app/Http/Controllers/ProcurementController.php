<?php
namespace App\Http\Controllers;
use App\Models\Procurement;
use Illuminate\Http\Request;

class ProcurementController extends Controller
{
    public function updateDelivery(Request $request, $id)
{
    // Find the procurement record by its id
    $procurement = Procurement::findOrFail($id);

    // Update the 'updateStatus' field with the new value
    $procurement->updateStatus = $request->input('updateStatus');

    // Save the changes
    $procurement->save();

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Delivery status updated!');
}
}
