<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\NotificationTrait;

class AdminController extends Controller
{
    use NotificationTrait;

    public function dashboard()
    {
        // Get pending staff requests
        $pendingStaff = User::where('role', 'staff')
            ->where('status', 'pending')
            ->get();
        
        // Get all staff
        $staff = User::where('role', 'staff')->get();
        
        return view('Pages.Admin.Doctor_management', compact('pendingStaff', 'staff'));
    }

    public function approveStaff($id)
    {
        try {
            $staff = User::findOrFail($id);
            $staff->status = 'active';
            $staff->save();

            // ✅ Send notification to staff
            $this->notifyUser(
                $staff->id,
                'Account Approved',
                'Your staff account has been approved. You can now access the staff dashboard.',
                'staff_approved',
                [
                    'url' => route('staff.dashboard')
                ]
            );

            // ✅ Notify all admins
            $this->notifyAdmin(
                'Staff Approved',
                $staff->name . ' has been approved as a staff member',
                'staff_approved',
                [
                    'staff_id' => $staff->id,
                    'staff_name' => $staff->name,
                    'url' => route('admin.user-management')
                ]
            );

            return redirect()->back()->with('success', 'Staff approved successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function rejectStaff($id)
    {
        try {
            $staff = User::findOrFail($id);
            $staffName = $staff->name;
            $staff->delete();

            // ✅ Notify all admins
            $this->notifyAdmin(
                'Staff Rejected',
                $staffName . '\'s staff application has been rejected',
                'staff_rejected',
                [
                    'staff_name' => $staffName
                ]
            );

            return redirect()->back()->with('success', 'Staff rejected successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}