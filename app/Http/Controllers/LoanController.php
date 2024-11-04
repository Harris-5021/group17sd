<?php
// app/Http/Controllers/LoanController.php
namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Media;
use App\Models\BranchInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'media_id' => 'required|exists:media,id',
            'branch_id' => 'required|exists:branches,id',
            'delivery_option' => 'required|in:pickup,delivery',
            'delivery_address' => 'required_if:delivery_option,delivery'
        ]);

        try {
            DB::beginTransaction();

            $inventory = BranchInventory::where('media_id', $request->media_id)
                ->where('branch_id', $request->branch_id)
                ->where('quantity', '>', 0)
                ->lockForUpdate()
                ->firstOrFail();

            $loan = Loan::create([
                'user_id' => Auth::user()->id,
                'media_id' => $request->media_id,
                'branch_id' => $request->branch_id,
                'borrow_date' => now(),
                'due_date' => now()->addDays(14),
                'status' => 'Active'
            ]);

            $inventory->decrement('quantity');

            DB::commit();

            return redirect()->route('loans.show', $loan)
                           ->with('success', 'Media borrowed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Unable to process loan. Please try again.');
        }
    }
}