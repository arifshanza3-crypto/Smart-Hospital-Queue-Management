<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Token;  // ✅ YEH LINE ADD KARO - TOKEN MODEL IMPORT

class PatientController extends Controller
{
    public function getTokenStatus(Request $request)
    {
        $patientId = Auth::id();
        
        $activeToken = Token::where('patient_id', $patientId)
                            ->whereIn('status', ['waiting', 'calling', 'serving'])
                            ->first();
        
        if ($activeToken) {
            // Calculate position in queue
            $position = Token::where('department', $activeToken->department)
                             ->where('status', 'waiting')
                             ->where('created_at', '<', $activeToken->created_at)
                             ->count() + 1;
            
            // Calculate wait time
            $waitTime = $position * 15; // 15 minutes per patient
            
            return response()->json([
                'success' => true,
                'token_number' => $activeToken->token_number,
                'position' => $position,
                'wait_time' => $waitTime,
                'serving' => $this->getCurrentServing($activeToken->department),
                'status' => $activeToken->status
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No active token'
        ]);
    }

    private function getCurrentServing($department)
    {
        $serving = Token::where('department', $department)
                        ->where('status', 'serving')
                        ->first();
        
        return $serving ? $serving->token_number : '--';
    }
}