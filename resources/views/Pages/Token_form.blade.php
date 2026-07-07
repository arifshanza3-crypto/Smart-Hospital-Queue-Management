@extends('layout.app')

@section('title', 'Generate Token - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Token_form.css') }}">

<div class="login-viewport">
    <div class="mesh-bg"></div>
    <div class="particles-container"></div>

    <section class="token-main-wrapper">
        <div class="token-card">
            <div class="token-header">
                <img src="{{ asset('Assert/logo.png') }}" alt="Smart Queue Logo" class="token-logo">
                <h2 class="token-title">Token Form</h2>
                <p class="token-subtitle">Enter details to join the queue</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('token.generate') }}" autocomplete="off">
                @csrf

                <div class="input-container">
                    <label class="input-label">Full Name</label>
                    <input type="text" name="patient_name" class="form-control token-input" placeholder="Enter your full name" required>
                </div>

                {{-- ❌ Phone Number Field Removed --}}

                <div class="input-container">
                    <label class="input-label">Email</label>
                    <input type="email" name="email" class="form-control token-input" placeholder="Enter your email">
                </div>

                <div class="input-container">
                    <label class="input-label">Department</label>
                    <select name="department" class="form-control token-input" required>
                        <option value="" disabled selected>Select Department</option>
                        <option value="OPD">OPD</option>
                        <option value="Pharmacy">Pharmacy</option>
                        <option value="Radiology">Radiology</option>
                    </select>
                </div>

                <button type="submit" class="btn-token-generate">Generate Token</button>
            </form>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const emailInput = document.querySelector('input[name="email"]');

    // Optional: Email validation on submit
    form.addEventListener('submit', function(e) {
        // Email is optional, no validation needed
        console.log('Form submitted');
    });
});
</script>

@endsection