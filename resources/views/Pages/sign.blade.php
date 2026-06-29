@extends('layout.app')

@section('title', 'Sign Up - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="{{ asset('css/sign.css') }}">

<div class="signup-viewport">
    <div class="signup-page-wrapper">
        <div class="signup-card">
            <div class="text-center mb-4">
                <img src="{{ asset('Assert/logo.png') }}" alt="Logo" class="signup-logo-massive mb-3">
                <h2 class="text-white fw-bold">Sign Up</h2>
                <p class="text-white-50">Create your account to join the queue</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- ✅ FORM ACTION CHANGED TO signup.post -->
            <form method="POST" action="{{ route('signup.post') }}" id="signupForm">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label text-white-50">Register As</label>
                    <select name="role" id="roleSelect" class="form-control custom-input" required>
                        <option value="patient">Patient</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>

                <div class="mb-3">
                    <input type="text" name="full_name" class="form-control custom-input" placeholder="Full Name" required>
                </div>

                <div class="mb-3">
                    <input type="email" name="email" class="form-control custom-input" placeholder="Email Address" required>
                </div>

                <!-- Staff Only Field (Hidden by default) -->
                <div id="staffEmployeeField" style="display: none;">
                    <div class="mb-3">
                        <input type="text" name="employee_id" class="form-control custom-input" placeholder="Employee ID">
                    </div>
                </div>

                <div class="mb-3">
                    <input type="password" name="password" class="form-control custom-input" placeholder="Password" required>
                </div>

                <div class="mb-3">
                    <input type="password" name="password_confirmation" class="form-control custom-input" placeholder="Confirm Password" required>
                </div>

                <button type="submit" class="btn btn-signup-submit w-100 mt-3">Create Account</button>
                
                <div class="text-center mt-4">
                    <p class="text-white-50 small">Already have an account? <a href="{{ route('login') }}" class="text-accent-cyan">Login</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/sign.js') }}"></script>
<script>
    document.getElementById('roleSelect').addEventListener('change', function() {
        const role = this.value;
        const staffField = document.getElementById('staffEmployeeField');
        
        if (role === 'staff') {
            staffField.style.display = 'block';
            document.querySelector('input[name="employee_id"]').required = true;
        } else {
            staffField.style.display = 'none';
            document.querySelector('input[name="employee_id"]').required = false;
        }
    });
</script>

@endsection