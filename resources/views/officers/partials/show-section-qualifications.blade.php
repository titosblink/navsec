<div class="bg-white rounded-xl shadow-sm p-5">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2"><i class="fas fa-award text-indigo-500"></i> Qualifications & Education</h3>
        @if($showAdmin)<a href="{{ route('qualifications.create',$officer) }}" class="text-xs text-blue-600 hover:underline">+ Add</a>@endif
    </div>
    @forelse($officer->qualifications as $qual)
    <div class="flex items-start gap-3 py-2 border-b border-gray-50 last:border-0">
        <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-0.5 rounded flex-shrink-0">{{ ucfirst($qual->qual_type) }}</span>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-800">{{ $qual->title }}</p>
            <p class="text-xs text-gray-400">{{ $qual->institution ?: '—' }} @if($qual->year_obtained)· {{ $qual->year_obtained }}@endif @if($qual->grade_class)· {{ $qual->grade_class }}@endif</p>
        </div>
        @if($showAdmin)
        <form method="POST" action="{{ route('qualifications.destroy',$qual) }}" class="inline">@csrf @method('DELETE')<button type="button" class="btn-delete text-red-400 hover:text-red-600 text-xs"><i class="fas fa-trash"></i></button></form>
        @endif
    </div>
    @empty<p class="text-sm text-gray-400 py-2">No qualifications recorded.</p>@endforelse
</div>
