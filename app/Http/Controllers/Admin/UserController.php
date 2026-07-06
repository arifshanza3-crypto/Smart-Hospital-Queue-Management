<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        
        $total = $users->count();
        $active = $users->where('status', 'active')->count();
        $inactive = $users->where('status', 'inactive')->count();
        $admins = $users->where('role', 'admin')->count();
        $staff = $users->where('role', 'staff')->count();
        $regular = $users->where('role', 'user')->count();
        
        return view('Pages.Admin.user_management', compact(
            'users', 'total', 'active', 'inactive', 'admins', 'staff', 'regular'
        ));
    }

    public function create()
    {
        return view('Pages.Admin.add_user');  // ✅ FIXED PATH
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,staff,user',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => $request->status
            ]);

            Log::info('User created: ' . $request->email);

            return redirect()->route('admin.users.index')
                ->with('success', 'User "' . $request->name . '" created successfully!');

        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('Pages.Admin.edit_user', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,staff,user',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->role = $request->role;
            $user->status = $request->status;
            
            if ($request->filled('password')) {
                $request->validate(['password' => 'min:6|confirmed']);
                $user->password = Hash::make($request->password);
            }
            
            $user->save();

            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $user = User::findOrFail($id);
            $user->status = $status;
            $user->save();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}