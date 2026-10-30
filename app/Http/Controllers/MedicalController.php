<?php
namespace App\Http\Controllers;
use App\Models\Officer;
use App\Models\OfficerMedical;
use Illuminate\Http\Request;

class MedicalController extends Controller {
    public function create(Officer $officer) {
        return view('medicals.create', compact('officer'));
    }
    public function store(Request $request, Officer $officer) {
        $request->validate(['exam_date' => 'required|date']);
        OfficerMedical::create(array_merge($request->except(['_token']), ['officer_id' => $officer->id, 'created_by' => auth()->id()]));
        return redirect()->route('officers.show', $officer)->with('success', 'Medical record added.');
    }
    public function destroy(OfficerMedical $medical) {
        $id = $medical->officer_id; $medical->delete();
        return redirect()->route('officers.show', $id)->with('success', 'Medical record deleted.');
    }
}
