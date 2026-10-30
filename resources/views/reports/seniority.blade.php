@extends('layouts.app')
@section('title','Seniority List')
@section('header','Seniority List')
@section('header-actions')
<a href="{{ route('reports.seniority.export', request()->only('rank')) }}"
   class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-2 rounded-lg flex items-center gap-2 no-print transition-colors">
    <i class="fas fa-file-excel"></i> Export Excel
</a>
<button onclick="window.print()" class="border border-gray-300 text-gray-600 text-sm px-3 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-50 no-print">
    <i class="fas fa-print"></i> Print
</button>
@endsection
@section('content')
<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-4 flex flex-wrap gap-3 items-center no-print">
    <select name="rank" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        <option value="">All Ranks</option>
        @foreach($ranks as $r)<option value="{{ $r }}" {{ $selectedRank===$r?'selected':'' }}>{{ $r }}</option>@endforeach
    </select>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700"><i class="fas fa-filter mr-1"></i> Filter</button>
    <a href="{{ route('reports.seniority') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Clear</a>
</form>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b text-sm font-semibold text-gray-700 flex justify-between">
        <span>{{ $officers->count() }} officer{{ $officers->count()===1?'':'s' }} — Nigerian Navy Seniority List</span>
        <span class="text-gray-400 font-normal">As of {{ now()->format('d M Y') }}</span>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-left">
            <tr>
                <th class="px-3 py-2 font-semibold w-10">#</th>
                <th class="px-3 py-2 font-semibold">Svc No</th>
                <th class="px-3 py-2 font-semibold">Rank</th>
                <th class="px-3 py-2 font-semibold">Name</th>
                <th class="px-3 py-2 font-semibold">Branch</th>
                <th class="px-3 py-2 font-semibold">Seniority Date</th>
                <th class="px-3 py-2 font-semibold">DOLP</th>
                <th class="px-3 py-2 font-semibold">Date Commissioned</th>
                <th class="px-3 py-2 font-semibold">Present Ship</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($officers as $i => $o)
            <tr class="{{ $i % 2 === 0 ? '' : 'bg-gray-50/40' }}">
                <td class="px-3 py-2 text-gray-400 text-xs">{{ $i+1 }}</td>
                <td class="px-3 py-2 font-mono text-xs text-gray-500">{{ $o->svc_no }}</td>
                <td class="px-3 py-2"><span class="font-bold text-xs text-[#0b1d55]">{{ $o->rank }}</span></td>
                <td class="px-3 py-2 font-medium text-gray-800">{{ $o->name }}</td>
                <td class="px-3 py-2 text-gray-500 text-xs">{{ $o->branch ?: '—' }}</td>
                <td class="px-3 py-2 text-xs">{{ $o->snrty_date?->format('d M Y') ?: '—' }}</td>
                <td class="px-3 py-2 text-xs">{{ $o->dolp?->format('d M Y') ?: '—' }}</td>
                <td class="px-3 py-2 text-xs">{{ $o->date_comm?->format('d M Y') ?: '—' }}</td>
                <td class="px-3 py-2 text-xs text-gray-500">{{ $o->pres_ship ?: '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="9" class="px-4 py-8 text-center text-gray-400">No officers found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
