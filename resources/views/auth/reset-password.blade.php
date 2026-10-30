<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Reset Password</title>
<script src="https://cdn.tailwindcss.com"></script></head>
<body class="min-h-screen bg-gradient-to-br from-[#0b1d55] to-[#071230] flex items-center justify-center p-4">
<div class="w-full max-w-md">
<div class="text-center mb-8"><h1 class="text-white text-xl font-bold">NIGERIAN NAVY</h1><p class="text-blue-300 text-sm">Set a new password</p></div>
<div class="bg-white rounded-2xl shadow-2xl p-8">
<h2 class="text-gray-800 text-lg font-semibold mb-4">Reset Password</h2>
@if($errors->any())<div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">{{ $errors->first() }}</div>@endif
<form method="POST" action="/reset-password">
@csrf
<input type="hidden" name="token" value="{{ $token }}">
<input type="hidden" name="email" value="{{ $email }}">
<div class="mb-4"><label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
<input type="password" name="password" required minlength="8" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
<div class="mb-5"><label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
<input type="password" name="password_confirmation" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
<button type="submit" class="w-full bg-[#0b1d55] text-white py-2.5 rounded-lg font-semibold hover:bg-[#071230]">Reset Password</button>
</form></div></div></body></html>
