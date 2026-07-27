<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token;
use App\Models\QueueReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    // ✅ Staff Dashboard - Allow both Admin and Staff
    public function dashboard()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login first.');
        }
        
        $user = Auth::user();
        
        // ✅ Allow admin and staff to access staff dashboard
        if (!in_array($user->role, ['admin', 'staff'])) {
            return redirect('/login')->with('error', 'Access denied. Only Admin and Staff can access this page.');
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

    // ✅ Add Physical Patient
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

            // Recalculate all positions for this department
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

    // ✅ Start Service (Patient Arrived)
    public function startService(Request $request)
    {
        try {
            $token = Token::findOrFail($request->token_id);
            $token->status = 'serving';
            $token->started_at = now();
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
            $department = $token->department;
            $token->status = 'completed';
            $token->completed_at = now();
            $token->save();

            // Recalculate positions for this department
            $this->recalculatePositions($department);
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
            $department = $token->department;
            $token->status = 'cancelled';
            $token->save();

            $this->recalculatePositions($department);
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

    // ✅ Call Next Patient
    public function callNext()
    {
        try {
            // Complete any serving patient
            $serving = Token::where('status', 'serving')->first();
            if ($serving) {
                $department = $serving->department;
                $serving->status = 'completed';
                $serving->completed_at = now();
                $serving->save();
                
                $this->recalculatePositions($department);
            }

            // Call next waiting patient
            $next = Token::where('status', 'waiting')
                         ->orderBy('position', 'asc')
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
            $department = $token->department;
            $token->status = 'missed';
            $token->save();

            $this->recalculatePositions($department);
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
}