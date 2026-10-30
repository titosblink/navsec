<?php
namespace App\Http\Controllers;
use App\Models\Officer;
use App\Models\OfficerLeave;
use Illuminate\Support\Carbon;

class NotificationController extends Controller {
    public function index() {
        return response()->json($this->getNotifications());
    }

    public static function getNotifications(): array {
        $today = Carbon::today();
        $items = [];

        // Pending leave approvals
        $pending = OfficerLeave::where('status','pending')->count();
        if ($pending > 0) {
            $items[] = ['type'=>'leave','icon'=>'fa-calendar-check','color'=>'text-amber-500',
                       'title'=>"{$pending} leave request".($pending>1?'s':'')." pending approval",'url'=>route('leaves.index').'?status=pending'];
        }

        // ROD within 30 days
        $urgent = Officer::where('servng_rtd','S')
            ->where(fn($q)=>$q->whereBetween('rod',[$today,$today->copy()->addDays(30)])
                              ->orWhereBetween('retired_dt',[$today,$today->copy()->addDays(30)]))
            ->count();
        if ($urgent > 0) {
            $items[] = ['type'=>'rod','icon'=>'fa-triangle-exclamation','color'=>'text-red-500',
                       'title'=>"{$urgent} officer".($urgent>1?'s':'')." with ROD within 30 days",'url'=>route('reports.seniority')];
        }

        // Officers within 6 months
        $approaching = Officer::where('servng_rtd','S')
            ->where(fn($q)=>$q->whereBetween('rod',[$today,$today->copy()->addMonths(6)])
                              ->orWhereBetween('retired_dt',[$today,$today->copy()->addMonths(6)]))
            ->count();
        if ($approaching > $urgent) {
            $items[] = ['type'=>'alert','icon'=>'fa-clock','color'=>'text-amber-500',
                       'title'=>"{$approaching} officer".($approaching>1?'s':'')." approaching separation",'url'=>route('dashboard')];
        }

        return $items;
    }

    public static function count(): int {
        return count(static::getNotifications());
    }
}
