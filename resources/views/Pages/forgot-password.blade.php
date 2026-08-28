@extends('layout.app')

@section('title', 'Forgot Password - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">

<div class="login-viewport">
    <div class="mesh-bg"></div>
    <div class="particles-container"></div>

    <div class="login-page-wrapper">
        <div class="login-card">
            <div class="text-center mb-4">
                <img src="{{ asset('Assert/logo.png') }}" alt="Logo" class="login-logo-massive mb-3">
                <h2 class="text-white fw-bold tracking-tight">🔐 Forgot Password</h2>
                <p class="text-white-50">Enter your email to receive a password reset link</p>
            </div>

            {{-- ✅ Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- ❌ Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-4">
                    <label class="form-label text-white-50 small text-uppercase fw-bold">Email Address</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control custom-input @error('email') is-invalid @enderror" 
                        placeholder="Enter your registered email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                    >
                    @error('email')
                        <span class="text-danger small"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-login-submit w-100 mt-2">
                    <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                </button>
                
                <div class="text-center mt-4">
                    <p class="text-white-50 small">
                        <i class="fas fa-arrow-left me-1"></i>
                        <a href="{{ route('login') }}" class="text-accent-cyan text-decoration-none fw-bold">Back to Login</a>
                    </p>
                </div>
            </form>

            {{-- Additional Info --}}
            <div class="mt-4 text-center">
                <p class="text-white-50 small" style="font-size: 0.75rem;">
                    <i class="fas fa-info-circle"></i> 
                    We'll send a secure link to your email. The link expires in 60 minutes.
                </p>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/login.js') }}"></script>

{{-- Auto-dismiss alerts after 5 seconds --}}
<script>
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>

@endsection