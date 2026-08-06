<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

            // ✅ Send notification to all admins
            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Doctor Added',
                    'message' => 'Dr. ' . $doctor->name . ' (' . $doctor->specialization . ') has been added to the system',
                    'type' => 'doctor_added',
                    'data' => json_encode([
                        'icon' => 'fa-user-md',
                        'doctor_id' => $doctor->id,
                        'doctor_name' => $doctor->name,
                        'doctor_specialization' => $doctor->specialization,
                        'url' => route('admin.doctors.edit', $doctor->id)
                    ]),
                    'read_at' => null,
                    'created_at' => now()
                ]);
            }

            Log::info('Doctor added: ' . $doctor->name . ' by admin');

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor "' . $doctor->name . '" added successfully!');

        } catch (\Exception $e) {
            Log::error('Error adding doctor: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
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

            // ✅ Send notification to all admins
            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Doctor Updated',
                    'message' => 'Dr. ' . $doctor->name . ' (' . $doctor->specialization . ') has been updated',
                    'type' => 'doctor_updated',
                    'data' => json_encode([
                        'icon' => 'fa-user-edit',
                        'doctor_id' => $doctor->id,
                        'doctor_name' => $doctor->name,
                        'doctor_specialization' => $doctor->specialization,
                        'old_name' => $oldName,
                        'old_specialization' => $oldSpecialization,
                        'url' => route('admin.doctors.edit', $doctor->id)
                    ]),
                    'read_at' => null,
                    'created_at' => now()
                ]);
            }

            Log::info('Doctor updated: ' . $doctor->name . ' by admin');

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating doctor: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
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

            // ✅ Send notification to all admins
            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Doctor Deleted',
                    'message' => 'Dr. ' . $doctorName . ' (' . $doctorSpecialization . ') has been removed from the system',
                    'type' => 'doctor_deleted',
                    'data' => json_encode([
                        'icon' => 'fa-user-times',
                        'doctor_name' => $doctorName,
                        'doctor_specialization' => $doctorSpecialization,
                        'doctor_id' => $doctorId
                    ]),
                    'read_at' => null,
                    'created_at' => now()
                ]);
            }

            Log::info('Doctor deleted: ' . $doctorName . ' by admin');

            return response()->json([
                'success' => true,
                'message' => 'Doctor "' . $doctorName . '" deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting doctor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting doctor: ' . $e->getMessage()
            ], 500);
        }
    }
}