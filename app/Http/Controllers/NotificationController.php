<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    // ✅ Show notification page (View)
    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login');
            }
            
            // ✅ Get notifications based on user role
            $notifications = $this->getNotificationsByRole($user);
            
            if ($notifications === null) {
                $notifications = collect([]);
            }
            
            $unreadCount = $notifications->whereNull('read_at')->count();
            $totalCount = $notifications->count();
            $readCount = $totalCount - $unreadCount;
            $userRole = $user->role ?? 'user';

            return view('Pages.Notification', compact(
                'notifications', 
                'unreadCount', 
                'totalCount', 
                'readCount', 
                'userRole'
            ));
            
        } catch (\Exception $e) {
            Log::error('Notification page error: ' . $e->getMessage());
            
            return view('Pages.Notification', [
                'notifications' => collect([]),
                'unreadCount' => 0,
                'totalCount' => 0,
                'readCount' => 0,
                'userRole' => 'user'
            ]);
        }
    }

    /**
     * ✅ Get notifications based on user role
     * - Admin: Doctors, Services, Users Management
     * - Staff: Token System (Queue)
     * - User: Own Token only
     */
    private function getNotificationsByRole($user)
    {
        $role = $user->role ?? 'user';

        switch ($role) {
            case 'admin':
                // ✅ Admin: Doctors, Services, Users, Staff Approval
                return Notification::where(function($query) use ($user) {
                        $query->where('user_id', $user->id)
                              ->orWhereNull('user_id');
                    })
                    ->whereIn('type', [
                        'doctor_added',
                        'doctor_updated',
                        'doctor_deleted',
                        'service_added',
                        'service_updated',
                        'service_deleted',
                        'staff_registered',
                        'staff_approved',
                        'staff_rejected',
                        'user_created',
                        'user_updated',
                        'user_deleted'
                    ])
                    ->orderBy('created_at', 'desc')
                    ->get();

            case 'staff':
                // ✅ Staff: Token System (Queue)
                return Notification::where(function($query) use ($user) {
                        $query->where('user_id', $user->id)
                              ->orWhereNull('user_id');
                    })
                    ->whereIn('type', [
                        'token_generated',
                        'token_called',
                        'token_arrived',
                        'token_completed',
                        'token_cancelled',
                        'physical_patient_added',
                        'patient_missed'
                    ])
                    ->orderBy('created_at', 'desc')
                    ->get();

            default:
                // ✅ User/Patient: Own Token only
                return Notification::where('user_id', $user->id)
                    ->whereIn('type', [
                        'token_generated',
                        'token_called',
                        'token_arrived',
                        'token_completed',
                        'token_cancelled'
                    ])
                    ->orderBy('created_at', 'desc')
                    ->get();
        }
    }

    // ✅ JSON response for AJAX calls
    public function getNotificationsJson()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $notifications = $this->getNotificationsByRole($user);
            $unreadCount = $notifications->whereNull('read_at')->count();

            $formatted = $notifications->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title ?? 'Notification',
                    'message' => $notification->message ?? '',
                    'type' => $notification->type ?? 'general',
                    'token_number' => $notification->token_number ?? null,
                    'is_read' => $notification->read_at ? true : false,
                    'created_at' => $notification->created_at->toISOString()
                ];
            });

            return response()->json([
                'success' => true,
                'notifications' => $formatted,
                'unread_count' => $unreadCount,
                'total_count' => $notifications->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Get notifications JSON error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead($id)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $notification = Notification::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();
            
            $notification->update(['read_at' => now()]);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Mark as read error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error marking as read'
            ], 500);
        }
    }

    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $updated = Notification::where('user_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'updated' => $updated
            ]);

        } catch (\Exception $e) {
            Log::error('Mark all as read error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error marking all as read'
            ], 500);
        }
    }

    public function unreadCount()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'count' => 0
                ], 401);
            }

            $count = Notification::where('user_id', $user->id)
                ->whereNull('read_at')
                ->count();
            
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
            
        } catch (\Exception $e) {
            Log::error('Unread count error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'count' => 0
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $notification = Notification::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();
            
            $notification->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Delete notification error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting notification'
            ], 500);
        }
    }
}