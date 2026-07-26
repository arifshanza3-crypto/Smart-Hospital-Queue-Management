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
        // ✅ Validate credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        Log::info('Login Attempt:', ['email' => $request->email]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            Log::info('Login Success:', ['user' => $user->email, 'role' => $user->role]);
            
            // ✅ ROLE BASED REDIRECTION
            if ($user->role === 'admin') {
                return redirect('/admin/doctor-management')->with('success', 'Welcome back, ' . $user->name . '!');
            }
            
            if ($user->role === 'staff') {
                return redirect('/staff/dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
            }
            
            if ($user->role === 'user' || $user->role === 'patient') {
                return redirect('/')->with('success', 'Welcome back, ' . $user->name . '!');
            }
            
            return redirect('/')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        Log::error('Login Failed:', ['email' => $request->email]);

        return back()->withErrors([
            'email' => 'Invalid credentials. Please check your email and password.',
        ])->onlyInput('email');
    }

    public function showSignupForm()
    {
        return view('Pages.signup');
    }

    public function signup(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:user,patient,staff,admin'
        ]);

        try {
            // ✅ If user selects staff, they need employee_id
            if ($request->role === 'staff') {
                $request->validate([
                    'employee_id' => 'required|unique:users,employee_id',
                ]);
            }

            // Create user
            $user = User::create([
                'name' => $request->name,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => $request->role,
                'employee_id' => $request->employee_id ?? null,
                'status' => $request->role === 'staff' ? 'pending' : 'active',
            ]);

            // ✅ Notification for admin about new staff registration
            if ($request->role === 'staff') {
                NotificationHelper::sendToAdmin(
                    '👤 New Staff Registration',
                    "New staff member {$request->full_name} has registered and is pending approval",
                    null,
                    ['staff_name' => $request->full_name, 'email' => $request->email]
                );
            }

            return redirect('/login')->with('success', 'Registration successful! Please login.');
        } catch (\Exception $e) {
            Log::error('Signup Error: ' . $e->getMessage());
            return back()->with('error', 'Registration failed: ' . $e->getMessage())->withInput();
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->flush();
            
            return redirect('/login')->with('success', 'You have been logged out successfully.');
        } catch (\Exception $e) {
            Log::error('Logout Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Logout completed.');
        }
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