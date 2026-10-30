<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Forgot Password</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-[#0b1d55] to-[#071230] flex items-center justify-center p-4">
<div class="w-full max-w-md">
<div class="text-center mb-8">
<div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 rounded-full mb-4 border-2 border-white/20">
<i class="fas fa-anchor text-white text-2xl"></i></div>
<h1 class="text-white text-xl font-bold">NIGERIAN NAVY</h1>
<p class="text-blue-300 text-sm">Reset your password</p></div>
<div class="bg-white rounded-2xl shadow-2xl p-8">
<h2 class="text-gray-800 text-lg font-semibold mb-4">Forgot Password</h2>
@if(session('success'))<div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>@endif
@if($errors->any())<div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">{{ $errors->first() }}</div>@endif
<p class="text-gray-500 text-sm mb-4">Enter your email address and we will send you a password reset link.</p>
<form method="POST" action="/forgot-password">
@csrf
<div class="mb-4"><label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
<input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none"></div>
<button type="submit" class="w-full bg-[#0b1d55] text-white py-2.5 rounded-lg font-semibold hover:bg-[#071230]">Send Reset Link</button>
</form>
<p class="text-center mt-4"><a href="/login" class="text-blue-600 text-sm hover:underline">← Back to Login</a></p>
</div></div></body></html>
