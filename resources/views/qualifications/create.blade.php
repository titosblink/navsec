@extends('layouts.app')
@section('title','Add Qualification')
@section('header','Add Qualification — ' . $officer->name)
@section('header-actions')
<a href="{{ route('officers.show',$officer) }}" class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Profile
</a>
@endsection
@section('content')
<form method="POST" action="{{ route('qualifications.store',$officer) }}" class="max-w-xl">
    @csrf
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                <select name="qual_type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="">— Select —</option>
                    @foreach($types as $val => $label)<option value="{{ $val }}" {{ old('qual_type')===$val?'selected':'' }}>{{ $label }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Year Obtained</label>
                <input type="number" name="year_obtained" value="{{ old('year_obtained') }}" min="1970" max="{{ date('Y') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title / Name <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" placeholder="e.g. BSc Computer Science">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Institution</label>
            <input type="text" name="institution" value="{{ old('institution') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" placeholder="e.g. University of Lagos">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                <input type="text" name="country" value="{{ old('country','Nigeria') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Grade / Class</label>
                <input type="text" name="grade_class" value="{{ old('grade_class') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" placeholder="e.g. Second Class Upper">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
            <textarea name="remarks" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">{{ old('remarks') }}</textarea>
        </div>
    </div>
    <div class="mt-4 flex gap-3">
        <button type="submit" class="bg-[#0a1640] hover:bg-[#071230] text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
            <i class="fas fa-save mr-2"></i>Save Qualification
        </button>
        <a href="{{ route('officers.show',$officer) }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50">Cancel</a>
    </div>
</form>
@endsection
