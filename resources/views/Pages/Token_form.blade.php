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

                <div class="input-container">
                    <label class="input-label">Phone Number</label>
                    <input type="tel" name="phone" class="form-control token-input" placeholder="Enter phone number" required>
                    <div id="errorMsg" class="validation-error d-none">Number must be 11 digits and start with 03</div>
                </div>

                <div class="input-container">
                    <label class="input-label">Email (Optional)</label>
                    <input type="email" name="email" class="form-control token-input" placeholder="Enter your email">
                </div>

                <div class="input-container">
                    <label class="input-label">Department</label>
                    <select name="department" class="form-control token-input" required>
                        <option value="">Select Department</option>
                        <option value="OPD">OPD</option>
                        <option value="Lab">Lab</option>
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
    const phoneInput = document.querySelector('input[name="phone"]');
    const errorMsg = document.getElementById('errorMsg');

    // Phone number validation
    phoneInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 11) {
            this.value = this.value.slice(0, 11);
        }
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        const phoneVal = phoneInput.value;
        const isValid = /^(03)\d{9}$/.test(phoneVal);

        if (!isValid && phoneVal.length > 0) {
            e.preventDefault();
            errorMsg.classList.remove('d-none');
            phoneInput.style.border = "1px solid #ff4b2b";
        } else {
            errorMsg.classList.add('d-none');
            phoneInput.style.border = "1px solid rgba(255,255,255,0.1)";
        }
    });
});
</script>

@endsection