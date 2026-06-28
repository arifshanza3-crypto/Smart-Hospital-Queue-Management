@extends('layout.app')

@section('title', 'Login - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">

<div class="login-viewport">
    <div class="mesh-bg"></div>
    <div class="particles-container"></div>

    <div class="login-page-wrapper">
        <div class="login-card">
            <div class="text-center mb-4">
                <img src="{{ asset('Assert/logo.png') }}" alt="Logo" class="login-logo-massive mb-3">
                <h2 class="text-white fw-bold tracking-tight">Login</h2>
                <p class="text-white-50">Efficiently managing your time</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label text-white-50 small text-uppercase fw-bold">Login As</label>
                    <select name="role" id="userRole" class="form-control custom-input" required>
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <!-- Admin Fields (visible when role = admin) -->
                <div id="admin-fields">
                    <div class="mb-3">
                        <label class="form-label text-white-50 small">Email Address</label>
                        <input type="email" name="email" class="form-control custom-input" placeholder="Enter your email" value="{{ old('email') }}">
                    </div>
                </div>

                <!-- Staff Fields (visible when role = staff) -->
                <div id="staff-fields" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label text-white-50 small">Employee ID</label>
                        <input type="text" name="employee_id" class="form-control custom-input" placeholder="Enter Employee ID" value="{{ old('employee_id') }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-white-50 small text-uppercase fw-bold">Password</label>
                    <input type="password" name="password" class="form-control custom-input" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-login-submit w-100 mt-2">Sign In</button>

                <div class="text-center mt-4">
                    <p class="text-white-50 small">
                        Don't have an account? 
                        <a href="/signup" class="text-accent-cyan text-decoration-none fw-bold">Sign Up Now</a>
                    </p>
                </div>

                <!-- ✅ FORGOT PASSWORD - LAST (Below Sign Up) -->
                <div class="text-center mt-3">
                    <a href="{{ route('password.request') }}" class="forgot-password-btn">
                        <i class="fas fa-key"></i> Forgot Password?
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/login.js') }}"></script>
<script>
    document.getElementById('userRole').addEventListener('change', function() {
        const role = this.value;
        const adminFields = document.getElementById('admin-fields');
        const staffFields = document.getElementById('staff-fields');
        
        if (role === 'admin') {
            adminFields.style.display = 'block';
            staffFields.style.display = 'none';
            document.querySelector('input[name="email"]').required = true;
            document.querySelector('input[name="employee_id"]').required = false;
        } else if (role === 'staff') {
            adminFields.style.display = 'none';
            staffFields.style.display = 'block';
            document.querySelector('input[name="email"]').required = false;
            document.querySelector('input[name="employee_id"]').required = true;
        }
    });
</script>

@endsection