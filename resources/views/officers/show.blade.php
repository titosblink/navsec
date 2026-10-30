@extends('layouts.app')
@section('title', $officer->name)
@section('header', ($officer->rank ? $officer->rank . ' ' : '') . $officer->name)
@section('breadcrumb', 'Officers / ' . $officer->svc_no)

@php
    $rankBadge = \App\Models\Officer::rankBadgeClass($officer->rank ?? '');
    $rankNames = \App\Models\Officer::rankFullNames();
    $canEdit   = in_array(auth()->user()->role, ['admin','hr']);
@endphp

@section('header-actions')
@if($canEdit)
<a href="{{ route('promotions.create', $officer) }}"
   class="bg-amber-500 hover:bg-amber-600 text-white text-sm px-3 py-2 rounded-lg flex items-center gap-2 transition-colors">
    <i class="fas fa-star"></i> <span class="hidden sm:inline">Promote</span>
</a>
<div x-data="{ open:false }" class="relative">
    <button @click="open=!open" class="bg-[#0a1640] hover:bg-[#071230] text-white text-sm px-3 py-2 rounded-lg flex items-center gap-2 transition-colors">
        <i class="fas fa-plus"></i> <span class="hidden sm:inline">Add Record</span> <i class="fas fa-chevron-down text-xs ml-1"></i>
    </button>
    <div x-show="open" @click.away="open=false" x-cloak
         class="absolute right-0 mt-1 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-20">
        @foreach([
            ['route'=>'appointments.create','icon'=>'fa-right-left','label'=>'Appointment','color'=>'text-blue-500'],
            ['route'=>'courses.create','icon'=>'fa-graduation-cap','label'=>'Course','color'=>'text-purple-500'],
            ['route'=>'acrs.create','icon'=>'fa-clipboard','label'=>'ACR','color'=>'text-green-500'],
            ['route'=>'leaves.create','icon'=>'fa-calendar-check','label'=>'Leave','color'=>'text-amber-500'],
            ['route'=>'disciplines.create','icon'=>'fa-gavel','label'=>'Disciplinary Record','color'=>'text-red-500'],
            ['route'=>'documents.create','icon'=>'fa-file-upload','label'=>'Document','color'=>'text-gray-500'],
            ['route'=>'medicals.create','icon'=>'fa-heartbeat','label'=>'Medical Record','color'=>'text-pink-500'],
            ['route'=>'qualifications.create','icon'=>'fa-award','label'=>'Qualification','color'=>'text-indigo-500'],
        ] as $item)
        <a href="{{ route($item['route'], $officer) }}"
           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas {{ $item['icon'] }} {{ $item['color'] }} w-4"></i>{{ $item['label'] }}
        </a>
        @endforeach
    </div>
</div>
@endif
<a href="{{ route('officers.id-card', $officer) }}" target="_blank"
   class="border border-gray-300 text-gray-600 text-sm px-3 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-50 transition-colors">
    <i class="fas fa-id-card"></i> <span class="hidden sm:inline">ID Card</span>
</a>
@if($canEdit)
<a href="{{ route('officers.edit', $officer) }}"
   class="bg-[#0a1640] hover:bg-[#071230] text-white text-sm px-3 py-2 rounded-lg flex items-center gap-2 transition-colors">
    <i class="fas fa-edit"></i> <span class="hidden sm:inline">Edit</span>
</a>
@endif
<a href="{{ route('officers.pdf', $officer) }}"
   class="border border-gray-300 text-gray-600 text-sm px-3 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-50 transition-colors">
    <i class="fas fa-file-pdf text-red-500"></i> <span class="hidden sm:inline">PDF</span>
</a>
<button onclick="window.print()" class="border border-gray-300 text-gray-600 text-sm px-3 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-50 transition-colors">
    <i class="fas fa-print"></i> <span class="hidden sm:inline">Print</span>
