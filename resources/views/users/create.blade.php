@extends('layouts.app')
@section('title', 'Add User')
@section('header', 'Add System User')

@section('header-actions')
<a href="{{ route('users.index') }}"
   class="flex items-center gap-2 border border-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
    <i class="fas fa-arrow-left text-xs"></i> Back to Users
</a>
@endsection

@section('content')
<form method="POST" action="{{ route('users.store') }}" class="max-w-md">
    @csrf
    <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select name="role" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="viewer" {{ old('role') === 'viewer' ? 'selected' : '' }}>Viewer — read only</option>
                <option value="hr" {{ old('role') === 'hr' ? 'selected' : '' }}>HR Officer — can create & edit</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin — full access</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" required minlength="8" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
        </div>
    </div>
    <div class="mt-4 flex gap-3">
        <button type="submit" class="bg-[#0a1640] text-white px-6 py-2.5 rounded-lg font-medium hover:bg-[#071230]">
            <i class="fas fa-save mr-2"></i>Create User
        </button>
        <a href="{{ route('users.index') }}" class="border border-gray-300 text-gray-600 px-6 py-2.5 rounded-lg hover:bg-gray-50">Cancel</a>
    </div>
</form>
@endsection
