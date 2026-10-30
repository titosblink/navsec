@extends('layouts.app')
@section('title', 'Add Officer')
@section('header', 'Add New Officer')
@section('breadcrumb', 'Officers / New')

@section('header-actions')
<a href="{{ route('officers.index') }}"
   class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Officers
</a>
@endsection

@section('content')
<form method="POST" action="{{ route('officers.store') }}" enctype="multipart/form-data">
    @csrf
    @include('components.officer-form')
    <div class="mt-4 flex items-center gap-3">
        <button type="submit" class="bg-[#0a1640] hover:bg-[#071230] text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
            <i class="fas fa-save mr-2"></i>Save Officer
        </button>
        <a href="{{ route('officers.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50 transition-colors">
            Cancel
        </a>
    </div>
</form>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
    $('.select2').select2({ width: '100%' });

    const errorFields = @json($errors->keys());
    if (!errorFields.length) return;

    const stepMap = {
        1: ['svc_no','name','rank','branch','spl','initials','type_comm','course_cod','servng_rtd'],
        2: ['doe','dt_birth','dt_cdt_trg_started','date_comm','snrty_date','dolp'],
        3: ['mid_ship','ag_slt','slt','lt','ltcdr','cdr','capt','cdre','radm','vadm','adm'],
        4: ['pres_ship','command','pre_appt','appt_date','date_joined_ship','r_cieling'],
        5: ['gender','blood_grup','religion','state_orig','lga','geo_p_zone','home_town','e_mail_add','tel_no','pre_addr'],
        6: ['names1','child_1','child_2','child_3','child_4','next_kin','relatnship','nok_add','nok_tel_no','nok_email','alt_nok'],
        7: ['rod','retired_dt','term_leave','sea_time','status','exam_fail','dis_cases','med_cases'],
    };

    for (const [step, fields] of Object.entries(stepMap)) {
        if (errorFields.some(f => fields.includes(f))) {
            if (typeof goToStep === 'function') goToStep(parseInt(step));
            break;
        }
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>
@endsection
