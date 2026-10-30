<div class="bg-white rounded-xl shadow-sm p-5">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2"><i class="fas fa-gavel text-red-500"></i> Disciplinary Records</h3>
        @if($showAdmin)<a href="{{ route('disciplines.create',$officer) }}" class="text-xs text-blue-600 hover:underline">+ Add</a>@endif
    </div>
    @forelse($officer->disciplines as $disc)
    <div class="flex items-start gap-3 py-2 border-b border-gray-50 last:border-0">
        <span class="text-xs px-2 py-0.5 rounded-full flex-shrink-0 {{ $disc->severity==='major'?'bg-red-100 text-red-700':'bg-amber-100 text-amber-700' }}">{{ ucfirst($disc->severity) }}</span>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-800">{{ $disc->charge }}</p>
            <p class="text-xs text-gray-400">{{ $disc->date_of_trial?->format('d M Y') }} · Outcome: {{ $disc->outcome ?: '—' }}</p>
            @if($disc->punishment)<p class="text-xs text-gray-500">Punishment: {{ $disc->punishment }}</p>@endif
        </div>
        @if($showAdmin)
        <form method="POST" action="{{ route('disciplines.destroy',$disc) }}" class="inline">@csrf @method('DELETE')<button type="button" class="btn-delete text-red-400 hover:text-red-600 text-xs"><i class="fas fa-trash"></i></button></form>
        @endif
    </div>
    @empty<p class="text-sm text-gray-400 py-2">No disciplinary records.</p>@endforelse
</div>
