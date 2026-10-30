@extends('layouts.app')
@section('title', 'Add Course')
@section('header', 'Add Course Record')

@section('header-actions')
@if($officer)
<a href="{{ route('officers.show', $officer) }}"
   class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Profile
</a>
@else
<a href="{{ route('courses.index') }}"
   class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back
</a>
@endif
@endsection

@section('content')
<form method="POST" action="{{ route('courses.store') }}" class="max-w-xl">
    @csrf
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Officer <span class="text-red-500">*</span></label>
            <select name="officer_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm select2">
                <option value="">Select Officer</option>
                @foreach($officers as $o)
                <option value="{{ $o->id }}" {{ (old('officer_id', $officer?->id) == $o->id) ? 'selected' : '' }}>
                    {{ $o->rank }} {{ $o->name }} ({{ $o->svc_no }})
                </option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Course Type <span class="text-red-500">*</span></label>
                <select name="course_type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Select Type</option>
                    @foreach($courseTypes as $t)
                    <option value="{{ $t }}" {{ old('course_type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="date" value="{{ old('date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Course Name / Description</label>
            <input type="text" name="course_name" value="{{ old('course_name') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="e.g. Junior Officers Leadership Course">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Grade</label>
                <input type="text" name="grade" value="{{ old('grade') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="e.g. Pass, Distinction">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Certificate Received</label>
                <select name="received" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Select</option>
                    <option value="Yes" {{ old('received') === 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ old('received') === 'No' ? 'selected' : '' }}>No</option>
                    <option value="Pending" {{ old('received') === 'Pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
            <textarea name="remarks" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('remarks') }}</textarea>
        </div>
    </div>
    <div class="mt-4 flex items-center gap-3">
        <button type="submit" class="bg-[#0a1640] hover:bg-[#071230] text-white px-6 py-2.5 rounded-lg font-medium">
            <i class="fas fa-save mr-2"></i>Save Course
        </button>
        <a href="{{ route('courses.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50">Cancel</a>
    </div>
</form>
@endsection

@section('scripts')
<script>$(document).ready(function(){ $('.select2').select2({ width:'100%' }); });</script>
@endsection
