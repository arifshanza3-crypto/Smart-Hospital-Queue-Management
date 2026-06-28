<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        
        // System Information
        $systemInfo = [
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'server_time' => now()->format('Y-m-d H:i:s'),
            'timezone' => config('app.timezone'),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
        ];
        
        return view('Pages.Admin.settings', compact('settings', 'systemInfo'));
    }
    
    public function update(Request $request)
    {
        $settings = $request->except('_token', '_method');
        
        try {
            foreach ($settings as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
            
            // Clear settings cache
            Cache::forget('app_settings');
            
            return redirect()->route('admin.settings')
                ->with('success', 'Settings updated successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error updating settings: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }
    
    // Backup function REMOVED as requested
    
    // Restore function also REMOVED (optional)
}