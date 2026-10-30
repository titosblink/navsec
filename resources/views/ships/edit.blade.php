@extends('layouts.app')
@section('title', 'Edit Ship')
@section('header', 'Edit Ship — ' . $ship->name)

@section('header-actions')
<a href="{{ route('ships.index') }}"
   class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Ships
</a>
@endsection

@section('content')
<form method="POST" action="{{ route('ships.update', $ship) }}" class="max-w-md">
    @csrf @method('PUT')
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $ship->name) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Call Sign</label>
            <input type="text" name="code" value="{{ old('code', $ship->code) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:ring-2 focus:ring-blue-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Command</label>
            <select name="command" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                <option value="">— Select Command —</option>
                @foreach(['NHQ','WNC','ENC','CNC','NAVTRAC','LOG COMMAND','NAVDOC','SOC'] as $cmd)
                <option value="{{ $cmd }}" {{ old('command', $ship->command) === $cmd ? 'selected' : '' }}>{{ $cmd }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                @foreach(['Frigate','Corvette','Patrol Vessel','Amphibious','Submarine','Headquarters','Shore Establishment','Naval Base','Other'] as $t)
                <option value="{{ $t }}" {{ $ship->type === $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ $ship->is_active ? 'checked' : '' }} class="rounded">
                <span class="text-sm font-medium text-gray-700">Active</span>
            </label>
        </div>
    </div>
    <div class="mt-4 flex gap-3">
        <button type="submit" class="bg-[#0a1640] text-white px-6 py-2.5 rounded-lg font-medium hover:bg-[#071230]">
            <i class="fas fa-save mr-2"></i>Update
        </button>
        <a href="{{ route('ships.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50">Cancel</a>
    </div>
</form>
@endsection
