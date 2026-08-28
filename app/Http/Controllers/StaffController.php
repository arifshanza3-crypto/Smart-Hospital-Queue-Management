<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token;
use App\Models\QueueReport;
use App\Models\User;
use App\Models\Notification;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    use NotificationTrait;

    // ✅ Staff Dashboard
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login first.');
        }
        
        $user = Auth::user();
        
        if (!in_array($user->role, ['admin', 'staff'])) {
            return redirect('/login')->with('error', 'Access denied.');
        }
        
        $patients = QueueReport::orderBy('created_at', 'desc')->get();
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

    private function calculateAverageWait($tokens)
    {
        if ($tokens->isEmpty()) return 0;
        $totalWait = 0;
        foreach ($tokens as $token) {
            $totalWait += $token->estimated_time ?? 15;
        }
        return round($totalWait / $tokens->count());
    }

    private function calculateDepartmentWait($tokens, $department)
    {
        if ($tokens->isEmpty()) return 0;
        $timePerPatient = $this->getDepartmentTime($department);
        $waiting = $tokens->where('status', 'waiting')->count();
        return $waiting * $timePerPatient;
    }

    private function getDepartmentTime($department)
    {
        $times = [
            'OPD' => 15, 'Pharmacy' => 15, 'Radiology' => 15,
            'General' => 15, 'Cardiology' => 20, 'Neurology' => 25,
            'Pediatrics' => 15, 'Orthopedics' => 20, 'Dermatology' => 15,
            'Ophthalmology' => 15
        ];
        return $times[$department] ?? 15;
    }

    // ✅ Add Physical Patient - Updated with Mobile Number
    public function addPatient(Request $request)
    {
        Log::info('Add patient called', $request->all());

        try {
            // ✅ Validation with mobile_number
            $request->validate([
                'name' => 'required|string|max:255',
                'mobile_number' => 'required|string|max:11|regex:/^03\d{9}$/',
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
                'phone' => $request->mobile_number,  // ✅ Mobile number saved here
                'email' => null,
                'department' => $department,
                'status' => 'waiting',
                'type' => 'physical',
                'position' => $position,
                'estimated_time' => $estimatedTime,
                'created_at' => now()
            ]);

            $this->recalculatePositions($department);

            Log::info('Token created: ' . $token->id . ' - ' . $tokenNumber);

            // ✅ BELL NOTIFICATION - Send to all staff and admins
            $this->notifyAllStaffAndAdmins(
                'New Patient Added',
                'Patient "' . $request->name . '" added to ' . $department . ' queue (Token: ' . $tokenNumber . ')',
                'physical_patient_added',
                [
                    'token_number' => $tokenNumber,
                    'patient_name' => $request->name,
                    'department' => $department,
                    'url' => route('staff.dashboard')
                ]
            );

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

    // ✅ Start Serving (Call Patient)
    public function startServing(Request $request)
    {
        try {
            $token = Token::findOrFail($request->token_id);
            $token->status = 'calling';
            $token->called_at = now();
            $token->save();

            // ✅ BELL NOTIFICATION - Staff & Admins
            $this->notifyAllStaffAndAdmins(
                'Token Called',
                'Token ' . $token->token_number . ' (' . $token->patient_name . ') has been called',
                'token_called',
                [
                    'token_number' => $token->token_number,
                    'patient_name' => $token->patient_name,
                    'department' => $token->department,
                    'url' => route('staff.dashboard')
                ]
            );

            // ✅ BELL NOTIFICATION - User/Patient
            if ($token->patient_id) {
                $this->notifyUser(
                    $token->patient_id,
                    'Token Called',
                    'Your token ' . $token->token_number . ' has been called. Please proceed to the counter.',
                    'token_called',
                    [
                        'token_number' => $token->token_number,
                        'department' => $token->department,
                        'url' => route('status.page', ['token' => $token->token_number])
                    ]
                );
            }

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

            // ✅ BELL NOTIFICATION - Staff & Admins
            $this->notifyAllStaffAndAdmins(
                'Patient Arrived',
                'Patient ' . $token->patient_name . ' (Token: ' . $token->token_number . ') has arrived',
                'token_arrived',
                [
                    'token_number' => $token->token_number,
                    'patient_name' => $token->patient_name,
                    'department' => $token->department,
                    'url' => route('staff.dashboard')
                ]
            );

            // ✅ BELL NOTIFICATION - User/Patient
            if ($token->patient_id) {
                $this->notifyUser(
                    $token->patient_id,
                    'Patient Arrived',
                    'You have arrived for token ' . $token->token_number . '. Please wait for your turn.',
                    'token_arrived',
                    [
                        'token_number' => $token->token_number,
                        'department' => $token->department,
                        'url' => route('status.page', ['token' => $token->token_number])
                    ]
                );
            }

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

            $this->recalculatePositions($department);
            $this->callNext();

            // ✅ BELL NOTIFICATION - Staff & Admins
            $this->notifyAllStaffAndAdmins(
                'Service Completed',
                'Service for ' . $token->patient_name . ' (Token: ' . $token->token_number . ') completed',
                'token_completed',
                [
                    'token_number' => $token->token_number,
                    'patient_name' => $token->patient_name,
                    'department' => $token->department,
                    'url' => route('staff.dashboard')
                ]
            );

            // ✅ BELL NOTIFICATION - User/Patient
            if ($token->patient_id) {
                $this->notifyUser(
                    $token->patient_id,
                    'Service Completed',
                    'Your service for token ' . $token->token_number . ' is complete. Thank you!',
                    'token_completed',
                    [
                        'token_number' => $token->token_number,
                        'department' => $token->department,
                        'url' => route('status.page', ['token' => $token->token_number])
                    ]
                );
            }

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
            $tokenName = $token->patient_name;
            $tokenNumber = $token->token_number;
            $token->status = 'cancelled';
            $token->save();

            $this->recalculatePositions($department);
            $this->callNext();

            // ✅ BELL NOTIFICATION - Staff & Admins
            $this->notifyAllStaffAndAdmins(
                'Token Cancelled',
                'Token ' . $tokenNumber . ' (' . $tokenName . ') has been cancelled',
                'token_cancelled',
                [
                    'token_number' => $tokenNumber,
                    'patient_name' => $tokenName,
                    'department' => $department,
                    'url' => route('staff.dashboard')
                ]
            );

            // ✅ BELL NOTIFICATION - User/Patient
            if ($token->patient_id) {
                $this->notifyUser(
                    $token->patient_id,
                    'Token Cancelled',
                    'Your token ' . $tokenNumber . ' has been cancelled.',
                    'token_cancelled',
                    [
                        'token_number' => $tokenNumber,
                        'department' => $department,
                        'url' => route('status.page', ['token' => $tokenNumber])
                    ]
                );
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Cancel token error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Cancel Patient (Missed)
    public function cancelPatient(Request $request)
    {
        try {
            $token = Token::findOrFail($request->token_id);
            $department = $token->department;
            $tokenName = $token->patient_name;
            $tokenNumber = $token->token_number;
            $token->status = 'missed';
            $token->save();

            $this->recalculatePositions($department);
            $this->callNext();

            // ✅ BELL NOTIFICATION - Staff & Admins
            $this->notifyAllStaffAndAdmins(
                'Patient Missed',
                'Patient ' . $tokenName . ' (Token: ' . $tokenNumber . ') missed appointment',
                'token_cancelled',
                [
                    'token_number' => $tokenNumber,
                    'patient_name' => $tokenName,
                    'department' => $department,
                    'url' => route('staff.dashboard')
                ]
            );

            // ✅ BELL NOTIFICATION - User/Patient
            if ($token->patient_id) {
                $this->notifyUser(
                    $token->patient_id,
                    'Token Missed',
                    'Your token ' . $tokenNumber . ' has been marked as missed.',
                    'token_cancelled',
                    [
                        'token_number' => $tokenNumber,
                        'department' => $department,
                        'url' => route('status.page', ['token' => $tokenNumber])
                    ]
                );
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Cancel patient error: ' . $e->getMessage());
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
            $serving = Token::where('status', 'serving')->first();
            if ($serving) {
                $department = $serving->department;
                $serving->status = 'completed';
                $serving->completed_at = now();
                $serving->save();
                $this->recalculatePositions($department);
            }

            $next = Token::where('status', 'waiting')
                         ->orderBy('position', 'asc')
                         ->first();

            if ($next) {
                $next->status = 'calling';
                $next->called_at = now();
                $next->save();

                // ✅ BELL NOTIFICATION - Staff & Admins
                $this->notifyAllStaffAndAdmins(
                    'Next Token Called',
                    'Token ' . $next->token_number . ' (' . $next->patient_name . ') is next',
                    'token_called',
                    [
                        'token_number' => $next->token_number,
                        'patient_name' => $next->patient_name,
                        'department' => $next->department,
                        'url' => route('staff.dashboard')
                    ]
                );

                // ✅ BELL NOTIFICATION - User/Patient
                if ($next->patient_id) {
                    $this->notifyUser(
                        $next->patient_id,
                        'Next Token Called',
                        'Your token ' . $next->token_number . ' is next in queue. Please be ready.',
                        'token_called',
                        [
                            'token_number' => $next->token_number,
                            'department' => $next->department,
                            'url' => route('status.page', ['token' => $next->token_number])
                        ]
                    );
                }

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

    // ✅ Recalculate positions
    private function recalculatePositions($department)
    {
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
    }

    // ✅ Set Global Time
    public function setGlobalTime(Request $request)
    {
        try {
            $minutes = $request->minutes;
            Token::where('status', 'waiting')->update(['estimated_time' => $minutes]);

            // ✅ BELL NOTIFICATION
            $this->notifyAllStaffAndAdmins(
                'Global Time Updated',
                'Global estimated time set to ' . $minutes . ' minutes',
                'queue_update',
                [
                    'time' => $minutes,
                    'url' => route('staff.dashboard')
                ]
            );

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
                $waiting = Token::where('department', $dept)->where('status', 'waiting')->count();
                $calling = Token::where('department', $dept)->where('status', 'calling')->count();
                $serving = Token::where('department', $dept)->where('status', 'serving')->first();
                $total = Token::where('department', $dept)->whereIn('status', ['waiting', 'calling', 'serving'])->count();

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