@extends('layouts.app')
@section('title','System Settings')
@section('header','System Settings')
@section('breadcrumb','Administration / Settings')

@section('content')
<form method="POST" action="{{ route('settings.update') }}">
@csrf
<div class="space-y-6 max-w-3xl">

    {{-- Promotion min-years ── --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center"><i class="fas fa-star text-amber-600 text-sm"></i></div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Minimum Years in Rank (Promotion Eligibility)</h3>
                <p class="text-xs text-gray-400">An officer must have held their current rank for at least this many years to appear on the eligibility list.</p>
            </div>
        </div>
        <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-4">
            @php
            $rankLabels = \App\Models\Officer::rankFullNames();
            $promotionSettings = $settings->get('promotion', collect());
            @endphp
            @foreach($promotionSettings as $setting)
            @php $rank = str_replace('min_years_', '', $setting->key); @endphp
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">
                    <span class="inline-block text-xs font-bold px-1.5 py-0.5 rounded text-white {{ \App\Models\Officer::rankBadgeClass($rank) }} mr-1">{{ $rank }}</span>
                    {{ $rankLabels[$rank] ?? $rank }}
                </label>
                <div class="flex items-center gap-2">
                    <input type="number" name="{{ $setting->key }}" value="{{ old($setting->key, $setting->value) }}"
                           min="0" max="20" class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                    <span class="text-xs text-gray-400">years</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- General settings ── --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center"><i class="fas fa-gear text-blue-600 text-sm"></i></div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">General Settings</h3>
                <p class="text-xs text-gray-400">Application-wide configuration.</p>
            </div>
        </div>
        <div class="p-6 space-y-4">
            @foreach($settings->get('general', collect()) as $setting)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $setting->label ?? $setting->key }}</label>
                <input type="text" name="{{ $setting->key }}" value="{{ old($setting->key, $setting->value) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
            @endforeach
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="bg-[#0b1d55] hover:bg-[#071230] text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
            <i class="fas fa-save mr-2"></i>Save Settings
        </button>
    </div>

</div>
</form>
@endsection
