@extends('layouts.app')
@section('title', 'Edit Officer')
@section('header', 'Edit Officer — ' . $officer->name)
@section('breadcrumb', 'Officers / Edit')

@section('header-actions')
<a href="{{ route('officers.show', $officer) }}"
   class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Profile
</a>
@endsection

@section('content')

{{-- ── Profile completeness banner ── --}}
@php
    $pct = $officer->completeness();
    $allFields = ['name','svc_no','rank','branch','dt_birth','doe','date_comm','snrty_date',
                  'pres_ship','pre_appt','command','state_orig','lga','gender','blood_grup',
                  'religion','e_mail_add','tel_no','pre_addr','next_kin','nok_tel_no','photo'];
    $fieldLabels = [
        'name'=>'Full Name','svc_no'=>'Service Number','rank'=>'Rank','branch'=>'Branch',
        'dt_birth'=>'Date of Birth','doe'=>'Date of Entry','date_comm'=>'Date Commissioned',
        'snrty_date'=>'Seniority Date','pres_ship'=>'Present Ship','pre_appt'=>'Current Appointment',
        'command'=>'Command','state_orig'=>'State of Origin','lga'=>'LGA','gender'=>'Gender',
        'blood_grup'=>'Blood Group','religion'=>'Religion','e_mail_add'=>'Email','tel_no'=>'Tel No',
        'pre_addr'=>'Address','next_kin'=>'Next of Kin','nok_tel_no'=>'NOK Tel No','photo'=>'Photo',
    ];
    $missing = collect($allFields)->filter(fn($f) => empty($officer->$f))->map(fn($f) => $fieldLabels[$f] ?? $f)->values();
@endphp

@if($pct < 100)
<div class="mb-4 {{ $pct >= 80 ? 'bg-green-50 border-green-200' : ($pct >= 50 ? 'bg-amber-50 border-amber-200' : 'bg-red-50 border-red-200') }} border rounded-xl p-4">
    <div class="flex items-start gap-3">
        <i class="fas {{ $pct >= 80 ? 'fa-circle-check text-green-500' : ($pct >= 50 ? 'fa-triangle-exclamation text-amber-500' : 'fa-circle-exclamation text-red-500') }} text-lg mt-0.5"></i>
        <div class="flex-1">
            <div class="flex items-center justify-between mb-1">
                <p class="text-sm font-semibold {{ $pct >= 80 ? 'text-green-800' : ($pct >= 50 ? 'text-amber-800' : 'text-red-800') }}">
                    Profile {{ $pct }}% complete
                </p>
            </div>
            <div class="w-full bg-white/60 rounded-full h-1.5 mb-2">
                <div class="h-1.5 rounded-full {{ $pct >= 80 ? 'bg-green-500' : ($pct >= 50 ? 'bg-amber-400' : 'bg-red-400') }}" style="width:{{ $pct }}%"></div>
            </div>
            @if($missing->count() > 0)
            <p class="text-xs {{ $pct >= 80 ? 'text-green-700' : ($pct >= 50 ? 'text-amber-700' : 'text-red-700') }}">
                Missing: {{ $missing->implode(', ') }}
            </p>
            @endif
        </div>
    </div>
</div>
@endif

<form method="POST" action="{{ route('officers.update', $officer) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('components.officer-form')
    <div class="mt-4 flex items-center gap-3">
        <button type="submit" class="bg-[#0a1640] hover:bg-[#071230] text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
            <i class="fas fa-save mr-2"></i>Update Officer
        </button>
        <a href="{{ route('officers.show', $officer) }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
            Cancel
        </a>
    </div>
</form>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
    $('.select2').select2({ width: '100%' });

    // ── Scroll to step that has validation errors ──
    const errorFields = @json($errors->keys());
    if (errorFields.length === 0) return;

    const stepMap = {
        1: ['svc_no','name','rank','branch','spl','initials','type_comm','course_cod','servng_rtd','conversion','conve_code','sep_code'],
        2: ['doe','dt_birth','dt_cdt_trg_started','date_comm','snrty_date','dolp'],
        3: ['mid_ship','ag_slt','slt','lt','ltcdr','cdr','capt','cdre','radm','vadm','adm'],
        4: ['pres_ship','command','pre_appt','appt_date','date_joined_ship','r_cieling'],
        5: ['gender','blood_grup','religion','state_orig','lga','geo_p_zone','home_town','e_mail_add','tel_no','pre_addr'],
        6: ['names1','child_1','child_2','child_3','child_4','next_kin','relatnship','nok_add','nok_tel_no','nok_email','alt_nok','alt_nokrel','alt_nok_add','alt_phone','alt_email'],
        7: ['rod','retired_dt','term_leave','sea_time','status','exam_fail','dis_cases','med_cases','msion_area','msion_date','d_ment'],
    };

    let targetStep = 1;
    for (const [step, fields] of Object.entries(stepMap)) {
        if (errorFields.some(f => fields.includes(f))) {
            targetStep = parseInt(step);
            break;
        }
    }

    if (typeof goToStep === 'function') {
        goToStep(targetStep);
        setTimeout(() => window.scrollTo({ top: 0, behavior: 'smooth' }), 100);
    }
});
</script>
@endsection
