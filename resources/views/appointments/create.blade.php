@extends('layouts.app')
@section('title', 'New Appointment')
@section('header', 'New Appointment')
@section('breadcrumb', 'Appointments / New')

@section('header-actions')
@if($officer)
<a href="{{ route('officers.show', $officer) }}"
   class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Profile
</a>
@else
<a href="{{ route('appointments.index') }}"
   class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back
</a>
@endif
@endsection

@section('content')
<form method="POST" action="{{ route('appointments.store') }}" class="max-w-2xl">
    @csrf
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Officer <span class="text-red-500">*</span></label>
            <select name="officer_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm select2 focus:ring-2 focus:ring-blue-400 outline-none">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="appointment" {{ old('type') === 'appointment' ? 'selected' : '' }}>Appointment</option>
                    <option value="course"      {{ old('type') === 'course'      ? 'selected' : '' }}>Course</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Is Current?</label>
                <label class="flex items-center gap-2 mt-2">
                    <input type="checkbox" name="is_current" value="1" {{ old('is_current') ? 'checked' : '' }} class="rounded">
                    <span class="text-sm text-gray-600">Mark as current appointment</span>
                </label>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Appointment / Post <span class="text-red-500">*</span></label>
            <input type="text" name="appointment" value="{{ old('appointment') }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none"
                   placeholder="e.g. Executive Officer">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ship / Unit / Establishment</label>
                <select name="ship" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm select2 focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="">— Select Ship / Unit —</option>
                    @foreach($ships as $s)
                    <option value="{{ $s->name }}" {{ old('ship') === $s->name ? 'selected' : '' }}>
                        {{ $s->name }}@if($s->command) ({{ $s->command }})@endif
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Command</label>
                <select name="command" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="">— Select Command —</option>
                    @foreach(['NHQ','WNC','ENC','CNC','NAVTRAC','LOG COMMAND','NAVDOC','SOC'] as $cmd)
                    <option value="{{ $cmd }}" {{ old('command') === $cmd ? 'selected' : '' }}>{{ $cmd }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ old('date_from') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ old('date_to') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
            <textarea name="remarks" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">{{ old('remarks') }}</textarea>
        </div>

    </div>

    <div class="mt-4 flex items-center gap-3">
        <button type="submit" class="bg-[#0a1640] hover:bg-[#071230] text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
            <i class="fas fa-save mr-2"></i>Save Appointment
        </button>
        <a href="{{ route('appointments.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50">Cancel</a>
    </div>
</form>
@endsection

@section('scripts')
<script>$(document).ready(function(){ $('.select2').select2({ width:'100%' }); });</script>
@endsection
