<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Procurement;
use Illuminate\Http\Request;

class ProcurementController extends Controller
{
    public function showProcurementForm()
    {
        // Fetch media items to display in the dropdown/select options
        $mediaItems = Media::all();
        return view('procurement.create', compact('mediaItems'));
    }

    public function storeProcurement(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'media_id' => 'required|exists:media,media_id',
            'procurement_date' => 'required|date',
            'procurement_type' => 'required|in:purchase,license,donation',
            'supplier_name' => 'required|string|max:255',
            'procurement_cost' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,overdue',
        ]);

        // Create a new procurement record
        Procurement::create($request->all());

        // Redirect or return a success message
        return redirect()->route('procurement.index')->with('success', 'Procurement record added successfully.');
    }
}
