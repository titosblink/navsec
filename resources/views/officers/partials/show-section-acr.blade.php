<div class="bg-white rounded-xl shadow-sm p-5">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2"><i class="fas fa-clipboard text-green-500"></i> Annual Confidential Reports</h3>
        @if($showAdmin)<a href="{{ route('acrs.create',$officer) }}" class="text-xs text-blue-600 hover:underline">+ Add ACR</a>@endif
    </div>
    @forelse($officer->acrs as $acr)
    <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
        <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded flex-shrink-0">{{ $acr->report_year }}</span>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-800">Reporting: {{ $acr->reporting_officer ?: '—' }}</p>
            <p class="text-xs text-gray-400">Rating: {{ $acr->overall_rating ?? '—' }}/9 · Fitness: {{ $acr->fitness_category ?: '—' }}</p>
        </div>
        @if($showAdmin)
        <form method="POST" action="{{ route('acrs.destroy',$acr) }}" class="inline">@csrf @method('DELETE')<button type="button" class="btn-delete text-red-400 hover:text-red-600 text-xs"><i class="fas fa-trash"></i></button></form>
        @endif
    </div>
    @empty<p class="text-sm text-gray-400 py-2">No ACR records.</p>@endforelse
</div>
