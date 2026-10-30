@extends('layouts.app')
@section('title','Audit Log')
@section('header','Audit Trail')
@section('breadcrumb','System activity log')

@section('header-actions')
<a href="{{ route('audit.export', request()->only(['action','search'])) }}"
   class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
    <i class="fas fa-file-excel"></i> Export Excel
</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm">
    <form id="filter-form" method="GET" class="flex flex-wrap gap-3 p-4 border-b border-gray-100 items-center">
        <div class="relative flex-1 min-w-48">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
            <input type="text" id="search-input" name="search" value="{{ request('search') }}" placeholder="Search officer name or user…"
                   class="w-full border border-gray-300 rounded-lg pl-9 pr-8 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            <button type="button" id="clear-search" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-500 text-sm {{ request('search')?'':'hidden' }}">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <select name="action" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            <option value="">All Actions</option>
            @foreach(['created','updated','deleted'] as $a)
            <option value="{{ $a }}" {{ request('action')===$a?'selected':'' }}>{{ ucfirst($a) }}</option>
            @endforeach
        </select>
        <div id="search-spinner" class="hidden text-blue-400 text-sm"><i class="fas fa-circle-notch fa-spin"></i></div>
    </form>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-left">
                <tr>
                    <th class="px-4 py-3 font-semibold">Date / Time</th>
                    <th class="px-4 py-3 font-semibold">User</th>
                    <th class="px-4 py-3 font-semibold">Action</th>
                    <th class="px-4 py-3 font-semibold">Description</th>
                    <th class="px-4 py-3 font-semibold">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3 font-medium text-gray-700">{{ $log->user_name ?? '—' }}</td>
                    <td class="px-4 py-3">
                        @php $ac=['created'=>'bg-green-100 text-green-700','updated'=>'bg-blue-100 text-blue-700','deleted'=>'bg-red-100 text-red-700']; @endphp
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $ac[$log->action] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($log->action) }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ $log->description }}</td>
                    <td class="px-4 py-3 text-xs text-gray-400 font-mono">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-history text-gray-200 text-3xl mb-2 block"></i>No activity recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">{{ $logs->links() }}</div>
</div>
@endsection
@section('scripts')
@include('components.live-search')
@endsection
