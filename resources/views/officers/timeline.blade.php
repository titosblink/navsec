@extends('layouts.app')
@section('title','Career Timeline')
@section('header','Career Timeline — ' . $officer->rank . ' ' . $officer->name)
@section('header-actions')
<a href="{{ route('officers.show',$officer) }}" class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Profile
</a>
@endsection
@section('content')
<div class="max-w-3xl">
@if($events->isEmpty())
<div class="bg-white rounded-xl shadow-sm p-12 text-center text-gray-400">
    <i class="fas fa-timeline text-gray-200 text-4xl mb-3 block"></i>
    <p>No timeline events recorded yet.</p>
</div>
@else
<div class="relative">
    <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200"></div>
    @foreach($events as $event)
    <div class="relative flex gap-4 mb-6">
        <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 z-10 border-2 border-white shadow-sm {{ $event['bg'] }}">
            <i class="fas {{ $event['icon'] }} text-sm {{ $event['color'] }}"></i>
        </div>
        <div class="flex-1 bg-white rounded-xl shadow-sm p-4 min-w-0">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wide {{ $event['color'] }}">{{ $event['type'] }}</span>
                    <p class="font-semibold text-gray-800 mt-0.5">{{ $event['title'] }}</p>
                    @if(!empty($event['subtitle']))<p class="text-sm text-gray-500 mt-0.5">{{ $event['subtitle'] }}</p>@endif
                </div>
                <span class="text-xs text-gray-400 whitespace-nowrap flex-shrink-0">{{ $event['date']?->format('d M Y') ?? '—' }}</span>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
</div>
@endsection
