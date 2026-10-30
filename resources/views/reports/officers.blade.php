@extends('layouts.app')
@section('title', 'Officers Report')
@section('header', 'Officers Report')

@section('header-actions')
<a href="{{ route('reports.officers.export', request()->query()) }}"
   class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-2 rounded-lg flex items-center gap-2 transition-colors no-print">
    <i class="fas fa-file-excel"></i> Export Excel
</a>
<button onclick="window.print()" class="border border-gray-300 text-gray-600 text-sm px-3 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-50 no-print">
    <i class="fas fa-print"></i> Print
</button>
@endsection

@section('content')
{{-- Filters --}}
<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-4 flex flex-wrap gap-3 no-print">
    <select name="rank" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <option value="">All Ranks</option>
        @foreach($ranks as $r)<option value="{{ $r }}" {{ ($filters['rank'] ?? '') === $r ? 'selected' : '' }}>{{ $r }}</option>@endforeach
    </select>
    <select name="branch" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <option value="">All Branches</option>
        @foreach($branches as $code => $name)<option value="{{ $code }}" {{ ($filters['branch'] ?? '') === $code ? 'selected' : '' }}>{{ $name }}</option>@endforeach
    </select>
    <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <option value="">All Status</option>
        <option value="S" {{ ($filters['status'] ?? '') === 'S' ? 'selected' : '' }}>Serving</option>
        <option value="R" {{ ($filters['status'] ?? '') === 'R' ? 'selected' : '' }}>Retired</option>
    </select>
    <input type="text" name="ship" value="{{ $filters['ship'] ?? '' }}" placeholder="Ship/Command..." class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-48">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        <i class="fas fa-filter mr-1"></i> Filter
    </button>
    <a href="{{ route('reports.officers') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Clear</a>
</form>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-sm font-semibold text-gray-700">{{ $officers->count() }} officers found</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-left">
                <tr>
                    <th class="px-3 py-2 font-semibold">Svc No</th>
                    <th class="px-3 py-2 font-semibold">Rank</th>
                    <th class="px-3 py-2 font-semibold">Name</th>
                    <th class="px-3 py-2 font-semibold">Branch</th>
                    <th class="px-3 py-2 font-semibold">Present Ship</th>
                    <th class="px-3 py-2 font-semibold">Appointment</th>
                    <th class="px-3 py-2 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($officers as $officer)
                <tr>
                    <td class="px-3 py-2 font-mono text-xs text-gray-500">{{ $officer->svc_no }}</td>
                    <td class="px-3 py-2 font-bold text-xs">{{ $officer->rank }}</td>
                    <td class="px-3 py-2 font-medium text-gray-800">{{ $officer->name }}</td>
                    <td class="px-3 py-2 text-gray-500">{{ $officer->branch ?: '—' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $officer->pres_ship ?: '—' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $officer->pre_appt ?: '—' }}</td>
                    <td class="px-3 py-2">{{ $officer->servng_rtd === 'R' ? 'Retired' : 'Serving' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No officers match the filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
