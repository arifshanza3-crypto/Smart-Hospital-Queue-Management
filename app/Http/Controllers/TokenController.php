<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\Log;

class TokenController extends Controller
{
    public function showForm()
    {
        return view('Pages.Token_form');
    }

    public function generateToken(Request $request)
    {
        Log::info('Token controller reached');
        Log::info($request->all());

        // ✅ Department validation removed
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $existingToken = Token::where('email', $request->email)
                              ->whereIn('status', ['waiting', 'calling', 'serving'])
                              ->first();

        if ($existingToken) {
            return redirect()->back()->with('error', 'You already have an active token: ' . $existingToken->token_number);
        }

        $lastToken = Token::orderBy('id', 'desc')->first();

        if ($lastToken && $lastToken->token_number) {
            $lastNumber = intval(substr($lastToken->token_number, 4));
            $newNumber = $lastNumber + 1;
            $tokenNumber = 'TKN-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        } else {
            $tokenNumber = 'TKN-001';
        }

        // ✅ Simple position - no department filter
        $position = Token::whereIn('status', ['waiting', 'calling'])->count() + 1;

        $estimatedTime = $position * 15;

        try {
            $token = Token::create([
                'token_number' => $tokenNumber,
                'patient_id' => null,
                'patient_name' => $request->patient_name,
                'phone' => null,
                'email' => $request->email,
                'department' => 'General', // ✅ Default department
                'type' => 'online',
                'status' => 'waiting',
                'position' => $position,
                'estimated_time' => $estimatedTime,
                'created_at' => now()
            ]);

            Log::info('Token saved: ' . $tokenNumber);

            session(['current_token' => $tokenNumber]);

            return redirect('/Status?token=' . $tokenNumber)->with('success', 'Token generated! Your Token: ' . $tokenNumber);

        } catch (\Exception $e) {
            Log::error('Token save error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function getTokenStatus(Request $request)
    {
        date_default_timezone_set('Asia/Karachi');
        
        $tokenNumber = $request->query('token') ?? session('current_token');

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

        // ✅ Simple position - no department filter
        $position = Token::whereIn('status', ['waiting', 'calling'])
                         ->where('created_at', '<', $token->created_at)
                         ->count() + 1;

        $timePerPatient = 15;
        $estimatedTime = $position * $timePerPatient;

        $createdAt = $token->created_at;
        $now = now();
        $minutesPassed = $createdAt->diffInMinutes($now);
        $remainingTime = max(0, $estimatedTime - $minutesPassed);

        $serving = Token::where('status', 'serving')->first();

        $totalWaiting = Token::whereIn('status', ['waiting', 'calling'])->count();
        
        $progress = $totalWaiting > 0 ? (($totalWaiting - $position + 1) / $totalWaiting * 100) : 100;
        $progress = min(100, max(0, $progress));

        $formattedTime = '--';
        if ($token->created_at) {
            $formattedTime = date('h:i A', strtotime($token->created_at));
        }

        return response()->json([
            'success' => true,
            'token_number' => $token->token_number,
            'patient_name' => $token->patient_name ?? 'N/A',
            'department' => $token->department ?? 'General',
            'status' => $token->status,
            'position' => $position,
            'estimated_time' => $remainingTime,
            'serving' => $serving ? $serving->token_number : '--',
            'progress' => round($progress, 0),
            'created_at' => $formattedTime
        ]);
    }
}