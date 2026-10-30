<?php
namespace App\Http\Controllers;
use App\Models\Officer;
use App\Models\OfficerDiscipline;
use Illuminate\Http\Request;

class DisciplineController extends Controller {
    public function create(Officer $officer) {
        return view('disciplines.create', compact('officer'));
    }
    public function store(Request $request, Officer $officer) {
        $request->validate(['charge' => 'required|string|max:255']);
        OfficerDiscipline::create(array_merge($request->except(['_token']), ['officer_id' => $officer->id, 'created_by' => auth()->id()]));
        return redirect()->route('officers.show', $officer)->with('success', 'Disciplinary record added.');
    }
    public function destroy(OfficerDiscipline $discipline) {
        $id = $discipline->officer_id; $discipline->delete();
        return redirect()->route('officers.show', $id)->with('success', 'Disciplinary record deleted.');
    }
}
