<?php

namespace App\Http\Controllers;

use App\Models\DeliveryRequest;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    // Show a form for delivery request (optional, not needed if it's handled via AJAX)
    public function showDeliveryForm($mediaId)
    {
        $media = Media::findOrFail($mediaId);
        return view('media.delivery', compact('media'));
    }

    // Handle the delivery request form submission
    public function requestDelivery(Request $request, $mediaId)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'delivery_date' => 'required|date|after_or_equal:today',
        ]);

        $media = Media::findOrFail($mediaId);

        // Create the delivery request record
        DeliveryRequest::create([
            'media_id' => $media->id,
            'address' => $request->input('address'),
            'delivery_date' => $request->input('delivery_date'),
            'user_id' => Auth::id(), // Get the current authenticated user
        ]);

        // Optionally, you can send a confirmation email or notification

        return redirect()->back()->with('status', 'Delivery request has been submitted successfully!');
    }
}
