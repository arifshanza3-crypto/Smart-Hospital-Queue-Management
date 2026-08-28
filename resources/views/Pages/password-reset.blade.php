<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SMART QUEUE</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0a0e1a;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #141b2b;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #2a3a5c;
            box-shadow: 0 20px 60px rgba(0,0,0,0.6);
        }
        .header {
            background: linear-gradient(135deg, #0f1729, #1a2744);
            padding: 40px 30px;
            text-align: center;
            border-bottom: 2px solid #2a3a5c;
        }
        .header .logo {
            max-width: 80px;
            margin-bottom: 15px;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header p {
            color: #8899bb;
            margin: 5px 0 0;
            font-size: 14px;
        }
        .content {
            padding: 40px 35px;
        }
        .content h2 {
            color: #ffffff;
            font-size: 20px;
            margin-top: 0;
        }
        .content p {
            color: #b0c4de;
            line-height: 1.7;
            font-size: 15px;
        }
        .content strong {
            color: #ffffff;
        }
        .reset-btn {
            display: inline-block;
            background: linear-gradient(135deg, #00d4ff, #0099ff);
            color: #ffffff !important;
            padding: 16px 45px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 16px;
            margin: 25px 0 15px;
            text-align: center;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 150, 255, 0.3);
        }
        .reset-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 30px rgba(0, 150, 255, 0.5);
        }
        .divider {
            border: none;
            border-top: 1px solid #2a3a5c;
            margin: 30px 0;
        }
        .expiry-notice {
            background: rgba(255, 70, 70, 0.1);
            border-left: 3px solid #ff4444;
            color: #ff8888;
            padding: 15px 20px;
            border-radius: 8px;
            font-size: 14px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            padding: 25px;
            color: #667799;
            font-size: 13px;
            background: #0f1729;
            border-top: 1px solid #1a2744;
        }
        .footer a {
            color: #00d4ff;
            text-decoration: none;
        }
        .brand-highlight {
            color: #00d4ff;
            font-weight: bold;
        }
        .security-badge {
            display: inline-block;
            background: rgba(0, 212, 255, 0.1);
            color: #00d4ff;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 10px;
        }
        @media (max-width: 480px) {
            .content { padding: 25px 20px; }
            .reset-btn { display: block; padding: 14px 20px; font-size: 15px; }
            .header h1 { font-size: 20px; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('Assert/logo.png') }}" alt="SMART QUEUE" class="logo">
            <h1>🔐 SMART QUEUE</h1>
            <p>Password Reset Request</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Hello {{ $user->name ?? 'User' }}! 👋</h2>
            
            <p>
                We received a request to reset the password for your account associated with 
                <strong>{{ $user->email }}</strong>.
            </p>
            
            <p>
                Click the button below to set a new password. This link will expire in 
                <strong>60 minutes</strong>.
            </p>

            <!-- Reset Button -->
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="reset-btn">
                    🔄 Reset My Password
                </a>
            </div>

            <p style="font-size: 13px; color: #667799; text-align: center;">
                Or copy this link into your browser:<br>
                <a href="{{ $resetUrl }}" style="word-break: break-all; color: #00d4ff;">
                    {{ $resetUrl }}
                </a>
            </p>

            <hr class="divider">

            <div class="expiry-notice">
                ⚠️ <strong>Security Notice:</strong> If you didn't request this, please ignore this email. 
                Your password won't change unless you click the link above.
            </div>

            <div style="text-align: center;">
                <span class="security-badge">🔒 Secure & Encrypted</span>
            </div>

            <p style="margin-top: 25px; color: #b0c4de;">
                Thanks,<br>
                <span class="brand-highlight">SMART QUEUE</span> Team
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                This email was sent to <strong>{{ $user->email }}</strong>.<br>
                © {{ date('Y') }} SMART QUEUE. All rights reserved.
            </p>
            <p style="margin-top: 10px; font-size: 11px; color: #445566;">
                <a href="{{ url('/') }}">Visit Our Website</a> • 
                <a href="mailto:support@smartqueue.com">Contact Support</a>
            </p>
        </div>
    </div>
</body>
</html>