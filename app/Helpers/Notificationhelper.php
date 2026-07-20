<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

class NotificationHelper
{
    // ✅ Send to specific user
    public static function send($userId, $type, $title, $message, $tokenNumber = null, $data = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'token_number' => $tokenNumber,
            'data' => $data,
            'is_read' => false
        ]);
    }

    // ✅ Send to all users with specific role
    public static function sendToRole($role, $type, $title, $message, $tokenNumber = null, $data = null)
    {
        $users = User::where('role', $role)->where('status', 'approved')->get();
        
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'token_number' => $tokenNumber,
                'data' => $data,
                'is_read' => false
            ]);
        }
    }

    // ✅ Send to all staff
    public static function sendToStaff($title, $message, $tokenNumber = null, $data = null)
    {
        self::sendToRole('staff', 'staff_notification', $title, $message, $tokenNumber, $data);
    }

    // ✅ Send to all admins
    public static function sendToAdmin($title, $message, $tokenNumber = null, $data = null)
    {
        self::sendToRole('admin', 'admin_notification', $title, $message, $tokenNumber, $data);
    }

    // ✅ Send to patient
    public static function sendToPatient($userId, $title, $message, $tokenNumber = null, $data = null)
    {
        return self::send($userId, 'patient_notification', $title, $message, $tokenNumber, $data);
    }

    // ✅ Get user notifications
    public static function getUserNotifications($userId, $limit = 50)
    {
        return Notification::where('user_id', $userId)
                           ->orderBy('created_at', 'desc')
                           ->limit($limit)
                           ->get();
    }

    // ✅ Get unread count
    public static function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
                           ->where('is_read', false)
                           ->count();
    }

    // ✅ Mark all as read
    public static function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
                           ->where('is_read', false)
                           ->update(['is_read' => true]);
    }
}