<?php

namespace App\Http\Controllers;

use App\Models\Procurement;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcurementController extends Controller
{
    public function index()
    {
        $procurements = Procurement::with(['media', 'branch', 'requestedByUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('procurement.index', compact('procurements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'media_id' => 'required|exists:media,id',
            'branch_id' => 'required|exists:branches,id',
            'quantity' => 'required|integer|min:1',
            'vendor_name' => 'required|string',
            'estimated_delivery_date' => 'required|date',
            'cost' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $procurement = Procurement::create([
                'media_id' => $request->media_id,
                'branch_id' => $request->branch_id,
                'requested_by' => auth()->id(),
                'quantity' => $request->quantity,
                'vendor_name' => $request->vendor_name,
                'estimated_delivery_date' => $request->estimated_delivery_date,
                'cost' => $request->cost,
                'status' => 'Pending',
                'purchase_order_number' => 'PO-' . time()
            ]);

            // Notify branch manager
            // Notification::send($procurement->branch->manager, new ProcurementCreated($procurement));

            DB::commit();
            return redirect()->route('procurement.show', $procurement)
                           ->with('success', 'Procurement order created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Unable to create procurement order. Please try again.');
        }
    }

    public function show(Procurement $procurement)
    {
        return view('procurement.show', compact('procurement'));
    }
}