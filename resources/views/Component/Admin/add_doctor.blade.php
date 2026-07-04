@extends('Layout.admin-layout')

@section('page-title', 'Add Doctor')
@section('breadcrumb', 'Add New Doctor')

@section('content')
<style>
    .form-container {
        max-width: 700px;
        margin: 0 auto;
    }
    .form-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.08);
    }
    .form-card h2 {
        color: #0b2e33;
        margin-bottom: 5px;
    }
    .form-card .subtitle {
        color: #666;
        font-size: 14px;
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-weight: 600;
        color: #0b2e33;
        margin-bottom: 6px;
        font-size: 14px;
    }
    .form-group label i {
        color: #00d4ff;
        margin-right: 8px;
    }
    .form-group label .required {
        color: #dc3545;
        margin-left: 4px;
    }
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }
    .form-control:focus {
        outline: none;
        border-color: #00d4ff;
        box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
    }
    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        cursor: pointer;
    }
    .row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .btn-row {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }
    .btn-submit {
        flex: 1;
        padding: 12px 30px;
        background: linear-gradient(135deg, #00d4ff, #0b2e33);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,212,255,0.3);
    }
    .btn-cancel {
        padding: 12px 30px;
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        flex: 0.5;
    }
    .btn-cancel:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }
    .error-box {
        background: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
    }
    .error-box small {
        display: block;
        margin: 3px 0;
    }
    .success-box {
        background: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #28a745;
    }

    @media (max-width: 600px) {
        .row {
            grid-template-columns: 1fr;
            gap: 0;
        }
        .btn-row {
            flex-direction: column;
        }
        .btn-cancel {
            flex: 1;
        }
        .form-container {
            padding: 0 10px;
        }
        .form-card {
            padding: 20px;
        }
    }
</style>

<div class="form-container">
    <div class="form-card">
        <h2><i class="fas fa-user-md" style="color:#00d4ff;"></i> Add New Doctor</h2>
        <p class="subtitle">Fill in the details to register a new doctor in the system.</p>
        <hr style="border: none; border-top: 1px solid #eee; margin-bottom: 25px;">

        <!-- Error Messages -->
        @if($errors->any())
        <div class="error-box">
            <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
            @foreach($errors->all() as $error)
                <small>• {{ $error }}</small>
            @endforeach
        </div>
        @endif

        <!-- Success Message -->
        @if(session('success'))
        <div class="success-box">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('admin.doctors.store') }}">
            @csrf

            <div class="form-group">
                <label><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Enter doctor's full name" value="{{ old('name') }}" required>
            </div>

            <div class="row">
                <div class="form-group">
                    <label><i class="fas fa-stethoscope"></i> Specialization <span class="required">*</span></label>
                    <input type="text" name="specialization" class="form-control" placeholder="e.g., Cardiologist" value="{{ old('specialization') }}" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-graduation-cap"></i> Qualification</label>
                    <input type="text" name="qualification" class="form-control" placeholder="e.g., MBBS, MD, PhD" value="{{ old('qualification') }}">
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="doctor@hospital.com" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Phone Number <span class="required">*</span></label>
                    <input type="text" name="phone" class="form-control" placeholder="+92 123 4567890" value="{{ old('phone') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-toggle-on"></i> Status <span class="required">*</span></label>
                <select name="status" class="form-control">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>🟢 Active - Available</option>
                    <option value="on_duty" {{ old('status') == 'on_duty' ? 'selected' : '' }}>🔵 On Duty - Working</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>🔴 Inactive - Not Available</option>
                </select>
            </div>

            <div class="btn-row">
                <a href="{{ route('admin.doctors.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Save Doctor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection