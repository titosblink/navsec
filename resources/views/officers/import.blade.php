@extends('layouts.app')
@section('title','Bulk Import Officers')
@section('header','Bulk Import Officers')
@section('header-actions')
<a href="{{ route('officers.index') }}" class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Officers
</a>
@endsection

@section('content')
<div class="max-w-2xl space-y-5">

    {{-- ── Import result ── --}}
    @if(session('import_result'))
    @php $result = session('import_result'); @endphp
    <div class="rounded-xl border p-5 {{ $result['imported'] > 0 ? 'bg-green-50 border-green-200' : 'bg-amber-50 border-amber-200' }}">
        <div class="flex items-start gap-3">
            <i class="fas {{ $result['imported'] > 0 ? 'fa-circle-check text-green-500' : 'fa-triangle-exclamation text-amber-500' }} text-xl mt-0.5"></i>
            <div class="flex-1">
                <p class="font-semibold {{ $result['imported'] > 0 ? 'text-green-800' : 'text-amber-800' }}">Import Complete</p>
                <div class="flex gap-5 mt-2 text-sm">
                    <span class="flex items-center gap-1.5 text-green-700">
                        <i class="fas fa-check-circle"></i>
                        <strong>{{ $result['imported'] }}</strong> officer{{ $result['imported'] !== 1 ? 's' : '' }} added
                    </span>
                    <span class="flex items-center gap-1.5 text-gray-500">
                        <i class="fas fa-forward"></i>
                        <strong>{{ $result['skipped'] }}</strong> skipped
                    </span>
                </div>
                @if(count($result['errors']))
                <div class="mt-3 bg-white rounded-lg border border-red-100 p-3 space-y-1 max-h-48 overflow-y-auto">
                    @foreach($result['errors'] as $err)
                    <p class="text-xs text-red-600 flex items-start gap-1.5"><i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>{{ $err }}</p>
                    @endforeach
                </div>
                @endif
                @if($result['imported'] > 0)
                <a href="{{ route('officers.index') }}" class="inline-flex items-center gap-2 mt-3 text-sm text-green-700 font-medium hover:underline">
                    <i class="fas fa-users"></i> View Officers List
                </a>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ── Instructions ── --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
        <h3 class="font-semibold text-blue-800 mb-3 flex items-center gap-2">
            <i class="fas fa-info-circle"></i> How It Works
        </h3>
        <ol class="text-sm text-blue-700 space-y-1.5 list-decimal list-inside mb-4">
            <li>Download the Excel template below.</li>
            <li>Fill in officer records — one row per officer.</li>
            <li>Upload the completed file using the form below.</li>
        </ol>
        <div class="bg-white rounded-lg border border-blue-100 p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Required Columns (exact header names)</p>
            <div class="flex gap-2 flex-wrap">
                <span class="bg-blue-100 text-blue-800 font-mono text-sm px-3 py-1 rounded-lg font-semibold">svc_no</span>
                <span class="text-gray-400 self-center">—</span>
                <span class="text-sm text-gray-600 self-center">Officer's Service Number (e.g. N/A/00123)</span>
            </div>
            <div class="flex gap-2 flex-wrap mt-2">
                <span class="bg-blue-100 text-blue-800 font-mono text-sm px-3 py-1 rounded-lg font-semibold">rank</span>
                <span class="text-gray-400 self-center">—</span>
                <span class="text-sm text-gray-600 self-center">Rank code (e.g. LT, CDR, CAPT, RADM)</span>
            </div>
            <div class="flex gap-2 flex-wrap mt-2">
                <span class="bg-blue-100 text-blue-800 font-mono text-sm px-3 py-1 rounded-lg font-semibold">name</span>
                <span class="text-gray-400 self-center">—</span>
                <span class="text-sm text-gray-600 self-center">Full name in CAPITALS (e.g. JOHN ADEBAYO SMITH)</span>
            </div>
            <p class="text-xs text-blue-500 mt-3">All three columns are required. Rows with a service number already in the system are skipped.</p>
        </div>

        <a href="{{ route('officers.import.template') }}"
           class="mt-4 inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2.5 rounded-lg font-medium transition-colors">
            <i class="fas fa-file-excel"></i> Download Excel Template
        </a>
    </div>

    {{-- ── Upload Form ── --}}
    <form method="POST" action="{{ route('officers.import') }}" enctype="multipart/form-data"
          class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        @csrf

        @error('file')
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ $message }}
        </div>
        @enderror

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Select File</label>
            <div id="drop-zone"
                 class="border-2 border-dashed border-gray-300 rounded-xl p-10 text-center hover:border-blue-400 hover:bg-blue-50 transition-colors cursor-pointer"
                 onclick="document.getElementById('import-file').click()">
                <i class="fas fa-file-excel text-green-500 text-5xl mb-3 block"></i>
                <p class="text-gray-600 font-medium">Click to choose or drag & drop your file</p>
                <p class="text-gray-400 text-sm mt-1">.xlsx, .xls, .csv — max 5 MB</p>
                <p id="import-filename" class="text-blue-600 text-sm mt-3 font-semibold hidden"></p>
            </div>
            <input type="file" id="import-file" name="file"
                   accept=".xlsx,.xls,.csv" required class="hidden">
        </div>

        <button type="submit" id="submit-btn"
                class="w-full bg-blue-700 hover:bg-blue-800 text-white py-3 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2"
                style="background:#0b1d55;">
            <i class="fas fa-upload"></i>
            <span>Upload & Import</span>
        </button>
    </form>

    {{-- ── Rank codes reference ── --}}
    <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
        <h3 class="font-semibold text-gray-700 mb-3 flex items-center gap-2 text-sm">
            <i class="fas fa-list-ol text-gray-400"></i> Valid Rank Codes
        </h3>
        <div class="flex flex-wrap gap-2">
            @foreach(['MIDN','AG/SLT','SLT','LT','LT/CDR','CDR','CAPT','CDRE','RADM','VADM','ADM'] as $r)
            <span class="bg-white border border-gray-200 font-mono text-xs px-2.5 py-1 rounded-lg text-gray-600">{{ $r }}</span>
            @endforeach
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
(function () {
    const input    = document.getElementById('import-file');
    const label    = document.getElementById('import-filename');
    const dropZone = document.getElementById('drop-zone');
    const form     = dropZone.closest('form');
    const btn      = document.getElementById('submit-btn');

    function setFile(file) {
        if (!file) return;
        label.textContent = file.name;
        label.classList.remove('hidden');
        dropZone.classList.add('border-blue-400', 'bg-blue-50');
    }

    input.addEventListener('change', () => setFile(input.files[0]));

    // Drag & drop
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-blue-400','bg-blue-50'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-blue-400','bg-blue-50'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        if (e.dataTransfer.files[0]) {
            const dt = new DataTransfer();
            dt.items.add(e.dataTransfer.files[0]);
            input.files = dt.files;
            setFile(e.dataTransfer.files[0]);
        }
    });

    // Show loading state on submit
    form.addEventListener('submit', () => {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i><span>Importing…</span>';
    });
})();
</script>
@endsection
