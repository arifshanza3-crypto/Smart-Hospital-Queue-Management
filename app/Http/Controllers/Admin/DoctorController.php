<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'email' => 'required|email|unique:doctors,email',
            'phone' => 'required|string|max:20',
            'status' => 'required|in:active,inactive,on_duty'
        ]);

        try {
            // Simplified insert using Eloquent
            $doctor = new Doctor();
            $doctor->name = $request->name;
            $doctor->specialization = $request->specialization;
            $doctor->qualification = $request->qualification;
            $doctor->email = $request->email;
            $doctor->phone = $request->phone;
            $doctor->status = $request->status;
            $doctor->save();
            
            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor "' . $request->name . '" added successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            return view('Layout.edit-doctor', compact('doctor'));
        } catch (\Exception $e) {
            return redirect()->route('admin.doctors.index')
                ->with('error', 'Doctor not found!');
        }
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
            $doctor->name = $request->name;
            $doctor->specialization = $request->specialization;
            $doctor->qualification = $request->qualification;
            $doctor->email = $request->email;
            $doctor->phone = $request->phone;
            $doctor->status = $request->status;
            $doctor->save();
            
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
            $doctor->delete();
            
            return response()->json([
                'success' => true, 
                'message' => 'Doctor deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error deleting doctor!'
            ], 500);
        }
    }
}