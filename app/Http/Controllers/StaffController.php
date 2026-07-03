<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    // ✅ Staff Dashboard
    public function dashboard()
    {
        return view('Pages.Staff');
    }

    // ✅ Get Combined Queue (Guest + Physical)
    public function getQueue()
    {
        try {
            $tokens = Token::whereIn('status', ['waiting', 'calling', 'serving'])
                           ->orderBy('created_at', 'asc')
                           ->get();

            $total = $tokens->count();
            $serving = Token::where('status', 'serving')->first();
            $avgWait = $total * 15;

            return response()->json([
                'success' => true,
                'queue' => $tokens,
                'total' => $total,
                'serving' => $serving ? $serving->token_number : '--',
                'avgWait' => $avgWait
            ]);
        } catch (\Exception $e) {
            Log::error('Get queue error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Add Physical Patient
    public function addPatient(Request $request)
    {
        Log::info('Add patient called', $request->all());

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'age' => 'nullable|integer|min:1|max:150',
                'department' => 'nullable|string|max:50'
            ]);

            // Generate token number
            $lastToken = Token::orderBy('id', 'desc')->first();
            
            if ($lastToken) {
                $lastNumber = intval(substr($lastToken->token_number, 4));
                $newNumber = $lastNumber + 1;
                $tokenNumber = 'TKN-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $tokenNumber = 'TKN-001';
            }

            // Calculate position
            $department = $request->department ?? 'OPD';
            $position = Token::where('department', $department)
                             ->whereIn('status', ['waiting', 'calling'])
                             ->count() + 1;
            $estimatedTime = $position * 15;

            // Save token
            $token = Token::create([
                'token_number' => $tokenNumber,
                'patient_id' => null,
                'patient_name' => $request->name,
                'age' => $request->age,
                'department' => $department,
                'status' => 'waiting',
                'type' => 'physical',
                'position' => $position,
                'estimated_time' => $estimatedTime,
                'created_at' => now()
            ]);

            Log::info('Token created: ' . $token->id . ' - ' . $tokenNumber);

            return response()->json([
                'success' => true,
                'message' => 'Patient added to queue!',
                'token_number' => $tokenNumber,
                'token' => $token
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . json_encode($e->errors())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Add patient error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding patient: ' . $e->getMessage()
            ], 500);
        }
    }

    // ✅ Start Serving (Call Patient)
    public function startServing(Request $request)
    {
        try {
            $token = Token::findOrFail($request->token_id);
            $token->status = 'calling';
            $token->called_at = now();
            $token->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Start serving error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Complete Service
    public function completeService(Request $request)
    {
        try {
            $token = Token::findOrFail($request->token_id);
            $token->status = 'completed';
            $token->completed_at = now();
            $token->save();

            // Auto call next
            $this->callNext();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Complete service error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Cancel Token
    public function cancelToken(Request $request)
    {
        try {
            $token = Token::findOrFail($request->token_id);
            $token->status = 'missed';
            $token->save();

            $this->callNext();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Cancel token error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Call Next Patient
    public function callNext()
    {
        try {
            $next = Token::where('status', 'waiting')
                         ->orderBy('created_at', 'asc')
                         ->first();

            if ($next) {
                $next->status = 'calling';
                $next->called_at = now();
                $next->save();

                return response()->json(['success' => true, 'patient' => $next]);
            }

            return response()->json(['success' => false, 'message' => 'No patients waiting']);
        } catch (\Exception $e) {
            Log::error('Call next error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Cancel Missed Patient (Timer Timeout)
    public function cancelPatient(Request $request)
    {
        try {
            $token = Token::findOrFail($request->token_id);
            $token->status = 'missed';
            $token->save();

            $this->callNext();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Cancel patient error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Set Global Time
    public function setGlobalTime(Request $request)
    {
        try {
            $minutes = $request->minutes;
            Token::where('status', 'waiting')->update(['estimated_time' => $minutes]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Set global time error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}