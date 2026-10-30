@extends('layouts.app')
@section('title', 'Officers')
@section('header', 'Officers')
@section('breadcrumb', 'All personnel records')

@section('header-actions')
{{-- View toggle (table/grid) --}}

<a href="{{ route('reports.officers.export', request()->only(['search','rank','branch','status'])) }}"
   class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-file-export text-green-600"></i> <span class="hidden sm:inline">Export</span>
</a>
@if(in_array(auth()->user()->role, ['admin','hr']))
<a href="{{ route('officers.import.form') }}"
   class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-file-import text-blue-500"></i> <span class="hidden sm:inline">Import</span>
</a>
<a href="{{ route('officers.create') }}"
   class="flex items-center gap-2 text-white text-sm px-3 py-2 rounded-lg font-medium transition-colors"
   style="background:#0b1d55;">
    <i class="fas fa-user-plus"></i> <span class="hidden sm:inline">Add Officer</span>
</a>
@endif
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm">

    {{-- ── Filters bar ── --}}
    <div class="p-4 border-b border-gray-100">
        <div class="flex flex-wrap gap-3 items-end">
        @php $activeFilterCount = collect(['search','rank','branch','status'])->filter(fn($k)=>request()->filled($k))->count(); @endphp

            {{-- Live search input --}}
            <div class="relative flex-1 min-w-48">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                <input type="text" id="live-search" value="{{ request('search') }}"
                       placeholder="Search name, service no, ship, appointment…"
                       class="w-full border border-gray-300 rounded-lg pl-9 pr-9 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none">
                <button id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-500 text-sm {{ request('search') ? '' : 'hidden' }}">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            {{-- Rank filter --}}
            <select id="filter-rank" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                <option value="">All Ranks</option>
                @foreach($ranks as $r)<option value="{{ $r }}" {{ request('rank')===$r?'selected':'' }}>{{ $r }}</option>@endforeach
            </select>

            {{-- Branch filter --}}
            <select id="filter-branch" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                <option value="">All Branches</option>
                @foreach($branches as $code => $name)<option value="{{ $code }}" {{ request('branch')===$code?'selected':'' }}>{{ $name }}</option>@endforeach
            </select>

            {{-- Status filter --}}
            <select id="filter-status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                <option value="">All Status</option>
                <option value="S" {{ request('status')==='S'?'selected':'' }}>Serving</option>
                <option value="R" {{ request('status')==='R'?'selected':'' }}>Retired</option>
            </select>

            {{-- Active filter badge + spinner --}}
            <div class="flex items-center gap-2">
                <div id="search-spinner" class="hidden">
                    <i class="fas fa-circle-notch fa-spin text-blue-400"></i>
                </div>
                <span id="filter-count-badge"
                      class="text-xs font-semibold bg-blue-100 text-blue-700 px-2 py-1 rounded-full {{ $activeFilterCount > 0 ? '' : 'hidden' }}">
                    <i class="fas fa-filter mr-1"></i><span id="filter-count-num">{{ $activeFilterCount }}</span> filter{{ $activeFilterCount !== 1 ? 's' : '' }} active
                </span>
            </div>
        </div>

        {{-- Active filter chips --}}
        <div id="filter-chips" class="flex flex-wrap gap-2 mt-2 {{ request()->anyFilled(['search','rank','branch','status']) ? '' : 'hidden' }}">
            <span class="text-xs text-gray-400 self-center">Active filters:</span>
            @if(request('search'))
            <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                "{{ request('search') }}"
                <button onclick="clearFilter('search')" class="hover:text-blue-900"><i class="fas fa-xmark"></i></button>
            </span>
            @endif
            @if(request('rank'))
            <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                Rank: {{ request('rank') }}
                <button onclick="clearFilter('rank')" class="hover:text-blue-900"><i class="fas fa-xmark"></i></button>
            </span>
            @endif
            @if(request('branch'))
            <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                Branch: {{ request('branch') }}
                <button onclick="clearFilter('branch')" class="hover:text-blue-900"><i class="fas fa-xmark"></i></button>
            </span>
            @endif
            @if(request('status'))
            <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full flex items-center gap-1">
                {{ request('status') === 'S' ? 'Serving' : 'Retired' }}
                <button onclick="clearFilter('status')" class="hover:text-blue-900"><i class="fas fa-xmark"></i></button>
            </span>
            @endif
        </div>
    </div>

    {{-- ── Filter presets bar ── --}}
    <div class="px-4 pb-2 flex flex-wrap gap-2 items-center" id="presets-bar">
        <span class="text-xs text-gray-400">Presets:</span>
        <div id="presets-list" class="flex flex-wrap gap-1.5"></div>
        <button onclick="savePreset()" class="text-xs text-blue-500 hover:underline ml-1" title="Save current filters as preset">
            <i class="fas fa-bookmark mr-1"></i>Save
        </button>
    </div>

    {{-- ── Bulk action bar ── --}}
    <div id="bulk-bar" class="hidden px-4 py-2 bg-blue-50 border-b border-blue-100 flex items-center gap-3">
        <span id="bulk-count" class="text-sm font-semibold text-blue-700">0 selected</span>
        <button onclick="bulkAction('export')"
                class="text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg transition-colors">
            <i class="fas fa-file-export mr-1"></i>Export Selected
        </button>
        @if(auth()->user()->role === 'admin')
        <button onclick="bulkAction('retire')"
                class="text-xs bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg transition-colors">
            <i class="fas fa-user-clock mr-1"></i>Mark as Retired
        </button>
        @endif
        <button onclick="clearSelection()" class="text-xs text-gray-500 hover:text-gray-700 ml-auto">
            <i class="fas fa-xmark mr-1"></i>Clear
        </button>
    </div>

    {{-- ── Per-page + sort info ── --}}
    <div class="px-4 py-2 border-b border-gray-50 flex items-center justify-between text-xs text-gray-400">
        <span id="total-count-label">{{ $officers->total() }} officer{{ $officers->total() !== 1 ? 's' : '' }} found</span>
        <div class="flex items-center gap-2">
            <label>Show:</label>
            <select id="per-page-select" class="border border-gray-200 rounded px-2 py-1 text-xs focus:outline-none">
                @foreach([20,50,100] as $pp)
                <option value="{{ $pp }}" {{ ($perPage ?? 20) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- ── Table view ── --}}
    <div id="view-table-wrap" class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-left sticky-thead">
                @php
                    $cols = [
                        'rank'       => 'Rank',
                        'name'       => 'Name',
                        'svc_no'     => 'Svc No',
                        'branch'     => 'Branch',
                        'pres_ship'  => 'Present Ship',
                        ''           => 'Current Appointment',
                        'servng_rtd' => 'Status',
                    ];
                @endphp
                
            </thead>
            @include('officers.partials.table')
        </table>
    </div>

    {{-- ── Grid / Yearbook view ── --}}
    <div id="view-grid-wrap" class="hidden p-4">
        <div id="grid-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($officers as $officer)
            <a href="{{ route('officers.show', $officer) }}"
               class="bg-gray-50 rounded-xl p-4 text-center hover:bg-blue-50 hover:shadow-md transition-all group border border-gray-100">
                @if($officer->photo)
                <img src="{{ $officer->photoUrl() }}" class="w-16 h-16 rounded-full object-cover mx-auto mb-2 border-2 border-white shadow">
                @else
                <div class="w-16 h-16 rounded-full mx-auto mb-2 flex items-center justify-center text-white font-bold text-xl border-2 border-white shadow"
                     style="background:linear-gradient(135deg,#0b1d55,#1e40af);">
                    {{ strtoupper(substr($officer->name ?? 'N', 0, 1)) }}
                </div>
                @endif
                <p class="text-xs font-bold text-gray-800 truncate group-hover:text-blue-700">{{ $officer->name }}</p>
                <span class="text-xs font-bold px-1.5 py-0.5 rounded text-white inline-block mt-1 bg-blue-600">
                    {{ $officer->rank ?: '—' }}
                </span>
                <p class="text-xs text-gray-400 font-mono mt-1">{{ $officer->svc_no }}</p>
            </a>
            @endforeach
        </div>
        <div class="mt-4" id="grid-pagination-area">{{ $officers->withQueryString()->links() }}</div>
    </div>

