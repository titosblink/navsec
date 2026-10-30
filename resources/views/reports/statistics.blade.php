@extends('layouts.app')
@section('title','Statistics')
@section('header','Officer Statistics & Analytics')
@section('header-actions')
<button onclick="window.print()" class="border border-gray-300 text-gray-600 text-sm px-3 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-50 no-print">
    <i class="fas fa-print"></i> Print
</button>
@endsection
@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- By Rank --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2"><i class="fas fa-medal text-amber-500"></i> Officers by Rank (Serving)</h3>
        <canvas id="chartRank" height="220"></canvas>
    </div>

    {{-- By Branch --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2"><i class="fas fa-sitemap text-blue-500"></i> Officers by Branch</h3>
        <canvas id="chartBranch" height="220"></canvas>
    </div>

    {{-- By Geo Zone --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2"><i class="fas fa-map-marker-alt text-green-500"></i> Officers by Geo-Political Zone</h3>
        <canvas id="chartZone" height="220"></canvas>
    </div>

    {{-- By Command --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2"><i class="fas fa-ship text-purple-500"></i> Officers by Command</h3>
        <canvas id="chartCommand" height="220"></canvas>
    </div>

    {{-- Age Distribution --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2"><i class="fas fa-users text-red-500"></i> Age Distribution</h3>
        <canvas id="chartAge" height="220"></canvas>
    </div>

    {{-- Retirement Forecast --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2"><i class="fas fa-calendar-check text-gray-500"></i> Retirement / ROD Forecast (5 Years)</h3>
        <canvas id="chartRetirement" height="220"></canvas>
    </div>

</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const palette = ['#0b1d55','#1e40af','#3b82f6','#93c5fd','#bfdbfe','#dbeafe','#f59e0b','#10b981','#ef4444','#8b5cf6','#06b6d4','#f97316'];

function bar(id, labels, data, color) {
    new Chart(document.getElementById(id), {
        type: 'bar',
        data: { labels, datasets: [{ data, backgroundColor: color ?? palette, borderRadius: 5, borderSkipped: false }] },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
}
function pie(id, labels, data) {
    new Chart(document.getElementById(id), {
        type: 'doughnut',
        data: { labels, datasets: [{ data, backgroundColor: palette }] },
        options: { plugins: { legend: { position: 'right', labels: { font: { size: 11 } } } }, cutout: '55%' }
    });
}

bar('chartRank',      {!! json_encode(array_keys($byRank->toArray())) !!},      {!! json_encode(array_values($byRank->toArray())) !!});
pie('chartBranch',    {!! json_encode(array_keys($byBranch->toArray())) !!},    {!! json_encode(array_values($byBranch->toArray())) !!});
pie('chartZone',      {!! json_encode(array_keys($byZone->toArray())) !!},      {!! json_encode(array_values($byZone->toArray())) !!});
bar('chartCommand',   {!! json_encode(array_keys($byCommand->toArray())) !!},   {!! json_encode(array_values($byCommand->toArray())) !!}, '#0b1d55');
bar('chartAge',       {!! json_encode(array_keys($ageGroups)) !!},              {!! json_encode(array_values($ageGroups)) !!},           '#3b82f6');
bar('chartRetirement',{!! json_encode(array_keys($retirementForecast)) !!},     {!! json_encode(array_values($retirementForecast)) !!},  '#ef4444');
</script>
@endsection
