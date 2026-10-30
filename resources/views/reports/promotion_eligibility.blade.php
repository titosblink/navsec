@extends('layouts.app')
@section('title','Promotion Eligibility')
@section('header','Promotion Eligibility List')
@section('header-actions')
<a href="{{ route('reports.promotion-eligibility.export') }}"
   class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-2 rounded-lg flex items-center gap-2 transition-colors no-print">
    <i class="fas fa-file-excel"></i> Export Excel
</a>
<button onclick="window.print()" class="border border-gray-300 text-gray-600 text-sm px-3 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-50 no-print">
    <i class="fas fa-print"></i> Print
</button>
@endsection
@section('content')
<div class="bg-white rounded-xl shadow-sm mb-4 p-4 no-print">
    <p class="text-sm text-gray-600">Minimum years in rank configured in
        @if(auth()->user()->role === 'admin')<a href="{{ route('settings.index') }}" class="text-blue-600 hover:underline">Settings</a>@else Settings@endif:
        @foreach($minYears as $r => $y)<span class="font-mono text-xs bg-gray-100 px-1.5 py-0.5 rounded mx-0.5">{{ $r }}→{{ $y }}y</span>@endforeach
    </p>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b text-sm font-semibold text-gray-700">{{ $eligible->count() }} officer{{ $eligible->count()===1?'':'s' }} eligible for consideration</div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-left">
            <tr>
                <th class="px-4 py-3 font-semibold">Rank & Name</th>
                <th class="px-4 py-3 font-semibold">Svc No</th>
                <th class="px-4 py-3 font-semibold">Branch</th>
                <th class="px-4 py-3 font-semibold">DOLP</th>
                <th class="px-4 py-3 font-semibold">Years in Rank</th>
                <th class="px-4 py-3 font-semibold">Min Required</th>
                <th class="px-4 py-3 font-semibold">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($eligible as $o)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3"><a href="{{ route('officers.show',$o) }}" class="font-medium text-blue-700 hover:underline">{{ $o->rank }} {{ $o->name }}</a></td>
                <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $o->svc_no }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $o->branch ?: '—' }}</td>
                <td class="px-4 py-3 text-xs">{{ $o->dolp?->format('d M Y') ?: '—' }}</td>
                <td class="px-4 py-3 font-medium">{{ $o->_years_in_rank }}y</td>
                <td class="px-4 py-3 text-gray-500">{{ $o->_min_years }}y</td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $o->_years_in_rank >= $o->_min_years + 2 ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $o->_years_in_rank >= $o->_min_years + 2 ? 'Overdue' : 'Eligible' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-10 text-center text-gray-400">No officers currently eligible.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
