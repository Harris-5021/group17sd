<?php
// app/Http/Controllers/MediaController.php
namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Branch;
use App\Models\BranchInventory;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::query();
        
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%');
        }
        
        $media = $query->paginate(10);
        return view('media.index', compact('media'));
    }

    public function show(Media $media)
    {
        $media->load('branchInventories.branch');
        return view('media.show', compact('media'));
    }

    public function byBranch(Branch $branch)
    {
        $inventory = BranchInventory::with('media')
            ->where('branch_id', $branch->id)
            ->where('quantity', '>', 0)
            ->paginate(10);
            
        return view('media.branch', compact('branch', 'inventory'));
    }

    public function checkAvailability(Media $media)
    {
        $availability = BranchInventory::with('branch')
            ->where('media_id', $media->id)
            ->where('quantity', '>', 0)
            ->get();
            
        return view('media.availability', compact('media', 'availability'));
    }
}