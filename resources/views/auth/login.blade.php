<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Nigerian Navy Personnel</title>
    <link rel="icon" type="image/png" href="{{ asset('navylogo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-[#0a1640] to-[#071230] flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
                <img src="{{ asset('navylogo.png') }}" alt="Logo" width="100" height="100" class="mx-auto mb-3">
            <h1 class="text-white text-2xl font-bold">NIGERIAN NAVY</h1>
            <p class="text-blue-300 text-sm mt-1">NAVSEC - Officers Record Management System</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-gray-800 text-xl font-semibold mb-6 text-center">Sign In</h2>

            @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                               placeholder="your@email.com">
                    </div>
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" required
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                               placeholder="••••••••">
                    </div>
                </div>
                <div class="flex items-center mb-5">
                    <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 mr-2">
                    <label for="remember" class="text-sm text-gray-600">Remember me</label>
                </div>
                <div class="text-right mb-3">
                    <a href="/forgot-password" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                </div>
                <button type="submit"
                        class="w-full bg-[#0a1640] hover:bg-[#071230] text-white font-semibold py-2.5 rounded-lg transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </button>
            </form>
        </div>
        <p class="text-center text-gray-400 text-xs mt-6">NAVSEC {{date('Y')}} | Designed by <a target="_blank" href="https://web.facebook.com/aluuogbonna">Ugly-Soft</a></p>
    </div>
</body>
</html>
