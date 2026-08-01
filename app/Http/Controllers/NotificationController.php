<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display notifications page with role-based filtering
     */
    public function index()
    {
        $user = Auth::user();
        
        // ✅ Get notifications based on user role
        $notifications = $this->getNotificationsByRole($user);
        
        // ✅ Format notifications for JSON response
        $formatted = $notifications->map(function($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->data['title'] ?? 'Notification',
                'message' => $notification->data['message'] ?? '',
                'type' => $notification->type ?? 'general',
                'token_number' => $notification->data['token_number'] ?? null,
                'is_read' => $notification->read_at ? true : false,
                'created_at' => $notification->created_at->toISOString(),
                'data' => $notification->data
            ];
        });

        $unreadCount = $notifications->whereNull('read_at')->count();

        // ✅ If request expects JSON (AJAX call)
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'notifications' => $formatted,
                'unread_count' => $unreadCount,
                'total_count' => $notifications->count()
            ]);
        }

        // ✅ Auto-detect view path
        $viewPath = $this->getViewPath();
        
        return view($viewPath, compact('notifications', 'unreadCount'));
    }

    /**
     * Auto-detect notification view path
     */
    private function getViewPath()
    {
        // ✅ Check in root directory first
        if (view()->exists('Notification')) {
            return 'Notification';
        }
        
        // ✅ Check in Pages/Notifications folder
        if (view()->exists('Pages.Notifications.index')) {
            return 'Pages.Notifications.index';
        }
        
        // ✅ Check in Pages folder
        if (view()->exists('Pages.Notification')) {
            return 'Pages.Notification';
        }
        
        // ✅ If no view found, create fallback
        return 'Notification';
    }

    /**
     * Get notifications based on user role
     */
    private function getNotificationsByRole($user)
    {
        $role = $user->role ?? 'user';

        switch ($role) {
            case 'admin':
                // ✅ Admin sees all notifications
                return Notification::where('user_id', $user->id)
                    ->orWhereNull('user_id')
                    ->orderBy('created_at', 'desc')
                    ->get();

            case 'staff':
                // ✅ Staff sees their notifications + general
                return Notification::where('user_id', $user->id)
                    ->orWhereNull('user_id')
                    ->orderBy('created_at', 'desc')
                    ->get();

            default:
                // ✅ Patient/User sees only their own
                return Notification::where('user_id', $user->id)
                    ->orWhereNull('user_id')
                    ->orderBy('created_at', 'desc')
                    ->get();
        }
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereNull('user_id');
            })
            ->firstOrFail();
        
        $notification->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        $notifications = Notification::where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereNull('user_id');
            })
            ->whereNull('read_at')
            ->get();
        
        foreach ($notifications as $notification) {
            $notification->update(['read_at' => now()]);
        }
        
        return response()->json(['success' => true]);
    }

    /**
     * Get unread count
     */
    public function unreadCount()
    {
        $user = Auth::user();
        
        $count = Notification::where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereNull('user_id');
            })
            ->whereNull('read_at')
            ->count();
        
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereNull('user_id');
            })
            ->firstOrFail();
        
        $notification->delete();
        
        return response()->json(['success' => true]);
    }
}