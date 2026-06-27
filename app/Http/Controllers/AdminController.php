<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Staff;
<<<<<<< HEAD
=======
use App\Models\Doctor;
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
<<<<<<< HEAD
=======
        $doctors = Doctor::all();
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
        $pendingStaff = User::where('role', 'staff')
                            ->where('status', 'pending')
                            ->get();
        
<<<<<<< HEAD
        return view('Pages.Admin.Doctor_management', compact('pendingStaff'));
=======
        return view('Pages.Admin.Doctor_management', compact('pendingStaff', 'doctors'));
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
    }
    
    public function approveStaff($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'staff') {
            return back()->with('error', 'Only staff members can be approved');
        }
        
<<<<<<< HEAD
        // Check if already in staff table
        $existingStaff = Staff::where('employee_id', $user->employee_id)->first();
        
        if (!$existingStaff) {
            // MOVE data to staff table
=======
        $existingStaff = Staff::where('employee_id', $user->employee_id)->first();
        
        if (!$existingStaff) {
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
            Staff::create([
                'full_name' => $user->full_name,
                'employee_id' => $user->employee_id,
                'email' => $user->email,
                'password' => $user->password,
                'department' => $user->department ?? 'General',
                'status' => 'active'
            ]);
        }
        
<<<<<<< HEAD
        // Update user status to approved
=======
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
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