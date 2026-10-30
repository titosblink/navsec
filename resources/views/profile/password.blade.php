@extends('layouts.app')
@section('title','Change Password')
@section('header','Change Password')
@section('content')
<div class="max-w-md">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                <input type="password" name="current_password" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" name="password" required minlength="8" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
            <button type="submit" class="w-full bg-[#0a1640] hover:bg-[#071230] text-white py-2.5 rounded-lg font-medium transition-colors">
                <i class="fas fa-key mr-2"></i>Update Password
            </button>
        </form>
    </div>
</div>
@endsection
