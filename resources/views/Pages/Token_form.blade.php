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

            <form id="tokenRequestForm" autocomplete="off">
<<<<<<< HEAD
=======
                 
>>>>>>> 3f9cfec078e8d9879dc8f908935c7d8b28740f60
                @csrf
                <div class="input-container">
                    <label class="input-label">Full Name</label>
                    <input type="text" id="userName" class="form-control token-input" placeholder="Enter your full name" required>
                </div>

                <div class="input-container">
                    <label class="input-label">Phone Number</label>
                    <input type="tel" id="userNumber" class="form-control token-input" placeholder="Click to enter number..." required readonly>
                    <div id="errorMsg" class="validation-error d-none">Number must be 11 digits and start with 03</div>
                </div>

                <button type="submit" class="btn-token-generate">Generate Token</button>
            </form>
        </div>
    </section>

    <div id="infoModal" class="custom-modal-overlay">
        <div class="custom-modal-card">
            <span class="modal-close-icon" id="closeInfo">&times;</span>
            <div class="modal-icon-circle">!</div>
            <h3>Note</h3>
            <p>Please Enter the Number where you want to Receive Token</p>
            <button type="button" id="confirmInfo" class="btn-modal-primary">Ok</button>
        </div>
    </div>

    <div id="patientModal" class="custom-modal-overlay">
        <div class="custom-modal-card">
            <div class="loading-spinner"></div>
            <h3 class="accent-text">Please stay Patient</h3>
            <p>It takes time. Your token will generate soon...</p>
        </div>
    </div>
</div>

<script src="{{ asset('js/Token_form.js') }}"></script>
@endsection