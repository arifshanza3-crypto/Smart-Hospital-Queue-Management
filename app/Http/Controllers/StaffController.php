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
                           ->orderBy('department', 'asc')
                           ->orderBy('position', 'asc')
                           ->get();

            $total = $tokens->count();
            $serving = Token::where('status', 'serving')->first();
            
            $avgWait = $this->calculateAverageWait($tokens);

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

    // ✅ Get Department-wise Queue
    public function getDepartmentQueue(Request $request)
    {
        try {
            $department = $request->query('dept');
            
            Log::info('Department queue requested: ' . $department);
            
            if (!$department || $department === 'all') {
                return $this->getQueue();
            }

            $tokens = Token::where('department', $department)
                           ->whereIn('status', ['waiting', 'calling', 'serving'])
                           ->orderBy('position', 'asc')
                           ->get();

            $total = $tokens->count();
            $serving = $tokens->where('status', 'serving')->first();
            $avgWait = $this->calculateDepartmentWait($tokens, $department);

            return response()->json([
                'success' => true,
                'queue' => $tokens,
                'total' => $total,
                'serving' => $serving ? $serving->token_number : '--',
                'avgWait' => $avgWait,
                'department' => $department
            ]);
        } catch (\Exception $e) {
            Log::error('Get department queue error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
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

    // ✅ Calculate department-wise wait time
    private function calculateDepartmentWait($tokens, $department)
    {
        if ($tokens->isEmpty()) return 0;
        
        $timePerPatient = $this->getDepartmentTime($department);
        $waiting = $tokens->where('status', 'waiting')->count();
        return $waiting * $timePerPatient;
    }

    // ✅ Department-wise time per patient
    private function getDepartmentTime($department)
    {
        $times = [
            'OPD' => 15,
            'Pharmacy' => 15,
            'Radiology' => 15,
            'General' => 15
        ];
        return $times[$department] ?? 15;
    }

    // ✅ Add Physical Patient
    public function addPatient(Request $request)
    {
        Log::info('Add patient called', $request->all());

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'department' => 'nullable|string|max:50'
            ]);

            $lastToken = Token::orderBy('id', 'desc')->first();
            
            if ($lastToken && $lastToken->token_number) {
                $lastNumber = intval(substr($lastToken->token_number, 4));
                $newNumber = $lastNumber + 1;
                $tokenNumber = 'TKN-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $tokenNumber = 'TKN-001';
            }

            $department = $request->department ?? 'OPD';
            $position = Token::where('department', $department)
                             ->whereIn('status', ['waiting', 'calling'])
                             ->count() + 1;
            
            $estimatedTime = $position * $this->getDepartmentTime($department);

            $token = Token::create([
                'token_number' => $tokenNumber,
                'patient_id' => null,
                'patient_name' => $request->name,
                'department' => $department,
                'status' => 'waiting',
                'type' => 'physical',
                'position' => $position,
                'estimated_time' => $estimatedTime,
                'created_at' => now()
            ]);

            $this->recalculatePositions($department);

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

    // ✅ Start Serving (Call Patient) - MANUAL
    public function startServing(Request $request)
    {
        try {
            $token = Token::findOrFail($request->token_id);
            
            // ✅ Only 'waiting' tokens can be called
            if ($token->status !== 'waiting') {
                return response()->json([
                    'success' => false,
                    'message' => 'This token is not waiting!'
                ], 400);
            }
            
            // ✅ Set status to 'calling'
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

    // ✅ Patient Arrived - Start Service - MANUAL
    public function startService(Request $request)
    {
        try {
            $token = Token::findOrFail($request->token_id);
            
            // ✅ Only 'calling' tokens can be served
            if ($token->status !== 'calling') {
                return response()->json([
                    'success' => false,
                    'message' => 'This token is not in calling status!'
                ], 400);
            }
            
            // ✅ Set status to 'serving'
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

    // ✅ Complete Service - MANUAL
    public function completeService(Request $request)
    {
        try {
            $token = Token::findOrFail($request->token_id);
            $department = $token->department;
            
            // ✅ Only 'serving' tokens can be completed
            if ($token->status !== 'serving') {
                return response()->json([
                    'success' => false,
                    'message' => 'This token is not being served!'
                ], 400);
            }
            
            $token->status = 'completed';
            $token->completed_at = now();
            $token->save();

            $this->recalculatePositions($department);
            
            // ❌ AUTO-CALL REMOVED

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Complete service error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Cancel Token - MANUAL
    public function cancelToken(Request $request)
    {
        try {
            $token = Token::findOrFail($request->token_id);
            $department = $token->department;
            
            // ✅ Only 'waiting' or 'calling' tokens can be cancelled
            if (!in_array($token->status, ['waiting', 'calling'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'This token cannot be cancelled!'
                ], 400);
            }
            
            $token->status = 'cancelled';
            $token->save();

            $this->recalculatePositions($department);
            
            // ❌ AUTO-CALL REMOVED

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Cancel token error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Cancel Patient (Timer Timeout) - MANUAL
    public function cancelPatient(Request $request)
    {
        try {
            $token = Token::findOrFail($request->token_id);
            $department = $token->department;
            
            $token->status = 'missed';
            $token->save();

            $this->recalculatePositions($department);
            
            // ❌ AUTO-CALL REMOVED

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Cancel patient error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Recalculate positions for a department
    private function recalculatePositions($department)
    {
        Log::info('Recalculating positions for department: ' . $department);
        
        $timePerPatient = $this->getDepartmentTime($department);
        
        $tokens = Token::where('department', $department)
                       ->whereIn('status', ['waiting', 'calling'])
                       ->orderBy('created_at', 'asc')
                       ->get();

        $position = 1;
        foreach ($tokens as $token) {
            $token->position = $position;
            $token->estimated_time = $position * $timePerPatient;
            $token->save();
            $position++;
        }
        
        Log::info('Recalculated ' . $tokens->count() . ' tokens for department: ' . $department);
    }

    // ❌ callNext() - COMPLETELY REMOVED (No auto-call anywhere)

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

    // ✅ Get Department Stats
    public function getDepartmentStats()
    {
        try {
            $departments = ['OPD', 'Pharmacy', 'Radiology'];
            $stats = [];

            foreach ($departments as $dept) {
                $waiting = Token::where('department', $dept)
                                ->where('status', 'waiting')
                                ->count();
                $calling = Token::where('department', $dept)
                                ->where('status', 'calling')
                                ->count();
                $serving = Token::where('department', $dept)
                                ->where('status', 'serving')
                                ->first();
                $total = Token::where('department', $dept)
                              ->whereIn('status', ['waiting', 'calling', 'serving'])
                              ->count();

                $stats[$dept] = [
                    'waiting' => $waiting,
                    'calling' => $calling,
                    'serving' => $serving ? $serving->token_number : '--',
                    'total' => $total,
                    'timePerPatient' => $this->getDepartmentTime($dept)
                ];
            }

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}