@extends('layouts.app')
@section('title', 'Edit Course')
@section('header', 'Edit Course Record')

@section('header-actions')
<a href="{{ route('officers.show', $course->officer_id) }}"
   class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Profile
</a>
@endsection

@section('content')
<form method="POST" action="{{ route('courses.update', $course) }}" class="max-w-xl">
    @csrf @method('PUT')
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Officer</label>
            <select name="officer_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm select2">
                @foreach($officers as $o)
                <option value="{{ $o->id }}" {{ $course->officer_id == $o->id ? 'selected' : '' }}>
                    {{ $o->rank }} {{ $o->name }} ({{ $o->svc_no }})
                </option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Course Type</label>
                <select name="course_type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @foreach($courseTypes as $t)
                    <option value="{{ $t }}" {{ $course->course_type === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="date" value="{{ old('date', $course->date?->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Course Name</label>
            <input type="text" name="course_name" value="{{ old('course_name', $course->course_name) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Grade</label>
                <input type="text" name="grade" value="{{ old('grade', $course->grade) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Certificate Received</label>
                <select name="received" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @foreach(['Yes','No','Pending'] as $r)
                    <option value="{{ $r }}" {{ $course->received === $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
            <textarea name="remarks" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('remarks', $course->remarks) }}</textarea>
        </div>
    </div>
    <div class="mt-4 flex items-center gap-3">
        <button type="submit" class="bg-[#0a1640] hover:bg-[#071230] text-white px-6 py-2.5 rounded-lg font-medium">
            <i class="fas fa-save mr-2"></i>Update
        </button>
        <a href="{{ route('courses.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50">Cancel</a>
    </div>
</form>
@endsection

@section('scripts')
<script>$(document).ready(function(){ $('.select2').select2({ width:'100%' }); });</script>
@endsection
