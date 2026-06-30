<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    // ✅ Login method for Admin and Staff
    public function login(Request $request)
    {
        // Admin login (using email + password)
        if ($request->role === 'admin') {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();
                
                if ($user->role === 'admin') {
                    return redirect('/admin/doctor-management');
                }
                
                return redirect('/');
            }

            return back()->withErrors([
                'email' => 'Invalid admin credentials.',
            ])->onlyInput('email');
        }

        // Staff login (using employee_id + password)
        if ($request->role === 'staff') {
            $request->validate([
                'employee_id' => 'required|string',
                'password' => 'required',
            ]);

            $user = User::where('employee_id', $request->employee_id)
                        ->where('role', 'staff')
                        ->first();

            if (!$user) {
                return back()->withErrors([
                    'employee_id' => 'Invalid employee ID.',
                ])->onlyInput('employee_id');
            }

            // ✅ Check if staff is approved
            if ($user->status === 'pending') {
                return back()->with('error', 'Your account is pending admin approval.');
            }

            if ($user->status === 'rejected') {
                return back()->with('error', 'Your account has been rejected. Contact admin.');
            }

            if (Hash::check($request->password, $user->password)) {
                Auth::login($user);
                $request->session()->regenerate();
                return redirect('/staff/dashboard');
            }

            return back()->withErrors([
                'employee_id' => 'Invalid credentials.',
            ])->onlyInput('employee_id');
        }

        return back()->withErrors(['error' => 'Invalid login attempt.']);
    }

    // ✅ Staff Signup (status = pending)
    public function signup(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'employee_id' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'employee_id' => $request->employee_id,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'status' => 'pending'  // ✅ Admin approval needed
        ]);

        return redirect('/login')->with('success', 'Registration successful! Waiting for admin approval.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // ✅ FORGOT PASSWORD - Show reset link form
    public function showForgotForm()
    {
        return view('Pages.forgot-password');
    }

    // ✅ FORGOT PASSWORD - Send reset link
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = $request->email;
        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );

        return back()->with('success', 'Password reset link sent to your email!');
    }

    // ✅ RESET PASSWORD - Show reset form
    public function showResetForm($token)
    {
        return view('Pages.reset-password', ['token' => $token]);
    }

    // ✅ RESET PASSWORD - Update password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);

        $reset = DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->where('token', $request->token)
                    ->first();

        if (!$reset) {
            return back()->with('error', 'Invalid or expired token.');
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Password reset successful! Please login.');
    }
}