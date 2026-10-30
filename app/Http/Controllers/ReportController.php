<?php

namespace App\Http\Controllers;

use App\Exports\CoursesExport;
use App\Exports\OfficersExport;
use App\Exports\PostingsExport;
use App\Exports\PromotionEligibilityExport;
use App\Exports\SeniorityExport;
use App\Models\AppSetting;
use App\Models\Officer;
use App\Models\OfficerAppointment;
use App\Models\OfficerCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    // ── Officers report ──
    public function officers(Request $request)
    {
        $filters  = $request->only(['rank','branch','status','ship','search']);
        $query    = Officer::query();
        if (!empty($filters['rank']))   $query->where('rank',      $filters['rank']);
        if (!empty($filters['branch'])) $query->where('branch',    $filters['branch']);
        if (!empty($filters['status'])) $query->where('servng_rtd',$filters['status']);
        if (!empty($filters['ship']))   $query->where('pres_ship','like','%'.$filters['ship'].'%');
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(fn($q) => $q->where('name','like',"%$s%")->orWhere('svc_no','like',"%$s%"));
        }
        $officers = $query->orderBy('rank')->orderBy('name')->get();
        return view('reports.officers', ['officers'=>$officers,'ranks'=>Officer::ranks(),'branches'=>Officer::branches(),'filters'=>$filters]);
    }

    public function exportOfficers(Request $request)
    {
        return Excel::download(new OfficersExport($request->only(['rank','branch','status','search'])), 'officers-'.now()->format('Ymd-His').'.xlsx');
    }

    // ── Postings report ──
    public function postings(Request $request)
    {
        $query = OfficerAppointment::with(['officer' => fn($q) => $q->withTrashed()]);
        if ($request->filled('date_from')) $query->where('date_from','>=',$request->date_from);
        if ($request->filled('date_to'))   $query->where('date_from','<=',$request->date_to);
        if ($request->filled('type'))      $query->where('type',$request->type);
        $postings = $query->latest('date_from')->get();
        return view('reports.postings', compact('postings'));
    }

    public function exportPostings(Request $request)
    {
        return Excel::download(new PostingsExport($request->only(['date_from','date_to','type'])), 'postings-'.now()->format('Ymd-His').'.xlsx');
    }

    // ── Courses report ──
    public function courses(Request $request)
    {
        $query = OfficerCourse::with(['officer' => fn($q) => $q->withTrashed()]);
        if ($request->filled('course_type')) $query->where('course_type',$request->course_type);
        if ($request->filled('year'))        $query->whereYear('date',$request->year);
        $courses     = $query->latest('date')->get();
        $courseTypes = OfficerCourse::types();
        return view('reports.courses', compact('courses','courseTypes'));
    }

    public function exportCourses(Request $request)
    {
        return Excel::download(new CoursesExport($request->only(['course_type','year'])), 'courses-'.now()->format('Ymd-His').'.xlsx');
    }

    // ── Seniority list ──
    public function seniority(Request $request)
    {
        $rank  = $request->get('rank', '');
        $query = Officer::where('servng_rtd','S')
            ->orderByRaw("FIELD(rank,'ADM','VADM','RADM','CDRE','CAPT','CDR','LT/CDR','LT','SLT','AG/SLT','MIDN')")
            ->orderByRaw('snrty_date IS NULL, snrty_date ASC')   // 1st: seniority date (NULLs last)
            ->orderByRaw('dolp IS NULL, dolp ASC')               // 2nd: date of last promotion
            ->orderByRaw('date_comm IS NULL, date_comm ASC')     // 3rd: date commissioned
            ->orderByRaw('doe IS NULL, doe ASC')                 // 4th: date of entry
            ->orderBy('svc_no');                                  // final: service number
        if ($rank) $query->where('rank', $rank);
        $officers = $query->get();
        return view('reports.seniority', ['officers'=>$officers,'ranks'=>Officer::ranks(),'selectedRank'=>$rank]);
    }

    public function exportSeniority(Request $request)
    {
        $rank     = $request->get('rank', '');
        $suffix   = $rank ? '-' . strtolower(str_replace('/', '', $rank)) : '';
        $filename = 'seniority-list' . $suffix . '-' . now()->format('Ymd') . '.xlsx';
        return Excel::download(new SeniorityExport($rank), $filename);
    }

    // ── Retirement / ROD report ──
    public function retirement(Request $request)
    {
        $months = (int) $request->get('months', 6);
        $today  = \Illuminate\Support\Carbon::today();
        $cutoff = $today->copy()->addMonths($months);

        $officers = Officer::where('servng_rtd','S')
            ->where(function($q) use ($today,$cutoff) {
                $q->whereBetween('rod',        [$today,$cutoff])
                  ->orWhereBetween('retired_dt', [$today,$cutoff])
                  ->orWhereBetween('term_leave', [$today,$cutoff]);
            })
            ->orderByRaw('COALESCE(rod,retired_dt,term_leave) ASC')
            ->get()
            ->map(function($o) use ($today) {
                $date = collect([$o->rod,$o->retired_dt,$o->term_leave])->filter()->sort()->first();
                $o->_sep_date  = $date;
                $o->_sep_days  = $today->diffInDays($date,false);
                $o->_sep_label = $o->rod && $o->rod->eq($date)?'ROD':($o->retired_dt && $o->retired_dt->eq($date)?'Retirement':'Terminal Leave');
                return $o;
            });

        return view('reports.retirement', compact('officers','months'));
    }

    // ── Promotion eligibility ──
    public function promotionEligibility()
    {
        [$eligible, $minYears] = $this->buildEligibilityList();
        return view('reports.promotion_eligibility', compact('eligible', 'minYears'));
    }

    public function exportPromotionEligibility()
    {
        [$eligible] = $this->buildEligibilityList();
        return Excel::download(
            new PromotionEligibilityExport($eligible),
            'promotion-eligibility-' . now()->format('Ymd') . '.xlsx'
        );
    }

    private function buildEligibilityList(): array
    {
        $minYears = AppSetting::minYearsInRank();
        $today    = \Illuminate\Support\Carbon::today();

        $eligible = Officer::where('servng_rtd', 'S')
            ->whereNotNull('dolp')
            ->whereIn('rank', array_keys($minYears))
            ->get()
            ->map(function ($o) use ($today, $minYears) {
                $o->_years_in_rank = $today->diffInYears($o->dolp);
                $o->_min_years     = $minYears[$o->rank] ?? 99;
                return $o;
            })
            ->filter(fn($o) => $o->_years_in_rank >= $o->_min_years)
            ->sortByDesc('_years_in_rank');

        return [$eligible, $minYears];
    }

    // ── Statistics ──
    public function statistics()
    {
        $serving = Officer::where('servng_rtd','S');

        $byRank = Officer::where('servng_rtd','S')
            ->selectRaw('rank, count(*) as total')->groupBy('rank')
            ->orderByRaw("FIELD(rank,'ADM','VADM','RADM','CDRE','CAPT','CDR','LT/CDR','LT','SLT','AG/SLT','MIDN')")
            ->pluck('total','rank');

        $byBranch = Officer::where('servng_rtd','S')
            ->selectRaw('branch, count(*) as total')->groupBy('branch')
            ->orderByDesc('total')->pluck('total','branch');

        $byZone = Officer::where('servng_rtd','S')
            ->selectRaw('geo_p_zone, count(*) as total')->groupBy('geo_p_zone')
            ->orderByDesc('total')->pluck('total','geo_p_zone');

        $byCommand = Officer::where('servng_rtd','S')
            ->selectRaw('command, count(*) as total')->groupBy('command')
            ->orderByDesc('total')->pluck('total','command');

        // Age distribution buckets
        $today = Carbon::today();
        $ageGroups = ['<30'=>0,'30-35'=>0,'36-40'=>0,'41-45'=>0,'46-50'=>0,'>50'=>0];
        Officer::where('servng_rtd','S')->whereNotNull('dt_birth')->get(['dt_birth'])->each(function($o) use (&$ageGroups, $today) {
            $age = $today->diffInYears($o->dt_birth);
            if     ($age < 30)  $ageGroups['<30']++;
            elseif ($age <= 35) $ageGroups['30-35']++;
            elseif ($age <= 40) $ageGroups['36-40']++;
            elseif ($age <= 45) $ageGroups['41-45']++;
            elseif ($age <= 50) $ageGroups['46-50']++;
            else                $ageGroups['>50']++;
        });

        // Retirement forecast (next 5 years)
        $retirementForecast = [];
        for ($y = now()->year; $y <= now()->year + 4; $y++) {
            $retirementForecast[$y] = Officer::whereYear('retired_dt', $y)->orWhereYear('rod', $y)->count();
        }

        return view('reports.statistics', compact('byRank','byBranch','byZone','byCommand','ageGroups','retirementForecast'));
    }
}
