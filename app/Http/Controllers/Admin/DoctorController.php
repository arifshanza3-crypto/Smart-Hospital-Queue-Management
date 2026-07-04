<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = DB::table('doctors')->get();
        return view('Pages.Admin.Doctor_management', compact('doctors'));
    }

    public function create()
    {
        return view('Component.Admin.add_doctor');
    }

    public function store(Request $request)
    {
        // SIMPLEST POSSIBLE INSERT - NO VALIDATION
        try {
            $result = DB::table('doctors')->insert([
                'name' => $request->input('name'),
                'specialization' => $request->input('specialization'),
                'qualification' => $request->input('qualification'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'status' => $request->input('status', 'active'),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            if ($result) {
                return redirect()->route('admin.doctors.index')
                    ->with('success', 'Doctor added successfully!');
            } else {
                return redirect()->back()->with('error', 'Insert failed!');
            }
            
        } catch (\Exception $e) {
            // Show exact error
            return redirect()->back()->with('error', 'ERROR: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $doctor = DB::table('doctors')->where('id', $id)->first();
        return view('Layout.edit-doctor', compact('doctor'));
    }

    public function update(Request $request, $id)
    {
        DB::table('doctors')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'specialization' => $request->specialization,
                'qualification' => $request->qualification,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $request->status,
                'updated_at' => now()
            ]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor updated successfully!');
    }

    public function destroy($id)
    {
        DB::table('doctors')->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }
}