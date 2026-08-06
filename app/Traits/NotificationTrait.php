<?php

namespace App\Traits;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

trait NotificationTrait
{
    /**
     * Create a notification
     */
    public function createNotification($userId, $title, $message, $type, $data = [])
    {
        try {
            $notification = Notification::create([
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'data' => json_encode(array_merge($data, [
                    'icon' => $this->getNotificationIcon($type),
                    'url' => $data['url'] ?? '#'
                ])),
                'read_at' => null,
                'created_at' => now()
            ]);

            Log::info('Notification created: ' . $title . ' for user: ' . $userId);
            return $notification;

        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Send notification to admin
     */
    public function notifyAdmin($title, $message, $type, $data = [])
    {
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            $this->createNotification($admin->id, $title, $message, $type, $data);
        }
    }

    /**
     * Send notification to specific user
     */
    public function notifyUser($userId, $title, $message, $type, $data = [])
    {
        if ($userId) {
            $this->createNotification($userId, $title, $message, $type, $data);
        }
    }

    /**
     * Send notification to all staff
     */
    public function notifyStaff($title, $message, $type, $data = [])
    {
        $staff = User::where('role', 'staff')->get();
        
        foreach ($staff as $user) {
            $this->createNotification($user->id, $title, $message, $type, $data);
        }
    }

    /**
     * Send notification to all staff and admins
     */
    public function notifyAllStaffAndAdmins($title, $message, $type, $data = [])
    {
        $this->notifyAdmin($title, $message, $type, $data);
        $this->notifyStaff($title, $message, $type, $data);
    }

    /**
     * Send notification to a specific role
     */
    public function notifyRole($role, $title, $message, $type, $data = [])
    {
        $users = User::where('role', $role)->get();
        
        foreach ($users as $user) {
            $this->createNotification($user->id, $title, $message, $type, $data);
        }
    }

    /**
     * Get notification icon based on type
     */
    private function getNotificationIcon($type)
    {
        $icons = [
            // Doctor related
            'doctor_added' => 'fa-user-md',
            'doctor_updated' => 'fa-user-edit',
            'doctor_deleted' => 'fa-user-times',
            
            // Service related
            'service_added' => 'fa-concierge-bell',
            'service_updated' => 'fa-edit',
            'service_deleted' => 'fa-trash',
            
            // Staff related
            'staff_approved' => 'fa-user-check',
            'staff_rejected' => 'fa-user-times',
            'staff_registered' => 'fa-user-plus',
            
            // Token/Patient related
            'token_generated' => 'fa-ticket-alt',
            'token_called' => 'fa-phone',
            'token_arrived' => 'fa-check-circle',
            'token_completed' => 'fa-check-double',
            'token_cancelled' => 'fa-times-circle',
            'physical_patient_added' => 'fa-user-plus',
            'queue_update' => 'fa-clock',
            
            // System
            'system_alert' => 'fa-exclamation-triangle',
            'user_registered' => 'fa-user-plus',
            'profile_updated' => 'fa-user-edit',
            'password_changed' => 'fa-key',
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