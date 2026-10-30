<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\OfficerCourse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = OfficerCourse::with('officer');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('officer', fn($q) => $q->where('name', 'like', "%$s%")
                                                      ->orWhere('svc_no', 'like', "%$s%"))
                  ->orWhere('course_name', 'like', "%$s%");
        }
        if ($request->filled('type')) $query->where('course_type', $request->type);

        $courses      = $query->latest()->paginate(20)->withQueryString();
        $courseTypes  = OfficerCourse::types();
        return view('courses.index', compact('courses', 'courseTypes'));
    }

    public function create(Request $request)
    {
        $officer     = $request->filled('officer_id') ? Officer::findOrFail($request->officer_id) : null;
        $officers    = Officer::orderBy('name')->get(['id', 'name', 'svc_no', 'rank']);
        $courseTypes = OfficerCourse::types();
        return view('courses.create', compact('officer', 'officers', 'courseTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'officer_id'  => 'required|exists:officers,id',
            'course_type' => 'required|string',
            'course_name' => 'nullable|string|max:150',
            'date'        => 'nullable|date',
            'grade'       => 'nullable|string|max:20',
        ]);

        OfficerCourse::create($request->except(['_token']));

        return redirect()->route('officers.show', $request->officer_id)
                         ->with('success', 'Course record added successfully.');
    }

    public function edit(OfficerCourse $course)
    {
        $officers    = Officer::orderBy('name')->get(['id', 'name', 'svc_no', 'rank']);
        $courseTypes = OfficerCourse::types();
        return view('courses.edit', compact('course', 'officers', 'courseTypes'));
    }

    public function update(Request $request, OfficerCourse $course)
    {
        $request->validate([
            'course_type' => 'required|string',
        ]);
        $course->update($request->except(['_token', '_method']));
        return redirect()->route('officers.show', $course->officer_id)->with('success', 'Course updated.');
    }

    public function destroy(OfficerCourse $course)
    {
        $officerId = $course->officer_id;
        $course->delete();
        return redirect()->route('officers.show', $officerId)->with('success', 'Course deleted.');
    }
}
