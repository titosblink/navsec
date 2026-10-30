@extends('layouts.app')
@section('title', 'Courses Report')
@section('header', 'Courses Report')

@section('header-actions')
<a href="{{ route('reports.courses.export', request()->query()) }}"
   class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-2 rounded-lg flex items-center gap-2 transition-colors no-print">
    <i class="fas fa-file-excel"></i> Export Excel
</a>
<button onclick="window.print()" class="border border-gray-300 text-gray-600 text-sm px-3 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-50 no-print">
    <i class="fas fa-print"></i> Print
</button>
@endsection

@section('content')
<form method="GET" class="bg-white rounded-xl shadow-sm p-4 mb-4 flex flex-wrap gap-3 no-print">
    <select name="course_type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <option value="">All Types</option>
        @foreach($courseTypes as $t)<option value="{{ $t }}" {{ request('course_type') === $t ? 'selected' : '' }}>{{ $t }}</option>@endforeach
    </select>
    <input type="number" name="year" value="{{ request('year') }}" placeholder="Year e.g. 2024" class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-36">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700"><i class="fas fa-filter mr-1"></i> Filter</button>
    <a href="{{ route('reports.courses') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Clear</a>
</form>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b text-sm font-semibold text-gray-700">{{ $courses->count() }} records</div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-left">
            <tr>
                <th class="px-3 py-2 font-semibold">Officer</th>
                <th class="px-3 py-2 font-semibold">Type</th>
                <th class="px-3 py-2 font-semibold">Course Name</th>
                <th class="px-3 py-2 font-semibold">Date</th>
                <th class="px-3 py-2 font-semibold">Grade</th>
                <th class="px-3 py-2 font-semibold">Certificate</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($courses as $c)
            <tr>
                <td class="px-3 py-2 font-medium">
                    @if($c->officer)
                    {{ $c->officer->rank }} {{ $c->officer->name }}<br><span class="text-xs text-gray-400 font-mono">{{ $c->officer->svc_no }}</span>
                    @else
                    <span class="text-gray-400 italic text-xs">Deleted officer</span>
                    @endif
                </td>
                <td class="px-3 py-2"><span class="bg-purple-100 text-purple-700 text-xs font-bold px-2 py-0.5 rounded">{{ $c->course_type }}</span></td>
                <td class="px-3 py-2">{{ $c->course_name ?: '—' }}</td>
                <td class="px-3 py-2 text-xs">{{ $c->date?->format('d M Y') ?: '—' }}</td>
                <td class="px-3 py-2">{{ $c->grade ?: '—' }}</td>
                <td class="px-3 py-2">{{ $c->received ?: '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No courses match.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
