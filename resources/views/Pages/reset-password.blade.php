@extends('layout.app')

@section('title', 'Reset Password - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">

<style>
    /* Password Strength Styles */
    .password-strength-wrapper {
        margin-top: 8px;
    }
    .strength-bar {
        height: 4px;
        border-radius: 4px;
        background: rgba(255,255,255,0.1);
        overflow: hidden;
        margin-top: 6px;
    }
    .strength-bar .fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.4s ease, background 0.4s ease;
        width: 0%;
    }
    .strength-text {
        font-size: 0.75rem;
        margin-top: 4px;
        display: block;
    }
    .password-requirements {
        font-size: 0.7rem;
        color: rgba(255,255,255,0.5);
        margin-top: 4px;
    }
    .password-requirements .requirement {
        display: inline-block;
        margin-right: 12px;
    }
    .password-requirements .requirement.valid {
        color: #4CAF50;
    }
    .password-requirements .requirement.invalid {
        color: rgba(255,255,255,0.3);
    }
    .toggle-password-btn {
        background: transparent;
        border: none;
        color: rgba(255,255,255,0.5);
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        z-index: 10;
    }
    .toggle-password-btn:hover {
        color: rgba(255,255,255,0.8);
    }
    .input-group-custom {
        position: relative;
    }
    .input-group-custom .form-control {
        padding-right: 45px;
    }
    .form-control.custom-input.is-valid {
        border-color: #4CAF50;
        background-image: none;
    }
    .form-control.custom-input.is-invalid {
        border-color: #f44336;
        background-image: none;
    }
    .password-match-indicator {
        font-size: 0.75rem;
        margin-top: 4px;
    }
    .password-match-indicator .match-success {
        color: #4CAF50;
    }
    .password-match-indicator .match-error {
        color: #f44336;
    }
</style>

<div class="login-viewport">
    <div class="mesh-bg"></div>
    <div class="particles-container"></div>

    <div class="login-page-wrapper">
        <div class="login-card" style="max-width: 480px;">
            <div class="text-center mb-4">
                <img src="{{ asset('Assert/logo.png') }}" alt="Logo" class="login-logo-massive mb-3">
                <h2 class="text-white fw-bold tracking-tight">🔑 Set New Password</h2>
                <p class="text-white-50">Create a strong password for your account</p>
            </div>

            {{-- ❌ Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- ❌ Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <ul class="mb-0 mt-1" style="padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
                @csrf
                
                {{-- Hidden token --}}
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email Field (hidden or visible) --}}
                <div class="mb-3" style="display: none;">
                    <input type="email" name="email" value="{{ old('email', request()->email) }}" required>
                </div>

                {{-- Or show email field if you want users to enter it --}}
                <div class="mb-3">
                    <label class="form-label text-white-50 small text-uppercase fw-bold">Email Address</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control custom-input @error('email') is-invalid @enderror" 
                        placeholder="Enter your email" 
                        value="{{ old('email') }}" 
                        required
                    >
                    @error('email')
                        <span class="text-danger small"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="mb-3">
                    <label class="form-label text-white-50 small text-uppercase fw-bold">New Password</label>
                    <div class="input-group-custom">
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="form-control custom-input @error('password') is-invalid @enderror" 
                            placeholder="Enter new password (min 8 characters)" 
                            required
                            minlength="8
                        >
                        <button type="button" class="toggle-password-btn" id="togglePassword">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    
                    {{-- Password Strength Indicator --}}
                    <div class="password-strength-wrapper">
                        <div class="strength-bar">
                            <div class="fill" id="strengthFill"></div>
                        </div>
                        <span class="strength-text" id="strengthText">Enter a password</span>
                    </div>

                    {{-- Password Requirements --}}
                    <div class="password-requirements">
                        <span class="requirement" id="reqLength">❌ Min 8 characters</span>
                        <span class="requirement" id="reqNumber">❌ Contains number</span>
                        <span class="requirement" id="reqUpper">❌ Uppercase letter</span>
                        <span class="requirement" id="reqSpecial">❌ Special character</span>
                    </div>

                    @error('password')
                        <span class="text-danger small"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-4">
                    <label class="form-label text-white-50 small text-uppercase fw-bold">Confirm Password</label>
                    <div class="input-group-custom">
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation"
                            class="form-control custom-input" 
                            placeholder="Confirm your new password" 
                            required
                        >
                        <button type="button" class="toggle-password-btn" id="toggleConfirmPassword">
                            <i class="fas fa-eye" id="eyeIconConfirm"></i>
                        </button>
                    </div>
                    <div class="password-match-indicator" id="matchIndicator"></div>
                </div>

                <button type="submit" class="btn btn-login-submit w-100 mt-2" id="submitBtn">
                    <i class="fas fa-sync-alt me-2"></i>Update Password
                </button>
                
                <div class="text-center mt-4">
                    <p class="text-white-50 small">
                        <i class="fas fa-arrow-left me-1"></i>
                        <a href="{{ route('login') }}" class="text-accent-cyan text-decoration-none fw-bold">Back to Login</a>
                    </p>
                </div>
            </form>

            {{-- Security Notice --}}
            <div class="mt-4 text-center">
                <p class="text-white-50 small" style="font-size: 0.7rem;">
                    <i class="fas fa-shield-alt"></i> 
                    Your password is securely encrypted. We never store it in plain text.
                </p>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/login.js') }}"></script>

