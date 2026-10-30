@extends('layouts.app')
@section('title','Add Medical Record')
@section('header','Medical Examination — ' . $officer->name)
@section('header-actions')
<a href="{{ route('officers.show',$officer) }}" class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Profile
</a>
@endsection
@section('content')
<form method="POST" action="{{ route('medicals.store',$officer) }}" class="max-w-xl">
    @csrf
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Exam Date <span class="text-red-500">*</span></label>
                <input type="date" name="exam_date" value="{{ old('exam_date') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Next Exam Date</label>
                <input type="date" name="next_exam_date" value="{{ old('next_exam_date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Medical Category</label>
                <select name="medical_category" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="">— Select —</option>
                    @foreach(['A1','A2','B1','B2','C','D'] as $c)<option value="{{ $c }}" {{ old('medical_category')===$c?'selected':'' }}>{{ $c }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fitness Status</label>
                <select name="fitness_status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="fit" {{ old('fitness_status','fit')==='fit'?'selected':'' }}>Fit</option>
                    <option value="unfit" {{ old('fitness_status')==='unfit'?'selected':'' }}>Unfit</option>
                    <option value="restricted" {{ old('fitness_status')==='restricted'?'selected':'' }}>Restricted</option>
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Examining Officer</label>
            <input type="text" name="examining_officer" value="{{ old('examining_officer') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Hospital / Facility</label>
            <input type="text" name="hospital_facility" value="{{ old('hospital_facility') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Clinical Findings</label>
            <textarea name="findings" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">{{ old('findings') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
            <textarea name="remarks" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">{{ old('remarks') }}</textarea>
        </div>
    </div>
    <div class="mt-4 flex gap-3">
        <button type="submit" class="bg-[#0a1640] hover:bg-[#071230] text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
            <i class="fas fa-save mr-2"></i>Save Record
        </button>
        <a href="{{ route('officers.show',$officer) }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50">Cancel</a>
    </div>
</form>
@endsection
