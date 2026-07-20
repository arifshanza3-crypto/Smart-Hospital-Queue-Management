<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('Pages.login');
    }

    public function login(Request $request)
    {
        // ✅ Admin login
        if ($request->role === 'admin') {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            Log::info('Admin Login Attempt:', ['email' => $request->email]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();
                
                Log::info('Admin Login Success:', ['user' => $user->email, 'role' => $user->role]);
                
                if ($user->role === 'admin') {
                    return redirect('/admin/doctor-management')->with('success', 'Welcome back, ' . ($user->name ?? $user->full_name) . '!');
                }
                
                return redirect('/');
            }

            Log::error('Admin Login Failed:', ['email' => $request->email]);

            return back()->withErrors([
                'email' => 'Invalid admin credentials.',
            ])->onlyInput('email');
        }

        // ✅ Staff login (using employee_id)
        if ($request->role === 'staff') {
            $request->validate([
                'employee_id' => 'required|string',
                'password' => 'required',
            ]);

            Log::info('Staff Login Attempt:', ['employee_id' => $request->employee_id]);

            $user = User::where('employee_id', $request->employee_id)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user);
                $request->session()->regenerate();
                
                Log::info('Staff Login Success:', ['employee_id' => $user->employee_id]);
                
                return redirect('/staff/dashboard')->with('success', 'Welcome back, ' . ($user->name ?? $user->full_name) . '!');
            }

            Log::error('Staff Login Failed:', ['employee_id' => $request->employee_id]);

            return back()->withErrors([
                'employee_id' => 'Invalid staff credentials.',
            ])->onlyInput('employee_id');
        }

        // ✅ User login (using email + password) - "patient" ki jagah "user"
        if ($request->role === 'user') {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            Log::info('User Login Attempt:', ['email' => $request->email]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();
                
                Log::info('User Login Success:', ['user' => $user->email, 'role' => $user->role]);
                
                if ($user->role === 'user') {
                    return redirect('/')->with('success', 'Welcome back, ' . ($user->name ?? $user->full_name) . '!');
                }
                
                return redirect('/');
            }

            Log::error('User Login Failed:', ['email' => $request->email]);

            return back()->withErrors([
                'email' => 'Invalid user credentials.',
            ])->onlyInput('email');
        }

        return back()->withErrors(['error' => 'Invalid login attempt.']);
    }

    public function showSignupForm()
    {
        return view('Pages.signup');
    }

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
            'status' => 'pending'
        ]);

        // ✅ Notification for admin about new staff registration
        NotificationHelper::sendToAdmin(
            '👤 New Staff Registration',
            "New staff member {$request->full_name} has registered and is pending approval",
            null,
            ['staff_name' => $request->full_name, 'email' => $request->email]
        );

        return redirect('/login')->with('success', 'Registration successful! Waiting for admin approval.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

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

    public function currentUser()
    {
        if (Auth::check()) {
            return response()->json([
                'success' => true,
                'user' => Auth::user()
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Not authenticated'
        ], 401);
    }
}