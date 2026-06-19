@extends('Layout.admin-layout')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    .form-container {
        font-family: 'Poppins', sans-serif;
    }
    
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        max-width: 700px;
        margin: 20px auto;
        overflow: hidden;
        animation: slideIn 0.5s ease;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .form-header {
        background: linear-gradient(135deg, #0b2e33 0%, #1a4a50 100%);
        padding: 30px;
        text-align: center;
    }
    
    .form-header h2 {
        color: #00d4ff;
        margin: 10px 0 0;
        font-size: 28px;
        font-weight: 600;
    }
    
    .form-header p {
        color: #a0d4d9;
        margin: 8px 0 0;
        font-size: 14px;
    }
    
    .form-header i {
        font-size: 60px;
        color: #00d4ff;
    }
    
    .form-body {
        padding: 40px;
    }
    
    .input-group {
        margin-bottom: 25px;
    }
    
    .input-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #0b2e33;
        font-size: 14px;
    }
    
    .input-group label i {
        margin-right: 8px;
        color: #00d4ff;
    }
    
    .input-group input,
    .input-group select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .input-group input:focus,
    .input-group select:focus {
        outline: none;
        border-color: #00d4ff;
        box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1);
    }
    
    .required {
        color: #dc3545;
        margin-left: 4px;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #00d4ff, #0b2e33);
        color: white;
        padding: 14px 30px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 212, 255, 0.3);
    }
    
    .btn-submit i {
        margin-right: 8px;
    }
    
    .btn-cancel {
        background: #6c757d;
        color: white;
        padding: 12px 25px;
        text-decoration: none;
        border-radius: 12px;
        display: inline-block;
        text-align: center;
        margin-top: 15px;
        width: 100%;
        transition: all 0.3s ease;
    }
    
    .btn-cancel:hover {
        background: #5a6268;
        transform: translateY(-1px);
    }
    
    .error-message {
        background: #f8d7da;
        color: #721c24;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
    }
    
    .error-message small {
        display: block;
        margin: 5px 0;
    }
</style>

<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <i class="fas fa-user-md"></i>
            <h2>Add New Doctor</h2>
            <p>Fill in the details to register a new doctor</p>
        </div>
        
        <div class="form-body">
            @if($errors->any())
                <div class="error-message">
                    @foreach($errors->all() as $error)
                        <small><i class="fas fa-exclamation-circle"></i> {{ $error }}</small>
                    @endforeach
                </div>
            @endif
            
            <!-- FIXED: Form action URL -->
            <form method="POST" action="{{ route('admin.doctors.store') }}">
                @csrf
                
                <div class="input-group">
                    <label><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                    <input type="text" name="name" placeholder="Enter doctor's full name" value="{{ old('name') }}" required>
                </div>
                
                <div class="input-group">
                    <label><i class="fas fa-stethoscope"></i> Specialization <span class="required">*</span></label>
                    <input type="text" name="specialization" placeholder="e.g., Cardiologist, Neurologist" value="{{ old('specialization') }}" required>
                </div>
                
                <div class="input-group">
                    <label><i class="fas fa-graduation-cap"></i> Qualification</label>
                    <input type="text" name="qualification" placeholder="e.g., MBBS, MD, PhD" value="{{ old('qualification') }}">
                </div>
                
                <div class="input-group">
                    <label><i class="fas fa-envelope"></i> Email Address <span class="required">*</span></label>
                    <input type="email" name="email" placeholder="doctor@hospital.com" value="{{ old('email') }}" required>
                </div>
                
                <div class="input-group">
                    <label><i class="fas fa-phone"></i> Phone Number <span class="required">*</span></label>
                    <input type="text" name="phone" placeholder="+92 123 4567890" value="{{ old('phone') }}" required>
                </div>
                
                <div class="input-group">
                    <label><i class="fas fa-toggle-on"></i> Status <span class="required">*</span></label>
                    <select name="status">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>🟢 Active - Available</option>
                        <option value="on_duty" {{ old('status') == 'on_duty' ? 'selected' : '' }}>🔵 On Duty - Working</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>🔴 Inactive - Not Available</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Save Doctor
                </button>
                
                <!-- FIXED: Cancel button route -->
                <a href="{{ route('admin.doctors.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </form>
        </div>
    </div>
</div>
@endsection