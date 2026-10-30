@extends('layouts.app')
@section('title', 'Appointments Report')
@section('header', 'Appointments Report')

@section('header-actions')
<a href="{{ route('reports.postings.export', request()->query()) }}"
   class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-2 rounded-lg flex items-center gap-2 transition-colors no-print">
    <i class="fas fa-file-excel"></i> Export Excel
</a>
<button onclick="window.print()" class="border border-gray-300 text-gray-600 text-sm px-3 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-50 no-print">
    <i class="fas fa-print"></i> Print
</button>
@endsection

@section('content')
<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-4 flex flex-wrap gap-3 no-print">
    <input type="date" name="date_from" value="{{ request('date_from') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
    <input type="date" name="date_to"   value="{{ request('date_to') }}"   class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
    <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <option value="">All Types</option>
        <option value="appointment" {{ request('type') === 'appointment' ? 'selected' : '' }}>Appointment</option>
        <option value="course"      {{ request('type') === 'course'      ? 'selected' : '' }}>Course</option>
    </select>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        <i class="fas fa-filter mr-1"></i> Filter
    </button>
    <a href="{{ route('reports.postings') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Clear</a>
</form>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b text-sm font-semibold text-gray-700">{{ $postings->count() }} record{{ $postings->count() === 1 ? '' : 's' }}</div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-left">
            <tr>
                <th class="px-3 py-2 font-semibold">Officer</th>
                <th class="px-3 py-2 font-semibold">Appointment</th>
                <th class="px-3 py-2 font-semibold">Ship / Command</th>
                <th class="px-3 py-2 font-semibold">Type</th>
                <th class="px-3 py-2 font-semibold">Date From</th>
                <th class="px-3 py-2 font-semibold">Date To</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($postings as $p)
            <tr>
                <td class="px-3 py-2 font-medium">
                    @if($p->officer)
                    {{ $p->officer->rank }} {{ $p->officer->name }}
                    <br><span class="text-xs text-gray-400 font-mono">{{ $p->officer->svc_no }}</span>
                    @else
                    <span class="text-gray-400 italic text-xs">Deleted officer</span>
                    @endif
                </td>
                <td class="px-3 py-2">{{ $p->appointment }}</td>
                <td class="px-3 py-2 text-gray-500">
                    {{ $p->ship ?: '—' }}
                    @if($p->command) <br><span class="text-xs text-gray-400">{{ $p->command }}</span> @endif
                </td>
                <td class="px-3 py-2">
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full
                        {{ $p->type === 'course' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($p->type) }}
                    </span>
                </td>
                <td class="px-3 py-2 text-xs">{{ $p->date_from?->format('d M Y') ?: '—' }}</td>
                <td class="px-3 py-2 text-xs">{{ $p->date_to?->format('d M Y') ?: '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-10 text-center text-gray-400">
                    <i class="fas fa-file-alt text-gray-200 text-3xl mb-2 block"></i>
                    No appointments match the selected filters.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
