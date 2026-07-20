<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - SMART QUEUE</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-teal: #3e8686;
            --accent-cyan: #00d4ff;
            --nav-bg: #0b2e33; 
        }

        body { 
            background-color: #f4f6f9; 
            font-family: 'Poppins', 'Segoe UI', sans-serif; 
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

        /* Profile Circle */
        .profile-circle {
            width: 45px;
            height: 45px;
            background: rgba(0, 212, 255, 0.1);
            border: 2px solid var(--accent-cyan);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-cyan);
            font-size: 20px;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .profile-circle:hover {
            background: rgba(0, 212, 255, 0.2);
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
        }

        .profile-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-circle .initials {
            font-size: 18px;
            font-weight: 600;
            color: var(--accent-cyan);
        }

        /* Profile Dropdown */
        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu-custom {
            display: none;
            position: absolute;
            right: 0;
            top: 55px;
            background: white;
            border-radius: 14px;
            min-width: 220px;
            padding: 8px 0;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            z-index: 1000;
            animation: slideDown 0.3s ease;
        }

        .dropdown-menu-custom.show {
            display: block;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-header-custom {
            padding: 12px 20px;
            border-bottom: 1px solid #f0f0f0;
        }

        .dropdown-header-custom .name {
            font-weight: 600;
            color: #0b2e33;
            font-size: 14px;
        }

        .dropdown-header-custom .email {
            font-size: 12px;
            color: #999;
        }

        .dropdown-header-custom .role-badge {
            font-size: 11px;
            color: #00d4ff;
            background: #f0f7ff;
            padding: 2px 12px;
            border-radius: 12px;
            display: inline-block;
            margin-top: 4px;
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            color: #0b2e33;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-size: 14px;
        }

        .dropdown-item-custom:hover {
            background: #f5f5f5;
        }

        .dropdown-item-custom i {
            color: #00d4ff;
            width: 20px;
        }

        .dropdown-item-custom.logout {
            color: #dc3545;
        }

        .dropdown-item-custom.logout i {
            color: #dc3545;
        }

        .dropdown-divider-custom {
            border-top: 1px solid #f0f0f0;
            margin: 5px 15px;
        }

        /* Buttons */
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
            border-radius: 8px;
        }

        .btn-logout-sharp:hover {
            background: #cc0000;
            color: white;
            box-shadow: 0 0 15px rgba(255, 77, 77, 0.4);
        }

        /* Staff Info in Dropdown */
        .staff-info-display {
            padding: 12px 20px;
            border-bottom: 1px solid #f0f0f0;
        }

        .staff-info-display .name {
            font-weight: 600;
            color: #0b2e33;
            font-size: 15px;
        }

        .staff-info-display .email {
            font-size: 12px;
            color: #999;
        }

        .staff-info-display .role {
            font-size: 11px;
            color: #00d4ff;
            background: #f0f7ff;
            padding: 2px 12px;
            border-radius: 12px;
            display: inline-block;
            margin-top: 4px;
        }

        main {
            padding: 40px 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-container {
                padding: 0 15px;
                flex-wrap: wrap;
                gap: 10px;
            }
            .staff-meta {
                gap: 10px;
            }
            .staff-details {
                display: none !important;
            }
            .btn-logout-sharp {
                padding: 8px 12px;
                font-size: 11px;
            }
            .btn-logout-sharp span {
                display: none;
            }
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
                    <span class="staff-name">{{ Auth::user()->name ?? 'Staff Member' }}</span>
                    <span class="staff-role">{{ Auth::user()->role ?? 'Operator' }}</span>
                </div>
                
                <!-- Profile Dropdown -->
                <div class="profile-dropdown">
                    <div class="profile-circle" onclick="toggleStaffDropdown()">
                        @auth
                            @if(Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                            @else
                                <span class="initials">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                            @endif
                        @else
                            <i class="bi bi-person-badge"></i>
                        @endauth
                    </div>

                    <!-- Dropdown Menu -->
                    <div class="dropdown-menu-custom" id="staffDropdown">
                        @auth
                        <div class="staff-info-display">
                            <div class="name">{{ Auth::user()->name }}</div>
                            <div class="email">{{ Auth::user()->email }}</div>
                            <div class="role"><i class="bi bi-person-badge"></i> {{ ucfirst(Auth::user()->role ?? 'Staff') }}</div>
                        </div>

                        <!-- ✅ My Profile Link -->
                        <a href="{{ route('staff.profile.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-person"></i> My Profile
                        </a>
                        
                        <a href="{{ route('staff.dashboard') }}" class="dropdown-item-custom">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        
                        <div class="dropdown-divider-custom"></div>

                        <!-- ✅ Logout Button -->
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" class="dropdown-item-custom logout">
                                <i class="bi bi-power"></i> Logout
                            </button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="dropdown-item-custom">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                        @endauth
                    </div>
                </div>

                <!-- Logout Button (Old - Replaced with dropdown) -->
                <!-- <a href="{{ url('/') }}" class="btn-logout-sharp">
                    <i class="bi bi-power"></i> Logout
                </a> -->
            </div>
        </div>
    </header>

    <main>
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    <script>
        function toggleStaffDropdown() {
            const dropdown = document.getElementById('staffDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('staffDropdown');
            const trigger = document.querySelector('.profile-circle');
            if (dropdown && trigger && !trigger.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Close dropdown on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const dropdown = document.getElementById('staffDropdown');
                if (dropdown) {
                    dropdown.classList.remove('show');
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>