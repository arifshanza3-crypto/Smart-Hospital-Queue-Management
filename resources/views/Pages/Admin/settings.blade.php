@extends('Layout.admin-layout')

@section('page-title', 'Settings')
@section('breadcrumb', 'General Settings')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* ============================================
       SETTINGS PAGE - LIGHT THEME
       ============================================ */
    
    :root {
        --bg-primary: #f8fafc;
        --bg-card: #ffffff;
        --text-primary: #1e293b;
        --text-secondary: #475569;
        --text-muted: #94a3b8;
        --border-color: #e2e8f0;
        --shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        --shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.06);
        --accent-1: #3b82f6;
        --accent-2: #6366f1;
        --accent-gradient: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
        --success: #10b981;
        --danger: #ef4444;
    }

    .settings-wrapper {
        padding: 24px 28px;
        background: var(--bg-primary);
        min-height: 100vh;
    }

    /* ===== PAGE HEADER ===== */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-header-left {
        display: flex;
        flex-direction: column;
    }

    .page-header-left h1 {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-header-left h1 i {
        color: var(--accent-1);
        font-size: 28px;
    }

    .page-header-left p {
        color: var(--text-secondary);
        font-size: 14px;
        margin: 4px 0 0 0;
    }

    /* ===== FORM CARD ===== */
    .form-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow);
        max-width: 800px;
        margin: 0 auto;
    }

    .form-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        background: #f8fafc;
    }

    .form-card-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-card-header h3 i {
        color: var(--accent-1);
    }

    .form-card-body {
        padding: 24px;
    }

    /* ===== FORM ELEMENTS ===== */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        font-size: 14px;
        color: var(--text-primary);
        display: block;
        margin-bottom: 6px;
    }

    .form-group label .required {
        color: var(--danger);
        margin-left: 2px;
    }

    .form-group label i {
        color: var(--accent-1);
        margin-right: 6px;
        width: 18px;
    }

    .form-control {
        width: 100%;
        padding: 10px 16px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 14px;
        transition: all 0.3s ease;
        outline: none;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: var(--accent-1);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
        background: white;
    }

    .form-control.is-invalid {
        border-color: var(--danger);
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    .invalid-feedback {
        font-size: 12px;
        color: var(--danger);
        margin-top: 4px;
    }

    /* ===== ALERTS ===== */
    .alert-modern {
        padding: 14px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-left: 4px solid;
        background: var(--bg-card);
        box-shadow: var(--shadow);
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .alert-modern.success {
        border-color: var(--success);
        color: #065f46;
    }

    .alert-modern.success i {
        color: var(--success);
    }

    .alert-modern.error {
        border-color: var(--danger);
        color: #991b1b;
    }

    .alert-modern.error i {
        color: var(--danger);
    }

    .alert-modern i {
        font-size: 18px;
    }

    /* ===== FORM ACTIONS ===== */
    .form-actions {
        display: flex;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        margin-top: 8px;
    }

    .btn-primary-gradient {
        background: var(--accent-gradient);
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.25);
    }

    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(59, 130, 246, 0.35);
        color: white;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .settings-wrapper {
            padding: 16px;
        }

        .page-header-left h1 {
            font-size: 22px;
        }

        .form-card-body {
            padding: 16px;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .settings-wrapper {
            padding: 12px;
        }

        .page-header-left h1 {
            font-size: 18px;
        }

        .btn-primary-gradient {
            padding: 10px 20px;
            font-size: 13px;
        }

        .form-card-body {
            padding: 12px;
        }

        .form-control {
            font-size: 13px;
            padding: 8px 12px;
        }
    }
</style>

<div class="settings-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                <i class="fas fa-sliders-h"></i> Settings
            </h1>
            <p><i class="fas fa-arrow-trend-up" style="color: var(--accent-1);"></i> Configure your hospital management system</p>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert-modern success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-modern error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-modern error">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h3>
                <i class="fas fa-building"></i> General Settings
            </h3>
        </div>

        <div class="form-card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')

                <!-- Hospital Name -->
                <div class="form-group">
                    <label for="hospital_name">
                        <i class="fas fa-hospital"></i> Hospital Name
                    </label>
                    <input type="text" class="form-control" 
                           id="hospital_name" name="hospital_name" 
                           placeholder="Enter hospital name" 
                           value="{{ old('hospital_name', 'Smart Queue Hospital') }}">
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <label for="contact_email">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type="email" class="form-control" 
                           id="contact_email" name="contact_email" 
                           placeholder="Enter contact email" 
                           value="{{ old('contact_email', 'info@smarthospital.com') }}">
                </div>

                <!-- Phone Number -->
                <div class="form-group">
                    <label for="contact_phone">
                        <i class="fas fa-phone"></i> Phone Number
                    </label>
                    <input type="text" class="form-control" 
                           id="contact_phone" name="contact_phone" 
                           placeholder="Enter phone number" 
                           value="{{ old('contact_phone', '+92 123 4567890') }}">
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label for="address">
                        <i class="fas fa-map-marker-alt"></i> Address
                    </label>
                    <input type="text" class="form-control" 
                           id="address" name="address" 
                           placeholder="Enter hospital address" 
                           value="{{ old('address', '123 Healthcare Street') }}">
                </div>

                <!-- Working Hours -->
                <div class="form-group">
                    <label for="working_hours">
                        <i class="fas fa-clock"></i> Working Hours
                    </label>
                    <input type="text" class="form-control" 
                           id="working_hours" name="working_hours" 
                           placeholder="Enter working hours" 
                           value="{{ old('working_hours', 'Mon-Sat: 9:00 AM - 9:00 PM') }}">
                </div>

                <!-- Emergency Hours -->
                <div class="form-group">
                    <label for="emergency_hours">
                        <i class="fas fa-ambulance"></i> Emergency Hours
                    </label>
                    <input type="text" class="form-control" 
                           id="emergency_hours" name="emergency_hours" 
                           placeholder="Enter emergency hours" 
                           value="{{ old('emergency_hours', '24/7 Emergency Service') }}">
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn-primary-gradient">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection