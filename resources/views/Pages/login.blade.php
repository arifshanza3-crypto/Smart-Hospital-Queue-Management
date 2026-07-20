<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMART QUEUE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0b2e33 0%, #1a4a50 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            background: white;
            border-radius: 24px;
            padding: 50px 40px;
            max-width: 450px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header i { font-size: 50px; color: #00d4ff; }
        .login-header h2 { color: #0b2e33; margin-top: 10px; }
        .login-header p { color: #666; font-size: 14px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; color: #0b2e33; margin-bottom: 6px; font-size: 14px; }
        .form-group label i { color: #00d4ff; margin-right: 8px; }
        .form-control {
            width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 10px;
            font-size: 14px; transition: all 0.3s ease;
        }
        .form-control:focus { outline: none; border-color: #00d4ff; box-shadow: 0 0 0 3px rgba(0,212,255,0.1); }
        .btn-login {
            width: 100%; padding: 14px; background: linear-gradient(135deg, #00d4ff, #0b2e33);
            color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 600;
            cursor: pointer; transition: all 0.3s ease;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,212,255,0.3); }
        .alert-danger { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #dc3545; }
        .alert-success { background: #d4edda; color: #155724; padding: 12px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #28a745; }
        .text-center { text-align: center; }
        .mt-3 { margin-top: 15px; }
        .text-muted { color: #666; font-size: 14px; }
        .text-accent { color: #00d4ff; text-decoration: none; font-weight: 600; }
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            cursor: pointer;
        }
        .forgot-link { color: #00d4ff; text-decoration: none; font-size: 14px; }
        .forgot-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <i class="fas fa-hospital-user"></i>
        <h2>SMART QUEUE</h2>
        <p>Login - Efficiently managing your time</p>
    </div>

    @if(session('success'))
        <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-danger">
            @foreach($errors->all() as $error)
                <small><i class="fas fa-exclamation-circle"></i> {{ $error }}</small><br>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <!-- ✅ ROLE SELECTION - Admin, Staff, User -->
        <div class="form-group">
            <label><i class="fas fa-user-tag"></i> LOGIN AS</label>
            <select name="role" id="roleSelect" class="form-control" required>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
                <option value="user">User</option>
            </select>
        </div>

        <!-- Email Field (for Admin & User) -->
        <div class="form-group" id="emailFields">
            <label><i class="fas fa-envelope"></i> Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" value="{{ old('email') }}">
        </div>

        <!-- Employee ID Field (for Staff) -->
        <div class="form-group" id="staffFields" style="display: none;">
            <label><i class="fas fa-id-badge"></i> Employee ID</label>
            <input type="text" name="employee_id" class="form-control" placeholder="Enter your employee ID" value="{{ old('employee_id') }}">
        </div>

        <div class="form-group">
            <label><i class="fas fa-lock"></i> PASSWORD</label>
            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt"></i> SIGN IN</button>
    </form>

    <div class="text-center mt-3">
        <p class="text-muted">Don't have an account? <a href="{{ route('signup') }}" class="text-accent">Sign Up Now</a></p>
        <p><a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a></p>
    </div>
</div>

<script>
    // Toggle between fields based on role
    document.querySelector('select[name="role"]').addEventListener('change', function() {
        const emailFields = document.getElementById('emailFields');
        const staffFields = document.getElementById('staffFields');
        const emailInput = document.querySelector('input[name="email"]');
        const employeeInput = document.querySelector('input[name="employee_id"]');
        
        if (this.value === 'admin' || this.value === 'user') {
            emailFields.style.display = 'block';
            staffFields.style.display = 'none';
            emailInput.required = true;
            employeeInput.required = false;
        } else if (this.value === 'staff') {
            emailFields.style.display = 'none';
            staffFields.style.display = 'block';
            emailInput.required = false;
            employeeInput.required = true;
        }
    });

    // Trigger change on load
    document.addEventListener('DOMContentLoaded', function() {
        const event = new Event('change');
        document.querySelector('select[name="role"]').dispatchEvent(event);
    });
</script>
</body>
</html>