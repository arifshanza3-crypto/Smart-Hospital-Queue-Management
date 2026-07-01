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
    }

    // ✅ Add Physical Patient
    public function addPatient(Request $request)
    {
        Log::info('Add patient called', $request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer'
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
        $position = Token::whereIn('status', ['waiting', 'calling'])->count() + 1;
        $estimatedTime = $position * 15;

        // Save token
        $token = Token::create([
            'token_number' => $tokenNumber,
            'patient_id' => null,
            'patient_name' => $request->name,
            'age' => $request->age,
            'department' => 'General',
            'status' => 'waiting',
            'type' => 'physical',
            'position' => $position,
            'estimated_time' => $estimatedTime,
            'created_at' => now()
        ]);

        Log::info('Token created: ' . $token->id);

        return response()->json([
            'success' => true,
            'message' => 'Patient added to queue!',
            'token' => $token
        ]);
    }

    // ✅ Start Serving (Call Patient)
    public function startServing(Request $request)
    {
        $token = Token::findOrFail($request->token_id);
        $token->status = 'calling';
        $token->called_at = now();
        $token->save();

        return response()->json(['success' => true]);
    }

    // ✅ Complete Service
    public function completeService(Request $request)
    {
        $token = Token::findOrFail($request->token_id);
        $token->status = 'completed';
        $token->completed_at = now();
        $token->save();

        // Auto call next
        $this->callNext();

        return response()->json(['success' => true]);
    }

    // ✅ Cancel Token
    public function cancelToken(Request $request)
    {
        $token = Token::findOrFail($request->token_id);
        $token->status = 'missed';
        $token->save();

        $this->callNext();

        return response()->json(['success' => true]);
    }

    // ✅ Call Next Patient
    public function callNext()
    {
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
    }

    // ✅ Cancel Missed Patient (Timer Timeout)
    public function cancelPatient(Request $request)
    {
        $token = Token::findOrFail($request->token_id);
        $token->status = 'missed';
        $token->save();

        $this->callNext();

        return response()->json(['success' => true]);
    }

    // ✅ Set Global Time
    public function setGlobalTime(Request $request)
    {
        $minutes = $request->minutes;
        Token::where('status', 'waiting')->update(['estimated_time' => $minutes]);

        return response()->json(['success' => true]);
    }
}