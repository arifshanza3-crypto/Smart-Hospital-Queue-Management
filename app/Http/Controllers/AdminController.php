<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Token;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // ✅ Get all required data
        $doctors = Doctor::all();
        $totalDoctors = Doctor::count();
        $totalPatients = User::where('role', 'user')->count();
        $totalStaff = User::where('role', 'staff')->count();
        $pendingStaff = User::where('role', 'staff')->where('status', 'pending')->get();
        $todayTokens = Token::whereDate('created_at', today())->count();
        $servingTokens = Token::where('status', 'serving')->count();
        
        return view('Pages.Admin.Doctor_management', compact(
            'doctors',
            'totalDoctors',
            'totalPatients',
            'totalStaff',
            'pendingStaff',
            'todayTokens',
            'servingTokens'
        ));
    }

    public function approveStaff($id)
    {
        $staff = User::findOrFail($id);
        $staff->status = 'active';
        $staff->save();
        
        return redirect()->back()->with('success', 'Staff approved successfully!');
    }

    public function rejectStaff($id)
    {
        $staff = User::findOrFail($id);
        $staff->delete();
        
        return redirect()->back()->with('success', 'Staff rejected successfully!');
    }
}