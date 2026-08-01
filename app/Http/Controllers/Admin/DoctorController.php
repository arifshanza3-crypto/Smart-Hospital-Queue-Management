<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Traits\NotificationTrait;

class DoctorController extends Controller
{
    use NotificationTrait;

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

            // ✅ Send notification to all admins
            $this->notifyAdmin(
                'Doctor Added',
                'Dr. ' . $doctor->name . ' has been added as a ' . $doctor->specialization,
                'doctor_added',
                [
                    'doctor_id' => $doctor->id,
                    'doctor_name' => $doctor->name,
                    'url' => route('admin.doctors.edit', $doctor->id)
                ]
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
            
            // ✅ Store old data for notification
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
            $this->notifyAdmin(
                'Doctor Updated',
                'Dr. ' . $doctor->name . ' (Specialization: ' . $doctor->specialization . ') has been updated',
                'doctor_updated',
                [
                    'doctor_id' => $doctor->id,
                    'doctor_name' => $doctor->name,
                    'old_name' => $oldName,
                    'old_specialization' => $oldSpecialization,
                    'url' => route('admin.doctors.edit', $doctor->id)
                ]
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
            $doctor->delete();

            // ✅ Send notification to all admins
            $this->notifyAdmin(
                'Doctor Deleted',
                'Dr. ' . $doctorName . ' (' . $doctorSpecialization . ') has been removed from the system',
                'doctor_deleted',
                [
                    'doctor_name' => $doctorName,
                    'doctor_specialization' => $doctorSpecialization
                ]
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}