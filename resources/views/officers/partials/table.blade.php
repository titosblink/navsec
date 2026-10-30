@php $rankNames = \App\Models\Officer::rankFullNames(); @endphp
<tbody id="officers-tbody" class="divide-y divide-gray-100">
    @forelse($officers as $officer)
    @php $badgeClass = \App\Models\Officer::rankBadgeClass($officer->rank ?? ''); @endphp
    <tr class="hover:bg-gray-50 transition-colors">

        {{-- Checkbox --}}
        <td class="px-3 py-3 w-8">
            <input type="checkbox" class="row-checkbox rounded" value="{{ $officer->id }}">
        </td>

        {{-- Rank badge --}}
        <td class="px-4 py-3">
            <span class="text-xs font-bold px-2 py-0.5 rounded text-white {{ $badgeClass }}"
                  title="{{ $rankNames[$officer->rank] ?? $officer->rank }}">
                {{ $officer->rank ?: '—' }}
            </span>
        </td>

        {{-- Avatar + Name --}}
        <td class="px-4 py-3">
            <div class="flex items-center gap-2.5">
                @if($officer->photo)
                    <img src="{{ $officer->photoUrl() }}" alt=""
                         class="w-8 h-8 rounded-full object-cover flex-shrink-0 border border-gray-200">
                @else
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                         style="background:linear-gradient(135deg,#0b1d55,#1e40af);">
                        {{ strtoupper(substr($officer->name ?? 'N', 0, 1)) }}
                    </div>
                @endif
                <a href="{{ route('officers.show', $officer) }}"
                   class="font-medium text-gray-800 hover:text-blue-700 transition-colors">
                    {{ $officer->name }}
                </a>
            </div>
        </td>

        {{-- Service Number with copy --}}
        <td class="px-4 py-3">
            <div class="flex items-center gap-1 group">
                <span class="text-gray-500 font-mono text-xs">{{ $officer->svc_no }}</span>
                <button onclick="copyToClipboard('{{ $officer->svc_no }}', this)"
                        class="opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-blue-500 p-0.5 rounded"
                        title="Copy service number">
                    <i class="fas fa-copy text-xs"></i>
                </button>
            </div>
        </td>

        <td class="px-4 py-3 text-gray-500 text-sm">{{ $officer->branch ?: '—' }}</td>
        <td class="px-4 py-3 text-gray-600 text-sm">{{ $officer->pres_ship ?: '—' }}</td>
        <td class="px-4 py-3 text-gray-600 text-sm max-w-xs truncate">{{ $officer->pre_appt ?: '—' }}</td>

        <td class="px-4 py-3">
            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                {{ $officer->servng_rtd === 'R' ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-700' }}">
                {{ $officer->servng_rtd === 'R' ? 'Retired' : 'Serving' }}
            </span>
        </td>

        <td class="px-4 py-3">
            <div class="flex items-center justify-center gap-2">
                <a href="{{ route('officers.show', $officer) }}"
                   class="text-blue-500 hover:text-blue-700 transition-colors" title="View Profile">
                    <i class="fas fa-eye"></i>
                </a>
                @if(in_array(auth()->user()->role, ['admin','hr']))
                <a href="{{ route('officers.edit', $officer) }}"
                   class="text-amber-500 hover:text-amber-700 transition-colors" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
                @endif
                @if(auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('officers.destroy', $officer) }}" class="inline">
                    @csrf @method('DELETE')
                    <button type="button" class="btn-delete text-red-500 hover:text-red-700 transition-colors" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                @endif
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="9" class="px-4 py-16 text-center">
            <i class="fas fa-user-slash text-gray-200 text-4xl mb-3 block"></i>
            <p class="text-gray-500 font-medium">No officers found</p>
            @if(request()->anyFilled(['search','rank','branch','status']))
            <p class="text-gray-400 text-sm mt-1">No results for the active filters.</p>
            <div class="flex items-center justify-center gap-3 mt-3">
                <button onclick="clearFilter('search');clearFilter('rank');clearFilter('branch');clearFilter('status')"
                        class="text-sm text-blue-600 hover:underline">
                    <i class="fas fa-xmark mr-1"></i>Clear all filters
                </button>
                @if(in_array(auth()->user()->role, ['admin','hr']))
                <span class="text-gray-300">·</span>
                <a href="{{ route('officers.create') }}" class="text-sm text-blue-600 hover:underline">
                    <i class="fas fa-user-plus mr-1"></i>Add officer
                </a>
                <span class="text-gray-300">·</span>
                <a href="{{ route('officers.import.form') }}" class="text-sm text-blue-600 hover:underline">
                    <i class="fas fa-file-import mr-1"></i>Import from Excel
                </a>
                @endif
            </div>
            @else
            <p class="text-gray-400 text-sm mt-1">Get started by adding your first officer.</p>
            @if(in_array(auth()->user()->role, ['admin','hr']))
            <div class="flex items-center justify-center gap-3 mt-3">
                <a href="{{ route('officers.create') }}" class="text-sm text-blue-600 hover:underline"><i class="fas fa-user-plus mr-1"></i>Add officer</a>
                <span class="text-gray-300">·</span>
                <a href="{{ route('officers.import.form') }}" class="text-sm text-blue-600 hover:underline"><i class="fas fa-file-import mr-1"></i>Import from Excel</a>
            </div>
            @endif
            @endif
        </td>
    </tr>
    @endforelse
</tbody>
<tfoot>
    <tr>
        <td colspan="9" class="px-4 py-3 border-t border-gray-100" id="pagination-area">
            {{ $officers->withQueryString()->links() }}
        </td>
    </tr>
</tfoot>
