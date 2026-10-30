<div class="bg-white rounded-xl shadow-sm p-5">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2"><i class="fas fa-calendar-check text-amber-500"></i> Leave Records</h3>
        @if($showAdmin)<a href="{{ route('leaves.create',['officer_id'=>$officer->id]) }}" class="text-xs text-blue-600 hover:underline">+ Add Leave</a>@endif
    </div>
    @php $balance = $officer->leaveBalance(); $currentYear = date('Y'); @endphp
    <div class="mb-4 grid grid-cols-2 md:grid-cols-3 gap-3">
        @foreach($balance as $type => $b)
        @if($b['entitled'] > 0)
        <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
            <p class="text-xs font-semibold text-gray-600 capitalize mb-1">{{ $type }}</p>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                @php $pct = $b['entitled'] > 0 ? min(100,round($b['used']/$b['entitled']*100)) : 0; @endphp
                <div class="h-1.5 rounded-full {{ $pct >= 100 ? 'bg-red-400' : ($pct >= 70 ? 'bg-amber-400' : 'bg-green-400') }}" style="width:{{ $pct }}%"></div>
            </div>
            <p class="text-xs text-gray-500">{{ $b['remaining'] }} / {{ $b['entitled'] }} days remaining</p>
        </div>
        @endif
        @endforeach
    </div>
    <p class="text-xs text-gray-400 mb-3">Leave balance for {{ $currentYear }}</p>
    @forelse($officer->leaves as $leave)
    <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
        <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2 py-0.5 rounded flex-shrink-0">{{ ucfirst($leave->leave_type) }}</span>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-800">{{ $leave->start_date->format('d M Y') }} — {{ $leave->end_date->format('d M Y') }}</p>
            <p class="text-xs text-gray-400">{{ $leave->days }} days · {{ ucfirst($leave->status) }}</p>
        </div>
        @php $lc=['pending'=>'bg-amber-100 text-amber-700','approved'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700','completed'=>'bg-gray-100 text-gray-600']; @endphp
        <span class="text-xs px-2 py-0.5 rounded-full {{ $lc[$leave->status] ?? '' }}">{{ ucfirst($leave->status) }}</span>
        @if($showAdmin)
        <form method="POST" action="{{ route('leaves.destroy',$leave) }}" class="inline">@csrf @method('DELETE')<button type="button" class="btn-delete text-red-400 hover:text-red-600 text-xs"><i class="fas fa-trash"></i></button></form>
        @endif
    </div>
    @empty<p class="text-sm text-gray-400 py-2">No leave records.</p>@endforelse
</div>
