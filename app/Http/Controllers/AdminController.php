<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Staff;
use App\Models\Doctor; // Ensure your Doctor Eloquent model is imported
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Fetch data so the view variables are present and do not error out
        $doctors = class_exists(\App\Models\Doctor::class) ? Doctor::all() : collect();

        $pendingStaff = User::where('role', 'staff')
                            ->where('status', 'pending')
                            ->get();
        
        return view('Pages.Admin.Doctor_management', compact('pendingStaff', 'doctors'));
    }
    
    public function approveStaff($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'staff') {
            return back()->with('error', 'Only staff members can be approved');
        }
        
        $existingStaff = Staff::where('employee_id', $user->employee_id)->first();
        
        if (!$existingStaff) {
            Staff::create([
                'full_name' => $user->full_name,
                'employee_id' => $user->employee_id,
                'email' => $user->email,
                'password' => $user->password,
                'department' => $user->department ?? 'General',
                'status' => 'active'
            ]);
        }
        
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