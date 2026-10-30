@extends('layouts.app')
@section('title','Retirement / ROD Report')
@section('header','Officers Due for Separation')
@section('header-actions')
<button onclick="window.print()" class="border border-gray-300 text-gray-600 text-sm px-3 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-50 no-print">
    <i class="fas fa-print"></i> Print
</button>
@endsection
@section('content')
<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-4 flex flex-wrap gap-3 items-center no-print">
    <label class="text-sm font-medium text-gray-700">Show officers separating within</label>
    <select name="months" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" onchange="this.form.submit()">
        @foreach([1=>'1 month',3=>'3 months',6=>'6 months',12=>'12 months'] as $val=>$label)
        <option value="{{ $val }}" {{ $months==$val?'selected':'' }}>{{ $label }}</option>
        @endforeach
    </select>
</form>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b text-sm font-semibold text-gray-700 flex justify-between">
        <span>{{ $officers->count() }} officer{{ $officers->count()===1?'':'s' }} found</span>
        <span class="text-gray-400 font-normal">As of {{ now()->format('d M Y') }}</span>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-left">
            <tr>
                <th class="px-4 py-3 font-semibold">Rank & Name</th>
                <th class="px-4 py-3 font-semibold">Svc No</th>
                <th class="px-4 py-3 font-semibold">Branch</th>
                <th class="px-4 py-3 font-semibold">Present Ship</th>
                <th class="px-4 py-3 font-semibold">Separation Type</th>
                <th class="px-4 py-3 font-semibold">Date</th>
                <th class="px-4 py-3 font-semibold">Days Left</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($officers as $o)
            @php $level = $o->_sep_days <= 30 ? 'red' : ($o->_sep_days <= 90 ? 'amber' : 'green'); @endphp
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3"><a href="{{ route('officers.show',$o) }}" class="font-medium text-blue-700 hover:underline">{{ $o->rank }} {{ $o->name }}</a></td>
                <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $o->svc_no }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $o->branch ?: '—' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $o->pres_ship ?: '—' }}</td>
                <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">{{ $o->_sep_label }}</span></td>
                <td class="px-4 py-3 text-sm">{{ $o->_sep_date?->format('d M Y') }}</td>
                <td class="px-4 py-3">
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full
                        {{ $level==='red'?'bg-red-100 text-red-700':($level==='amber'?'bg-amber-100 text-amber-700':'bg-green-100 text-green-700') }}">
                        {{ $o->_sep_days }}d
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-10 text-center text-gray-400">No officers approaching separation in this period.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
