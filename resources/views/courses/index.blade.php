@extends('layouts.app')
@section('title', 'Courses')
@section('header', 'Courses')

@section('header-actions')
@if(in_array(auth()->user()->role, ['admin','hr']))
<a href="{{ route('courses.create') }}" class="bg-[#0a1640] hover:bg-[#071230] text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
    <i class="fas fa-plus"></i> Add Course
</a>
@endif
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm">

    {{-- Search bar --}}
    <form id="filter-form" method="GET" class="flex flex-wrap gap-3 p-4 border-b border-gray-100 items-center">
        <div class="relative flex-1 min-w-48">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
            <input type="text" id="search-input" name="search" value="{{ request('search') }}"
                   placeholder="Search officer or course name…"
                   class="w-full border border-gray-300 rounded-lg pl-9 pr-8 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none">
            <button type="button" id="clear-search"
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-500 text-sm {{ request('search') ? '' : 'hidden' }}">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            <option value="">All Types</option>
            @foreach($courseTypes as $t)
            <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
        <div id="search-spinner" class="hidden text-blue-400 text-sm"><i class="fas fa-circle-notch fa-spin"></i></div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-left">
                <tr>
                    <th class="px-4 py-3 font-semibold">Officer</th>
                    <th class="px-4 py-3 font-semibold">Type</th>
                    <th class="px-4 py-3 font-semibold">Course Name</th>
                    <th class="px-4 py-3 font-semibold">Date</th>
                    <th class="px-4 py-3 font-semibold">Grade</th>
                    <th class="px-4 py-3 font-semibold">Received</th>
                    <th class="px-4 py-3 font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($courses as $course)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        @if($course->officer)
                        <a href="{{ route('officers.show', $course->officer) }}" class="font-medium text-blue-700 hover:underline">
                            {{ $course->officer->rank }} {{ $course->officer->name }}
                        </a>
                        <p class="text-xs text-gray-400 font-mono">{{ $course->officer->svc_no }}</p>
                        @else
                        <span class="text-gray-400 italic text-xs">Deleted officer</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2 py-0.5 rounded">{{ $course->course_type }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ $course->course_name ?: '—' }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $course->date?->format('d M Y') ?: '—' }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $course->grade ?: '—' }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $course->received ?: '—' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            @if(in_array(auth()->user()->role, ['admin','hr']))
                            <a href="{{ route('courses.edit', $course) }}" class="text-amber-500 hover:text-amber-700"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('courses.destroy', $course) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-delete text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                        <i class="fas fa-graduation-cap text-gray-200 text-3xl mb-2 block"></i>
                        No courses found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">{{ $courses->links() }}</div>
</div>
@endsection

@section('scripts')
@include('components.live-search')
@endsection
