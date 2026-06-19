<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // General Settings
            ['key' => 'hospital_name', 'value' => 'Smart Queue Hospital', 'type' => 'text', 'group' => 'general', 'description' => 'Hospital Name'],
            ['key' => 'hospital_logo', 'value' => '', 'type' => 'image', 'group' => 'general', 'description' => 'Hospital Logo'],
            ['key' => 'contact_email', 'value' => 'info@smarthospital.com', 'type' => 'email', 'group' => 'general', 'description' => 'Contact Email'],
            ['key' => 'contact_phone', 'value' => '+92 123 4567890', 'type' => 'text', 'group' => 'general', 'description' => 'Contact Phone'],
            ['key' => 'contact_phone_2', 'value' => '+92 123 4567891', 'type' => 'text', 'group' => 'general', 'description' => 'Alternate Phone'],
            ['key' => 'address', 'value' => '123 Healthcare Street, Medical District, City', 'type' => 'textarea', 'group' => 'general', 'description' => 'Hospital Address'],
            ['key' => 'working_hours', 'value' => 'Monday - Saturday: 9:00 AM - 9:00 PM', 'type' => 'text', 'group' => 'general', 'description' => 'Working Hours'],
            ['key' => 'emergency_hours', 'value' => '24/7 Emergency Service Available', 'type' => 'text', 'group' => 'general', 'description' => 'Emergency Hours'],
            
            // Queue Settings
            ['key' => 'token_prefix', 'value' => 'T', 'type' => 'text', 'group' => 'queue', 'description' => 'Token Number Prefix'],
            ['key' => 'token_start_number', 'value' => '1', 'type' => 'number', 'group' => 'queue', 'description' => 'Token Starting Number'],
            ['key' => 'default_waiting_time', 'value' => '15', 'type' => 'number', 'group' => 'queue', 'description' => 'Default Waiting Time (minutes)'],
            ['key' => 'max_tokens_per_day', 'value' => '200', 'type' => 'number', 'group' => 'queue', 'description' => 'Maximum Tokens Per Day'],
            ['key' => 'enable_notifications', 'value' => '1', 'type' => 'checkbox', 'group' => 'queue', 'description' => 'Enable SMS/Email Notifications'],
            ['key' => 'notification_sound', 'value' => '1', 'type' => 'checkbox', 'group' => 'queue', 'description' => 'Enable Notification Sound'],
            ['key' => 'auto_refresh_interval', 'value' => '30', 'type' => 'number', 'group' => 'queue', 'description' => 'Auto Refresh Interval (seconds)'],
            ['key' => 'display_board_refresh', 'value' => '10', 'type' => 'number', 'group' => 'queue', 'description' => 'Display Board Refresh Rate (seconds)'],
            
            // Appearance Settings
            ['key' => 'primary_color', 'value' => '#00d4ff', 'type' => 'color', 'group' => 'appearance', 'description' => 'Primary Theme Color'],
            ['key' => 'secondary_color', 'value' => '#0b2e33', 'type' => 'color', 'group' => 'appearance', 'description' => 'Secondary Theme Color'],
            ['key' => 'accent_color', 'value' => '#ff6b35', 'type' => 'color', 'group' => 'appearance', 'description' => 'Accent Color'],
            ['key' => 'items_per_page', 'value' => '10', 'type' => 'select', 'group' => 'appearance', 'description' => 'Items Per Page'],
            ['key' => 'date_format', 'value' => 'Y-m-d', 'type' => 'select', 'group' => 'appearance', 'description' => 'Date Format'],
            ['key' => 'time_format', 'value' => 'H:i', 'type' => 'select', 'group' => 'appearance', 'description' => 'Time Format'],
            
            // Email Settings
            ['key' => 'mail_driver', 'value' => 'smtp', 'type' => 'select', 'group' => 'email', 'description' => 'Mail Driver'],
            ['key' => 'mail_host', 'value' => 'smtp.gmail.com', 'type' => 'text', 'group' => 'email', 'description' => 'SMTP Host'],
            ['key' => 'mail_port', 'value' => '587', 'type' => 'number', 'group' => 'email', 'description' => 'SMTP Port'],
            ['key' => 'mail_username', 'value' => '', 'type' => 'text', 'group' => 'email', 'description' => 'SMTP Username'],
            ['key' => 'mail_password', 'value' => '', 'type' => 'password', 'group' => 'email', 'description' => 'SMTP Password'],
            ['key' => 'mail_encryption', 'value' => 'tls', 'type' => 'select', 'group' => 'email', 'description' => 'Mail Encryption'],
            
            // System Settings
            ['key' => 'app_name', 'value' => 'Smart Queue Management', 'type' => 'text', 'group' => 'system', 'description' => 'Application Name'],
            ['key' => 'app_debug', 'value' => 'false', 'type' => 'checkbox', 'group' => 'system', 'description' => 'Debug Mode'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'checkbox', 'group' => 'system', 'description' => 'Maintenance Mode'],
            ['key' => 'maintenance_message', 'value' => 'We are currently under maintenance. Please check back later.', 'type' => 'textarea', 'group' => 'system', 'description' => 'Maintenance Message'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
        
        $this->command->info('Settings seeded successfully!');
    }
}