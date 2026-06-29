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
    // ✅ FIXED: Login method for both Admin and Staff
    public function login(Request $request)
    {
        // Validate based on role
        if ($request->role === 'admin') {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

<<<<<<< HEAD
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            if ($user->role === 'admin') {
                return redirect('/admin/doctor-management');
            }
            
            if ($user->role === 'staff') {
                return redirect('/staff/dashboard');
            }
            
            return redirect('/');
=======
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();
                
                if ($user->role === 'admin') {
                    return redirect('/admin/doctor-management'); // ✅ Correct redirect
                }
                
                return redirect('/');
            }

            return back()->withErrors([
                'email' => 'Invalid admin credentials.',
            ])->onlyInput('email');
>>>>>>> ca4fbdc795d112985a4e7ec317add8b20c7be9e0
        }

        // Staff login (using employee_id)
        if ($request->role === 'staff') {
            $request->validate([
                'employee_id' => 'required|string',
                'password' => 'required',
            ]);

            // Find user by employee_id
            $user = User::where('employee_id', $request->employee_id)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user);
                $request->session()->regenerate();
                
                return redirect('/staff/dashboard'); // ✅ Staff redirect
            }

            return back()->withErrors([
                'employee_id' => 'Invalid staff credentials.',
            ])->onlyInput('employee_id');
        }

        return back()->withErrors(['error' => 'Invalid login attempt.']);
    }

    public function signup(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'patient',
            'status' => 'approved'
        ]);

        Auth::login($user);

        return redirect('/');
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