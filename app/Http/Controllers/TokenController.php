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
            // ✅ Validation without department
            $validated = $request->validate([
                'patient_name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255'
            ]);

            // ✅ Check if user is logged in
            $userId = null;
            if (Auth::check()) {
                $userId = Auth::id();
            }

            // ✅ Default department
            $department = $request->department ?? 'General';

            // ✅ Generate token number
            $lastToken = Token::orderBy('id', 'desc')->first();
            if ($lastToken && $lastToken->token_number) {
                $lastNumber = intval(substr($lastToken->token_number, 4));
                $newNumber = $lastNumber + 1;
                $tokenNumber = 'TKN-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $tokenNumber = 'TKN-001';
            }

            // ✅ Create token
            $token = Token::create([
                'token_number' => $tokenNumber,
                'patient_name' => $request->patient_name,
                'patient_id' => $userId,
                'department' => $department,
                'phone' => $request->phone,
                'email' => $request->email,
                'status' => 'waiting',
                'type' => 'online',
                'position' => Token::where('department', $department)
                    ->whereIn('status', ['waiting', 'calling'])
                    ->count() + 1,
                'estimated_time' => 15,
                'created_at' => now()
            ]);

            // ✅ Save token in session for status page
            session(['current_token' => $tokenNumber]);

            Log::info('Token generated: ' . $tokenNumber . ' for ' . $request->patient_name);

            // ✅ Send notification to user (if logged in)
            if ($userId) {
                $this->notifyUser(
                    $userId,
                    'Token Generated',
                    'Your token ' . $tokenNumber . ' has been generated successfully',
                    'token_generated',
                    [
                        'token_number' => $tokenNumber,
                        'department' => $department,
                        'patient_name' => $request->patient_name,
                        'url' => route('status.page', ['token' => $tokenNumber])
                    ]
                );
            }

            // ✅ Send notification to all staff and admins
            $this->notifyAllStaffAndAdmins(
                'New Token Generated',
                'Token ' . $tokenNumber . ' generated for ' . $request->patient_name,
                'token_generated',
                [
                    'token_number' => $tokenNumber,
                    'patient_name' => $request->patient_name,
                    'department' => $department,
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

            // ✅ Calculate waiting time
            $waitingTime = 0;
            if ($token->status === 'waiting') {
                $waitingTokens = Token::where('department', $token->department)
                    ->where('status', 'waiting')
                    ->where('position', '<', $token->position)
                    ->count();
                $waitingTime = $waitingTokens * 15;
            }

            return response()->json([
                'success' => true,
                'token' => [
                    'token_number' => $token->token_number,
                    'patient_name' => $token->patient_name,
                    'department' => $token->department,
                    'status' => $token->status,
                    'position' => $token->position,
                    'estimated_time' => $token->estimated_time,
                    'waiting_time' => $waitingTime,
                    'created_at' => $token->created_at
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