<?php

namespace App\Traits;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

trait NotificationTrait
{
    /**
     * Create a notification
     */
    public function createNotification($userId, $title, $message, $type, $data = [])
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => array_merge($data, [
                'icon' => $this->getNotificationIcon($type),
                'url' => $data['url'] ?? '#'
            ]),
            'created_at' => now()
        ]);
    }

    /**
     * Send notification to admin
     */
    public function notifyAdmin($title, $message, $type, $data = [])
    {
        $admins = \App\Models\User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            $this->createNotification($admin->id, $title, $message, $type, $data);
        }
    }

    /**
     * Send notification to specific user
     */
    public function notifyUser($userId, $title, $message, $type, $data = [])
    {
        $this->createNotification($userId, $title, $message, $type, $data);
    }

    /**
     * Send notification to all staff
     */
    public function notifyStaff($title, $message, $type, $data = [])
    {
        $staff = \App\Models\User::where('role', 'staff')->get();
        
        foreach ($staff as $user) {
            $this->createNotification($user->id, $title, $message, $type, $data);
        }
    }

    /**
     * Get notification icon based on type
     */
    private function getNotificationIcon($type)
    {
        $icons = [
            'doctor_added' => 'fa-user-md',
            'doctor_updated' => 'fa-user-edit',
            'doctor_deleted' => 'fa-user-times',
            'staff_approved' => 'fa-user-check',
            'staff_rejected' => 'fa-user-times',
            'staff_registered' => 'fa-user-plus',
            'token_generated' => 'fa-ticket-alt',
            'token_called' => 'fa-phone',
            'token_completed' => 'fa-check-double',
            'service_added' => 'fa-concierge-bell',
            'service_updated' => 'fa-edit',
            'service_deleted' => 'fa-trash',
            'system_alert' => 'fa-exclamation-triangle',
            'user_registered' => 'fa-user-plus',
            'profile_updated' => 'fa-user-edit',
            'password_changed' => 'fa-key',
            'queue_update' => 'fa-clock',
            'report_generated' => 'fa-file-alt',
            'settings_updated' => 'fa-cog',
            'appointment_booked' => 'fa-calendar-check',
            'appointment_cancelled' => 'fa-calendar-times',
            'payment_received' => 'fa-credit-card',
            'payment_failed' => 'fa-exclamation-circle',
        ];
        
        return $icons[$type] ?? 'fa-bell';
    }
}