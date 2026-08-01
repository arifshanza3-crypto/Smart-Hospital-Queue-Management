<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::all();
        return view('Pages.Admin.Doctor_management', compact('doctors'));
    }

    public function create()
    {
        return view('Component.Admin.add_doctor');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'email' => 'required|email|unique:doctors,email',
            'phone' => 'required|string|max:20',
            'status' => 'required|in:active,inactive,on_duty'
        ]);

        try {
            $doctor = Doctor::create([
                'name' => $request->name,
                'specialization' => $request->specialization,
                'qualification' => $request->qualification,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $request->status,
                'slug' => Str::slug($request->name)
            ]);

            Log::info('Doctor added: ' . $doctor->name);

            // ✅ Notification for doctor added
            $this->sendDoctorNotification(
                'Doctor Added',
                'Dr. ' . $doctor->name . ' (' . $doctor->specialization . ') has been added to the system',
                'doctor_added',
                $doctor,
                'fa-user-md'
            );

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor "' . $doctor->name . '" added successfully!');

        } catch (\Exception $e) {
            Log::error('Error adding doctor: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        return view('Layout.edit-doctor', compact('doctor'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'email' => 'required|email|unique:doctors,email,' . $id,
            'phone' => 'required|string|max:20',
            'status' => 'required|in:active,inactive,on_duty'
        ]);

        try {
            $doctor = Doctor::findOrFail($id);
            
            $oldName = $doctor->name;
            $oldSpecialization = $doctor->specialization;
            
            $doctor->name = $request->name;
            $doctor->specialization = $request->specialization;
            $doctor->qualification = $request->qualification;
            $doctor->email = $request->email;
            $doctor->phone = $request->phone;
            $doctor->status = $request->status;
            $doctor->slug = Str::slug($request->name);
            $doctor->save();

            // ✅ Notification for doctor updated
            $this->sendDoctorNotification(
                'Doctor Updated',
                'Dr. ' . $doctor->name . ' (' . $doctor->specialization . ') has been updated',
                'doctor_updated',
                $doctor,
                'fa-user-edit',
                ['old_name' => $oldName, 'old_specialization' => $oldSpecialization]
            );

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            $doctorName = $doctor->name;
            $doctorSpecialization = $doctor->specialization;
            $doctorId = $doctor->id;
            $doctor->delete();

            Log::info('Doctor deleted: ' . $doctorName);

            // ✅ Notification for doctor deleted
            $this->sendDoctorDeleteNotification($doctorName, $doctorSpecialization, $doctorId);

            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            Log::error('Error deleting doctor: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * ✅ Send notification for doctor add/update
     */
    private function sendDoctorNotification($title, $message, $type, $doctor, $icon, $extra = [])
    {
        try {
            $admins = User::where('role', 'admin')->get();
            
            if ($admins->count() === 0) {
                Log::warning('No admin users found to send notification');
                return;
            }

            $data = array_merge([
                'icon' => $icon,
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->name,
                'doctor_specialization' => $doctor->specialization,
                'url' => route('admin.doctors.edit', $doctor->id)
            ], $extra);

            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => $title,
                    'message' => $message,
                    'type' => $type,
                    'data' => json_encode($data),
                    'created_at' => now()
                ]);
            }

            Log::info('Notification sent to ' . $admins->count() . ' admins: ' . $title);

        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Send notification for doctor delete
     */
    private function sendDoctorDeleteNotification($doctorName, $doctorSpecialization, $doctorId)
    {
        try {
            $admins = User::where('role', 'admin')->get();
            
            if ($admins->count() === 0) {
                Log::warning('No admin users found to send notification');
                return;
            }

            $data = [
                'icon' => 'fa-user-times',
                'doctor_name' => $doctorName,
                'doctor_specialization' => $doctorSpecialization,
                'doctor_id' => $doctorId,
                'message' => 'Dr. ' . $doctorName . ' (' . $doctorSpecialization . ') has been removed from the system'
            ];

            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Doctor Deleted',
                    'message' => 'Dr. ' . $doctorName . ' (' . $doctorSpecialization . ') has been removed from the system',
                    'type' => 'doctor_deleted',
                    'data' => json_encode($data),
                    'created_at' => now()
                ]);
            }

            Log::info('Delete notification sent to ' . $admins->count() . ' admins');

        } catch (\Exception $e) {
            Log::error('Failed to create delete notification: ' . $e->getMessage());
        }
    }
}