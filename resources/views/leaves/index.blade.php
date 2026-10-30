@extends('layouts.app')
@section('title','Leave Management')
@section('header','Leave Management')
@section('header-actions')
<a href="{{ route('leaves.export', request()->only(['status','type'])) }}"
   class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
    <i class="fas fa-file-excel"></i> Export Excel
</a>
@if(in_array(auth()->user()->role,['admin','hr']))
<a href="{{ route('leaves.create') }}" class="bg-[#0a1640] hover:bg-[#071230] text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
    <i class="fas fa-plus"></i> New Leave
</a>
@endif
@endsection

@section('content')
<div x-data="{ view: localStorage.getItem('leavesView') || 'list' }"
     x-init="$watch('view', v => localStorage.setItem('leavesView', v))">

    {{-- View tabs --}}
    <div class="bg-white rounded-xl shadow-sm mb-4 overflow-hidden">
        <div class="flex border-b border-gray-100">
            <button @click="view='list'" :class="view==='list'?'border-b-2 border-blue-600 text-blue-700 bg-blue-50/50 font-semibold':'text-gray-500 hover:text-gray-700'"
                    class="flex items-center gap-2 px-5 py-3 text-sm transition-colors">
                <i class="fas fa-list text-xs"></i> List
            </button>
            <button @click="view='calendar'" :class="view==='calendar'?'border-b-2 border-blue-600 text-blue-700 bg-blue-50/50 font-semibold':'text-gray-500 hover:text-gray-700'"
                    class="flex items-center gap-2 px-5 py-3 text-sm transition-colors">
                <i class="fas fa-calendar text-xs"></i> Calendar
            </button>
        </div>
    </div>

    {{-- ── LIST VIEW ── --}}
    <div x-show="view==='list'">
    <div class="bg-white rounded-xl shadow-sm">
        <form id="filter-form" method="GET" class="flex flex-wrap gap-3 p-4 border-b border-gray-100 items-center">
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                <option value="">All Status</option>
                @foreach(['pending','approved','rejected','completed'] as $s)
                <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                <option value="">All Types</option>
                @foreach($types as $t)<option value="{{ $t }}" {{ request('type')===$t?'selected':'' }}>{{ ucfirst($t) }}</option>@endforeach
            </select>
            <div id="search-spinner" class="hidden text-blue-400 text-sm"><i class="fas fa-circle-notch fa-spin"></i></div>
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-left sticky-thead">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Officer</th>
                        <th class="px-4 py-3 font-semibold">Type</th>
                        <th class="px-4 py-3 font-semibold">From</th>
                        <th class="px-4 py-3 font-semibold">To</th>
                        <th class="px-4 py-3 font-semibold">Days</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Approved By</th>
                        <th class="px-4 py-3 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($leaves as $leave)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            @if($leave->officer)
                            <a href="{{ route('officers.show',$leave->officer) }}" class="font-medium text-blue-700 hover:underline">{{ $leave->officer->rank }} {{ $leave->officer->name }}</a>
                            <p class="text-xs text-gray-400 font-mono">{{ $leave->officer->svc_no }}</p>
                            @else
                            <span class="text-gray-400 italic text-xs">Deleted officer</span>
                            @endif
                        </td>
                        <td class="px-4 py-3"><span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">{{ ucfirst($leave->leave_type) }}</span></td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $leave->start_date->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $leave->end_date->format('d M Y') }}</td>
                        <td class="px-4 py-3 font-medium text-gray-700">{{ $leave->days }}</td>
                        <td class="px-4 py-3">
                            @php $colours=['pending'=>'bg-amber-100 text-amber-700','approved'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700','completed'=>'bg-gray-100 text-gray-600']; @endphp
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $colours[$leave->status] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($leave->status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $leave->approved_by ?: '—' }}</td>
                        <td class="px-4 py-3">
                            @if(in_array(auth()->user()->role,['admin','hr']) && $leave->status === 'pending')
                            <div class="flex items-center justify-center gap-1">
                                <form method="POST" action="{{ route('leaves.update',$leave) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded hover:bg-green-200">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('leaves.update',$leave) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded hover:bg-red-200">Reject</button>
                                </form>
                                <form method="POST" action="{{ route('leaves.destroy',$leave) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-delete text-red-400 hover:text-red-600 text-xs ml-1"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-calendar-times text-gray-200 text-3xl mb-2 block"></i>No leave records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100">{{ $leaves->links() }}</div>
    </div>
    </div>

    {{-- ── CALENDAR VIEW ── --}}
    <div x-show="view==='calendar'" x-cloak>
    @php
        $monthName  = \Carbon\Carbon::create($calYear, $calMonth, 1)->format('F Y');
        $firstDay   = \Carbon\Carbon::create($calYear, $calMonth, 1);
        $daysInMonth = $firstDay->daysInMonth;
        $startDow   = $firstDay->dayOfWeek; // 0=Sun

        // Build a day=>leaves map
        $dayLeaves = [];
        foreach ($calLeaves as $lv) {
            $s = max($lv->start_date, $firstDay);
            $e = min($lv->end_date,   $firstDay->copy()->endOfMonth());
            $cur = $s->copy();
            while ($cur->lte($e)) {
                $dayLeaves[$cur->day][] = $lv;
                $cur->addDay();
            }
        }
    @endphp

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        {{-- Calendar navigation --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            @php $prevMonth = $calMonth === 1 ? 12 : $calMonth-1; $prevYear = $calMonth === 1 ? $calYear-1 : $calYear; @endphp
            @php $nextMonth = $calMonth === 12 ? 1 : $calMonth+1; $nextYear = $calMonth === 12 ? $calYear+1 : $calYear; @endphp
            <a href="{{ route('leaves.index', array_merge(request()->only(['status','type']), ['cal_year'=>$prevYear,'cal_month'=>$prevMonth])) }}"
               class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
            <h3 class="font-semibold text-gray-800">{{ $monthName }}</h3>
            <a href="{{ route('leaves.index', array_merge(request()->only(['status','type']), ['cal_year'=>$nextYear,'cal_month'=>$nextMonth])) }}"
               class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50">
                <i class="fas fa-chevron-right text-xs"></i>
            </a>
        </div>

        {{-- Day headers --}}
        <div class="grid grid-cols-7 border-b border-gray-100">
            @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)
            <div class="px-2 py-2 text-xs font-semibold text-gray-400 text-center">{{ $d }}</div>
            @endforeach
        </div>

        {{-- Calendar grid --}}
        <div class="grid grid-cols-7">
            @for($i = 0; $i < $startDow; $i++)
            <div class="min-h-24 border-b border-r border-gray-50 bg-gray-50/50"></div>
            @endfor

            @for($d = 1; $d <= $daysInMonth; $d++)
            @php $isToday = ($d == now()->day && $calMonth == now()->month && $calYear == now()->year); @endphp
            <div class="min-h-24 border-b border-r border-gray-100 p-1.5 {{ $isToday ? 'bg-blue-50' : '' }}">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-semibold {{ $isToday ? 'bg-blue-600 text-white w-5 h-5 rounded-full flex items-center justify-center' : 'text-gray-500' }}">
                        {{ $d }}
                    </span>
                </div>
                @if(isset($dayLeaves[$d]))
                @foreach(array_slice($dayLeaves[$d], 0, 3) as $lv)
                @php $typeColors = ['annual'=>'bg-blue-100 text-blue-700','medical'=>'bg-red-100 text-red-700','study'=>'bg-purple-100 text-purple-700','compassionate'=>'bg-amber-100 text-amber-700','maternity'=>'bg-pink-100 text-pink-700','paternity'=>'bg-indigo-100 text-indigo-700','other'=>'bg-gray-100 text-gray-600']; @endphp
                @if($lv->officer)
                <a href="{{ route('officers.show',$lv->officer) }}"
                   class="block text-xs px-1.5 py-0.5 rounded mb-0.5 truncate {{ $typeColors[$lv->leave_type] ?? 'bg-gray-100 text-gray-600' }}"
                   title="{{ $lv->officer->rank }} {{ $lv->officer->name }} — {{ ucfirst($lv->leave_type) }}">
                    {{ $lv->officer->name }}
                </a>
                @endif
                @endforeach
                @if(count($dayLeaves[$d]) > 3)
                <p class="text-xs text-gray-400 px-1">+{{ count($dayLeaves[$d]) - 3 }} more</p>
                @endif
                @endif
            </div>

            @if(($startDow + $d) % 7 === 0 && $d < $daysInMonth)
            @endif
            @endfor
        </div>

        {{-- Legend --}}
        <div class="px-4 py-3 border-t border-gray-100 flex flex-wrap gap-2">
            @foreach(['annual'=>'Annual','medical'=>'Medical','study'=>'Study','compassionate'=>'Compassionate','maternity'=>'Maternity','paternity'=>'Paternity'] as $type => $label)
            @php $typeColors = ['annual'=>'bg-blue-100 text-blue-700','medical'=>'bg-red-100 text-red-700','study'=>'bg-purple-100 text-purple-700','compassionate'=>'bg-amber-100 text-amber-700','maternity'=>'bg-pink-100 text-pink-700','paternity'=>'bg-indigo-100 text-indigo-700']; @endphp
            <span class="text-xs px-2 py-0.5 rounded {{ $typeColors[$type] }}">{{ $label }}</span>
            @endforeach
        </div>
    </div>
    </div>

</div>
@endsection

@section('scripts')
@include('components.live-search')
@endsection