</button>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ── LEFT COLUMN: Profile card ── --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm p-6 text-center">
            @if($officer->photo)
            <div class="w-24 h-24 rounded-full overflow-hidden mx-auto mb-4 border-4 border-white shadow-md">
                <img src="{{ $officer->photoUrl() }}" alt="{{ $officer->name }}" class="w-full h-full object-cover">
            </div>
            @else
            <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold shadow-md"
                 style="background:linear-gradient(135deg,#0b1d55,#1e40af);">
                {{ strtoupper(substr($officer->name ?? 'N', 0, 2)) }}
            </div>
            @endif
            <h2 class="text-gray-800 font-bold text-lg">{{ $officer->name }}</h2>
            <p class="text-gray-500 text-sm mt-0.5">
                <span class="inline-block text-xs font-bold px-2 py-0.5 rounded text-white {{ $rankBadge }}"
                      title="{{ $rankNames[$officer->rank] ?? $officer->rank }}">
                    {{ $officer->rank }}
                </span>
            </p>
            <div class="flex items-center justify-center gap-1 mt-2">
                <span class="text-blue-600 font-mono text-sm">{{ $officer->svc_no }}</span>
                <button onclick="copyToClipboard('{{ $officer->svc_no }}', this)"
                        class="text-gray-400 hover:text-blue-500 p-0.5 rounded transition-colors" title="Copy service number">
                    <i class="fas fa-copy text-xs"></i>
                </button>
            </div>
            <span class="{{ $officer->servng_rtd === 'R' ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-700' }} text-xs px-3 py-1 rounded-full font-medium inline-block mt-2">
                {{ $officer->servng_rtd === 'R' ? 'Retired' : 'Serving' }}
            </span>
            @php $pct = $officer->completeness(); @endphp
            <div class="mt-3 text-left px-2">
                <div class="flex justify-between text-xs text-gray-500 mb-1">
                    <span>Profile completeness</span>
                    <span class="font-semibold {{ $pct >= 80 ? 'text-green-600' : ($pct >= 50 ? 'text-amber-600' : 'text-red-500') }}">{{ $pct }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full {{ $pct >= 80 ? 'bg-green-500' : ($pct >= 50 ? 'bg-amber-400' : 'bg-red-400') }}" style="width:{{ $pct }}%"></div>
                </div>
                @if($pct < 100 && $canEdit)
                <a href="{{ route('officers.edit', $officer) }}" class="text-xs text-blue-500 hover:underline mt-1 inline-block">Complete profile →</a>
                @endif
            </div>

            {{-- Retirement countdown --}}
            @php
                $sepDate = collect([$officer->rod, $officer->retired_dt, $officer->term_leave])->filter()->sort()->first();
                $daysLeft = $sepDate ? now()->diffInDays($sepDate, false) : null;
            @endphp
            @if($daysLeft !== null && $daysLeft <= 365 && $officer->servng_rtd === 'S')
            <div class="mt-3 rounded-lg px-3 py-2 text-xs {{ $daysLeft <= 30 ? 'bg-red-50 text-red-700' : ($daysLeft <= 90 ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700') }}">
                <i class="fas fa-hourglass-half mr-1"></i>
                <strong>{{ $daysLeft > 0 ? $daysLeft . ' days' : 'Overdue' }}</strong>
                until {{ $officer->rod && $officer->rod->eq($sepDate) ? 'Run Out Date' : 'Retirement' }}
                ({{ $sepDate->format('d M Y') }})
            </div>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 space-y-3">
            <h3 class="text-sm font-semibold text-gray-700 border-b pb-2">Quick Info</h3>
            @php $rows = [
                'Branch'           => $officer->branch,
                'Specialisation'   => $officer->spl,
                'Gender'           => $officer->gender === 'M' ? 'Male' : ($officer->gender === 'F' ? 'Female' : null),
                'Blood Group'      => $officer->blood_grup,
                'Religion'         => $officer->religion,
                'State of Origin'  => $officer->state_orig,
                'LGA'              => $officer->lga,
                'Home Town'        => $officer->home_town,
                'Date of Birth'    => $officer->dt_birth?->format('d M Y'),
                'Date of Entry'    => $officer->doe?->format('d M Y'),
                'Date Commissioned'=> $officer->date_comm?->format('d M Y'),
                'Sea Time'         => $officer->sea_time ? $officer->sea_time . ' months' : null,
            ]; @endphp
            @foreach($rows as $label => $val)
            @if($val)
            <div class="flex justify-between text-sm gap-2">
                <span class="text-gray-400 flex-shrink-0">{{ $label }}</span>
                <span class="text-gray-800 font-medium text-right">{{ $val }}</span>
            </div>
            @endif
            @endforeach
        </div>
    </div>

    {{-- ── RIGHT COLUMN: Tabbed content ── --}}
    <div class="lg:col-span-2" x-data="{ tab: 'overview' }">

        {{-- Tab bar --}}
        <div class="bg-white rounded-xl shadow-sm mb-4 overflow-x-auto">
            <div class="flex min-w-max border-b border-gray-100">
                @foreach([
                    ['key'=>'overview',  'icon'=>'fa-id-card',      'label'=>'Overview'],
                    ['key'=>'career',    'icon'=>'fa-star',          'label'=>'Career'],
                    ['key'=>'postings',  'icon'=>'fa-right-left',    'label'=>'Postings'],
                    ['key'=>'personal',  'icon'=>'fa-user',          'label'=>'Personal'],
                    ['key'=>'records',   'icon'=>'fa-folder-open',   'label'=>'Records'],
                ] as $t)
                <button @click="tab = '{{ $t['key'] }}'"
                        :class="tab === '{{ $t['key'] }}' ? 'border-b-2 border-blue-600 text-blue-700 font-semibold bg-blue-50/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="flex items-center gap-2 px-5 py-3 text-sm transition-colors whitespace-nowrap">
                    <i class="fas {{ $t['icon'] }} text-xs"></i>
                    {{ $t['label'] }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- ═══ TAB: Overview ═══ --}}
        <div x-show="tab === 'overview'" class="space-y-4">

            {{-- Current Posting (with inline-edit) --}}
            <div class="bg-white rounded-xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="fas fa-ship text-blue-500"></i> Current Posting
                    </h3>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    @php $postingFields = [
                        'pres_ship' => ['label'=>'Present Ship', 'type'=>'text'],
                        'command'   => ['label'=>'Command', 'type'=>'text'],
                        'pre_appt'  => ['label'=>'Appointment', 'type'=>'text'],
                        'appt_date' => ['label'=>'Appt Date', 'type'=>'date', 'val'=>$officer->appt_date?->format('d M Y'), 'raw'=>$officer->appt_date?->format('Y-m-d')],
                        'date_joined_ship' => ['label'=>'Date Joined', 'type'=>'date', 'val'=>$officer->date_joined_ship?->format('d M Y'), 'raw'=>$officer->date_joined_ship?->format('Y-m-d')],
                    ]; @endphp
                    @foreach($postingFields as $field => $meta)
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">{{ $meta['label'] }}</p>
                        @if($canEdit && in_array($field, ['pres_ship','command','pre_appt']))
                        <div class="inline-edit-field flex items-center gap-1"
                             data-field="{{ $field }}" data-officer="{{ $officer->id }}">
                            <span class="text-gray-800 font-medium quick-edit-display">
                                {{ $officer->$field ?: '—' }}
                            </span>
                            <button onclick="startInlineEdit(this)"
                                    class="edit-trigger text-gray-400 hover:text-blue-500 ml-1" title="Edit">
                                <i class="fas fa-pencil"></i>
                            </button>
                        </div>
                        @else
                        <p class="text-gray-800 font-medium">{{ $meta['val'] ?? ($officer->$field ?: '—') }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Promotion rank dates --}}
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-star text-amber-500"></i> Rank Dates
                </h3>
                @php $promos = ['MIDN'=>$officer->mid_ship,'AG/SLT'=>$officer->ag_slt,'SLT'=>$officer->slt,'LT'=>$officer->lt,'LT/CDR'=>$officer->ltcdr,'CDR'=>$officer->cdr,'CAPT'=>$officer->capt,'CDRE'=>$officer->cdre,'RADM'=>$officer->radm,'VADM'=>$officer->vadm,'ADM'=>$officer->adm]; @endphp
                @php $hasAny = collect($promos)->filter()->isNotEmpty(); @endphp
                @if($hasAny)
                <div class="flex flex-wrap gap-2">
                    @foreach($promos as $rank => $date)
                    @if($date)
                    @php $bc = \App\Models\Officer::rankBadgeClass($rank); @endphp
                    <div class="flex flex-col items-center bg-gray-50 rounded-lg px-3 py-2">
                        <span class="text-xs font-bold text-white px-2 py-0.5 rounded {{ $bc }}"
                              title="{{ $rankNames[$rank] ?? $rank }}">{{ $rank }}</span>
                        <span class="text-xs text-gray-500 mt-1">{{ $date->format('d M Y') }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
                @else
                <p class="text-sm text-gray-400">No rank dates recorded.</p>
                @endif
            </div>

            {{-- Contact --}}
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-phone text-green-500"></i> Contact
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    @if($officer->tel_no)
                    <div>
                        <p class="text-xs text-gray-400">Tel No</p>
                        @if($canEdit)
                        <div class="inline-edit-field flex items-center gap-1" data-field="tel_no" data-officer="{{ $officer->id }}">
                            <span class="text-gray-800 quick-edit-display">{{ $officer->tel_no }}</span>
                            <button onclick="startInlineEdit(this)" class="edit-trigger text-gray-400 hover:text-blue-500" title="Edit"><i class="fas fa-pencil text-xs"></i></button>
                        </div>
                        @else
                        <p class="text-gray-800">{{ $officer->tel_no }}</p>
                        @endif
                    </div>
                    @endif
                    @if($officer->e_mail_add)
                    <div>
                        <p class="text-xs text-gray-400">Email</p>
                        @if($canEdit)
                        <div class="inline-edit-field flex items-center gap-1" data-field="e_mail_add" data-officer="{{ $officer->id }}">
                            <span class="text-gray-800 quick-edit-display">{{ $officer->e_mail_add }}</span>
                            <button onclick="startInlineEdit(this)" class="edit-trigger text-gray-400 hover:text-blue-500" title="Edit"><i class="fas fa-pencil text-xs"></i></button>
                        </div>
                        @else
                        <p class="text-gray-800">{{ $officer->e_mail_add }}</p>
                        @endif
                    </div>
                    @endif
                    @if($officer->pre_addr)
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-400">Address</p>
                        <p class="text-gray-800">{{ $officer->pre_addr }}</p>
                    </div>
                    @endif
                </div>

                @if($officer->next_kin || $officer->nok_photo)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Next of Kin</p>
                    <div class="flex items-start gap-4">
                        @if($officer->nok_photo)
                        <div class="w-14 h-14 rounded-xl overflow-hidden border border-gray-200 flex-shrink-0 shadow-sm">
                            <img src="{{ $officer->nokPhotoUrl() }}" alt="NOK" class="w-full h-full object-cover">
                        </div>
                        @endif
                        <div class="grid grid-cols-1 gap-1.5 text-sm flex-1">
                            @foreach(['Name'=>$officer->next_kin,'Relationship'=>$officer->relatnship,'Tel No'=>$officer->nok_tel_no,'Email'=>$officer->nok_email] as $label => $val)
                            @if($val)
                            <div class="flex gap-2">
                                <span class="text-gray-400 text-xs w-24 flex-shrink-0 mt-0.5">{{ $label }}</span>
                                <span class="text-gray-800 font-medium text-xs">{{ $val }}</span>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- ═══ TAB: Career ═══ --}}
        <div x-show="tab === 'career'" class="space-y-4">

            {{-- Promotion History --}}
            @php $showAdmin = $canEdit; @endphp
            <div class="bg-white rounded-xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="fas fa-star text-amber-500"></i> Promotion History
                    </h3>
                    @if($showAdmin)<a href="{{ route('promotions.create',$officer) }}" class="text-xs text-blue-600 hover:underline">+ Promote</a>@endif
                </div>
                @forelse($officer->promotions as $promo)
                <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
                    <div class="flex items-center gap-1 flex-shrink-0">
                        <span class="text-xs font-bold px-1.5 py-0.5 rounded text-white {{ \App\Models\Officer::rankBadgeClass($promo->from_rank ?? '') }}"
                              title="{{ $rankNames[$promo->from_rank] ?? '' }}">{{ $promo->from_rank ?: '—' }}</span>
                        <i class="fas fa-arrow-right text-gray-300 text-xs mx-1"></i>
                        <span class="text-xs font-bold px-1.5 py-0.5 rounded text-white {{ \App\Models\Officer::rankBadgeClass($promo->to_rank ?? '') }}"
                              title="{{ $rankNames[$promo->to_rank] ?? '' }}">{{ $promo->to_rank }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800">{{ $promo->effective_date->format('d M Y') }}</p>
                        <p class="text-xs text-gray-400">{{ $promo->approved_by ?: '' }}{{ $promo->authority ? ' · '.$promo->authority : '' }}</p>
                    </div>
                    @if($showAdmin)
                    <form method="POST" action="{{ route('promotions.destroy',$promo) }}" class="inline">@csrf @method('DELETE')<button type="button" class="btn-delete text-red-400 hover:text-red-600 text-xs"><i class="fas fa-trash"></i></button></form>
                    @endif
                </div>
                @empty<p class="text-sm text-gray-400 py-2">No promotion records.</p>@endforelse
            </div>

            {{-- Courses --}}
            <div class="bg-white rounded-xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2"><i class="fas fa-graduation-cap text-purple-500"></i> Courses</h3>
                    @if($canEdit)<a href="{{ route('courses.create', ['officer_id' => $officer->id]) }}" class="text-xs text-blue-600 hover:underline">+ Add</a>@endif
                </div>
                @forelse($officer->courses as $course)
                <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
                    <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2 py-0.5 rounded w-16 text-center flex-shrink-0">{{ $course->course_type }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $course->course_name ?: $course->course_type }}</p>
                        <p class="text-xs text-gray-400">{{ $course->date?->format('d M Y') }}</p>
                    </div>
                    @if($course->grade)<span class="text-xs text-gray-600 bg-gray-100 px-2 py-0.5 rounded">{{ $course->grade }}</span>@endif
                    @if($canEdit)
                    <div class="flex gap-1">
                        <a href="{{ route('courses.edit', $course) }}" class="text-amber-400 hover:text-amber-600 text-xs"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('courses.destroy', $course) }}" class="inline">@csrf @method('DELETE')<button type="button" class="btn-delete text-red-400 hover:text-red-600 text-xs"><i class="fas fa-trash"></i></button></form>
                    </div>
                    @endif
                </div>
                @empty<p class="text-sm text-gray-400 py-2">No course records.</p>@endforelse
            </div>

            {{-- ACRs --}}
            @include('officers.partials.show-section-acr')
        </div>

        {{-- ═══ TAB: Postings ═══ --}}
        <div x-show="tab === 'postings'" class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2"><i class="fas fa-exchange-alt text-amber-500"></i> Postings & Appointments</h3>
                    @if($canEdit)<a href="{{ route('appointments.create', ['officer_id' => $officer->id]) }}" class="text-xs text-blue-600 hover:underline">+ Add Posting</a>@endif
                </div>
                @forelse($officer->appointments as $appt)
                <div class="flex items-start gap-3 py-3 border-b border-gray-50 last:border-0">
                    <div class="w-2 h-2 rounded-full mt-2 flex-shrink-0 {{ $appt->is_current ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800">{{ $appt->appointment }}</p>
                        <p class="text-xs text-gray-500">{{ $appt->ship }} @if($appt->command) · {{ $appt->command }} @endif</p>
                        <p class="text-xs text-gray-400">{{ $appt->date_from?->format('d M Y') }} @if($appt->date_to) — {{ $appt->date_to->format('d M Y') }} @endif</p>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $appt->type === 'transfer' ? 'bg-blue-100 text-blue-700' : ($appt->type === 'secondment' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600') }}">
                        {{ ucfirst($appt->type) }}
                    </span>
                    @if($canEdit)
                    <div class="flex gap-1">
                        <a href="{{ route('appointments.edit', $appt) }}" class="text-amber-400 hover:text-amber-600 text-xs"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('appointments.destroy', $appt) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-delete text-red-400 hover:text-red-600 text-xs"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                    @endif
                </div>
                @empty
                <p class="text-sm text-gray-400 py-2">No appointment history recorded.</p>
                @endforelse
            </div>
        </div>

        {{-- ═══ TAB: Personal ═══ --}}
        <div x-show="tab === 'personal'" class="space-y-4">

            {{-- Personal details --}}
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-blue-500"></i> Personal Information
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    @php $personal = [
                        'Gender'       => $officer->gender === 'M' ? 'Male' : ($officer->gender === 'F' ? 'Female' : null),
                        'Blood Group'  => $officer->blood_grup,
                        'Religion'     => $officer->religion,
                        'Date of Birth'=> $officer->dt_birth?->format('d M Y'),
                        'Date of Entry'=> $officer->doe?->format('d M Y'),
                        'Commissioned' => $officer->date_comm?->format('d M Y'),
                        'State'        => $officer->state_orig,
                        'LGA'          => $officer->lga,
                        'Geo Zone'     => $officer->geo_p_zone,
                        'Home Town'    => $officer->home_town,
                        'Sea Time'     => $officer->sea_time ? $officer->sea_time.' months' : null,
                    ]; @endphp
                    @foreach($personal as $lbl => $val)
                    @if($val)
                    <div>
                        <p class="text-xs text-gray-400">{{ $lbl }}</p>
                        <p class="text-gray-800 font-medium">{{ $val }}</p>
                    </div>
                    @endif
                    @endforeach
                </div>
                @if($officer->pre_addr)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-400 mb-1">Present Address</p>
                    <p class="text-gray-800 text-sm">{{ $officer->pre_addr }}</p>
                </div>
                @endif
            </div>

            {{-- Spouse & children --}}
            @if($officer->names1 || $officer->child_1)
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-heart text-pink-500"></i> Family
                </h3>
                @if($officer->names1)
                <div class="mb-3">
                    <p class="text-xs text-gray-400">Spouse</p>
                    <p class="text-gray-800 font-medium text-sm">{{ $officer->names1 }}</p>
                </div>
                @endif
                @php $children = array_filter([$officer->child_1, $officer->child_2, $officer->child_3, $officer->child_4]); @endphp
                @if(count($children) > 0)
                <div>
                    <p class="text-xs text-gray-400 mb-1">Children</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($children as $child)
                        <span class="bg-pink-50 text-pink-700 text-xs px-2.5 py-1 rounded-lg">{{ $child }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif

            {{-- NOK --}}
            @if($officer->next_kin || $officer->nok_photo)
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-user-shield text-indigo-500"></i> Next of Kin
                </h3>
                <div class="flex items-start gap-4">
                    @if($officer->nok_photo)
                    <div class="w-16 h-16 rounded-xl overflow-hidden border border-gray-200 flex-shrink-0 shadow-sm">
                        <img src="{{ $officer->nokPhotoUrl() }}" alt="NOK" class="w-full h-full object-cover">
                    </div>
                    @endif
                    <div class="grid grid-cols-2 gap-2 text-sm flex-1">
                        @foreach(['Name'=>$officer->next_kin,'Relationship'=>$officer->relatnship,'Tel'=>$officer->nok_tel_no,'Email'=>$officer->nok_email,'Address'=>$officer->nok_add] as $l => $v)
                        @if($v)
                        <div>
                            <p class="text-xs text-gray-400">{{ $l }}</p>
                            <p class="text-gray-800 font-medium">{{ $v }}</p>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @if($officer->alt_nok)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Alternate Next of Kin</p>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        @foreach(['Name'=>$officer->alt_nok,'Relationship'=>$officer->alt_nokrel,'Tel'=>$officer->alt_phone,'Email'=>$officer->alt_email] as $l => $v)
                        @if($v)
                        <div>
                            <p class="text-xs text-gray-400">{{ $l }}</p>
                            <p class="text-gray-800 font-medium">{{ $v }}</p>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif

            {{-- Leaves --}}
            @include('officers.partials.show-section-leaves')
        </div>

        {{-- ═══ TAB: Records ═══ --}}
        <div x-show="tab === 'records'" class="space-y-4">
            @include('officers.partials.show-section-disciplines')
            @include('officers.partials.show-section-documents')
            @include('officers.partials.show-section-medicals')
            @include('officers.partials.show-section-qualifications')

            {{-- Per-officer audit trail --}}
            @if($auditLog->count())
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-history text-gray-400"></i> Change History
                </h3>
                <div class="space-y-2.5">
                    @foreach($auditLog as $log)
                    @php
                        $aStyle = ['created'=>['icon'=>'fa-plus-circle','color'=>'text-green-500','bg'=>'bg-green-50'],'updated'=>['icon'=>'fa-pen-circle','color'=>'text-blue-500','bg'=>'bg-blue-50'],'deleted'=>['icon'=>'fa-minus-circle','color'=>'text-red-500','bg'=>'bg-red-50']];
                        $as = $aStyle[$log->action] ?? ['icon'=>'fa-circle-dot','color'=>'text-gray-400','bg'=>'bg-gray-50'];
                    @endphp
                    <div class="flex items-start gap-2.5">
                        <div class="w-6 h-6 rounded-full {{ $as['bg'] }} flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas {{ $as['icon'] }} text-xs {{ $as['color'] }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-700">{{ $log->description }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $log->user_name }} · {{ $log->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Service Records --}}
            @if($officer->rod || $officer->retired_dt || $officer->dis_cases || $officer->med_cases)
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-clipboard text-gray-500"></i> Service Remarks
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
                    @foreach(['Run Out Date'=>$officer->rod?->format('d M Y'),'Retirement Date'=>$officer->retired_dt?->format('d M Y'),'Terminal Leave'=>$officer->term_leave?->format('d M Y'),'Sea Time'=>$officer->sea_time?$officer->sea_time.' months':null,'Rank Ceiling'=>$officer->r_cieling] as $l => $v)
                    @if($v)<div><p class="text-xs text-gray-400">{{ $l }}</p><p class="text-gray-800 font-medium">{{ $v }}</p></div>@endif
                    @endforeach
                </div>
                @if($officer->dis_cases)
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-400 mb-1">Disciplinary Cases</p>
                    <p class="text-sm text-gray-700">{{ $officer->dis_cases }}</p>
                </div>
                @endif
                @if($officer->med_cases)
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-400 mb-1">Medical Cases</p>
                    <p class="text-sm text-gray-700">{{ $officer->med_cases }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>

    </div>{{-- end tabbed right column --}}
</div>
@endsection

@section('scripts')
<script>
// ── Inline quick-edit ──
const QUICK_UPDATE_URL = '{{ route("officers.quick-update", $officer) }}';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function startInlineEdit(btn) {
    const wrapper = btn.closest('.inline-edit-field');
    const display = wrapper.querySelector('.quick-edit-display');
    const field   = wrapper.dataset.field;
    const current = display.textContent.trim();
    if (current === '—') display.textContent = '';

    const input = document.createElement('input');
    input.type  = 'text';
    input.value = display.textContent.trim();
    input.className = 'border border-blue-400 rounded px-2 py-0.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 w-full max-w-xs';

    wrapper.replaceChild(input, display);
    btn.remove();
    input.focus();

    const save = () => {
        const val = input.value.trim();
        fetch(QUICK_UPDATE_URL, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ field, value: val })
        })
        .then(r => r.json())
        .then(data => {
            const newSpan = document.createElement('span');
            newSpan.className = 'text-gray-800 font-medium quick-edit-display';
            newSpan.textContent = val || '—';
            const newBtn = document.createElement('button');
            newBtn.onclick = function(){ startInlineEdit(this); };
            newBtn.className = 'edit-trigger text-gray-400 hover:text-blue-500 ml-1';
            newBtn.title = 'Edit';
            newBtn.innerHTML = '<i class="fas fa-pencil text-xs"></i>';
            input.replaceWith(newSpan);
            newSpan.closest('.inline-edit-field').appendChild(newBtn);
            showToast('success', 'Saved', data.message || 'Field updated');
        })
        .catch(() => showToast('error', 'Error', 'Could not save change'));
    };

    input.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); save(); } if (e.key === 'Escape') { input.value = current; save(); } });
    input.addEventListener('blur', save);
}
</script>
@endsection
