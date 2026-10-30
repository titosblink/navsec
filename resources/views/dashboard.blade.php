@extends('layouts.app')
@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')

{{-- ── Stat cards (clickable) ── --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    @php
    $cards = [
        ['icon'=>'fa-users',          'label'=>'Total Officers',      'value'=>$stats['total'],    'color'=>'#2563eb','bg'=>'#eff6ff', 'href'=>route('officers.index')],
        ['icon'=>'fa-user-check',     'label'=>'Serving',             'value'=>$stats['serving'],  'color'=>'#16a34a','bg'=>'#f0fdf4', 'href'=>route('officers.index',['status'=>'S'])],
        ['icon'=>'fa-user-clock',     'label'=>'Retired',             'value'=>$stats['retired'],  'color'=>'#64748b','bg'=>'#f8fafc', 'href'=>route('officers.index',['status'=>'R'])],
        ['icon'=>'fa-right-left',     'label'=>'Postings This Month', 'value'=>$stats['postings'], 'color'=>'#d97706','bg'=>'#fffbeb', 'href'=>route('appointments.index')],
        ['icon'=>'fa-graduation-cap', 'label'=>'Courses This Year',   'value'=>$stats['courses'],  'color'=>'#7c3aed','bg'=>'#f5f3ff', 'href'=>route('courses.index')],
    ];
    @endphp
    @foreach($cards as $card)
    <a href="{{ $card['href'] }}"
       class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:-translate-y-0.5 transition-all group">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background:{{ $card['bg'] }};">
            <i class="fas {{ $card['icon'] }} text-xl group-hover:scale-110 transition-transform" style="color:{{ $card['color'] }};"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($card['value']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $card['label'] }}</p>
        </div>
    </a>
    @endforeach
</div>

{{-- ── Alert panel ── --}}
@if($alerts->count())
<div class="mb-6">
    <div class="flex items-center gap-2 mb-3">
        <i class="fas fa-triangle-exclamation text-amber-500"></i>
        <h3 class="text-sm font-semibold text-gray-700">Action Required — {{ $alerts->count() }} officer{{ $alerts->count() > 1 ? 's' : '' }} approaching separation</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
        @foreach($alerts as $a)
        @php
        $levelStyles = [
            'red'    => ['bg'=>'#fef2f2','border'=>'#fca5a5','dot'=>'#ef4444','text'=>'#991b1b','badge'=>'#fee2e2','badgeText'=>'#b91c1c'],
            'amber'  => ['bg'=>'#fffbeb','border'=>'#fcd34d','dot'=>'#f59e0b','text'=>'#92400e','badge'=>'#fef3c7','badgeText'=>'#b45309'],
            'yellow' => ['bg'=>'#fefce8','border'=>'#fde68a','dot'=>'#eab308','text'=>'#713f12','badge'=>'#fef9c3','badgeText'=>'#854d0e'],
        ];
        $s = $levelStyles[$a->_alert_level];
        @endphp
        <a href="{{ route('officers.show', $a) }}"
           class="flex items-center gap-3 rounded-xl p-4 border hover:opacity-90 transition-opacity"
           style="background:{{ $s['bg'] }};border-color:{{ $s['border'] }};">
            <div class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $s['dot'] }};"></div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold truncate" style="color:{{ $s['text'] }};">{{ $a->rank }} {{ $a->name }}</p>
                <p class="text-xs mt-0.5" style="color:{{ $s['text'] }};opacity:.8;">{{ $a->_alert_label }}: {{ $a->_alert_date->format('d M Y') }}</p>
            </div>
            <span class="text-xs font-bold px-2 py-0.5 rounded-full flex-shrink-0"
                  style="background:{{ $s['badge'] }};color:{{ $s['badgeText'] }};">{{ $a->_alert_days }}d</span>
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- ── Bottom grid ── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Rank Distribution --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-bar text-blue-500"></i> Officers by Rank (Serving)
        </h3>
        <div class="space-y-2.5">
            @forelse($byRank as $row)
            @php
                $pct = $stats['serving'] > 0 ? min(100, round($row->total / $stats['serving'] * 100)) : 0;
                $barColor = match(true) {
                    in_array($row->rank, ['CDRE','RADM','VADM','ADM']) => 'linear-gradient(90deg,#b91c1c,#ef4444)',
                    in_array($row->rank, ['LT/CDR','CDR','CAPT'])      => 'linear-gradient(90deg,#b45309,#f59e0b)',
                    default                                             => 'linear-gradient(90deg,#0b1d55,#1e40af)',
                };
            @endphp
            <div class="flex items-center gap-2">
                <span class="text-xs font-mono w-14 text-gray-500 flex-shrink-0">{{ $row->rank ?: 'N/A' }}</span>
                <div class="flex-1 bg-gray-100 rounded-full h-4 overflow-hidden">
                    <div class="h-4 rounded-full flex items-center justify-end pr-2 transition-all"
                         style="width:{{ $pct }}%;background:{{ $barColor }};">
                        @if($pct > 10)<span class="text-white text-xs leading-none">{{ $row->total }}</span>@endif
                    </div>
                </div>
                @if($pct <= 10)<span class="text-xs text-gray-500 w-4">{{ $row->total }}</span>@endif
            </div>
            @empty
            <div class="text-center py-6">
                <i class="fas fa-chart-bar text-gray-200 text-3xl mb-2"></i>
                <p class="text-sm text-gray-400">No data yet</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Postings --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <i class="fas fa-right-left text-amber-500"></i> Recent Postings
        </h3>
        <div class="space-y-2">
            @forelse($recentPostings as $posting)
            <a href="{{ $posting->officer ? route('officers.show', $posting->officer) : '#' }}"
               class="block p-3 rounded-lg border border-gray-100 hover:border-blue-100 hover:bg-blue-50/30 transition-colors">
                <p class="text-sm font-semibold text-gray-800">{{ $posting->officer->name ?? '—' }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $posting->appointment }}</p>
                <p class="text-xs text-blue-500 mt-1">
                    <i class="fas fa-calendar-day mr-1"></i>{{ $posting->date_from?->format('d M Y') ?? '—' }}
                </p>
            </a>
            @empty
            <div class="text-center py-6">
                <i class="fas fa-right-left text-gray-200 text-3xl mb-2"></i>
                <p class="text-sm text-gray-400">No postings yet</p>
            </div>
            @endforelse
        </div>
        @if($recentPostings->count())
        <a href="{{ route('appointments.index') }}" class="block text-center text-xs text-blue-600 hover:underline mt-3 pt-3 border-t border-gray-50">
            View all postings →
        </a>
        @endif
    </div>

    {{-- Activity Feed ── --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <i class="fas fa-bolt text-purple-500"></i> Recent Activity
        </h3>
        <div class="space-y-3">
            @forelse($activityFeed as $log)
            @php
                $actionStyles = [
                    'created' => ['icon'=>'fa-plus-circle',  'color'=>'text-green-500',  'bg'=>'bg-green-50'],
                    'updated' => ['icon'=>'fa-pen-circle',   'color'=>'text-blue-500',   'bg'=>'bg-blue-50'],
                    'deleted' => ['icon'=>'fa-minus-circle', 'color'=>'text-red-500',    'bg'=>'bg-red-50'],
                ];
                $style = $actionStyles[$log->action] ?? ['icon'=>'fa-circle-dot','color'=>'text-gray-400','bg'=>'bg-gray-50'];
            @endphp
            <div class="flex items-start gap-2.5">
                <div class="w-7 h-7 rounded-full {{ $style['bg'] }} flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fas {{ $style['icon'] }} text-xs {{ $style['color'] }}"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-700 leading-snug">{{ $log->description }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $log->user_name }}
                        · {{ $log->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center py-6">
                <i class="fas fa-bolt text-gray-200 text-3xl mb-2"></i>
                <p class="text-sm text-gray-400">No activity yet</p>
            </div>
            @endforelse
        </div>
        @if($activityFeed->count() > 0 && auth()->user()->role === 'admin')
        <a href="{{ route('audit.index') }}" class="block text-center text-xs text-blue-600 hover:underline mt-3 pt-3 border-t border-gray-50">
            View full audit trail →
        </a>
        @endif
    </div>

</div>
@endsection
