<?php
// app/Http/Controllers/StaffController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token;
use App\Models\Patient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    // ... other functions ...

    /**
     * Add Physical Patient
     */
    public function addPatient(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:150',
            'department' => 'nullable|string|max:50'
        ]);

        try {
            DB::beginTransaction();

            // ✅ Generate token number
            $lastToken = Token::orderBy('id', 'desc')->first();
            if ($lastToken && $lastToken->token_number) {
                $lastNumber = intval(substr($lastToken->token_number, 4));
                $newNumber = $lastNumber + 1;
                $tokenNumber = 'TKN-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $tokenNumber = 'TKN-001';
            }

            // ✅ Get position
            $department = $request->department ?? 'OPD';
            $position = Token::where('department', $department)
                             ->whereIn('status', ['waiting', 'calling'])
                             ->count() + 1;

            $estimatedTime = $position * 15;

            // ✅ Create token with patient_name
            $token = Token::create([
                'token_number' => $tokenNumber,
                'patient_id' => null,
                'patient_name' => $request->name,
                'phone' => null,
                'email' => null,
                'department' => $department,
                'status' => 'waiting',
                'position' => $position,
                'estimated_time' => $estimatedTime,
                'created_at' => now()
            ]);

            // ✅ Save age in patient model or token (if you have age column)
            // If you have a Patient model, create it too
            // Patient::create([...]);

            DB::commit();

            Log::info('Physical patient added: ' . $tokenNumber);

            return response()->json([
                'success' => true,
                'message' => 'Patient added successfully',
                'token_number' => $tokenNumber,
                'token' => $token
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Add patient error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding patient: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Queue List
     */
    public function getQueue()
    {
        try {
            $tokens = Token::whereIn('status', ['waiting', 'calling', 'serving'])
                           ->orderBy('position', 'asc')
                           ->get();

            $total = Token::whereIn('status', ['waiting', 'calling', 'serving'])->count();
            $serving = Token::where('status', 'serving')->first();
            $avgWait = Token::whereIn('status', ['waiting', 'calling'])->avg('estimated_time') ?? 0;

            return response()->json([
                'success' => true,
                'queue' => $tokens,
                'total' => $total,
                'serving' => $serving ? $serving->token_number : null,
                'avgWait' => round($avgWait)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ... other functions ...
}