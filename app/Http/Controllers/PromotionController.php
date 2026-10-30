<?php
namespace App\Http\Controllers;
use App\Models\Officer;
use App\Models\OfficerPromotion;
use Illuminate\Http\Request;

class PromotionController extends Controller {
    public function create(Officer $officer) {
        return view('promotions.create', ['officer' => $officer, 'ranks' => Officer::ranks()]);
    }

    public function store(Request $request, Officer $officer) {
        $request->validate([
            'to_rank'        => 'required|string',
            'effective_date' => 'required|date',
        ]);
        OfficerPromotion::create([
            'officer_id'     => $officer->id,
            'from_rank'      => $officer->rank,
            'to_rank'        => $request->to_rank,
            'effective_date' => $request->effective_date,
            'approved_by'    => $request->approved_by,
            'authority'      => $request->authority,
            'remarks'        => $request->remarks,
            'created_by'     => auth()->id(),
        ]);
        $officer->update(['rank' => $request->to_rank, 'dolp' => $request->effective_date]);
        return redirect()->route('officers.show', $officer)->with('success', 'Officer promoted successfully.');
    }

    public function destroy(OfficerPromotion $promotion) {
        $officerId = $promotion->officer_id;
        $promotion->delete();
        return redirect()->route('officers.show', $officerId)->with('success', 'Promotion record deleted.');
    }
}
