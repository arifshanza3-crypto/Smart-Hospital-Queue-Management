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
                <h2 class="text-white fw-bold tracking-tight">Reset Password</h2>
                <p class="text-white-50">Enter your email to reset your password</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-4">
                    <label class="form-label text-white-50 small text-uppercase fw-bold">Email Address</label>
                    <input type="email" name="email" class="form-control custom-input" placeholder="Enter your email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-login-submit w-100 mt-2">Send Reset Link</button>
                
                <div class="text-center mt-4">
                    <p class="text-white-50 small">
                        <a href="{{ route('login') }}" class="text-accent-cyan text-decoration-none fw-bold">Back to Login</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/login.js') }}"></script>
@endsection