<?php
// app/Http/Controllers/WishlistController.php
namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wishlist = Wishlist::with('media')
            ->where('user_id', Auth::user()->id)
            ->paginate(10);
            
        return view('wishlist.index', compact('wishlist'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'media_id' => 'required|exists:media,id',
            'notification_preference' => 'required|in:email,sms,none',
            'priority' => 'required|integer|min:1|max:5'
        ]);

        $wishlist = Wishlist::create([
            'user_id' => Auth::user()->id,
            'media_id' => $request->media_id,
            'notification_preference' => $request->notification_preference,
            'priority' => $request->priority,
            'notes' => $request->notes
        ]);

        return redirect()->route('wishlist.index')
                        ->with('success', 'Item added to wishlist');
    }

    public function destroy(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::user()->id) {
            abort(403);
        }

        $wishlist->delete();
        return redirect()->route('wishlist.index')
                        ->with('success', 'Item removed from wishlist');
    }
}