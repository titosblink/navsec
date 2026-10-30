<?php

namespace App\Console\Commands;

use App\Models\Officer;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SendRodAlerts extends Command
{
    protected $signature   = 'nn:rod-alerts {--dry-run : List officers without sending emails}';
    protected $description = 'Send ROD / retirement alert emails for officers within 6 months';

    public function handle(): int
    {
        $today     = Carbon::today();
        $sixMonths = $today->copy()->addMonths(6);

        $officers = Officer::where('servng_rtd', 'S')
            ->where(function ($q) use ($today, $sixMonths) {
                $q->whereBetween('rod',        [$today, $sixMonths])
                  ->orWhereBetween('retired_dt', [$today, $sixMonths])
                  ->orWhereBetween('term_leave', [$today, $sixMonths]);
            })
            ->orderByRaw('COALESCE(rod, retired_dt, term_leave) ASC')
            ->get();

        if ($officers->isEmpty()) {
            $this->info('No officers approaching separation within 6 months.');
            return 0;
        }

        $this->table(
            ['Name', 'Rank', 'Svc No', 'Alert Date', 'Days Left'],
            $officers->map(function ($o) use ($today) {
                $date = collect([$o->rod, $o->retired_dt, $o->term_leave])->filter()->sort()->first();
                return [$o->name, $o->rank, $o->svc_no, $date?->format('d M Y'), $today->diffInDays($date)];
            })
        );

        if ($this->option('dry-run')) {
            $this->info('Dry run — no emails sent.');
            return 0;
        }

        // Send to admin users who have an email address
        $adminEmails = \App\Models\User::where('role', 'admin')->pluck('email');
        if ($adminEmails->isEmpty()) {
            $this->warn('No admin users found to notify.');
            return 0;
        }

        $body = "The following officers are approaching separation within 6 months:\n\n";
        foreach ($officers as $o) {
            $date = collect([$o->rod, $o->retired_dt, $o->term_leave])->filter()->sort()->first();
            $days = $today->diffInDays($date);
            $body .= "- {$o->rank} {$o->name} ({$o->svc_no}) — {$date?->format('d M Y')} ({$days} days)\n";
        }

        foreach ($adminEmails as $email) {
            Mail::raw($body, function ($m) use ($email, $officers) {
                $m->to($email)
                  ->subject('[NN Personnel] ROD / Retirement Alert — ' . $officers->count() . ' officer(s)');
            });
        }

        $this->info("Alert sent to " . $adminEmails->count() . " admin(s) for {$officers->count()} officer(s).");
        return 0;
    }
}
