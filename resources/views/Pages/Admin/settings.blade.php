@extends('Layout.admin-layout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    .settings-container {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .settings-header {
        margin-bottom: 30px;
    }
    
    .settings-header h1 {
        color: #0b2e33;
        margin-bottom: 5px;
        font-size: 28px;
    }
    
    .settings-header p {
        color: #666;
    }
    
    /* Mobile Style Settings Cards */
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 20px;
    }
    
    .settings-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 16px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .settings-card:hover {
        box-shadow: 0 4px 24px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .card-header {
        padding: 18px 24px;
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .card-header .icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #00d4ff, #0b2e33);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }
    
    .card-header h3 {
        color: #0b2e33;
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }
    
    .card-header .badge {
        margin-left: auto;
        background: #00d4ff;
        color: white;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 11px;
    }
    
    .card-body {
        padding: 20px 24px;
    }
    
    /* Setting Item - Mobile Style */
    .setting-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px solid #f5f5f5;
        transition: background 0.2s ease;
        cursor: pointer;
    }
    
    .setting-item:last-child {
        border-bottom: none;
    }
    
    .setting-item:hover {
        background: #fafafa;
        margin: 0 -24px;
        padding: 14px 24px;
    }
    
    .setting-info {
        display: flex;
        align-items: center;
        gap: 14px;
        flex: 1;
    }
    
    .setting-info .icon {
        width: 36px;
        height: 36px;
        background: #f0f7ff;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #00d4ff;
        font-size: 16px;
        flex-shrink: 0;
    }
    
    .setting-info .label {
        font-size: 14px;
        font-weight: 500;
        color: #0b2e33;
    }
    
    .setting-info .description {
        font-size: 12px;
        color: #999;
        margin-top: 2px;
    }
    
    /* Toggle Switch - Mobile Style */
    .toggle {
        position: relative;
        width: 48px;
        height: 28px;
        flex-shrink: 0;
    }
    
    .toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .3s;
        border-radius: 28px;
    }
    
    .toggle .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .toggle input:checked + .slider {
        background: linear-gradient(135deg, #00d4ff, #0b2e33);
    }
    
    .toggle input:checked + .slider:before {
        transform: translateX(20px);
    }
    
    /* Input Fields - Mobile Style */
    .setting-input {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e8e8e8;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #fafafa;
    }
    
    .setting-input:focus {
        outline: none;
        border-color: #00d4ff;
        background: white;
        box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
    }
    
    .setting-select {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e8e8e8;
        border-radius: 10px;
        font-size: 14px;
        background: #fafafa;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
    }
    
    .setting-select:focus {
        outline: none;
        border-color: #00d4ff;
        background: white;
        box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
    }
    
    .setting-color {
        width: 100%;
        height: 44px;
        padding: 4px;
        border: 2px solid #e8e8e8;
        border-radius: 10px;
        cursor: pointer;
    }
    
    .setting-color:focus {
        outline: none;
        border-color: #00d4ff;
    }
    
    /* Save Button - Mobile Style */
    .save-btn {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #00d4ff, #0b2e33);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .save-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,212,255,0.3);
    }
    
    .save-btn i {
        font-size: 18px;
    }
    
    /* Alert Styles */
    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border-left: 4px solid #28a745;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    /* System Info - Mobile Style */
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-item .label {
        color: #666;
        font-size: 14px;
    }
    
    .info-item .value {
        color: #0b2e33;
        font-weight: 500;
        font-size: 14px;
    }
    
    .info-item .value .badge {
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 12px;
    }
    
    .badge-success {
        background: #d4edda;
        color: #155724;
    }
    
    .badge-danger {
        background: #f8d7da;
        color: #721c24;
    }
    
    .badge-info {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }
        
        .settings-container {
            padding: 10px;
        }
        
        .card-body {
            padding: 16px;
        }
        
        .setting-item:hover {
            margin: 0 -16px;
            padding: 14px 16px;
        }
    }
</style>

@php
    use App\Models\Setting;
    
    $systemInfo = [
        'php_version' => phpversion(),
        'laravel_version' => app()->version(),
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        'server_time' => now()->format('Y-m-d H:i:s'),
        'timezone' => config('app.timezone'),
    ];
@endphp

