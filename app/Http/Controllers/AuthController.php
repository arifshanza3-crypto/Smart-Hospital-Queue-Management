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
<<<<<<< HEAD
        // Admin login (using email + password)
=======
        // ✅ Admin login
>>>>>>> 6b3e1247f30e0d61f40e6ce48d469327b3ab9296
        if ($request->role === 'admin') {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            Log::info('Admin Login Attempt:', ['email' => $request->email]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();
                
                Log::info('Login Success:', ['user' => $user->email, 'role' => $user->role]);
                
                // ✅ ROLE BASED REDIRECTION - ADMIN
                if ($user->role === 'admin') {
                    return redirect('/admin/doctor-management')->with('success', 'Welcome back, ' . $user->name . '!');
                }
                
                // ✅ ROLE BASED REDIRECTION - USER (Regular)
                if ($user->role === 'user' || $user->role === 'patient') {
                    return redirect('/')->with('success', 'Welcome back, ' . $user->name . '!');
                }
                
                return redirect('/');
            }

            Log::error('Admin Login Failed:', ['email' => $request->email]);

            return back()->withErrors([
                'email' => 'Invalid admin credentials.',
            ])->onlyInput('email');
        }

<<<<<<< HEAD
        // Staff login (using employee_id)
=======
        // ✅ Staff login (using employee_id)
>>>>>>> 6b3e1247f30e0d61f40e6ce48d469327b3ab9296
        if ($request->role === 'staff') {
            $request->validate([
                'employee_id' => 'required|string',
                'password' => 'required',
            ]);

            Log::info('Staff Login Attempt:', ['employee_id' => $request->employee_id]);

            // Find user by employee_id
            $user = User::where('employee_id', $request->employee_id)->first();

            if ($user) {
                Log::info('Staff found:', ['name' => $user->name, 'role' => $user->role]);
            } else {
                Log::error('Staff not found with employee_id: ' . $request->employee_id);
                return back()->withErrors([
                    'employee_id' => 'Staff not found with this Employee ID.',
                ])->onlyInput('employee_id');
            }

            if (Hash::check($request->password, $user->password)) {
                Auth::login($user);
                $request->session()->regenerate();
                
                Log::info('Staff Login Success:', ['employee_id' => $user->employee_id]);
                
                // ✅ ROLE BASED REDIRECTION - STAFF
                if ($user->role === 'staff') {
                    return redirect('/staff/dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
                }
                
                return redirect('/');
            }

            Log::error('Staff Login Failed:', ['employee_id' => $request->employee_id]);

            return back()->withErrors([
                'employee_id' => 'Invalid staff credentials.',
            ])->onlyInput('employee_id');
        }

<<<<<<< HEAD
        // ✅ USER LOGIN (Regular User - Using email + password)
=======
        // ✅ User login (using email + password) - "patient" ki jagah "user"
>>>>>>> 6b3e1247f30e0d61f40e6ce48d469327b3ab9296
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
                
<<<<<<< HEAD
                // ✅ ROLE BASED REDIRECTION - USER
                if ($user->role === 'user' || $user->role === 'patient') {
                    return redirect('/')->with('success', 'Welcome back, ' . $user->name . '!');
                }
                
                // Agar user admin hai toh admin panel
                if ($user->role === 'admin') {
                    return redirect('/admin/doctor-management')->with('success', 'Welcome back, ' . $user->name . '!');
=======
                if ($user->role === 'user') {
                    return redirect('/')->with('success', 'Welcome back, ' . ($user->name ?? $user->full_name) . '!');
>>>>>>> 6b3e1247f30e0d61f40e6ce48d469327b3ab9296
                }
                
                return redirect('/');
            }

            Log::error('User Login Failed:', ['email' => $request->email]);

            return back()->withErrors([
<<<<<<< HEAD
                'email' => 'Invalid credentials.',
=======
                'email' => 'Invalid user credentials.',
>>>>>>> 6b3e1247f30e0d61f40e6ce48d469327b3ab9296
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

<<<<<<< HEAD
            $user = User::create([
                'name' => $request->name,
                'full_name' => $request->full_name ?? $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => $request->role,
                'employee_id' => $request->employee_id ?? null,
                'status' => ($request->role === 'admin' || $request->role === 'staff') ? 'pending' : 'active',
                'department' => $request->department ?? null,
            ]);

            Log::info('User registered:', ['email' => $user->email, 'role' => $user->role]);

            // ✅ Auto login after signup
            Auth::login($user);

            // ✅ ROLE BASED REDIRECTION AFTER SIGNUP
            if ($user->role === 'admin') {
                return redirect('/admin/doctor-management')->with('success', 'Welcome Admin!');
            } elseif ($user->role === 'staff') {
                return redirect('/staff/dashboard')->with('success', 'Welcome Staff! Your account is pending admin approval.');
            } else {
                return redirect('/')->with('success', 'Account created successfully! Welcome to Smart Queue.');
            }

        } catch (\Exception $e) {
            Log::error('Signup error: ' . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
=======
        // ✅ Notification for admin about new staff registration
        NotificationHelper::sendToAdmin(
            '👤 New Staff Registration',
            "New staff member {$request->full_name} has registered and is pending approval",
            null,
            ['staff_name' => $request->full_name, 'email' => $request->email]
        );

        return redirect('/login')->with('success', 'Registration successful! Waiting for admin approval.');
>>>>>>> 6b3e1247f30e0d61f40e6ce48d469327b3ab9296
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