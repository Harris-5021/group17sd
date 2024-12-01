<?php

// app/Http/Controllers/VendorController.php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    // Show the list of all vendors
    public function index()
    {
        $vendors = Vendor::all();  // Get all vendors from the database
        return view('vendors.index', compact('vendors'));
    }

    // Show the form to create a new vendor
    public function create()
    {
        return view('vendors.create');
    }

    // Store a new vendor in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact' => 'nullable|string',
        ]);

        Vendor::create($request->all());  // Store vendor in the database

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully!');
    }

    // Show the list of media for a selected vendor
    public function showMedia(Vendor $vendor)
    {
        $mediaList = $vendor->media;  // Get media associated with the selected vendor
        return view('vendors.showMedia', compact('vendor', 'mediaList'));
    }
}
