<?php

namespace App\Http\Controllers;

use App\Models\DeliveryRequest;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    // Handle the delivery request form submission
    public function requestDelivery(Request $request, $mediaId)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'delivery_date' => 'required|date|after_or_equal:today',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $media = Media::findOrFail($mediaId);

        // Check inventory for the selected branch
        $inventory = DB::table('inventory')
            ->where('media_id', $mediaId)
            ->where('branch_id', $request->branch_id)
            ->where('quantity', '>', 0)
            ->first();

        if (!$inventory) {
            return redirect()->back()->with('error', 'This item is not available at the selected branch.');
        }

        // Check if the user already has an active loan for this media
        $existingLoan = DB::table('loans')
            ->where('user_id', Auth::id())
            ->where('media_id', $mediaId)
            ->where('status', 'active')
            ->first();

        if ($existingLoan) {
            return redirect()->back()->with('error', 'You already have this item borrowed.');
        }

        DB::beginTransaction();

        try {
            // Decrease the quantity in inventory
            DB::table('inventory')
                ->where('media_id', $mediaId)
                ->where('branch_id', $request->branch_id)
                ->decrement('quantity');

            // Create a delivery request record
            DeliveryRequest::create([
                'media_id' => $mediaId,
                'branch_id' => $request->branch_id,
                'user_id' => Auth::id(),
                'address' => $request->input('address'),
                'delivery_date' => $request->input('delivery_date'),
            ]);

            // Optionally, create a loan record if delivery also initiates borrowing
            DB::table('loans')->insert([
                'user_id' => Auth::id(),
                'media_id' => $mediaId,
                'branch_id' => $request->branch_id,
                'borrowed_date' => now(),
                'due_date' => now()->addDays(14), // Assuming a 14-day loan period
                'returned_date' => null,
                'status' => 'active',
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Delivery request has been submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to submit delivery request: ' . $e->getMessage());
        }
    }
}
