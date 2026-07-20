<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    public function dashboard()
    {
        return view('Pages.Staff');
    }

    // ✅ Simple queue - no department filter
    public function getQueue()
    {
        try {
            $tokens = Token::whereIn('status', ['waiting', 'calling', 'serving'])
                           ->orderBy('position', 'asc')
                           ->get();

            $total = $tokens->count();
            
            $servingTokens = Token::where('status', 'serving')
                                  ->get();
            
            $servingText = '';
            if ($servingTokens->count() > 0) {
                $servingText = $servingTokens->map(function($token) {
                    return $token->token_number;
                })->implode(', ');
            } else {
                $servingText = '--';
            }
            
            $avgWait = $this->calculateAverageWait($tokens);

            return response()->json([
                'success' => true,
                'queue' => $tokens,
                'total' => $total,
                'serving' => $servingText,
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

    // ✅ Add Physical Patient - Simple (No Department)
    public function addPatient(Request $request)
    {
        Log::info('Add patient called', $request->all());

        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $lastToken = Token::orderBy('id', 'desc')->first();
            
            if ($lastToken && $lastToken->token_number) {
                $lastNumber = intval(substr($lastToken->token_number, 4));
                $newNumber = $lastNumber + 1;
                $tokenNumber = 'TKN-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $tokenNumber = 'TKN-001';
            }

            // ✅ Simple position - global queue
            $position = Token::whereIn('status', ['waiting', 'calling'])->count() + 1;
            
            $estimatedTime = $position * 15;

            $token = Token::create([
                'token_number' => $tokenNumber,
                'patient_id' => null,
                'patient_name' => $request->name,
                'department' => 'General',
                'status' => 'waiting',
                'type' => 'physical',
                'position' => $position,
                'estimated_time' => $estimatedTime,
                'created_at' => now()
            ]);

            $this->recalculatePositions();

            Log::info('Token created: ' . $token->id . ' - ' . $tokenNumber);

            return response()->json([
                'success' => true,
                'message' => 'Patient added to queue!',
                'token_number' => $tokenNumber,
                'token' => $token
            ]);

        } catch (\Exception $e) {
            Log::error('Add patient error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding patient: ' . $e->getMessage()
            ], 500);
        }
    }

    // ✅ Simple recalculate positions
    private function recalculatePositions()
    {
        $tokens = Token::whereIn('status', ['waiting', 'calling'])
                       ->orderBy('created_at', 'asc')
                       ->get();

        $position = 1;
        foreach ($tokens as $token) {
            $token->position = $position;
            $token->estimated_time = $position * 15;
            $token->save();
            $position++;
        }
    }

    // ✅ Calculate average wait time
    private function calculateAverageWait($tokens)
    {
        if ($tokens->isEmpty()) return 0;
        
        $totalWait = 0;
        foreach ($tokens as $token) {
            $totalWait += $token->estimated_time ?? 15;
        }
        return round($totalWait / $tokens->count());
    }

    // ✅ Start Serving (Call Patient)
    public function startServing(Request $request)
    {
        try {
            $token = Token::findOrFail($request->token_id);
            
            if ($token->status !== 'waiting') {
                return response()->json([
                    'success' => false,
                    'message' => 'This token is not waiting!'
                ], 400);
            }
            
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

    // ✅ Patient Arrived - Start Service
    public function startService(Request $request)
    {
        try {
            $token = Token::findOrFail($request->token_id);
            
            if ($token->status !== 'calling') {
                return response()->json([
                    'success' => false,
                    'message' => 'This token is not in calling status!'
                ], 400);
            }
            
            $token->status = 'serving';
            $token->called_at = now();
            $token->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Start service error: ' . $e->getMessage());
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

            $this->recalculatePositions();

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
            $token->status = 'cancelled';
            $token->save();

            $this->recalculatePositions();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Cancel token error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Cancel Patient (Timer Timeout)
    public function cancelPatient(Request $request)
    {
        try {
            $token = Token::findOrFail($request->token_id);
            $token->status = 'missed';
            $token->save();

            $this->recalculatePositions();

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