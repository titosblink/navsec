@extends('layouts.app')
@section('title','Upload Document')
@section('header','Upload Document — ' . $officer->name)
@section('header-actions')
<a href="{{ route('officers.show',$officer) }}" class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Profile
</a>
@endsection
@section('content')
<form method="POST" action="{{ route('documents.store',$officer) }}" enctype="multipart/form-data" class="max-w-xl">
    @csrf
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Document Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" placeholder="e.g. BSc Computer Science Certificate">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Document Type <span class="text-red-500">*</span></label>
            <select name="document_type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                <option value="">— Select —</option>
                @foreach($types as $val => $label)<option value="{{ $val }}" {{ old('document_type')===$val?'selected':'' }}>{{ $label }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">File <span class="text-red-500">*</span></label>
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-colors" onclick="document.getElementById('doc-file').click()">
                <i class="fas fa-cloud-upload-alt text-gray-300 text-3xl mb-2 block"></i>
                <p class="text-sm text-gray-500">Click to choose a file</p>
                <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG, DOC, DOCX — max 10MB</p>
                <p id="file-chosen" class="text-xs text-blue-600 mt-2 hidden font-medium"></p>
            </div>
            <input type="file" id="doc-file" name="file" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="hidden"
                   onchange="document.getElementById('file-chosen').textContent=this.files[0].name; document.getElementById('file-chosen').classList.remove('hidden');">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
            <textarea name="remarks" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">{{ old('remarks') }}</textarea>
        </div>
    </div>
    <div class="mt-4 flex gap-3">
        <button type="submit" class="bg-[#0a1640] hover:bg-[#071230] text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
            <i class="fas fa-upload mr-2"></i>Upload Document
        </button>
        <a href="{{ route('officers.show',$officer) }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50">Cancel</a>
    </div>
</form>
@endsection
