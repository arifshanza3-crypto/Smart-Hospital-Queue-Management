<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Staff;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $doctors = Doctor::all();
        $pendingStaff = User::where('role', 'staff')
                            ->where('status', 'pending')
                            ->get();

        return view('Pages.Admin.Doctor_management', compact('pendingStaff', 'doctors'));
    }

    public function approveStaff($id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'staff') {
            return back()->with('error', 'Only staff members can be approved.');
        }

        $user->status = 'approved';
        $user->save();

        return back()->with('success', 'Staff approved successfully!');
    }

    public function rejectStaff($id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'staff') {
            return back()->with('error', 'Invalid operation.');
        }

        $user->status = 'rejected';
        $user->save();

        return back()->with('error', 'Staff rejected.');
    }
}