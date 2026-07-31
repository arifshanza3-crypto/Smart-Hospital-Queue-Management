<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;

class ProfileController extends Controller
{
    // ✅ Show Profile Page (For All Roles - Admin, Staff, User)
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login first.');
        }
        
        $user = Auth::user();
        $profile = $user->profile;
        
        if (!$profile) {
            $profile = Profile::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
                'join_date' => now(),
                'status' => 'active'
            ]);
            $user->refresh();
        }

        $roleData = $this->getRoleSpecificData($user);

        return view('Pages.profile', compact('user', 'profile', 'roleData'));
    }

    // ✅ Update Profile
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'hostel' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            if ($request->hasFile('avatar')) {
                if ($profile->avatar && Storage::disk('public')->exists($profile->avatar)) {
                    Storage::disk('public')->delete($profile->avatar);
                }
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $profile->avatar = $avatarPath;
            }

            $profile->full_name = $request->full_name;
            $profile->phone = $request->phone;
            $profile->address = $request->address;
            $profile->city = $request->city;
            $profile->country = $request->country;
            $profile->hostel = $request->hostel;
            $profile->location = $request->location;
            $profile->bio = $request->bio;
            $profile->save();

            return redirect()->back()->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ✅ Update Password
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect!');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    // ✅ Get Role Specific Data
    private function getRoleSpecificData($user)
    {
        $data = [
            'admin' => [
                'title' => 'Admin Panel Access',
                'icon' => 'fas fa-user-shield',
                'permissions' => [
                    'Full Access', 'Manage Users', 'Manage Doctors', 
                    'Manage Services', 'View Reports', 'System Settings'
                ]
            ],
            'staff' => [
                'title' => 'Staff Dashboard',
                'icon' => 'fas fa-user-tie',
                'permissions' => [
                    'Queue Management', 'Patient Management', 'View Reports', 
                    'Token Generation', 'Department Dashboard'
                ]
            ],
            'user' => [
                'title' => 'Patient Portal',
                'icon' => 'fas fa-user',
                'permissions' => [
                    'Book Appointment', 'View Token Status', 'Track Queue', 
                    'View Profile', 'Update Profile'
                ]
            ]
        ];

        return $data[$user->role] ?? $data['user'];
    }
}