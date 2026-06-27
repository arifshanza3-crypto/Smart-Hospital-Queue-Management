<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display user management page
     */
    public function index()
    {
        try {
            $users = User::orderBy('name')->get();
            
            // Statistics
            $total = $users->count();
            $active = $users->where('status', 'active')->count();
            $inactive = $users->where('status', 'inactive')->count();
            $admins = $users->where('role', 'admin')->count();
            $staff = $users->where('role', 'staff')->count();
            $regular = $users->where('role', 'user')->count();
            
            return view('admin.user_management', compact(
                'users', 'total', 'active', 'inactive', 
                'admins', 'staff', 'regular'
            ));
        } catch (\Exception $e) {
            Log::error('Error loading users: ' . $e->getMessage());
            return view('admin.user_management', [
                'users' => collect([]),
                'total' => 0, 'active' => 0, 'inactive' => 0,
                'admins' => 0, 'staff' => 0, 'regular' => 0
            ])->with('error', 'Unable to load users');
        }
    }

    /**
     * Show create user form
     */
    public function create()
    {
        return view('admin.add_user');
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,staff,user',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('profile_image')) {
                $imagePath = $request->file('profile_image')->store('users', 'public');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'phone' => $request->phone,
                'address' => $request->address,
                'profile_image' => $imagePath,
                'status' => $request->status
            ]);

            Log::info('User created: ' . $user->email);
            
            return redirect()->route('admin.users.index')
                ->with('success', 'User "' . $user->name . '" created successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show edit user form
     */
    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('admin.edit_user', compact('user'));
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'User not found!');
        }
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,staff,user',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('profile_image')) {
                if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }
                $imagePath = $request->file('profile_image')->store('users', 'public');
                $user->profile_image = $imagePath;
            }

            // Update password if provided
            if ($request->filled('password')) {
                $request->validate(['password' => 'min:6|confirmed']);
                $user->password = Hash::make($request->password);
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->status = $request->status;
            $user->save();

            Log::info('User updated: ' . $user->email);
            
            return redirect()->route('admin.users.index')
                ->with('success', 'User "' . $user->name . '" updated successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $userName = $user->name;
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'User "' . $userName . '" deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user!'
            ], 500);
        }
    }

    /**
     * Update user status
     */
    public function updateStatus($id, $status)
    {
        try {
            $user = User::findOrFail($id);
            $user->status = $status;
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status!'
            ], 500);
        }
    }

    /**
     * Search users
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $users = User::where('name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->orWhere('phone', 'LIKE', "%{$query}%")
                ->orderBy('name')
                ->get();
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'data' => $users]);
            }
            
            return view('admin.user_management', compact('users'));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Search failed'], 500);
        }
    }
}