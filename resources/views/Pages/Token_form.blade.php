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

            <form method="POST" action="{{ route('token.generate') }}" autocomplete="off" id="tokenRequestForm">
                @csrf

                <div class="input-container">
                    <label class="input-label">Full Name</label>
                    <input type="text" name="patient_name" class="form-control token-input" placeholder="Enter your full name" required>
                </div>

                <div class="input-container">
                    <label class="input-label">Email</label>
                    <input type="email" name="email" class="form-control token-input" placeholder="Enter your email">
                </div>

                {{-- ✅ Mobile Number Field --}}
                <div class="input-container">
                    <label class="input-label">Mobile Number</label>
                    <input type="tel" name="mobile_number" id="mobileNumber" class="form-control token-input" placeholder="03XX-XXXXXXX" required maxlength="11">
                    <div id="mobileError" class="validation-error d-none">Please enter a valid 11-digit number starting with 03</div>
                </div>

                <button type="submit" class="btn-token-generate">Generate Token</button>
            </form>
        </div>
    </section>
</div>

{{-- ✅ Success Modal --}}
<div class="custom-modal-overlay" id="patientModal">
    <div class="custom-modal-card">
        <span class="modal-close-icon" id="closeModal">&times;</span>
        <div class="modal-icon-circle">✓</div>
        <h4 style="color:#fff; font-weight:700;">Token Generated!</h4>
        <p style="color:rgba(255,255,255,0.6);">Your token has been created successfully.</p>
        <button class="btn-modal-primary" id="modalOkBtn">OK</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('tokenRequestForm');
    const mobileInput = document.getElementById('mobileNumber');
    const mobileError = document.getElementById('mobileError');
    const patientModal = document.getElementById('patientModal');
    const closeModal = document.getElementById('closeModal');
    const modalOkBtn = document.getElementById('modalOkBtn');

    // ✅ Mobile Number Validation: Only numbers, max 11 digits, starts with 03
    mobileInput.addEventListener('input', function(e) {
        // Remove non-numeric characters
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Max 11 digits
        if (this.value.length > 11) {
            this.value = this.value.slice(0, 11);
        }

        // Format display: 03XX-XXXXXXX
        if (this.value.length > 0) {
            // Validate in real-time
            const isValid = /^(03)\d{9}$/.test(this.value);
            if (this.value.length > 0 && !isValid) {
                mobileError.classList.remove('d-none');
                this.style.border = "1px solid #ff4b2b";
            } else {
                mobileError.classList.add('d-none');
                this.style.border = "1px solid rgba(255,255,255,0.1)";
            }
        }
    });

    // ✅ Form Submit Validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const mobileVal = mobileInput.value;
        const isValid = /^(03)\d{9}$/.test(mobileVal);

        if (!isValid) {
            mobileError.classList.remove('d-none');
            mobileInput.style.border = "1px solid #ff4b2b";
            mobileInput.focus();
            return;
        }

        // ✅ All good - show success modal
        patientModal.style.display = 'flex';
    });

    // ✅ Close Modal functions
    function closeModalFn() {
        patientModal.style.display = 'none';
        form.submit(); // Submit the form after modal
    }

    closeModal.addEventListener('click', closeModalFn);
    modalOkBtn.addEventListener('click', closeModalFn);

    // Close modal on outside click
    patientModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModalFn();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && patientModal.style.display === 'flex') {
            closeModalFn();
        }
    });
});
</script>

@endsection