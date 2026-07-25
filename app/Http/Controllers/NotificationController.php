<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    // ✅ Get all notifications
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
                                     ->orderBy('created_at', 'desc')
                                     ->limit(50)
                                     ->get();

        $unreadCount = Notification::where('user_id', auth()->id())
                                   ->where('is_read', false)
                                   ->count();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    // ✅ Mark as read
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    // ✅ Mark all as read
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    // ✅ Get unread count
    public function unreadCount()
    {
        $count = Notification::where('user_id', auth()->id())
                             ->where('is_read', false)
                             ->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    // ✅ Delete notification
    public function destroy($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
    }
}