<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller {
    public function showForm() { return view('auth.forgot-password'); }

    public function send(Request $request) {
        $request->validate(['email' => 'required|email|exists:users,email']);
        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );
        $resetUrl = url("/reset-password?token={$token}&email=" . urlencode($request->email));
        Mail::raw("Click the link below to reset your NN Personnel password:\n\n{$resetUrl}\n\nThis link expires in 60 minutes.", function($m) use ($request) {
            $m->to($request->email)->subject('NN Personnel — Password Reset');
        });
        return back()->with('success', 'If that email exists in our system, a reset link has been sent.');
    }

    public function showReset(Request $request) {
        return view('auth.reset-password', ['token' => $request->token, 'email' => $request->email]);
    }

    public function reset(Request $request) {
        $request->validate([
            'email'    => 'required|email',
            'token'    => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['token' => 'Invalid or expired reset link.']);
        }
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['token' => 'This reset link has expired.']);
        }
        User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        return redirect()->route('login')->with('success', 'Password reset successfully. Please login.');
    }
}
