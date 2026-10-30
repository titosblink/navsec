<div class="bg-white rounded-xl shadow-sm p-5">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2"><i class="fas fa-folder-open text-gray-500"></i> Documents</h3>
        @if($showAdmin)<a href="{{ route('documents.create',$officer) }}" class="text-xs text-blue-600 hover:underline">+ Upload</a>@endif
    </div>
    @forelse($officer->documents as $doc)
    @php $ext=strtolower(pathinfo($doc->original_name??'',PATHINFO_EXTENSION)); $isImg=in_array($ext,['jpg','jpeg','png']); $isPdf=$ext==='pdf'; @endphp
    <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $isImg?'bg-blue-100':($isPdf?'bg-red-50':'bg-gray-100') }}">
            <i class="fas {{ $isImg?'fa-image text-blue-500':($isPdf?'fa-file-pdf text-red-500':'fa-file text-gray-400') }} text-sm"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-800 truncate">{{ $doc->title }}</p>
            <p class="text-xs text-gray-400">{{ str_replace('_',' ',ucfirst($doc->document_type)) }} · {{ $doc->created_at->format('d M Y') }}</p>
        </div>
        <div class="flex items-center gap-2">
            @if($isImg || $isPdf)
            <button onclick="openDocViewer('{{ $doc->fileUrl() }}','{{ addslashes($doc->title) }}','{{ $isPdf ? 'pdf' : 'img' }}')"
                    class="text-xs text-blue-500 hover:text-blue-700" title="View">
                <i class="fas fa-eye"></i>
            </button>
            @endif
            <a href="{{ $doc->fileUrl() }}" target="_blank" class="text-xs text-gray-400 hover:text-gray-600" title="Open in new tab">
                <i class="fas fa-external-link-alt"></i>
            </a>
            @if($showAdmin)
            <form method="POST" action="{{ route('documents.destroy',$doc) }}" class="inline">@csrf @method('DELETE')<button type="button" class="btn-delete text-red-400 hover:text-red-600 text-xs"><i class="fas fa-trash"></i></button></form>
            @endif
        </div>
    </div>
    @empty<p class="text-sm text-gray-400 py-2">No documents uploaded.</p>@endforelse
</div>

{{-- Document viewer modal --}}
<div id="doc-viewer-modal" class="hidden fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4" onclick="if(event.target===this)closeDocViewer()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <p id="doc-viewer-title" class="font-semibold text-gray-800 text-sm truncate"></p>
            <div class="flex items-center gap-2">
                <a id="doc-viewer-download" href="#" target="_blank" class="text-xs text-blue-500 hover:underline"><i class="fas fa-download mr-1"></i>Download</a>
                <button onclick="closeDocViewer()" class="text-gray-400 hover:text-gray-600 ml-3"><i class="fas fa-xmark text-lg"></i></button>
            </div>
        </div>
        <div id="doc-viewer-body" class="flex-1 overflow-auto flex items-center justify-center bg-gray-50 min-h-64"></div>
    </div>
</div>

<script>
function openDocViewer(url, title, type) {
    document.getElementById('doc-viewer-title').textContent = title;
    document.getElementById('doc-viewer-download').href = url;
    const body = document.getElementById('doc-viewer-body');
    if (type === 'pdf') {
        body.innerHTML = `<iframe src="${url}" class="w-full" style="height:70vh;border:none;"></iframe>`;
    } else {
        body.innerHTML = `<img src="${url}" class="max-w-full max-h-full object-contain p-4">`;
    }
    document.getElementById('doc-viewer-modal').classList.remove('hidden');
}
function closeDocViewer() {
    document.getElementById('doc-viewer-modal').classList.add('hidden');
    document.getElementById('doc-viewer-body').innerHTML = '';
}
</script>
