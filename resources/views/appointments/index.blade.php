@extends('layouts.app')
@section('title', 'Appointments')
@section('header', 'Appointments')

@section('header-actions')
@if(in_array(auth()->user()->role, ['admin','hr']))
<a href="{{ route('appointments.create') }}" class="bg-[#0a1640] hover:bg-[#071230] text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
    <i class="fas fa-plus"></i> New Appointment
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
                   placeholder="Search officer, appointment, ship…"
                   class="w-full border border-gray-300 rounded-lg pl-9 pr-8 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none">
            <button type="button" id="clear-search"
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-500 text-sm {{ request('search') ? '' : 'hidden' }}">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            <option value="">All Types</option>
            <option value="appointment" {{ request('type') === 'appointment' ? 'selected' : '' }}>Appointment</option>
            <option value="course"      {{ request('type') === 'course'      ? 'selected' : '' }}>Course</option>
        </select>
        <div id="search-spinner" class="hidden text-blue-400 text-sm"><i class="fas fa-circle-notch fa-spin"></i></div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-left">
                <tr>
                    <th class="px-4 py-3 font-semibold">Officer</th>
                    <th class="px-4 py-3 font-semibold">Appointment</th>
                    <th class="px-4 py-3 font-semibold">Ship / Command</th>
                    <th class="px-4 py-3 font-semibold">Type</th>
                    <th class="px-4 py-3 font-semibold">Date From</th>
                    <th class="px-4 py-3 font-semibold">Date To</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($appointments as $appt)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        @if($appt->officer)
                        <a href="{{ route('officers.show', $appt->officer) }}" class="font-medium text-blue-700 hover:underline">
                            {{ $appt->officer->rank }} {{ $appt->officer->name }}
                        </a>
                        <p class="text-xs text-gray-400 font-mono">{{ $appt->officer->svc_no }}</p>
                        @else
                        <span class="text-gray-400 italic text-xs">Deleted officer</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-800">{{ $appt->appointment }}</td>
                    <td class="px-4 py-3 text-gray-500">
                        {{ $appt->ship ?: '—' }}
                        @if($appt->command)<br><span class="text-xs">{{ $appt->command }}</span>@endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $appt->type === 'course' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ ucfirst($appt->type) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        {{ $appt->date_from?->format('d M Y') ?: '—' }}
                        @if($appt->is_current && $appt->date_from)
                        @php $months = $appt->date_from->diffInMonths(now()); $yrs = intdiv($months, 12); $mos = $months % 12; @endphp
                        <span class="block text-blue-500 font-medium mt-0.5">
                            {{ $yrs > 0 ? $yrs.'y ' : '' }}{{ $mos }}m tenure
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $appt->date_to?->format('d M Y') ?: '—' }}</td>
                    <td class="px-4 py-3">
                        @if($appt->is_current)
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">Current</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            @if(in_array(auth()->user()->role, ['admin','hr']))
                            <a href="{{ route('appointments.edit', $appt) }}" class="text-amber-500 hover:text-amber-700" title="Edit"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('appointments.destroy', $appt) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-delete text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                        <i class="fas fa-exchange-alt text-gray-200 text-3xl mb-2 block"></i>
                        No appointments found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">{{ $appointments->links() }}</div>
</div>
@endsection

@section('scripts')
@include('components.live-search')
@endsection
