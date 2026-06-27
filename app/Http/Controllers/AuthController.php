<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
<<<<<<< HEAD
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/doctors');
            }
            return redirect()->intended('/');
=======
            $user = Auth::user();
            
            // Normalize string comparison against database values
            $role = strtolower(trim($user->role));
            
            if ($role === 'admin') {
                return redirect()->intended('Admin\Doctor_management');
            }
            
            if ($role === 'staff') {
                return redirect('/staff/dashboard');
            }
            
            return redirect('/');
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function signup(Request $request)
    {
        $validated = $request->validate([
<<<<<<< HEAD
            'name' => 'required|string|max:255',
=======
            'full_name' => 'required|string|max:255',
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
<<<<<<< HEAD
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'patient',
            'status' => 'pending'
=======
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'patient',
            'status' => 'approved'
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
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
<<<<<<< HEAD
=======

    public function showForgotForm()
    {
        return view('Pages.forgot-password');
    }

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

    public function showResetForm($token)
    {
        return view('Pages.reset-password', ['token' => $token]);
    }

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
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
}