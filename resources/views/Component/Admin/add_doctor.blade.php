@extends('Layout.admin-layout')

@section('page-title', 'Add New Doctor')
@section('breadcrumb', 'Create Doctor')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* ============================================
       ADD DOCTOR - LIGHT THEME
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

    .add-doctor-wrapper {
        padding: 24px 28px;
        background: var(--bg-primary);
        min-height: 100vh;
        max-width: 100%;
        overflow-x: hidden;
    }

    /* ===== PAGE HEADER ===== */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
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

    .btn-secondary-gradient {
        background: #e2e8f0;
        color: var(--text-primary);
        padding: 12px 28px;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-secondary-gradient:hover {
        background: #cbd5e1;
        color: var(--text-primary);
        text-decoration: none;
    }

    .btn-primary-gradient {
        background: var(--accent-gradient);
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.25);
    }

    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(59, 130, 246, 0.35);
        color: white;
        text-decoration: none;
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

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        cursor: pointer;
        padding-right: 40px;
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

    .alert-modern ul {
        margin: 0;
        padding-left: 20px;
    }

    .alert-modern ul li {
        list-style-type: disc;
    }

    /* ===== FORM ACTIONS ===== */
    .form-actions {
        display: flex;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        margin-top: 8px;
    }

    .form-actions .btn {
        flex: 1;
        justify-content: center;
    }

    .form-actions .btn-secondary-gradient {
        flex: 0.5;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .add-doctor-wrapper {
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
            flex: 1;
        }

        .form-actions .btn-secondary-gradient {
            flex: 1;
        }
    }

    @media (max-width: 576px) {
        .add-doctor-wrapper {
            padding: 12px;
        }

        .page-header-left h1 {
            font-size: 18px;
        }

        .btn-primary-gradient,
        .btn-secondary-gradient {
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

<div class="add-doctor-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                <i class="fas fa-user-md"></i> Add New Doctor
            </h1>
            <p><i class="fas fa-arrow-trend-up" style="color: var(--accent-1);"></i> Register a new doctor in the system</p>
        </div>
        <div>
            <a href="{{ route('admin.doctors.index') }}" class="btn-secondary-gradient">
                <i class="fas fa-arrow-left"></i> Back to Doctors
            </a>
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
                <i class="fas fa-user-md"></i> Doctor Information
            </h3>
        </div>

        <div class="form-card-body">
            <form method="POST" action="{{ route('admin.doctors.store') }}">
                @csrf

                <!-- Full Name -->
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user"></i> Full Name <span class="required">*</span>
                    </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" placeholder="Enter doctor's full name" 
                           value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Specialization -->
                <div class="form-group">
                    <label for="specialization">
                        <i class="fas fa-stethoscope"></i> Specialization <span class="required">*</span>
                    </label>
                    <input type="text" class="form-control @error('specialization') is-invalid @enderror" 
                           id="specialization" name="specialization" placeholder="e.g., Cardiologist" 
                           value="{{ old('specialization') }}" required>
                    @error('specialization')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Qualification -->
                <div class="form-group">
                    <label for="qualification">
                        <i class="fas fa-graduation-cap"></i> Qualification
                    </label>
                    <input type="text" class="form-control @error('qualification') is-invalid @enderror" 
                           id="qualification" name="qualification" placeholder="e.g., MBBS, MD, PhD" 
                           value="{{ old('qualification') }}">
                    @error('qualification')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address <span class="required">*</span>
                    </label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" placeholder="doctor@hospital.com" 
                           value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label for="phone">
                        <i class="fas fa-phone"></i> Phone Number <span class="required">*</span>
                    </label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                           id="phone" name="phone" placeholder="+92 123 4567890" 
                           value="{{ old('phone') }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ✅ Status - Only Active/Inactive --}}
                <div class="form-group">
                    <label for="status">
                        <i class="fas fa-toggle-on"></i> Status <span class="required">*</span>
                    </label>
                    <select class="form-control @error('status') is-invalid @enderror" 
                            id="status" name="status" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>🟢 Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>🔴 Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary-gradient">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary-gradient">
                        <i class="fas fa-save"></i> Save Doctor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection