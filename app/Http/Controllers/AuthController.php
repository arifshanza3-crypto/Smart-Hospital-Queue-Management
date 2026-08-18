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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    /**
     * Show Login Form
     */
    public function showLoginForm()
    {
        return view('Pages.login');
    }

    /**
     * Handle Login Request
     */
    public function login(Request $request)
    {
        try {
            // ✅ Validate credentials
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            Log::info('Login Attempt:', ['email' => $request->email]);

            // Check if user exists first
            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                Log::error('Login Failed - User not found:', ['email' => $request->email]);
                return back()->withErrors([
                    'email' => 'Invalid credentials. Please check your email and password.',
                ])->onlyInput('email');
            }

            // ✅ Check user status
            if (!in_array($user->status, ['active', 'approved'])) {
                Log::warning('Login Failed - User not active:', ['email' => $request->email, 'status' => $user->status]);
                
                if ($user->status === 'pending') {
                    return back()->withErrors([
                        'email' => 'Your account is pending approval. Please wait for admin approval.',
                    ])->onlyInput('email');
                }
                
                return back()->withErrors([
                    'email' => 'Your account is not active. Please contact administrator.',
                ])->onlyInput('email');
            }

            // ✅ Attempt login
            if (Auth::attempt($credentials)) {
                // Regenerate session to prevent fixation
                $request->session()->regenerate();
                
                $user = Auth::user();
                
                Log::info('Login Success:', [
                    'user' => $user->email, 
                    'role' => $user->role,
                    'session_id' => session()->getId()
                ]);
                
                // ✅ ROLE BASED REDIRECTION
                if ($user->role === 'admin') {
                    return redirect()->intended('/admin/doctor-management')->with('success', 'Welcome back, ' . $user->name . '!');
                }
                
                if ($user->role === 'staff') {
                    return redirect()->intended('/staff/dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
                }
                
                // User/Patient
                return redirect()->intended('/')->with('success', 'Welcome back, ' . $user->name . '!');
            }

            Log::error('Login Failed - Invalid password:', ['email' => $request->email]);

            return back()->withErrors([
                'email' => 'Invalid credentials. Please check your email and password.',
            ])->onlyInput('email');
            
        } catch (\Exception $e) {
            Log::error('Login Exception: ' . $e->getMessage());
            Log::error('Login Exception Trace: ' . $e->getTraceAsString());
            
            return back()->withErrors([
                'email' => 'An error occurred during login. Please try again.',
            ])->onlyInput('email');
        }
    }

    /**
     * Show Signup Form
     */
    public function showSignupForm()
    {
        return view('Pages.signup');
    }

    /**
     * Handle Signup Request
     */
    public function signup(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'full_name' => 'nullable|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
                'phone' => 'nullable|string|max:20',
                'role' => 'required|in:user,patient,staff,admin'
            ]);

            // ✅ Prevent regular users from signing up as admin
            if ($request->role === 'admin') {
                return back()->with('error', 'Admin accounts cannot be created through registration.')->withInput();
            }

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

    /**
     * Handle Logout
     */
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

    // ============================================================
    // ==================== FORGOT PASSWORD ========================
    // ============================================================

    /**
     * Show Forgot Password Form
     */
    public function showForgotForm()
    {
        return view('Pages.forgot-password');
    }

    /**
     * Send Password Reset Link to Email
     */
    public function sendResetLink(Request $request)
    {
        // ✅ Rate Limiting - Max 3 requests per hour per email
        $rateLimitKey = 'reset-password:' . $request->email;
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return back()->with('error', 'Too many reset attempts. Please try again in ' . ceil($seconds / 60) . ' minutes.');
        }

        // ✅ Validate email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'No account found with this email address.'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Email not found. Please check and try again.');
        }

        $email = $request->email;
        $user = User::where('email', $email)->first();
        
        // ✅ Generate secure token
        $token = Str::random(64);
        
        // ✅ Store token in database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $token, 
                'created_at' => now()
            ]
        );

        // ✅ Increment rate limiter
        RateLimiter::hit($rateLimitKey, 3600); // 1 hour

        // ✅ SEND EMAIL
        try {
            Mail::send('emails.password-reset', [
                'user' => $user,
                'token' => $token,
                'resetUrl' => url('/reset-password/' . $token)
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                        ->subject('Reset Your Password - SMART QUEUE');
                $message->from(config('mail.from.address'), config('mail.from.name'));
            });

            Log::info('Password reset email sent to:', ['email' => $email]);

            return back()->with('success', '✅ We have emailed your password reset link! Please check your inbox.');

        } catch (\Exception $e) {
            Log::error('Failed to send reset email: ' . $e->getMessage());
            Log::error('Mail Error Trace: ' . $e->getTraceAsString());
            
            // Delete the token if email fails
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            
            return back()->with('error', '❌ Unable to send reset email. Please try again later or contact support.');
        }
    }

    /**
     * Show Reset Password Form with Token
     */
    public function showResetForm($token)
    {
        // ✅ Check if token exists in database
        $reset = DB::table('password_reset_tokens')->where('token', $token)->first();
        
        if (!$reset) {
            return redirect('/forgot-password')->with('error', '❌ Invalid or expired reset link. Please request a new one.');
        }

        // ✅ Check if token is expired (60 minutes)
        $tokenExpiry = now()->diffInMinutes($reset->created_at);
        if ($tokenExpiry > 60) {
            // Delete expired token
            DB::table('password_reset_tokens')->where('token', $token)->delete();
            return redirect('/forgot-password')->with('error', '⏰ Reset link has expired (60 minutes). Please request a new one.');
        }

        return view('Pages.reset-password', [
            'token' => $token,
            'email' => $reset->email // Pass email to pre-fill the field
        ]);
    }

    /**
     * Handle Password Reset Submission
     */
    public function resetPassword(Request $request)
    {
        // ✅ Validate inputs
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ], [
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'email.exists' => 'No account found with this email address.'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        // ✅ Get reset record
        $reset = DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->where('token', $request->token)
                    ->first();

        // ✅ Check if token exists
        if (!$reset) {
            return back()
                ->withInput()
                ->with('error', '❌ Invalid or expired token. Please request a new reset link.');
        }

        // ✅ Check if token is expired (60 minutes)
        $tokenExpiry = now()->diffInMinutes($reset->created_at);
        if ($tokenExpiry > 60) {
            // Delete expired token
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()
                ->withInput()
                ->with('error', '⏰ Token has expired (60 minutes). Please request a new reset link.');
        }

        // ✅ Update password
        try {
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            // ✅ Delete used token
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            // ✅ Log successful reset
            Log::info('Password reset successful for:', ['email' => $request->email, 'user_id' => $user->id]);

            // ✅ Send confirmation email (Optional)
            try {
                Mail::send('emails.password-reset-confirmation', [
                    'user' => $user,
                    'loginUrl' => url('/login')
                ], function ($message) use ($user) {
                    $message->to($user->email, $user->name)
                            ->subject('Password Reset Successful - SMART QUEUE');
                    $message->from(config('mail.from.address'), config('mail.from.name'));
                });
            } catch (\Exception $e) {
                // Don't fail if confirmation email fails
                Log::warning('Password reset confirmation email failed: ' . $e->getMessage());
            }

            return redirect('/login')->with('success', '✅ Password reset successful! Please login with your new password.');

        } catch (\Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage());
            return back()->with('error', '❌ An error occurred while resetting your password. Please try again.');
        }
    }

    // ============================================================
    // ==================== API / UTILITY METHODS ==================
    // ============================================================

    /**
     * Get Current Authenticated User (API)
     */
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

    /**
     * Check if Reset Token is Valid (AJAX)
     */
    public function validateResetToken(Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);

        $reset = DB::table('password_reset_tokens')
                    ->where('token', $request->token)
                    ->first();

        if (!$reset) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid token'
            ]);
        }

        $tokenExpiry = now()->diffInMinutes($reset->created_at);
        if ($tokenExpiry > 60) {
            DB::table('password_reset_tokens')->where('token', $request->token)->delete();
            return response()->json([
                'valid' => false,
                'message' => 'Token expired'
            ]);
        }

        return response()->json([
            'valid' => true,
            'email' => $reset->email
        ]);
    }

    /**
     * Resend Reset Link (AJAX)
     */
    public function resendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = $request->email;
        $user = User::where('email', $email)->first();
        
        // ✅ Generate new token
        $token = Str::random(64);
        
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $token, 
                'created_at' => now()
            ]
        );

        try {
            Mail::send('emails.password-reset', [
                'user' => $user,
                'token' => $token,
                'resetUrl' => url('/reset-password/' . $token)
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                        ->subject('Reset Your Password - SMART QUEUE');
                $message->from(config('mail.from.address'), config('mail.from.name'));
            });

            return response()->json([
                'success' => true,
                'message' => 'Reset link resent successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to resend reset email: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email. Please try again.'
            ], 500);
        }
    }
}