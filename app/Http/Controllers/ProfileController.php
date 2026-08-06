<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;

class ProfileController extends Controller
{
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
                'full_name' => $user->full_name ?? $user->name,
                'join_date' => now(),
                'status' => 'active'
            ]);
            $user->refresh();
        }

        $roleData = $this->getRoleSpecificData($user);
        $stats = $this->getUserStats($user);

        // ✅ FIXED: Put 'stats' in quotes, not $stats
        return view('Pages.profile', compact('user', 'profile', 'roleData', 'stats'));
    }

    public function edit()
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $user = Auth::user();
        $profile = $user->getProfile();
        $roleData = $this->getRoleSpecificData($user);

        return view('Pages.profile-edit', compact('user', 'profile', 'roleData'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            return redirect()->back()->with('error', 'Profile not found!');
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]*$/',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'hostel' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'employee_id' => 'nullable|string|max:50',
            'department' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

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
            
            if ($user->isStaff() || $user->isAdmin()) {
                $profile->employee_id = $request->employee_id;
                $profile->department = $request->department;
                $profile->designation = $request->designation;
            }
            
            $profile->save();

            if ($user->name !== $request->full_name) {
                $user->name = $request->full_name;
                $user->save();
            }

            return redirect()->route('profile.index')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect!');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    public function updateAvatar(Request $request)
    {
        try {
            $user = Auth::user();
            $profile = $user->profile;

            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profile not found!'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            if ($profile->avatar && Storage::disk('public')->exists($profile->avatar)) {
                Storage::disk('public')->delete($profile->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $profile->avatar = $avatarPath;
            $profile->save();

            return response()->json([
                'success' => true,
                'message' => 'Avatar updated successfully!',
                'avatar_url' => asset('storage/' . $avatarPath)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating avatar: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getUserStats($user)
    {
        $stats = [
            'appointments' => 0,
            'tokens' => 0,
            'completed' => 0,
            'pending' => 0,
            'patients' => 0,
            'staff' => 0
        ];

        try {
            if ($user->isAdmin()) {
                if (class_exists('App\Models\Appointment')) {
                    $stats['appointments'] = \App\Models\Appointment::count() ?? 0;
                }
                if (class_exists('App\Models\Token')) {
                    $stats['tokens'] = \App\Models\Token::count() ?? 0;
                    $stats['completed'] = \App\Models\Token::where('status', 'completed')->count() ?? 0;
                    $stats['pending'] = \App\Models\Token::where('status', 'pending')->count() ?? 0;
                }
                $stats['patients'] = User::where('role', User::ROLE_USER)->count() ?? 0;
                $stats['staff'] = User::where('role', User::ROLE_STAFF)->count() ?? 0;
                
            } elseif ($user->isStaff()) {
                if (class_exists('App\Models\Appointment')) {
                    $stats['appointments'] = \App\Models\Appointment::where('staff_id', $user->id)->count() ?? 0;
                }
                if (class_exists('App\Models\Token')) {
                    $stats['tokens'] = \App\Models\Token::where('staff_id', $user->id)->count() ?? 0;
                    $stats['completed'] = \App\Models\Token::where('staff_id', $user->id)->where('status', 'completed')->count() ?? 0;
                    $stats['pending'] = \App\Models\Token::where('staff_id', $user->id)->where('status', 'pending')->count() ?? 0;
                }
                
            } elseif ($user->isUser()) {
                if (class_exists('App\Models\Appointment')) {
                    $stats['appointments'] = \App\Models\Appointment::where('user_id', $user->id)->count() ?? 0;
                }
                if (class_exists('App\Models\Token')) {
                    $stats['tokens'] = \App\Models\Token::where('user_id', $user->id)->count() ?? 0;
                    $stats['completed'] = \App\Models\Token::where('user_id', $user->id)->where('status', 'completed')->count() ?? 0;
                    $stats['pending'] = \App\Models\Token::where('user_id', $user->id)->where('status', 'pending')->count() ?? 0;
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Error fetching user stats: ' . $e->getMessage());
        }

        return $stats;
    }

    private function getRoleSpecificData($user)
    {
        $data = [
            User::ROLE_ADMIN => [
                'title' => 'Administrator',
                'icon' => 'fa-user-shield',
                'color' => '#6366f1',
                'badge' => 'Admin',
                'badgeClass' => 'badge-admin',
                'description' => 'Full system access and management',
                'permissions' => [
                    'Full System Access',
                    'Manage Users',
                    'Manage Staff',
                    'Manage Doctors',
                    'Manage Services',
                    'View Reports',
                    'System Settings',
                    'User Management',
                    'Role Management'
                ]
            ],
            User::ROLE_STAFF => [
                'title' => 'Staff Member',
                'icon' => 'fa-user-tie',
                'color' => '#3b82f6',
                'badge' => 'Staff',
                'badgeClass' => 'badge-staff',
                'description' => 'Department management and patient care',
                'permissions' => [
                    'Queue Management',
                    'Patient Management',
                    'View Reports',
                    'Token Generation',
                    'Department Dashboard',
                    'Patient Records',
                    'Appointment Management'
                ]
            ],
            User::ROLE_USER => [
                'title' => 'Patient',
                'icon' => 'fa-user',
                'color' => '#10b981',
                'badge' => 'Patient',
                'badgeClass' => 'badge-user',
                'description' => 'Personal health management',
                'permissions' => [
                    'Book Appointment',
                    'View Token Status',
                    'Track Queue',
                    'View Profile',
                    'Update Profile',
                    'Medical History',
                    'Prescription View'
                ]
            ]
        ];

        return $data[$user->role] ?? $data[User::ROLE_USER];
    }
}