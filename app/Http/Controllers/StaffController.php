<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QueueReport;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function dashboard()
    {
        // Get all patients
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
    
    public function addPatient(Request $request)
    {
        try {
            // Generate token
            $lastToken = QueueReport::latest()->first();
            $nextNumber = $lastToken ? intval(substr($lastToken->token_number, 1)) + 1 : 1;
            $token = 'T' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            
            $patient = QueueReport::create([
                'token_number' => $token,
                'patient_name' => $request->patient_name,
                'doctor_id' => 1,
                'doctor_name' => 'Dr. Staff',
                'department' => $request->department ?? 'General',
                'status' => 'waiting',
                'type' => 'physical',
                'waiting_time' => 15,
                'service_time' => 0,
                'date' => today()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Patient added successfully',
                'token' => $token
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function serve($id)
    {
        $patient = QueueReport::findOrFail($id);
        $patient->status = 'in_progress';
        $patient->save();
        return response()->json(['success' => true]);
    }
    
    public function complete($id)
    {
        $patient = QueueReport::findOrFail($id);
        $patient->status = 'completed';
        $patient->completed_at = now();
        $patient->service_time = $patient->waiting_time;
        $patient->save();
        return response()->json(['success' => true]);
    }
    
    public function cancel($id)
    {
        $patient = QueueReport::findOrFail($id);
        $patient->status = 'cancelled';
        $patient->save();
        return response()->json(['success' => true]);
    }
}