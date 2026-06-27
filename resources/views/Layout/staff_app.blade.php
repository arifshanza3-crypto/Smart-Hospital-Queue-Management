<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - SMART QUEUE</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-teal: #3e8686;
            --accent-cyan: #00d4ff;
            --nav-bg: #0b2e33; 
        }

        body { 
            background-color: #05191c; 
            font-family: 'Segoe UI', sans-serif; 
            margin: 0; 
            padding: 0; 
        }

        .staff-header {
            background: var(--nav-bg);
            border-bottom: 2px solid rgba(0, 212, 255, 0.2);
            padding: 10px 0;
            position: sticky; 
            top: 0;
            width: 100%;
            z-index: 1030;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 40px; 
        }

        .nav-logo-img {
            height: 60px;
            width: auto;
            filter: drop-shadow(0 0 10px rgba(0, 212, 255, 0.3));
        }

        .staff-meta {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .staff-details {
            text-align: right;
            color: white;
            line-height: 1.2;
        }

        .staff-name {
            font-size: 15px;
            font-weight: 700;
            display: block;
        }

        .staff-role {
            font-size: 11px;
            color: var(--accent-cyan);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .profile-circle {
            width: 45px;
            height: 45px;
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid var(--accent-cyan);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-cyan);
            font-size: 20px;
        }

        .btn-logout-sharp {
            background: #ff4d4d;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            text-decoration: none;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-logout-sharp:hover {
            background: #cc0000;
            color: white;
            box-shadow: 0 0 15px rgba(255, 77, 77, 0.4);
        }

        main {
            padding: 40px 20px;
        }
    </style>
</head>
<body>

    <header class="staff-header">
        <div class="header-container">
            <div class="header-logo">
                <a href="/staff">
                    <img src="{{ asset('Assert/logo.png') }}" alt="Logo" class="nav-logo-img">
                </a>
            </div>

            <div class="staff-meta">
                <div class="staff-details d-none d-sm-block">
                    <span class="staff-name">Staff Member</span>
                    <span class="staff-role">Operator</span>
                </div>
                
                <div class="profile-circle">
                    <i class="bi bi-person-badge"></i>
                </div>

                <a href="{{ url('/') }}" class="btn-logout-sharp">
                    <i class="bi bi-power"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <main>
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>