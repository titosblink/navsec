@extends('layouts.app')
@section('title', 'Edit User')
@section('header', 'Edit User — ' . $user->name)

@section('header-actions')
<a href="{{ route('users.index') }}"
   class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Users
</a>
@endsection

@section('content')
<form method="POST" action="{{ route('users.update', $user) }}" class="max-w-md">
    @csrf @method('PUT')
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                @foreach(['viewer'=>'Viewer — read only','hr'=>'HR Officer — can create & edit','admin'=>'Admin — full access'] as $val => $label)
                <option value="{{ $val }}" {{ $user->role === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="border-t pt-3">
            <p class="text-xs text-gray-500 mb-2">Leave password blank to keep unchanged.</p>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password" minlength="8" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4 flex gap-3">
        <button type="submit" class="bg-[#0a1640] text-white px-6 py-2.5 rounded-lg font-medium hover:bg-[#071230]">
            <i class="fas fa-save mr-2"></i>Update User
        </button>
        <a href="{{ route('users.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50">Cancel</a>
    </div>
</form>
@endsection
