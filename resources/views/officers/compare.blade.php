@extends('layouts.app')
@section('title', 'Compare Officers')
@section('header', 'Compare Officers')
@section('breadcrumb', 'Officers / Compare')

@section('header-actions')
<a href="{{ route('officers.index') }}" class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Officers
</a>
@endsection

@section('content')

{{-- ── Officer selector ── --}}
<div class="bg-white rounded-xl shadow-sm p-5 mb-6">
    <form method="GET" action="{{ route('officers.compare') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-64">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Select Up to 4 Officers</label>
            <select name="ids[]" multiple class="select2-compare w-full border border-gray-300 rounded-lg text-sm"
                    style="height:auto;" data-placeholder="Search and select officers…">
                @foreach($all as $o)
                <option value="{{ $o->id }}" {{ in_array($o->id, $officers->pluck('id')->toArray()) ? 'selected' : '' }}>
                    {{ $o->rank }} {{ $o->name }} ({{ $o->svc_no }})
                </option>
                @endforeach
            </select>
        </div>
        <button type="submit"
                class="px-5 py-2.5 rounded-lg text-white font-medium text-sm transition-colors"
                style="background:#0b1d55;">
            <i class="fas fa-code-compare mr-2"></i>Compare
        </button>
    </form>
</div>

@if($officers->count() >= 2)
@php
    $rankNames  = \App\Models\Officer::rankFullNames();
    $rowGroups  = [
        'Identity' => [
            'Rank'              => fn($o) => $o->rank,
            'Service Number'    => fn($o) => $o->svc_no,
            'Branch'            => fn($o) => $o->branch,
            'Specialisation'    => fn($o) => $o->spl,
            'Type of Commission'=> fn($o) => $o->type_comm,
            'Status'            => fn($o) => $o->servng_rtd === 'R' ? 'Retired' : 'Serving',
        ],
        'Dates' => [
            'Date of Birth'     => fn($o) => $o->dt_birth?->format('d M Y'),
            'Date of Entry'     => fn($o) => $o->doe?->format('d M Y'),
            'Date Commissioned' => fn($o) => $o->date_comm?->format('d M Y'),
            'Seniority Date'    => fn($o) => $o->snrty_date?->format('d M Y'),
            'Last Promotion'    => fn($o) => $o->dolp?->format('d M Y'),
            'Run Out Date'      => fn($o) => $o->rod?->format('d M Y'),
        ],
        'Current Assignment' => [
            'Present Ship'      => fn($o) => $o->pres_ship,
            'Command'           => fn($o) => $o->command,
            'Appointment'       => fn($o) => $o->pre_appt,
        ],
        'Personal' => [
            'Gender'            => fn($o) => $o->gender === 'M' ? 'Male' : ($o->gender === 'F' ? 'Female' : null),
            'Blood Group'       => fn($o) => $o->blood_grup,
            'Religion'          => fn($o) => $o->religion,
            'State of Origin'   => fn($o) => $o->state_orig,
            'Sea Time'          => fn($o) => $o->sea_time ? $o->sea_time.' months' : null,
        ],
        'Contact' => [
            'Tel No'            => fn($o) => $o->tel_no,
            'Email'             => fn($o) => $o->e_mail_add,
        ],
    ];
@endphp

<div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide w-36">Field</th>
                @foreach($officers as $officer)
                <th class="px-4 py-3 text-left">
                    <div class="flex items-center gap-2.5">
                        @if($officer->photo)
                        <img src="{{ $officer->photoUrl() }}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm flex-shrink-0">
                        @else
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                             style="background:linear-gradient(135deg,#0b1d55,#1e40af);">
                            {{ strtoupper(substr($officer->name ?? 'N', 0, 2)) }}
                        </div>
                        @endif
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800 truncate">{{ $officer->name }}</p>
                            <span class="text-xs font-bold px-1.5 py-0.5 rounded text-white {{ \App\Models\Officer::rankBadgeClass($officer->rank ?? '') }}"
                                  title="{{ $rankNames[$officer->rank] ?? '' }}">
                                {{ $officer->rank }}
                            </span>
                            <a href="{{ route('officers.show', $officer) }}" class="ml-1 text-xs text-blue-500 hover:underline">View →</a>
                        </div>
                    </div>
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rowGroups as $group => $fields)
            <tr>
                <td colspan="{{ $officers->count() + 1 }}" class="px-4 py-2 bg-gray-50 text-xs font-bold text-gray-400 uppercase tracking-wider border-y border-gray-100">
                    {{ $group }}
                </td>
            </tr>
            @foreach($fields as $label => $getter)
            @php
                $values  = $officers->map($getter);
                $unique  = $values->filter()->unique()->count();
                $differs = $unique > 1;
            @endphp
            <tr class="border-b border-gray-50 {{ $differs ? 'bg-amber-50/40' : '' }}">
                <td class="px-4 py-2.5 text-xs text-gray-500 font-medium align-top">
                    {{ $label }}
                    @if($differs)<i class="fas fa-arrows-left-right text-amber-400 ml-1" title="Values differ"></i>@endif
                </td>
                @foreach($officers as $officer)
                @php $val = $getter($officer); @endphp
                <td class="px-4 py-2.5 text-gray-800 align-top">
                    @if($label === 'Rank' && $val)
                    <span class="text-xs font-bold px-1.5 py-0.5 rounded text-white {{ \App\Models\Officer::rankBadgeClass($val) }}"
                          title="{{ $rankNames[$val] ?? '' }}">{{ $val }}</span>
                    @elseif($label === 'Service Number' && $val)
                    <div class="flex items-center gap-1 group">
                        <span class="font-mono text-xs">{{ $val }}</span>
                        <button onclick="copyToClipboard('{{ $val }}', this)" class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-blue-500 transition-opacity"><i class="fas fa-copy text-xs"></i></button>
                    </div>
                    @else
                    <span class="{{ $val ? '' : 'text-gray-300' }}">{{ $val ?: '—' }}</span>
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@elseif($officers->count() === 1)
<div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-center">
    <i class="fas fa-code-compare text-amber-400 text-3xl mb-3 block"></i>
    <p class="text-amber-700 font-medium">Select at least 2 officers to compare.</p>
</div>
@else
<div class="bg-gray-50 border border-gray-200 rounded-xl p-10 text-center">
    <i class="fas fa-code-compare text-gray-200 text-5xl mb-4 block"></i>
    <p class="text-gray-500 font-medium text-lg">Select Officers to Compare</p>
    <p class="text-gray-400 text-sm mt-1">Use the search above to select 2–4 officers and see their details side by side.</p>
</div>
@endif

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.select2-compare').select2({
        width: '100%',
        placeholder: 'Search and select officers…',
        maximumSelectionLength: 4,
    });
});
</script>
@endsection
