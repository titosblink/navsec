@extends('layouts.app')
@section('title','New Leave')
@section('header','Record Leave')
@section('header-actions')
@if($officer)
<a href="{{ route('officers.show',$officer) }}" class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Profile
</a>
@else
<a href="{{ route('leaves.index') }}" class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back
</a>
@endif
@endsection
@section('content')
<form method="POST" action="{{ route('leaves.store') }}" class="max-w-xl">
    @csrf
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Officer <span class="text-red-500">*</span></label>
            <select name="officer_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm select2 focus:ring-2 focus:ring-blue-400 outline-none">
                <option value="">Select Officer</option>
                @foreach($officers as $o)
                <option value="{{ $o->id }}" {{ (old('officer_id',$officer?->id)==$o->id)?'selected':'' }}>{{ $o->rank }} {{ $o->name }} ({{ $o->svc_no }})</option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type <span class="text-red-500">*</span></label>
                <select name="leave_type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="">— Select —</option>
                    @foreach($types as $t)<option value="{{ $t }}" {{ old('leave_type')===$t?'selected':'' }}>{{ ucfirst($t) }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                    @foreach(['pending','approved'] as $s)<option value="{{ $s }}" {{ old('status','pending')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date <span class="text-red-500">*</span></label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Approved By</label>
            <input type="text" name="approved_by" value="{{ old('approved_by') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
            <textarea name="remarks" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">{{ old('remarks') }}</textarea>
        </div>
    </div>
    <div class="mt-4 flex gap-3">
        <button type="submit" class="bg-[#0a1640] hover:bg-[#071230] text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
            <i class="fas fa-save mr-2"></i>Save Leave
        </button>
        <a href="{{ route('leaves.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50">Cancel</a>
    </div>
</form>
@endsection
@section('scripts')
<script>$(document).ready(function(){ $('.select2').select2({ width:'100%' }); });</script>
@endsection
