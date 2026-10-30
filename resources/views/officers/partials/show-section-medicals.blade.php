<div class="bg-white rounded-xl shadow-sm p-5">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2"><i class="fas fa-heartbeat text-pink-500"></i> Medical Records</h3>
        @if($showAdmin)<a href="{{ route('medicals.create',$officer) }}" class="text-xs text-blue-600 hover:underline">+ Add</a>@endif
    </div>
    @forelse($officer->medicals as $med)
    <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
        <span class="text-xs font-bold px-2 py-0.5 rounded flex-shrink-0
            {{ $med->medical_category==='A1'?'bg-green-100 text-green-700':($med->medical_category==='A2'?'bg-blue-100 text-blue-700':'bg-amber-100 text-amber-700') }}">
            {{ $med->medical_category ?: '—' }}
        </span>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-800">{{ $med->exam_date->format('d M Y') }} · {{ ucfirst($med->fitness_status) }}</p>
            <p class="text-xs text-gray-400">{{ $med->hospital_facility ?: ($med->examining_officer ?: '—') }}</p>
        </div>
        @if($med->next_exam_date)<span class="text-xs text-gray-400">Next: {{ $med->next_exam_date->format('d M Y') }}</span>@endif
        @if($showAdmin)
        <form method="POST" action="{{ route('medicals.destroy',$med) }}" class="inline">@csrf @method('DELETE')<button type="button" class="btn-delete text-red-400 hover:text-red-600 text-xs"><i class="fas fa-trash"></i></button></form>
        @endif
    </div>
    @empty<p class="text-sm text-gray-400 py-2">No medical records.</p>@endforelse
</div>