</div>
@endsection

@section('scripts')
<script>
(function(){
    let debounceTimer;
    const baseUrl = window.location.origin + window.location.pathname.replace(/\/$/, '');

    let currentSort      = '{{ $sort ?? "name" }}';
    let currentDirection = '{{ $direction ?? "asc" }}';
    let currentPerPage   = {{ $perPage ?? 20 }};

    function getFilters() {
        return {
            search:    $('#live-search').val().trim(),
            rank:      $('#filter-rank').val(),
            branch:    $('#filter-branch').val(),
            status:    $('#filter-status').val(),
            sort:      currentSort,
            direction: currentDirection,
            per_page:  currentPerPage,
        };
    }

    function fetchTable(params) {
        $('#search-spinner').removeClass('hidden');
        $.ajax({
            url: baseUrl,
            data: params,
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success(response) {
                let htmlContent = response;
                if (typeof response === 'object' && response !== null) {
                    htmlContent = response.html || '';
                    if (response.total !== undefined) {
                        $('#total-count-label').text(response.total + ' officer' + (response.total !== 1 ? 's' : '') + ' found');
                    }
                }

                const $table = $('#view-table-wrap table');
                $table.find('tbody, tfoot').remove();
                $('<table>').append(htmlContent).find('tbody, tfoot').appendTo($table);

                const qs = $.param(Object.fromEntries(Object.entries(params).filter(([,v])=>v)));
                history.replaceState(null, '', qs ? baseUrl + '?' + qs : baseUrl);

                updateChips(params);
                bindDeletes();
            },
            complete() { $('#search-spinner').addClass('hidden'); }
        });
    }

    function updateChips(params) {
        const hasAny = Object.values(params).some(v => v);
        $('#filter-chips').toggleClass('hidden', !hasAny);
        const count = Object.values(params).filter(v => v).length;
        const badge = $('#filter-count-badge');
        if (count > 0) {
            $('#filter-count-num').text(count);
            badge.find('span').last().text(' filter' + (count !== 1 ? 's' : '') + ' active');
            badge.removeClass('hidden');
        } else {
            badge.addClass('hidden');
        }
    }

    function trigger() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchTable(getFilters()), 320);
    }

    $('#live-search').on('input', function(){
        $('#clear-search').toggleClass('hidden', !this.value);
        trigger();
    });

    $('#clear-search').on('click', function(){
        $('#live-search').val('');
        $(this).addClass('hidden');
        trigger();
    });

    $('#filter-rank, #filter-branch, #filter-status').on('change', trigger);

    window.clearFilter = function(key) {
        if (key === 'search') { $('#live-search').val(''); $('#clear-search').addClass('hidden'); }
        if (key === 'rank')   $('#filter-rank').val('');
        if (key === 'branch') $('#filter-branch').val('');
        if (key === 'status') $('#filter-status').val('');
        fetchTable(getFilters());
    };

    $(document).on('click', '#pagination-area a, #grid-pagination-area a', function(e){
        e.preventDefault();
        const href = $(this).attr('href');
        const qPos = href.indexOf('?');
        const qs = qPos !== -1 ? href.slice(qPos + 1) : '';
        const params = qs ? Object.fromEntries(new URLSearchParams(qs)) : {};
        Object.assign(params, getFilters());
        fetchTable(params);
        window.scrollTo({top:0,behavior:'smooth'});
    });

    function bindDeletes() {}

    $('#per-page-select').on('change', function() {
        currentPerPage = parseInt($(this).val());
        fetchTable(getFilters());
    });

    window.sortBy = function(col) {
        if (currentSort === col) {
            currentDirection = currentDirection === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort = col;
            currentDirection = 'asc';
        }
        fetchTable(getFilters());
    };

    let selectedIds = new Set();

    $(document).on('change', '#select-all', function() {
        const checked = this.checked;
        $('tbody .row-checkbox').prop('checked', checked);
        if (checked) {
            $('tbody .row-checkbox').each(function() { selectedIds.add($(this).val()); });
        } else {
            selectedIds.clear();
        }
        updateBulkBar();
    });

    $(document).on('change', '.row-checkbox', function() {
        if (this.checked) selectedIds.add(this.value);
        else selectedIds.delete(this.value);
        updateBulkBar();
        $('#select-all').prop('indeterminate', selectedIds.size > 0 && selectedIds.size < $('tbody .row-checkbox').length);
    });

    function updateBulkBar() {
        if (selectedIds.size > 0) {
            $('#bulk-bar').removeClass('hidden');
            $('#bulk-count').text(selectedIds.size + ' selected');
        } else {
            $('#bulk-bar').addClass('hidden');
        }
    }

    window.clearSelection = function() {
        selectedIds.clear();
        $('.row-checkbox, #select-all').prop('checked', false);
        updateBulkBar();
    };

    window.bulkAction = function(action) {
        if (!selectedIds.size) return;
        if (action === 'export') {
            const form = $('<form method="POST" action="{{ route("officers.bulk-action") }}" style="display:none">');
            form.append('<input type="hidden" name="_token" value="{{ csrf_token() }}">');
            form.append('<input type="hidden" name="action" value="export">');
            selectedIds.forEach(id => form.append(`<input type="hidden" name="ids[]" value="${id}">`));
            $('body').append(form);
            form.submit();
        } else if (action === 'retire') {
            Swal.fire({
                title: 'Mark as Retired?',
                text: `Mark ${selectedIds.size} officer(s) as Retired?`,
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#d97706', confirmButtonText: 'Yes, retire them',
            }).then(result => {
                if (!result.isConfirmed) return;
                fetch('{{ route("officers.bulk-action") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ action: 'retire', ids: [...selectedIds] })
                }).then(r => r.json()).then(data => {
                    showToast('success', 'Done', data.message);
                    clearSelection();
                    fetchTable(getFilters());
                });
            });
        }
    };

    window.setView = function(v) {
        localStorage.setItem('officersView', v);
        if (v === 'grid') {
            $('#view-table-wrap').addClass('hidden');
            $('#view-grid-wrap').removeClass('hidden');
            $('#view-table').removeClass('bg-blue-50 text-blue-600').addClass('text-gray-400');
            $('#view-grid').addClass('bg-blue-50 text-blue-600').removeClass('text-gray-400');
        } else {
            $('#view-grid-wrap').addClass('hidden');
            $('#view-table-wrap').removeClass('hidden');
            $('#view-grid').removeClass('bg-blue-50 text-blue-600').addClass('text-gray-400');
            $('#view-table').addClass('bg-blue-50 text-blue-600').removeClass('text-gray-400');
        }
    };

    if (localStorage.getItem('officersView') === 'grid') setView('grid');

    function renderPresets() {
        const presets = JSON.parse(localStorage.getItem('officerPresets') || '[]');
        const list = $('#presets-list');
        list.empty();
        if (!presets.length) { $('#presets-bar').addClass('hidden'); return; }
        $('#presets-bar').removeClass('hidden');
        presets.forEach((p, i) => {
            list.append(`<button onclick="loadPreset(${i})" class="text-xs bg-gray-100 hover:bg-blue-100 text-gray-600 hover:text-blue-700 px-2.5 py-1 rounded-full transition-colors flex items-center gap-1">
                <i class="fas fa-bookmark text-xs"></i>${p.name}
                <span onclick="event.stopPropagation();deletePreset(${i})" class="ml-1 text-gray-400 hover:text-red-500">&times;</span>
            </button>`);
        });
    }

    window.savePreset = function() {
        const filters = getFilters();
        const hasFilters = filters.search || filters.rank || filters.branch || filters.status;
        if (!hasFilters) { showToast('warning','No filters','Apply filters first to save a preset.'); return; }
        const name = prompt('Preset name (e.g. "Engineers at WNC"):');
        if (!name) return;
        const presets = JSON.parse(localStorage.getItem('officerPresets') || '[]');
        presets.push({ name, ...filters });
        localStorage.setItem('officerPresets', JSON.stringify(presets));
        renderPresets();
        showToast('success','Preset saved', '"' + name + '" saved.');
    };

    window.loadPreset = function(i) {
        const presets = JSON.parse(localStorage.getItem('officerPresets') || '[]');
        const p = presets[i];
        if (!p) return;
        $('#live-search').val(p.search || '');
        $('#filter-rank').val(p.rank || '');
        $('#filter-branch').val(p.branch || '');
        $('#filter-status').val(p.status || '');
        $('#clear-search').toggleClass('hidden', !p.search);
        currentSort = p.sort || 'name';
        currentDirection = p.direction || 'asc';
        fetchTable(getFilters());
    };

    window.deletePreset = function(i) {
        const presets = JSON.parse(localStorage.getItem('officerPresets') || '[]');
        presets.splice(i, 1);
        localStorage.setItem('officerPresets', JSON.stringify(presets));
        renderPresets();
    };

    renderPresets();

})();
</script>
@endsection