{{-- Password Strength & Validation JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    const matchIndicator = document.getElementById('matchIndicator');
    const submitBtn = document.getElementById('submitBtn');

    // Requirements elements
    const reqLength = document.getElementById('reqLength');
    const reqNumber = document.getElementById('reqNumber');
    const reqUpper = document.getElementById('reqUpper');
    const reqSpecial = document.getElementById('reqSpecial');

    // Toggle Password Visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        document.getElementById('eyeIcon').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
        document.getElementById('eyeIconConfirm').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    });

    // Password Strength Check
    password.addEventListener('input', function() {
        const val = this.value;
        let strength = 0;
        const checks = {
            length: val.length >= 8,
            number: /\d/.test(val),
            upper: /[A-Z]/.test(val),
            special: /[^a-zA-Z0-9]/.test(val)
        };

        // Update requirements
        updateRequirement(reqLength, checks.length, '✅ Min 8 characters', '❌ Min 8 characters');
        updateRequirement(reqNumber, checks.number, '✅ Contains number', '❌ Contains number');
        updateRequirement(reqUpper, checks.upper, '✅ Uppercase letter', '❌ Uppercase letter');
        updateRequirement(reqSpecial, checks.special, '✅ Special character', '❌ Special character');

        // Calculate strength
        if (checks.length) strength++;
        if (checks.number) strength++;
        if (checks.upper) strength++;
        if (checks.special) strength++;

        const percentage = (strength / 4) * 100;
        strengthFill.style.width = percentage + '%';

        const colors = ['#e2e8f0', '#f56565', '#ed8936', '#ecc94b', '#48bb78'];
        const labels = ['Enter password', 'Weak', 'Fair', 'Good', 'Strong'];
        
        const index = Math.min(Math.floor(percentage / 25), 4);
        strengthFill.style.background = colors[index];
        strengthText.textContent = labels[index];
        strengthText.style.color = colors[index];

        // Check password match
        checkMatch();
    });

    // Confirm Password Check
    confirmPassword.addEventListener('input', function() {
        checkMatch();
    });

    function checkMatch() {
        const pwd = password.value;
        const confirm = confirmPassword.value;
        
        if (confirm.length === 0) {
            matchIndicator.innerHTML = '';
            return;
        }
        
        if (pwd === confirm) {
            matchIndicator.innerHTML = '<span class="match-success"><i class="fas fa-check-circle"></i> Passwords match</span>';
            confirmPassword.classList.add('is-valid');
            confirmPassword.classList.remove('is-invalid');
        } else {
            matchIndicator.innerHTML = '<span class="match-error"><i class="fas fa-times-circle"></i> Passwords do not match</span>';
            confirmPassword.classList.add('is-invalid');
            confirmPassword.classList.remove('is-valid');
        }
    }

    function updateRequirement(element, isValid, validText, invalidText) {
        element.textContent = isValid ? validText : invalidText;
        element.className = 'requirement ' + (isValid ? 'valid' : 'invalid');
    }

    // Form validation before submit
    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        const pwd = password.value;
        const confirm = confirmPassword.value;
        
        if (pwd.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long.');
            return false;
        }
        
        if (pwd !== confirm) {
            e.preventDefault();
            alert('Passwords do not match. Please check and try again.');
            return false;
        }
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>

@endsection