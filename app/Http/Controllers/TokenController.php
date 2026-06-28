<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        
        if ($lastToken && $lastToken->token_number) {
            $lastNumber = intval(substr($lastToken->token_number, 4));
            $newNumber = $lastNumber + 1;
            $tokenNumber = 'TKN-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        } else {
            $tokenNumber = 'TKN-001';
        }
        
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
}