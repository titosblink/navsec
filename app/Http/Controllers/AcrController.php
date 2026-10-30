<?php
namespace App\Http\Controllers;
use App\Models\Officer;
use App\Models\OfficerAcr;
use Illuminate\Http\Request;

class AcrController extends Controller {
    public function create(Officer $officer) {
        return view('acrs.create', ['officer' => $officer, 'categories' => OfficerAcr::fitnessCategories()]);
    }
    public function store(Request $request, Officer $officer) {
        $request->validate(['report_year' => 'required|integer|min:1980|max:'.date('Y')]);
        OfficerAcr::create(array_merge($request->except(['_token']), ['officer_id' => $officer->id, 'created_by' => auth()->id()]));
        return redirect()->route('officers.show', $officer)->with('success', 'ACR recorded successfully.');
    }
    public function destroy(OfficerAcr $acr) {
        $id = $acr->officer_id; $acr->delete();
        return redirect()->route('officers.show', $id)->with('success', 'ACR deleted.');
    }
}
