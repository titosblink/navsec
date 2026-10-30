<?php
namespace App\Http\Controllers;
use App\Models\Officer;
use App\Models\OfficerQualification;
use Illuminate\Http\Request;

class QualificationController extends Controller {
    public function create(Officer $officer) {
        return view('qualifications.create', ['officer' => $officer, 'types' => OfficerQualification::types()]);
    }
    public function store(Request $request, Officer $officer) {
        $request->validate(['qual_type'=>'required','title'=>'required|string|max:150']);
        OfficerQualification::create(array_merge($request->except(['_token']), ['officer_id' => $officer->id]));
        return redirect()->route('officers.show', $officer)->with('success', 'Qualification added.');
    }
    public function destroy(OfficerQualification $qualification) {
        $id = $qualification->officer_id; $qualification->delete();
        return redirect()->route('officers.show', $id)->with('success', 'Qualification deleted.');
    }
}
