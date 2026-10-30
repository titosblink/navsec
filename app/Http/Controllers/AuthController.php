<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $key = 'login:' . Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['email' => "Too many login attempts. Please wait {$seconds} seconds."])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($key);
            $request->session()->regenerate();
            // check if 2FA enabled
            if (auth()->user()->two_factor_enabled) {
                $this->sendOtp(auth()->user());
                session(['2fa_user_id' => auth()->id()]);
                Auth::logout();
                return redirect()->route('2fa.verify');
            }
            return redirect()->intended(route('dashboard'));
        }

        RateLimiter::hit($key, 60);
        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function sendOtp(User $user): void
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update(['otp_code' => $code, 'otp_expires_at' => now()->addMinutes(10), 'otp_verified' => false]);
        Mail::raw("Your NN Personnel login OTP is: {$code}\n\nExpires in 10 minutes.", function ($m) use ($user) {
            $m->to($user->email)->subject('NN Personnel — Login OTP');
        });
    }

    public function show2fa()
    {
        if (!session('2fa_user_id')) return redirect()->route('login');
        return view('auth.2fa');
    }

    public function verify2fa(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);
        $userId = session('2fa_user_id');
        if (!$userId) return redirect()->route('login');
        $user = User::find($userId);
        if (!$user || $user->otp_code !== $request->otp || now()->gt($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }
        $user->update(['otp_verified' => true, 'otp_code' => null]);
        session()->forget('2fa_user_id');
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }
}
