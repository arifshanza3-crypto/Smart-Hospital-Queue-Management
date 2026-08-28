<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\User;
use App\Models\Notification;
use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TokenController extends Controller
{
    use NotificationTrait;

    public function showForm()
    {
        return view('Pages.Token_form');
    }

    public function generateToken(Request $request)
    {
        try {
<<<<<<< HEAD
=======
            // ✅ Validation - Email is nullable (optional)
>>>>>>> 0e2f13c81a0d22d7d61fb3ea1cdf2a8f08f28036
            $validated = $request->validate([
                'patient_name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',  // ✅ Email Optional
                'mobile_number' => 'required|string|max:11|regex:/^03\d{9}$/',
            ]);

            // ✅ Check if user is logged in
            $userId = null;
            if (Auth::check()) {
                $userId = Auth::id();
                Log::info('User logged in, ID: ' . $userId);
            } else {
                Log::info('User not logged in');
            }

<<<<<<< HEAD
            $department = $request->department ?? 'General';

=======
            // ✅ Generate token number
>>>>>>> 0e2f13c81a0d22d7d61fb3ea1cdf2a8f08f28036
            $lastToken = Token::orderBy('id', 'desc')->first();
            if ($lastToken && $lastToken->token_number) {
                $lastNumber = intval(substr($lastToken->token_number, 4));
                $newNumber = $lastNumber + 1;
                $tokenNumber = 'TKN-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $tokenNumber = 'TKN-001';
            }

<<<<<<< HEAD
=======
            // ✅ Get last position
            $lastPosition = Token::whereIn('status', ['waiting', 'calling'])->count();

            // ✅ Create token with mobile_number as phone
>>>>>>> 0e2f13c81a0d22d7d61fb3ea1cdf2a8f08f28036
            $token = Token::create([
                'token_number' => $tokenNumber,
                'patient_name' => $request->patient_name,
                'patient_id' => $userId,
                'department' => 'General',
                'phone' => $request->mobile_number,
                'email' => $request->email,  // ✅ Can be null
                'status' => 'waiting',
                'type' => 'online',
                'position' => $lastPosition + 1,
                'estimated_time' => 15,
                'created_at' => now()
            ]);

            session(['current_token' => $tokenNumber]);

            Log::info('Token generated: ' . $tokenNumber . ' for ' . $request->patient_name);

            // ✅ Send notification to user (if logged in)
            if ($userId) {
                Log::info('Sending notification to user: ' . $userId);
                
                $this->notifyUser(
                    $userId,
                    'Token Generated',
                    'Your token ' . $tokenNumber . ' has been generated successfully for ' . $department,
                    'token_generated',
                    [
                        'token_number' => $tokenNumber,
                        'patient_name' => $request->patient_name,
                        'url' => route('status.page', ['token' => $tokenNumber])
                    ]
                );
                
                Log::info('✅ Notification sent to user: ' . $userId);
            } else {
                Log::info('⚠️ User not logged in, skipping user notification');
            }

            // ✅ Send notification to all staff and admins
            $this->notifyAllStaffAndAdmins(
                'New Token Generated',
                'Token ' . $tokenNumber . ' generated for ' . $request->patient_name . ' (' . $department . ')',
                'token_generated',
                [
                    'token_number' => $tokenNumber,
                    'patient_name' => $request->patient_name,
                    'url' => route('staff.dashboard')
                ]
            );

            return redirect()->route('status.page', ['token' => $tokenNumber])
                ->with('success', 'Token ' . $tokenNumber . ' generated successfully!');

        } catch (\Exception $e) {
            Log::error('Error generating token: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error generating token: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function getTokenStatus(Request $request)
    {
        try {
            $tokenNumber = $request->query('token');
            
            if (!$tokenNumber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token number is required'
                ], 400);
            }

            $token = Token::where('token_number', $tokenNumber)->first();

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token not found'
                ], 404);
            }

            $waitingTime = 0;
            if ($token->status === 'waiting') {
                $waitingTokens = Token::where('status', 'waiting')
                    ->where('position', '<', $token->position)
                    ->count();
                $waitingTime = $waitingTokens * 15;
            }

            // ✅ Get currently serving token
            $servingToken = Token::where('status', 'serving')
                ->orderBy('created_at', 'desc')
                ->first();
            $nowServing = $servingToken ? $servingToken->token_number : 'N/A';

            return response()->json([
                'success' => true,
                'token' => [
                    'token_number' => $token->token_number,
                    'patient_name' => $token->patient_name,
                    'status' => $token->status,
                    'position' => $token->position,
                    'estimated_time' => $token->estimated_time,
                    'waiting_time' => $waitingTime,
                    'now_serving' => $nowServing,
                    'created_at' => $token->created_at ? $token->created_at->format('h:i A') : 'N/A'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting token status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting token status'
            ], 500);
        }
    }
}