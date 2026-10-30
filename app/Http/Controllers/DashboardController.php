<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Officer;
use App\Models\OfficerAppointment;
use App\Models\OfficerCourse;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'    => Officer::count(),
            'serving'  => Officer::where('servng_rtd', 'S')->count(),
            'retired'  => Officer::where('servng_rtd', 'R')->count(),
            'postings' => OfficerAppointment::whereMonth('date_from', now()->month)->count(),
            'courses'  => OfficerCourse::whereYear('date', now()->year)->count(),
        ];

        $byRank = Officer::where('servng_rtd', 'S')
            ->selectRaw('rank, count(*) as total')
            ->groupBy('rank')
            ->orderByRaw("FIELD(rank,'ADM','VADM','RADM','CDRE','CAPT','CDR','LT/CDR','LT','SLT','AG/SLT','MIDN')")
            ->get();

        $recentOfficers = Officer::latest()->limit(5)->get();
        $recentPostings = OfficerAppointment::with('officer')->latest()->limit(5)->get();

        // ── Alerts: officers whose ROD or retirement date is within 6 months ──
        $today       = Carbon::today();
        $sixMonths   = $today->copy()->addMonths(6);
        $threeMonths = $today->copy()->addMonths(3);
        $oneMonth    = $today->copy()->addMonth();

        $alerts = Officer::where('servng_rtd', 'S')
            ->where(function ($q) use ($today, $sixMonths) {
                $q->whereBetween('rod', [$today, $sixMonths])
                  ->orWhereBetween('retired_dt', [$today, $sixMonths])
                  ->orWhereBetween('term_leave', [$today, $sixMonths]);
            })
            ->orderByRaw('COALESCE(rod, retired_dt, term_leave) ASC')
            ->get()
            ->map(function ($o) use ($today, $oneMonth, $threeMonths) {
                $date = collect([$o->rod, $o->retired_dt, $o->term_leave])
                    ->filter()->sort()->first();

                $days = $today->diffInDays($date, false);

                $o->_alert_date  = $date;
                $o->_alert_days  = $days;
                $o->_alert_label = $o->rod && $o->rod->eq($date) ? 'Run Out Date'
                    : ($o->retired_dt && $o->retired_dt->eq($date) ? 'Retirement Date' : 'Terminal Leave');
                $o->_alert_level = $days <= $oneMonth->diffInDays($today)    ? 'red'
                    : ($days <= $threeMonths->diffInDays($today) ? 'amber' : 'yellow');

                return $o;
            });

        $activityFeed = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'byRank', 'recentOfficers', 'recentPostings', 'alerts', 'activityFeed'));
    }
}
