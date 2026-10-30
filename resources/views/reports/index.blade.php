@extends('layouts.app')
@section('title','Reports')
@section('header','Reports')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    @php
    $cards = [
        ['route'=>'reports.officers',   'icon'=>'fa-user-tie',       'color'=>'bg-blue-100',   'icon_color'=>'text-blue-600',   'title'=>'Officers Report',     'desc'=>'Filter officers by rank, branch, status or ship.'],
        ['route'=>'reports.postings',   'icon'=>'fa-exchange-alt',   'color'=>'bg-amber-100',  'icon_color'=>'text-amber-600',  'title'=>'Appointments Report', 'desc'=>'Appointments and transfers within a date range.'],
        ['route'=>'reports.courses',    'icon'=>'fa-graduation-cap', 'color'=>'bg-purple-100', 'icon_color'=>'text-purple-600', 'title'=>'Courses Report',      'desc'=>'Course records filtered by type or year.'],
        ['route'=>'reports.seniority',  'icon'=>'fa-list-ol',        'color'=>'bg-green-100',  'icon_color'=>'text-green-600',  'title'=>'Seniority List',      'desc'=>'Official seniority list sorted by rank and date.'],
        ['route'=>'reports.statistics', 'icon'=>'fa-chart-bar',      'color'=>'bg-red-100',    'icon_color'=>'text-red-600',    'title'=>'Statistics & Charts', 'desc'=>'Visual analytics: rank, branch, age, forecast.'],
    ];
    @endphp
    @foreach($cards as $card)
    <a href="{{ route($card['route']) }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow group">
        <div class="w-12 h-12 {{ $card['color'] }} rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="fas {{ $card['icon'] }} {{ $card['icon_color'] }} text-xl"></i>
        </div>
        <h3 class="font-semibold text-gray-800 mb-1">{{ $card['title'] }}</h3>
        <p class="text-sm text-gray-500">{{ $card['desc'] }}</p>
    </a>
    @endforeach
</div>
@endsection
