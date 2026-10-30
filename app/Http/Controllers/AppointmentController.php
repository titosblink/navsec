<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\OfficerAppointment;
use App\Models\Ship;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = OfficerAppointment::with('officer');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('officer', fn($q) => $q->where('name', 'like', "%$s%")
                                                      ->orWhere('svc_no', 'like', "%$s%"))
                  ->orWhere('appointment', 'like', "%$s%")
                  ->orWhere('ship', 'like', "%$s%");
        }
        if ($request->filled('type')) $query->where('type', $request->type);

        $appointments = $query->latest()->paginate(20)->withQueryString();
        return view('appointments.index', compact('appointments'));
    }

    public function create(Request $request)
    {
        $officer = $request->filled('officer_id') ? Officer::findOrFail($request->officer_id) : null;
        $officers = Officer::where('servng_rtd', 'S')->orderBy('name')->get(['id', 'name', 'svc_no', 'rank']);
        $ships    = Ship::where('is_active', true)->orderBy('name')->get();
        return view('appointments.create', compact('officer', 'officers', 'ships'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'officer_id'  => 'required|exists:officers,id',
            'appointment' => 'required|string|max:150',
            'type'        => 'required|in:appointment,course',
            'date_from'   => 'nullable|date',
            'date_to'     => 'nullable|date|after_or_equal:date_from',
        ]);

        // End previous current appointment if marking as current
        if ($request->boolean('is_current')) {
            OfficerAppointment::where('officer_id', $request->officer_id)
                              ->where('is_current', true)
                              ->update(['is_current' => false, 'date_to' => $request->date_from]);

            Officer::where('id', $request->officer_id)->update([
                'pre_appt'         => $request->appointment,
                'pres_ship'        => $request->ship,
                'command'          => $request->command,
                'appt_date'        => $request->date_from,
                'date_joined_ship' => $request->date_from,
            ]);
        }

        OfficerAppointment::create($request->except(['_token']));

        return redirect()->route('officers.show', $request->officer_id)
                         ->with('success', 'Appointment / posting recorded successfully.');
    }

    public function edit(OfficerAppointment $appointment)
    {
        $officers = Officer::orderBy('name')->get(['id', 'name', 'svc_no', 'rank']);
        $ships    = Ship::where('is_active', true)->orderBy('name')->get();
        return view('appointments.edit', compact('appointment', 'officers', 'ships'));
    }

    public function update(Request $request, OfficerAppointment $appointment)
    {
        $request->validate([
            'appointment' => 'required|string|max:150',
            'type'        => 'required|in:appointment,course',
        ]);
        $appointment->update($request->except(['_token', '_method']));
        return redirect()->route('officers.show', $appointment->officer_id)
                         ->with('success', 'Appointment updated.');
    }

    public function destroy(OfficerAppointment $appointment)
    {
        $officerId = $appointment->officer_id;
        $appointment->delete();
        return redirect()->route('officers.show', $officerId)->with('success', 'Appointment deleted.');
    }
}
