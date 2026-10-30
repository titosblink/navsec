<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Two-Factor Verification</title>
<script src="https://cdn.tailwindcss.com"></script></head>
<body class="min-h-screen bg-gradient-to-br from-[#0b1d55] to-[#071230] flex items-center justify-center p-4">
<div class="w-full max-w-sm">
<div class="text-center mb-8"><h1 class="text-white text-xl font-bold">NIGERIAN NAVY</h1><p class="text-blue-300 text-sm">Two-Factor Verification</p></div>
<div class="bg-white rounded-2xl shadow-2xl p-8">
<div class="text-center mb-5"><div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3"><svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></div>
<h2 class="text-gray-800 font-semibold">Enter Verification Code</h2>
<p class="text-gray-400 text-sm mt-1">A 6-digit code has been sent to your email</p></div>
@if($errors->any())<div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm text-center">{{ $errors->first() }}</div>@endif
<form method="POST" action="/2fa/verify">
@csrf
<input type="text" name="otp" maxlength="6" pattern="\d{6}" required autofocus
       class="w-full text-center text-2xl font-mono tracking-[.5em] border-2 border-gray-300 rounded-xl px-4 py-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none mb-5"
       placeholder="000000">
<button type="submit" class="w-full bg-[#0b1d55] text-white py-3 rounded-xl font-semibold hover:bg-[#071230]">Verify</button>
</form>
<p class="text-center mt-4"><a href="/login" class="text-blue-600 text-sm hover:underline">← Cancel login</a></p>
</div></div></body></html>
