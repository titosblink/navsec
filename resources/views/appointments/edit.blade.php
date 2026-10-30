@extends('layouts.app')
@section('title', 'Edit Appointment')
@section('header', 'Edit Appointment')

@section('header-actions')
<a href="{{ route('officers.show', $appointment->officer_id) }}"
   class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Profile
</a>
@endsection

@section('content')
<form method="POST" action="{{ route('appointments.update', $appointment) }}" class="max-w-2xl">
    @csrf @method('PUT')
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Officer</label>
            <select name="officer_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm select2">
                @foreach($officers as $o)
                <option value="{{ $o->id }}" {{ $appointment->officer_id == $o->id ? 'selected' : '' }}>
                    {{ $o->rank }} {{ $o->name }} ({{ $o->svc_no }})
                </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                    @foreach(['appointment','course'] as $t)
                    <option value="{{ $t }}" {{ $appointment->type === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Is Current?</label>
                <label class="flex items-center gap-2 mt-2">
                    <input type="checkbox" name="is_current" value="1" {{ $appointment->is_current ? 'checked' : '' }} class="rounded">
                    <span class="text-sm text-gray-600">Current appointment</span>
                </label>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Appointment / Post</label>
            <input type="text" name="appointment" value="{{ old('appointment', $appointment->appointment) }}" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ship / Unit / Establishment</label>
                <select name="ship" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm select2 focus:ring-2 focus:ring-blue-400 outline-none">
                    <option value="">— Select Ship / Unit —</option>
                    @foreach($ships as $s)
                    <option value="{{ $s->name }}" {{ old('ship', $appointment->ship) === $s->name ? 'selected' : '' }}>
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
                    <option value="{{ $cmd }}" {{ old('command', $appointment->command) === $cmd ? 'selected' : '' }}>{{ $cmd }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ old('date_from', $appointment->date_from?->format('Y-m-d')) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ old('date_to', $appointment->date_to?->format('Y-m-d')) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
            <textarea name="remarks" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">{{ old('remarks', $appointment->remarks) }}</textarea>
        </div>

    </div>
    <div class="mt-4 flex items-center gap-3">
        <button type="submit" class="bg-[#0a1640] hover:bg-[#071230] text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
            <i class="fas fa-save mr-2"></i>Update
        </button>
        <a href="{{ route('appointments.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50">Cancel</a>
    </div>
</form>
@endsection

@section('scripts')
<script>$(document).ready(function(){ $('.select2').select2({ width:'100%' }); });</script>
@endsection
