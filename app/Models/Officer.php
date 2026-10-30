<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Officer extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'dolp' => 'date', 'appt_date' => 'date', 'date_joined_ship' => 'date',
        'snrty_date' => 'date', 'doe' => 'date', 'dt_birth' => 'date',
        'dt_cdt_trg_started' => 'date', 'date_comm' => 'date',
        'mid_ship' => 'date', 'ag_slt' => 'date', 'slt' => 'date',
        'lt' => 'date', 'ltcdr' => 'date', 'cdr' => 'date',
        'capt' => 'date', 'cdre' => 'date', 'radm' => 'date',
        'vadm' => 'date', 'adm' => 'date', 'rod' => 'date',
        'term_leave' => 'date', 'retired_dt' => 'date',
        'msion_date' => 'date',
    ];

    public function appointments()
    {
        return $this->hasMany(OfficerAppointment::class)->orderByDesc('date_from');
    }

    public function currentAppointment()
    {
        return $this->hasOne(OfficerAppointment::class)->where('is_current', true);
    }

    public function courses()
    {
        return $this->hasMany(OfficerCourse::class)->orderByDesc('date');
    }

    public function promotions() { return $this->hasMany(OfficerPromotion::class)->orderByDesc('effective_date'); }
    public function acrs() { return $this->hasMany(OfficerAcr::class)->orderByDesc('report_year'); }
    public function leaves() { return $this->hasMany(OfficerLeave::class)->orderByDesc('start_date'); }

    public function leaveBalance(int $year = null): array {
        $year = $year ?? date('Y');
        $used = $this->leaves()->where('status','!=','rejected')->whereYear('start_date',$year)->get()->groupBy('leave_type')->map(fn($g) => $g->sum('days'));
        $defaults = \App\Models\LeaveEntitlement::defaultEntitlements();
        $balance = [];
        foreach ($defaults as $type => $entitled) {
            $entitledDays = \App\Models\LeaveEntitlement::getEntitlement($this->id, $year, $type);
            $usedDays = $used[$type] ?? 0;
            $balance[$type] = ['entitled'=>$entitledDays,'used'=>$usedDays,'remaining'=>max(0,$entitledDays-$usedDays)];
        }
        return $balance;
    }

    public function completeness(): int {
        $fields = ['name','svc_no','rank','branch','dt_birth','doe','date_comm','snrty_date',
                   'pres_ship','pre_appt','command','state_orig','lga','gender','blood_grup',
                   'religion','e_mail_add','tel_no','pre_addr','next_kin','nok_tel_no','photo'];
        $filled = collect($fields)->filter(fn($f) => !empty($this->$f))->count();
        return (int) round($filled / count($fields) * 100);
    }
    public function disciplines() { return $this->hasMany(OfficerDiscipline::class)->orderByDesc('date_of_trial'); }
    public function documents() { return $this->hasMany(OfficerDocument::class)->latest(); }
    public function medicals() { return $this->hasMany(OfficerMedical::class)->orderByDesc('exam_date'); }
    public function qualifications() { return $this->hasMany(OfficerQualification::class)->orderByDesc('year_obtained'); }

    public static function ranks(): array
    {
        return ['MIDN', 'AG/SLT', 'SLT', 'LT', 'LT/CDR', 'CDR', 'CAPT', 'CDRE', 'RADM', 'VADM', 'ADM'];
    }

    public static function rankBadgeClass(string $rank): string
    {
        if (in_array($rank, ['CDRE', 'RADM', 'VADM', 'ADM'])) return 'bg-red-700';
        if (in_array($rank, ['LT/CDR', 'CDR', 'CAPT']))        return 'bg-amber-600';
        return 'bg-[#0b1d55]';
    }

    public static function rankFullNames(): array
    {
        return [
            'MIDN'   => 'Midshipman',
            'AG/SLT' => 'Acting Sub-Lieutenant',
            'SLT'    => 'Sub-Lieutenant',
            'LT'     => 'Lieutenant',
            'LT/CDR' => 'Lieutenant Commander',
            'CDR'    => 'Commander',
            'CAPT'   => 'Captain',
            'CDRE'   => 'Commodore',
            'RADM'   => 'Rear Admiral',
            'VADM'   => 'Vice Admiral',
            'ADM'    => 'Admiral',
        ];
    }

    public static function branches(): array
    {
        return [
            'X'   => 'Executive',
            'ENG' => 'Engineering',
            'S'   => 'Supply',
            'M'   => 'Medical',
            'D'   => 'Dental',
            'L'   => 'Legal',
            'ED'  => 'Education',
            'CH'  => 'Chaplaincy',
            'NI'  => 'Naval Intelligence',
            'W'   => 'Warfare / Weapons',
            'H'   => 'Hydrography',
            'C'   => 'Communications / Signals',
            'NA'  => 'Naval Aviation',
        ];
    }

    public static function nigerianStates(): array
    {
        return array_keys(config('nigeria_lgas'));
    }

    public function photoUrl(): ?string
    {
        return $this->photo ? asset('uploads/officers/' . $this->photo) : null;
    }

    public function nokPhotoUrl(): ?string
    {
        return $this->nok_photo ? asset('uploads/officers/' . $this->nok_photo) : null;
    }
}
