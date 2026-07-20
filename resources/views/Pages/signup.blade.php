@extends('Layout.app')

@section('title', 'Sign Up - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="{{ asset('css/sign.css') }}">

<style>
    .signup-viewport {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #0b2e33 0%, #1a4a50 100%);
        padding: 40px 20px;
    }
    .signup-page-wrapper {
        width: 100%;
        max-width: 480px;
    }
    .signup-card {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 24px;
        padding: 40px 35px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    .signup-logo-massive {
        height: 80px;
        width: auto;
        filter: drop-shadow(0 0 20px rgba(0, 212, 255, 0.3));
    }
    .text-white { color: white; }
    .text-white-50 { color: rgba(255,255,255,0.7); }
    .fw-bold { font-weight: 700; }
    .text-center { text-align: center; }
    .mb-3 { margin-bottom: 16px; }
    .mb-4 { margin-bottom: 24px; }
    .mt-3 { margin-top: 16px; }
    .mt-4 { margin-top: 24px; }
    .w-100 { width: 100%; }
    .small { font-size: 14px; }
    .text-accent-cyan { color: #00d4ff; text-decoration: none; font-weight: 600; }
    .text-accent-cyan:hover { text-decoration: underline; }

    .custom-input {
        width: 100%;
        padding: 14px 18px;
        background: rgba(255,255,255,0.08);
        border: 2px solid rgba(255,255,255,0.15);
        border-radius: 12px;
        color: white;
        font-size: 15px;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }
    .custom-input:focus {
        outline: none;
        border-color: #00d4ff;
        box-shadow: 0 0 0 4px rgba(0, 212, 255, 0.15);
        background: rgba(255,255,255,0.12);
    }
    .custom-input::placeholder {
        color: rgba(255,255,255,0.5);
    }
    .custom-input option {
        background: #0b2e33;
        color: white;
    }

    .btn-signup-submit {
        padding: 14px;
        background: linear-gradient(135deg, #00d4ff, #0b2e33);
        border: none;
        border-radius: 12px;
        color: white;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        font-family: 'Poppins', sans-serif;
    }
    .btn-signup-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 212, 255, 0.3);
    }

    .alert {
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 16px;
        font-size: 14px;
    }
    .alert-danger {
        background: rgba(220, 53, 69, 0.2);
        border: 1px solid rgba(220, 53, 69, 0.3);
        color: #ff6b6b;
    }
    .alert-success {
        background: rgba(40, 167, 69, 0.2);
        border: 1px solid rgba(40, 167, 69, 0.3);
        color: #5cb85c;
    }
    .form-label {
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 600;
        color: rgba(255,255,255,0.8);
        letter-spacing: 0.5px;
    }
    select.custom-input {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='white' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        cursor: pointer;
    }
</style>

<div class="signup-viewport">
    <div class="signup-page-wrapper">
        <div class="signup-card">
            <div class="text-center mb-4">
                <img src="{{ asset('Assert/logo.png') }}" alt="Logo" class="signup-logo-massive mb-3">
                <h2 class="text-white fw-bold">Create Account</h2>
                <p class="text-white-50">Join Smart Queue Management System</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left:20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('signup.post') }}" id="signupForm">
                @csrf

                <!-- ✅ Role Selection - All Roles -->
                <div class="mb-3">
                    <label class="form-label">Register As</label>
                    <select name="role" id="roleSelect" class="custom-input" required>
                        <option value="user">User</option>
                        <option value="patient">Patient</option>
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="mb-3">
                    <input type="text" name="name" class="custom-input" placeholder="Full Name" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <input type="email" name="email" class="custom-input" placeholder="Email Address" value="{{ old('email') }}" required>
                </div>

                <!-- Employee ID (Only for Staff) -->
                <div class="mb-3" id="employeeField" style="display: none;">
                    <input type="text" name="employee_id" class="custom-input" placeholder="Employee ID" value="{{ old('employee_id') }}">
                </div>

                <!-- Department (Only for Staff) -->
                <div class="mb-3" id="departmentField" style="display: none;">
                    <input type="text" name="department" class="custom-input" placeholder="Department (e.g., Cardiology)" value="{{ old('department') }}">
                </div>

                <div class="mb-3">
                    <input type="text" name="phone" class="custom-input" placeholder="Phone Number (Optional)" value="{{ old('phone') }}">
                </div>

                <div class="mb-3">
                    <input type="password" name="password" class="custom-input" placeholder="Password (min 6 characters)" required>
                </div>

                <div class="mb-3">
                    <input type="password" name="password_confirmation" class="custom-input" placeholder="Confirm Password" required>
                </div>

                <button type="submit" class="btn-signup-submit">Create Account</button>

                <div class="text-center mt-4">
                    <p class="text-white-50 small">Already have an account? <a href="{{ route('login') }}" class="text-accent-cyan">Login</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('roleSelect');
        const employeeField = document.getElementById('employeeField');
        const departmentField = document.getElementById('departmentField');
        const employeeInput = document.querySelector('input[name="employee_id"]');
        const departmentInput = document.querySelector('input[name="department"]');

        function toggleFields() {
            const role = roleSelect.value;

            if (role === 'staff') {
                employeeField.style.display = 'block';
                departmentField.style.display = 'block';
                employeeInput.required = true;
                departmentInput.required = false;
            } else {
                employeeField.style.display = 'none';
                departmentField.style.display = 'none';
                employeeInput.required = false;
                departmentInput.required = false;
            }
        }

        roleSelect.addEventListener('change', toggleFields);
        toggleFields(); // Initial call
    });
</script>

@endsection