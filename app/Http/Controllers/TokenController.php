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

        $request->validate([
            'patient_name' => 'required|string|max:255',
            'phone'        => 'required|string|max:15',
            'email'        => 'nullable|email|max:255',
        ]);

        $department = $request->department ?? 'OPD';

        $existingToken = Token::where('phone', $request->phone)
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

        $position = Token::whereIn('status', ['waiting', 'calling'])->count() + 1;
        $estimatedTime = 0; // ✅ Default 0 set karein, dynamic calculate hoga

        try {
            $token = Token::create([
                'token_number'   => $tokenNumber,
                'patient_id'     => null,
                'patient_name'   => $request->patient_name,
                'phone'          => $request->phone,
                'email'          => $request->email,
                'department'     => $department,
                'type'           => 'online',
                'status'         => 'waiting',
                'position'       => $position,
                'estimated_time' => $estimatedTime,
                'created_at'     => date('Y-m-d H:i:s')
            ]);

            Log::info('Token saved: ' . $tokenNumber);
            Log::info('Created at: ' . $token->created_at);

            session(['current_token' => $tokenNumber]);

            return redirect('/Status?token=' . $tokenNumber)->with('success', 'Token generated! Your Token: ' . $tokenNumber);

        } catch (\Exception $e) {
            Log::error('Token save error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating token: ' . $e->getMessage());
        }
    }

    public function getTokenStatus(Request $request)
    {
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

        // ✅ Dynamic Position Calculate
        $position = Token::where('department', $token->department)
                         ->whereIn('status', ['waiting', 'calling'])
                         ->where('created_at', '<', $token->created_at)
                         ->count() + 1;

        // ✅ Dynamic Estimated Time - Model method use karein
        $estimatedTime = $token->getDynamicEstimatedTime();

        $serving = Token::where('department', $token->department)
                        ->where('status', 'serving')
                        ->first();

        $totalWaiting = Token::where('department', $token->department)
                             ->whereIn('status', ['waiting', 'calling'])
                             ->count();
        
        $progress = $totalWaiting > 0 ? (($totalWaiting - $position + 1) / $totalWaiting * 100) : 100;
        $progress = min(100, max(0, $progress));

        // ✅ RAW time from database - MINUS 2 HOURS (fix for server time)
        $rawTime = '--';
        if ($token->created_at) {
            $timestamp = strtotime($token->created_at);
            $timestamp = $timestamp - (2 * 3600);
            $rawTime = date('h:i A', $timestamp);
        }

        return response()->json([
            'success'        => true,
            'token_number'   => $token->token_number,
            'patient_name'   => $token->patient_name ?? 'N/A',
            'department'     => $token->department,
            'status'         => $token->status,
            'position'       => $position,
            'estimated_time' => $estimatedTime,
            'serving'        => $serving ? $serving->token_number : '--',
            'progress'       => round($progress, 0),
            'created_at'     => $rawTime
        ]);
    }
}