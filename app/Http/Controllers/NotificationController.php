<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login');
            }
            
            // ✅ Get notifications for this user
            $notifications = Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            $unreadCount = $notifications->whereNull('read_at')->count();

            // ✅ JSON response for AJAX calls
            if (request()->wantsJson()) {
                $formatted = $notifications->map(function($notification) {
                    $data = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;
                    
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title ?? 'Notification',
                        'message' => $notification->message ?? '',
                        'type' => $notification->type ?? 'general',
                        'data' => $data ?? [],
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
            }

            // ✅ Use Notification view (root folder)
            return view('Notification', [
                'notifications' => $notifications,
                'unreadCount' => $unreadCount,
                'userRole' => $user->role ?? 'user'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Notification page error: ' . $e->getMessage());
            
            return view('Notification', [
                'notifications' => collect([]),
                'unreadCount' => 0,
                'userRole' => 'user'
            ]);
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

            Log::info('Marked all notifications as read for user: ' . $user->id . ', Count: ' . $updated);

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