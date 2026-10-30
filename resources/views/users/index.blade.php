@extends('layouts.app')
@section('title', 'Users')
@section('header', 'System Users')

@section('header-actions')
<a href="{{ route('users.create') }}" class="bg-[#0a1640] hover:bg-[#071230] text-white text-sm px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
    <i class="fas fa-user-plus"></i> Add User
</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left font-semibold">Name</th>
                <th class="px-4 py-3 text-left font-semibold">Email</th>
                <th class="px-4 py-3 text-left font-semibold">Role</th>
                <th class="px-4 py-3 text-left font-semibold">Joined</th>
                <th class="px-4 py-3 text-center font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-[#0a1640] text-white flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr($user->name,0,2)) }}
                        </div>
                        {{ $user->name }}
                        @if($user->id === auth()->id())<span class="text-xs text-blue-500">(you)</span>@endif
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $user->email }}</td>
                <td class="px-4 py-3">
                    <span class="{{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : ($user->role === 'hr' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600') }} text-xs px-2 py-0.5 rounded-full font-medium capitalize">
                        {{ $user->role }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-400 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('users.edit', $user) }}" class="text-amber-500 hover:text-amber-700"><i class="fas fa-edit"></i></a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-delete text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100">{{ $users->links() }}</div>
</div>
@endsection