<div class="settings-container">
    <!-- Header -->
    <div class="settings-header">
        <h1><i class="fas fa-sliders-h" style="color: #00d4ff;"></i> Settings</h1>
        <p>Configure your hospital management system</p>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')
        
        <div class="settings-grid">
            
            <!-- General Settings Card -->
            <div class="settings-card">
                <div class="card-header">
                    <div class="icon"><i class="fas fa-building"></i></div>
                    <h3>General Settings</h3>
                    <span class="badge">Hospital</span>
                </div>
                <div class="card-body">
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-hospital"></i></div>
                            <div>
                                <div class="label">Hospital Name</div>
                                <div class="description">Your hospital name</div>
                            </div>
                        </div>
                        <input type="text" name="hospital_name" class="setting-input" style="max-width: 160px;" 
                               value="{{ Setting::get('hospital_name', 'Smart Queue Hospital') }}">
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-envelope"></i></div>
                            <div>
                                <div class="label">Email Address</div>
                                <div class="description">Contact email</div>
                            </div>
                        </div>
                        <input type="email" name="contact_email" class="setting-input" style="max-width: 160px;" 
                               value="{{ Setting::get('contact_email', 'info@smarthospital.com') }}">
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-phone"></i></div>
                            <div>
                                <div class="label">Phone Number</div>
                                <div class="description">Primary contact number</div>
                            </div>
                        </div>
                        <input type="text" name="contact_phone" class="setting-input" style="max-width: 160px;" 
                               value="{{ Setting::get('contact_phone', '+92 123 4567890') }}">
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <div class="label">Address</div>
                                <div class="description">Hospital location</div>
                            </div>
                        </div>
                        <input type="text" name="address" class="setting-input" style="max-width: 160px;" 
                               value="{{ Setting::get('address', '123 Healthcare Street') }}">
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-clock"></i></div>
                            <div>
                                <div class="label">Working Hours</div>
                                <div class="description">Hospital operating hours</div>
                            </div>
                        </div>
                        <input type="text" name="working_hours" class="setting-input" style="max-width: 160px;" 
                               value="{{ Setting::get('working_hours', 'Mon-Sat: 9:00 AM - 9:00 PM') }}">
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-ambulance"></i></div>
                            <div>
                                <div class="label">Emergency Hours</div>
                                <div class="description">24/7 emergency service</div>
                            </div>
                        </div>
                        <input type="text" name="emergency_hours" class="setting-input" style="max-width: 160px;" 
                               value="{{ Setting::get('emergency_hours', '24/7 Emergency Service') }}">
                    </div>
                </div>
            </div>
            
            <!-- Queue Settings Card -->
            <div class="settings-card">
                <div class="card-header">
                    <div class="icon"><i class="fas fa-ticket-alt"></i></div>
                    <h3>Queue Settings</h3>
                    <span class="badge">Smart Queue</span>
                </div>
                <div class="card-body">
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-tag"></i></div>
                            <div>
                                <div class="label">Token Prefix</div>
                                <div class="description">Token number prefix</div>
                            </div>
                        </div>
                        <input type="text" name="token_prefix" class="setting-input" style="max-width: 100px;" 
                               value="{{ Setting::get('token_prefix', 'T') }}">
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-sort-numeric-up"></i></div>
                            <div>
                                <div class="label">Start Number</div>
                                <div class="description">Starting token number</div>
                            </div>
                        </div>
                        <input type="number" name="token_start_number" class="setting-input" style="max-width: 100px;" 
                               value="{{ Setting::get('token_start_number', '1') }}">
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                            <div>
                                <div class="label">Waiting Time</div>
                                <div class="description">Default waiting time (min)</div>
                            </div>
                        </div>
                        <input type="number" name="default_waiting_time" class="setting-input" style="max-width: 100px;" 
                               value="{{ Setting::get('default_waiting_time', '15') }}">
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-chart-line"></i></div>
                            <div>
                                <div class="label">Max Tokens/Day</div>
                                <div class="description">Maximum tokens per day</div>
                            </div>
                        </div>
                        <input type="number" name="max_tokens_per_day" class="setting-input" style="max-width: 100px;" 
                               value="{{ Setting::get('max_tokens_per_day', '200') }}">
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-bell"></i></div>
                            <div>
                                <div class="label">Notifications</div>
                                <div class="description">Send SMS/Email alerts</div>
                            </div>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" name="enable_notifications" value="1" 
                                   {{ Setting::get('enable_notifications', '1') == '1' ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-volume-up"></i></div>
                            <div>
                                <div class="label">Sound Alerts</div>
                                <div class="description">Play sound on new tokens</div>
                            </div>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" name="notification_sound" value="1" 
                                   {{ Setting::get('notification_sound', '1') == '1' ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-sync-alt"></i></div>
                            <div>
                                <div class="label">Auto Refresh</div>
                                <div class="description">Refresh interval (seconds)</div>
                            </div>
                        </div>
                        <input type="number" name="auto_refresh_interval" class="setting-input" style="max-width: 100px;" 
                               value="{{ Setting::get('auto_refresh_interval', '30') }}">
                    </div>
                </div>
            </div>
            
            <!-- Appearance Settings Card -->
            <div class="settings-card">
                <div class="card-header">
                    <div class="icon"><i class="fas fa-palette"></i></div>
                    <h3>Appearance</h3>
                    <span class="badge">Theme</span>
                </div>
                <div class="card-body">
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-fill-drip"></i></div>
                            <div>
                                <div class="label">Primary Color</div>
                                <div class="description">Main theme color</div>
                            </div>
                        </div>
                        <input type="color" name="primary_color" class="setting-color" style="max-width: 60px;" 
                               value="{{ Setting::get('primary_color', '#00d4ff') }}">
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-fill-drip"></i></div>
                            <div>
                                <div class="label">Secondary Color</div>
                                <div class="description">Secondary theme color</div>
                            </div>
                        </div>
                        <input type="color" name="secondary_color" class="setting-color" style="max-width: 60px;" 
                               value="{{ Setting::get('secondary_color', '#0b2e33') }}">
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-fill-drip"></i></div>
                            <div>
                                <div class="label">Accent Color</div>
                                <div class="description">Accent theme color</div>
                            </div>
                        </div>
                        <input type="color" name="accent_color" class="setting-color" style="max-width: 60px;" 
                               value="{{ Setting::get('accent_color', '#ff6b35') }}">
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-border-all"></i></div>
                            <div>
                                <div class="label">Items Per Page</div>
                                <div class="description">Records per page</div>
                            </div>
                        </div>
                        <select name="items_per_page" class="setting-select" style="max-width: 120px;">
                            <option value="10" {{ Setting::get('items_per_page', '10') == '10' ? 'selected' : '' }}>10</option>
                            <option value="25" {{ Setting::get('items_per_page') == '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ Setting::get('items_per_page') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ Setting::get('items_per_page') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-calendar"></i></div>
                            <div>
                                <div class="label">Date Format</div>
                                <div class="description">Display date format</div>
                            </div>
                        </div>
                        <select name="date_format" class="setting-select" style="max-width: 140px;">
                            <option value="Y-m-d" {{ Setting::get('date_format', 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>2024-01-15</option>
                            <option value="d/m/Y" {{ Setting::get('date_format') == 'd/m/Y' ? 'selected' : '' }}>15/01/2024</option>
                            <option value="m/d/Y" {{ Setting::get('date_format') == 'm/d/Y' ? 'selected' : '' }}>01/15/2024</option>
                            <option value="F j, Y" {{ Setting::get('date_format') == 'F j, Y' ? 'selected' : '' }}>Jan 15, 2024</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Email Settings Card -->
            <div class="settings-card">
                <div class="card-header">
                    <div class="icon"><i class="fas fa-envelope"></i></div>
                    <h3>Email Settings</h3>
                    <span class="badge">SMTP</span>
                </div>
                <div class="card-body">
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-server"></i></div>
                            <div>
                                <div class="label">Mail Driver</div>
                                <div class="description">Email driver</div>
                            </div>
                        </div>
                        <select name="mail_driver" class="setting-select" style="max-width: 140px;">
                            <option value="smtp" {{ Setting::get('mail_driver', 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="sendmail" {{ Setting::get('mail_driver') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            <option value="mailgun" {{ Setting::get('mail_driver') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                        </select>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-network-wired"></i></div>
                            <div>
                                <div class="label">SMTP Host</div>
                                <div class="description">SMTP server</div>
                            </div>
                        </div>
                        <input type="text" name="mail_host" class="setting-input" style="max-width: 160px;" 
                               value="{{ Setting::get('mail_host', 'smtp.gmail.com') }}">
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-plug"></i></div>
                            <div>
                                <div class="label">SMTP Port</div>
                                <div class="description">SMTP port number</div>
                            </div>
                        </div>
                        <input type="number" name="mail_port" class="setting-input" style="max-width: 100px;" 
                               value="{{ Setting::get('mail_port', '587') }}">
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-lock"></i></div>
                            <div>
                                <div class="label">Encryption</div>
                                <div class="description">Mail encryption</div>
                            </div>
                        </div>
                        <select name="mail_encryption" class="setting-select" style="max-width: 120px;">
                            <option value="tls" {{ Setting::get('mail_encryption', 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ Setting::get('mail_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-user"></i></div>
                            <div>
                                <div class="label">SMTP Username</div>
                                <div class="description">Email username</div>
                            </div>
                        </div>
                        <input type="text" name="mail_username" class="setting-input" style="max-width: 160px;" 
                               value="{{ Setting::get('mail_username', '') }}">
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-key"></i></div>
                            <div>
                                <div class="label">SMTP Password</div>
                                <div class="description">Email password</div>
                            </div>
                        </div>
                        <input type="password" name="mail_password" class="setting-input" style="max-width: 160px;" 
                               placeholder="********" value="{{ Setting::get('mail_password', '') }}">
                    </div>
                </div>
            </div>
            
            <!-- System Settings Card -->
            <div class="settings-card">
                <div class="card-header">
                    <div class="icon"><i class="fas fa-server"></i></div>
                    <h3>System Settings</h3>
                    <span class="badge">Advanced</span>
                </div>
                <div class="card-body">
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-tag"></i></div>
                            <div>
                                <div class="label">App Name</div>
                                <div class="description">Application name</div>
                            </div>
                        </div>
                        <input type="text" name="app_name" class="setting-input" style="max-width: 160px;" 
                               value="{{ Setting::get('app_name', 'Smart Queue Management') }}">
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-bug"></i></div>
                            <div>
                                <div class="label">Debug Mode</div>
                                <div class="description">Enable debug mode</div>
                            </div>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" name="app_debug" value="true" 
                                   {{ Setting::get('app_debug', 'false') == 'true' ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-tools"></i></div>
                            <div>
                                <div class="label">Maintenance Mode</div>
                                <div class="description">Put site in maintenance</div>
                            </div>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" name="maintenance_mode" value="1" 
                                   {{ Setting::get('maintenance_mode', '0') == '1' ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="setting-item" style="flex-direction: column; align-items: stretch; gap: 8px;">
                        <div class="setting-info">
                            <div class="icon"><i class="fas fa-comment"></i></div>
                            <div>
                                <div class="label">Maintenance Message</div>
                                <div class="description">Message during maintenance</div>
                            </div>
                        </div>
                        <textarea name="maintenance_message" class="setting-input" rows="2" style="width: 100%;">{{ Setting::get('maintenance_message', 'We are currently under maintenance. Please check back later.') }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- System Information Card -->
            <div class="settings-card">
                <div class="card-header">
                    <div class="icon"><i class="fas fa-info-circle"></i></div>
                    <h3>System Info</h3>
                    <span class="badge">Version</span>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <span class="label"><i class="fab fa-php"></i> PHP Version</span>
                        <span class="value">{{ $systemInfo['php_version'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label"><i class="fab fa-laravel"></i> Laravel Version</span>
                        <span class="value">{{ $systemInfo['laravel_version'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label"><i class="fas fa-server"></i> Server Software</span>
                        <span class="value">{{ $systemInfo['server_software'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label"><i class="fas fa-clock"></i> Server Time</span>
                        <span class="value">{{ $systemInfo['server_time'] }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label"><i class="fas fa-globe"></i> Timezone</span>
                        <span class="value">{{ $systemInfo['timezone'] }}</span>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Save Button -->
        <button type="submit" class="save-btn" style="margin-top: 30px;">
            <i class="fas fa-save"></i> Save All Settings
        </button>
    </form>
</div>
@endsection