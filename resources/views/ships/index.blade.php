@extends('layouts.app')
@section('title', 'Ships & Commands')
@section('header', 'Ships & Commands')

@section('header-actions')
@if(in_array(auth()->user()->role, ['admin','hr']))
<a href="{{ route('ships.create') }}" class="bg-[#0a1640] hover:bg-[#071230] text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2">
    <i class="fas fa-plus"></i> Add Ship / Command
</a>
@endif
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm">

    {{-- Search bar --}}
    <form id="filter-form" method="GET" class="flex flex-wrap gap-3 p-4 border-b border-gray-100 items-center">
        <div class="relative flex-1 min-w-48">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
            <input type="text" id="search-input" name="search" value="{{ request('search') }}"
                   placeholder="Search name, call sign, command…"
                   class="w-full border border-gray-300 rounded-lg pl-9 pr-8 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none">
            <button type="button" id="clear-search"
                    class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-500 text-sm {{ request('search') ? '' : 'hidden' }}">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div id="search-spinner" class="hidden text-blue-400 text-sm"><i class="fas fa-circle-notch fa-spin"></i></div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-left">
                <tr>
                    <th class="px-4 py-3 font-semibold">Name</th>
                    <th class="px-4 py-3 font-semibold">Call Sign</th>
                    <th class="px-4 py-3 font-semibold">Command</th>
                    <th class="px-4 py-3 font-semibold">Type</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($ships as $ship)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $ship->name }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-block bg-[#0b1d55] text-white font-mono text-xs font-bold px-2.5 py-0.5 rounded tracking-wider">
                            {{ $ship->code }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($ship->command)
                        <span class="inline-block bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            {{ $ship->command }}
                        </span>
                        @else
                        <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $ship->type ?: '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="{{ $ship->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }} text-xs px-2 py-0.5 rounded-full">
                            {{ $ship->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            @if(in_array(auth()->user()->role, ['admin','hr']))
                            <a href="{{ route('ships.edit', $ship) }}" class="text-amber-500 hover:text-amber-700"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('ships.destroy', $ship) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-delete text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                        <i class="fas fa-ship text-gray-200 text-3xl mb-2 block"></i>
                        No ships / commands found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">{{ $ships->links() }}</div>
</div>
@endsection

@section('scripts')
@include('components.live-search')
@endsection
