@extends('layouts.app')
@section('title','Add ACR')
@section('header','Annual Confidential Report — ' . $officer->name)
@section('header-actions')
<a href="{{ route('officers.show',$officer) }}" class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Profile
</a>
@endsection
@section('content')
<form method="POST" action="{{ route('acrs.store',$officer) }}" class="max-w-xl">
    @csrf
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Report Year <span class="text-red-500">*</span></label>
                <input type="number" name="report_year" value="{{ old('report_year', date('Y')) }}" required min="1980" max="{{ date('Y') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fitness Category</label>
                <select name="fitness_category" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="">— Select —</option>
                    @foreach($categories as $c)<option value="{{ $c }}" {{ old('fitness_category')===$c?'selected':'' }}>{{ $c }}</option>@endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Overall Rating (1–9)</label>
                <select name="overall_rating" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="">— Select —</option>
                    @for($i=1;$i<=9;$i++)<option value="{{ $i }}" {{ old('overall_rating')==$i?'selected':'' }}>{{ $i }}</option>@endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date Submitted</label>
                <input type="date" name="date_submitted" value="{{ old('date_submitted') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Reporting Officer</label>
            <input type="text" name="reporting_officer" value="{{ old('reporting_officer') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Countersigning Officer</label>
            <input type="text" name="countersigning_officer" value="{{ old('countersigning_officer') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Strengths</label>
            <textarea name="strengths" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">{{ old('strengths') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Areas for Improvement</label>
            <textarea name="weaknesses" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">{{ old('weaknesses') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">General Remarks</label>
            <textarea name="remarks" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">{{ old('remarks') }}</textarea>
        </div>
    </div>
    <div class="mt-4 flex gap-3">
        <button type="submit" class="bg-[#0a1640] hover:bg-[#071230] text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
            <i class="fas fa-save mr-2"></i>Save ACR
        </button>
        <a href="{{ route('officers.show',$officer) }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50">Cancel</a>
    </div>
</form>
@endsection
