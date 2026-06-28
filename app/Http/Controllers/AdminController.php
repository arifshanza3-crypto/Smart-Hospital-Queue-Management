<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingStaff = User::where('role', 'staff')
                            ->where('status', 'pending')
                            ->get();
        
        return view('Pages.Admin.Doctor_management', compact('pendingStaff'));
    }
    
    public function approveStaff($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'staff') {
            return back()->with('error', 'Only staff members can be approved');
        }
        
        // Check if already in staff table
        $existingStaff = Staff::where('employee_id', $user->employee_id)->first();
        
        if (!$existingStaff) {
            // MOVE data to staff table
            Staff::create([
                'full_name' => $user->full_name,
                'employee_id' => $user->employee_id,
                'email' => $user->email,
                'password' => $user->password,
                'department' => $user->department ?? 'General',
                'status' => 'active'
            ]);
        }
        
        // Update user status to approved
        $user->status = 'approved';
        $user->save();
        
        return back()->with('success', 'Staff approved and moved to staff table!');
    }
    
    public function rejectStaff($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'staff') {
            return back()->with('error', 'Invalid operation');
        }
        
        $user->status = 'rejected';
        $user->save();
        
        return back()->with('error', 'Staff rejected.');
    }
}