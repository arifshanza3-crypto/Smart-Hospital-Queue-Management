<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
<<<<<<< HEAD
use App\Models\Token;
use Illuminate\Support\Facades\Log;

class TokenController extends Controller
{
    // ✅ Show token form
    public function showForm()
    {
        return view('Pages.Token_form');
    }

    public function generateToken(Request $request)
    {
        Log::info('Token controller reached');
        Log::info($request->all());

        $request->validate([
            'patient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'department' => 'required|in:OPD,Lab,Pharmacy,Radiology'
        ]);

        $existingToken = Token::where('phone', $request->phone)
                              ->whereIn('status', ['waiting', 'calling', 'serving'])
                              ->first();

        if ($existingToken) {
            return redirect()->back()->with('error', 'You already have an active token: ' . $existingToken->token_number);
        }

        $lastToken = Token::orderBy('id', 'desc')->first();

=======
use Illuminate\Support\Facades\Auth;
use App\Models\Token;

class TokenController extends Controller
{
    public function generateToken(Request $request)
    {
        // Check if user is logged in
        $patientId = Auth::id();
        
        if (!$patientId) {
            return back()->with('error', 'Please login first');
        }
        
        // Validate form data
        $request->validate([
            'full_name' => 'required|string',
            'email' => 'required|email'
        ]);
        
        // Generate token number
        $lastToken = Token::orderBy('id', 'desc')->first();
        
>>>>>>> dd270c6b25ecbed4a506a00e6cb776eb298c5d16
        if ($lastToken && $lastToken->token_number) {
            $lastNumber = intval(substr($lastToken->token_number, 4));
            $newNumber = $lastNumber + 1;
            $tokenNumber = 'TKN-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        } else {
            $tokenNumber = 'TKN-001';
        }
<<<<<<< HEAD

        $position = Token::where('department', $request->department)
                         ->whereIn('status', ['waiting', 'calling'])
                         ->count() + 1;

        $estimatedTime = $position * 15;

        try {
            $token = Token::create([
                'token_number' => $tokenNumber,
                'patient_id' => null,
                'patient_name' => $request->patient_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'department' => $request->department,
                'status' => 'waiting',
                'position' => $position,
                'estimated_time' => $estimatedTime,
                'created_at' => now()
            ]);

            Log::info('Token saved: ' . $tokenNumber);

            session(['current_token' => $tokenNumber]);

            return redirect('/Status')->with('success', 'Token generated! Your Token: ' . $tokenNumber);

        } catch (\Exception $e) {
            Log::error('Token save error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function getTokenStatus()
    {
        $tokenNumber = session('current_token');

        if (!$tokenNumber) {
            return response()->json([
                'success' => false,
                'message' => 'No token found'
            ]);
        }

        $token = Token::where('token_number', $tokenNumber)->first();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ]);
        }

        $position = Token::where('department', $token->department)
                         ->whereIn('status', ['waiting', 'calling'])
                         ->where('created_at', '<', $token->created_at)
                         ->count() + 1;

        $estimatedTime = $position * 15;

        $serving = Token::where('department', $token->department)
                        ->where('status', 'serving')
                        ->first();

        return response()->json([
            'success' => true,
            'token_number' => $token->token_number,
            'patient_name' => $token->patient_name,
            'department' => $token->department,
            'status' => $token->status,
            'position' => $position,
            'estimated_time' => $estimatedTime,
            'serving' => $serving ? $serving->token_number : '--'
        ]);
    }
=======
        
        // Save token
        try {
            $token = Token::create([
                'token_number' => $tokenNumber,
                'patient_id' => $patientId,
                'department' => 'General',
                'status' => 'waiting',
                'full_name' => $request->full_name,
                'email' => $request->email,
                'type' => 'Online',
                'est_time' => 15,
                'created_at' => now()
            ]);
            
            return redirect('/Status')->with('success', 'Token generated! Your Token: ' . $tokenNumber);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Database Error: ' . $e->getMessage());
        }
    }
>>>>>>> dd270c6b25ecbed4a506a00e6cb776eb298c5d16
}