<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token;
use App\Models\QueueReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    public function dashboard()
    {
<<<<<<< HEAD
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login first.');
=======
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
>>>>>>> 06415fc995ad0e042d0d75894c2f3c150e4c0a70
        }
        
        // Check if user is staff
        if (Auth::user()->role !== 'staff') {
            return redirect('/login')->with('error', 'Access denied. Staff only.');
        }
        
        // Get patients
        $patients = QueueReport::orderBy('created_at', 'desc')->get();
        
        // Statistics
        $totalQueue = QueueReport::whereIn('status', ['waiting', 'in_progress'])->count();
        $nowServing = QueueReport::where('status', 'in_progress')->count();
        $nowServingToken = QueueReport::where('status', 'in_progress')->first()->token_number ?? 'N/A';
        $completedToday = QueueReport::whereDate('created_at', today())->where('status', 'completed')->count();
        $avgWaitTime = QueueReport::where('status', 'completed')->avg('waiting_time') ?? 0;
        
        return view('Pages.Staff', compact(
            'patients', 'totalQueue', 'nowServing', 
            'nowServingToken', 'completedToday', 'avgWaitTime'
        ));
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

<<<<<<< HEAD
    // ✅ Get Queue (All)
    public function getQueue()
    {
        try {
            $tokens = Token::whereIn('status', ['waiting', 'calling', 'serving'])
                           ->orderBy('position', 'asc')
                           ->get();

            return response()->json([
                'success' => true,
                'queue' => $tokens
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
=======
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
>>>>>>> 06415fc995ad0e042d0d75894c2f3c150e4c0a70
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

<<<<<<< HEAD
    // ✅ Calculate department-wise wait time
    private function calculateDepartmentWait($tokens, $department)
    {
        if ($tokens->isEmpty()) return 0;
        
        $timePerPatient = $this->getDepartmentTime($department);
        $waiting = $tokens->where('status', 'waiting')->count();
        return $waiting * $timePerPatient;
    }

    // ✅ Add Physical Patient - FIXED
    public function addPatient(Request $request)
    {
        Log::info('Add patient called', $request->all());

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'department' => 'nullable|string|max:50'
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

            // Department-wise position calculate
            $department = $request->department ?? 'OPD';
            $position = Token::where('department', $department)
                             ->whereIn('status', ['waiting', 'calling'])
                             ->count() + 1;
            
            // Department-wise estimated time
            $estimatedTime = $position * $this->getDepartmentTime($department);

            // Save token
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

            // ✅ FIX: Recalculate all positions for this department
            $this->recalculatePositions($department);

            Log::info('Token created: ' . $token->id . ' - ' . $tokenNumber . ' - Position: ' . $position);

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

    // ✅ Department-wise time per patient
    private function getDepartmentTime($department)
    {
        $times = [
            'OPD' => 15,
            'Pharmacy' => 15,
            'Radiology' => 15,
            'General' => 15,
            'Cardiology' => 20,
            'Neurology' => 25,
            'Pediatrics' => 15,
            'Orthopedics' => 20,
            'Dermatology' => 15,
            'Ophthalmology' => 15
        ];
        return $times[$department] ?? 15;
    }

=======
>>>>>>> 06415fc995ad0e042d0d75894c2f3c150e4c0a70
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
<<<<<<< HEAD

    // ✅ Get Department Stats
    public function getDepartmentStats()
    {
        try {
            $departments = ['OPD', 'Pharmacy', 'Radiology', 'Cardiology', 'Neurology', 'Pediatrics'];
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
=======
>>>>>>> 06415fc995ad0e042d0d75894c2f3c150e4c0a70
}