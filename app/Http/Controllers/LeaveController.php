<?php
namespace App\Http\Controllers;
use App\Exports\LeavesExport;
use App\Models\Officer;
use App\Models\OfficerLeave;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LeaveController extends Controller {
    public function index(Request $request) {
        $query = OfficerLeave::with('officer');
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('type'))   $query->where('leave_type', $request->type);
        $leaves = $query->latest()->paginate(20)->withQueryString();

        // Calendar data for current/requested month
        $calYear  = (int)$request->get('cal_year',  now()->year);
        $calMonth = (int)$request->get('cal_month', now()->month);
        $calMonth = max(1, min(12, $calMonth));
        $calLeaves = OfficerLeave::with('officer')
            ->where('status', '!=', 'rejected')
            ->where(function($q) use ($calYear, $calMonth) {
                $start = \Carbon\Carbon::create($calYear, $calMonth, 1);
                $end   = $start->copy()->endOfMonth();
                $q->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end])
                  ->orWhere(fn($q2) => $q2->where('start_date','<=',$start)->where('end_date','>=',$end));
            })
            ->get();

        return view('leaves.index', [
            'leaves'    => $leaves,
            'types'     => OfficerLeave::types(),
            'filters'   => $request->only(['status','type']),
            'calLeaves' => $calLeaves,
            'calYear'   => $calYear,
            'calMonth'  => $calMonth,
        ]);
    }
    public function create(Request $request) {
        $officer  = $request->filled('officer_id') ? Officer::findOrFail($request->officer_id) : null;
        $officers = Officer::where('servng_rtd','S')->orderBy('name')->get(['id','name','svc_no','rank']);
        return view('leaves.create', ['officer' => $officer, 'officers' => $officers, 'types' => OfficerLeave::types()]);
    }
    public function store(Request $request) {
        $request->validate(['officer_id'=>'required|exists:officers,id','leave_type'=>'required','start_date'=>'required|date','end_date'=>'required|date|after_or_equal:start_date']);
        $days = \Carbon\Carbon::parse($request->start_date)->diffInDays($request->end_date) + 1;
        OfficerLeave::create(array_merge($request->except(['_token']), ['days' => $days, 'created_by' => auth()->id()]));
        return redirect()->route('officers.show', $request->officer_id)->with('success', 'Leave recorded.');
    }
    public function update(Request $request, OfficerLeave $leave) {
        $leave->update($request->only(['status','approved_by','approved_date','remarks']));
        return redirect()->route('leaves.index')->with('success', 'Leave status updated.');
    }
    public function destroy(OfficerLeave $leave) {
        $id = $leave->officer_id; $leave->delete();
        return redirect()->route('officers.show', $id)->with('success', 'Leave deleted.');
    }

    public function export(Request $request)
    {
        $filename = 'leave-records-' . now()->format('Ymd-His') . '.xlsx';
        return Excel::download(new LeavesExport($request->only(['status','type'])), $filename);
    }
}
