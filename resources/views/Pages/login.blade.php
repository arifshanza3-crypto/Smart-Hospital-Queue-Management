<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        .forgot-link { color: #00d4ff; text-decoration: none; font-size: 14px; }
        .forgot-link:hover { text-decoration: underline; }
        .role-hint {
            text-align: center;
            margin-top: 5px;
            font-size: 12px;
            color: #999;
        }
        .role-hint i {
            color: #00d4ff;
        }
        
        /* Loading spinner */
        .btn-login.loading {
            opacity: 0.7;
            pointer-events: none;
        }
        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top: 3px solid #fff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto;
        }
        .btn-login.loading .spinner {
            display: inline-block;
        }
        .btn-login.loading .btn-text {
            display: none;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
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

    @if(session('error'))
        <div class="alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-danger">
            @foreach($errors->all() as $error)
                <small><i class="fas fa-exclamation-circle"></i> {{ $error }}</small><br>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" id="loginForm">
        @csrf

        <div class="form-group">
            <label><i class="fas fa-envelope"></i> Email Address</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
            <label><i class="fas fa-lock"></i> Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="btn-login" id="loginBtn">
            <span class="btn-text"><i class="fas fa-sign-in-alt"></i> SIGN IN</span>
            <span class="spinner"></span>
        </button>
    </form>

    <div class="text-center mt-3">
        <p class="text-muted">Don't have an account? <a href="{{ route('signup') }}" class="text-accent">Sign Up Now</a></p>
        <p><a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a></p>
        <p class="role-hint"><i class="fas fa-info-circle"></i> Access based on your role (Admin/Staff/User)</p>
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('loginBtn');
        btn.classList.add('loading');
    });
</script>

</body>
</html>