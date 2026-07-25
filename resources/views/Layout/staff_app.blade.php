<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

<<<<<<< HEAD
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
=======
        /* ============================================ */
        /* ✅ NOTIFICATION BELL STYLES - YELLOW COLOR   */
        /* ============================================ */
        .notification-wrapper {
            position: relative;
            display: inline-block;
        }

        /* ✅ YELLOW BELL */
        .notification-bell {
            cursor: pointer;
            color: #ffd700;  /* ✅ Yellow color */
            font-size: 22px;
            transition: all 0.3s ease;
            position: relative;
            background: rgba(255, 215, 0, 0.08);
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid rgba(255, 215, 0, 0.15);
        }

        .notification-bell:hover {
            transform: scale(1.05);
            background: rgba(255, 215, 0, 0.15);
        }

        .notification-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #dc3545;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 7px;
            border-radius: 50%;
            min-width: 18px;
            text-align: center;
            display: none;
            animation: pulse-badge 1.5s ease-in-out infinite;
            border: 2px solid #0b2e33;
        }

        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .notification-dropdown {
            position: absolute;
            top: 50px;
            right: 0;
            width: 380px;
            max-height: 450px;
            background: #1a1a2e;
            border: 1px solid rgba(255, 215, 0, 0.2);
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow: hidden;
            display: none;
        }

        .notification-dropdown.active {
            display: block !important;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .notification-header {
            padding: 12px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h4 {
            margin: 0;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
        }

        .notification-header .mark-all {
            color: #ffd700;  /* ✅ Yellow color */
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
        }

        .notification-header .mark-all:hover {
            text-decoration: underline;
        }

        .notification-list {
            max-height: 350px;
            overflow-y: auto;
            padding: 0;
        }

        .notification-list::-webkit-scrollbar {
            width: 4px;
        }

        .notification-list::-webkit-scrollbar-thumb {
            background: #ffd700;  /* ✅ Yellow color */
            border-radius: 10px;
        }

        .notification-item {
            padding: 10px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .notification-item:hover {
            background: rgba(255, 215, 0, 0.05);
        }

        .notification-item.unread {
            background: rgba(255, 215, 0, 0.05);
            border-left: 3px solid #ffd700;  /* ✅ Yellow border */
        }

        .notification-item .notification-icon {
            font-size: 18px;
            min-width: 30px;
        }

        .notification-item .notification-content {
            flex: 1;
        }

        .notification-item .notification-title {
            color: #fff;
            font-weight: 600;
            font-size: 13px;
        }

        .notification-item .notification-message {
            color: rgba(255, 255, 255, 0.6);
            font-size: 12px;
            margin-top: 2px;
        }

        .notification-item .notification-token {
            color: #ffd700;  /* ✅ Yellow color */
            font-size: 11px;
            font-weight: 500;
            margin-top: 2px;
        }

        .notification-item .notification-time {
            color: rgba(255, 255, 255, 0.3);
            font-size: 10px;
            margin-top: 3px;
        }

        .notification-empty {
            padding: 30px 20px;
            text-align: center;
            color: rgba(255, 255, 255, 0.3);
        }

        .notification-empty .icon {
            font-size: 35px;
            margin-bottom: 8px;
        }

        .notification-footer {
            padding: 8px 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            text-align: center;
        }

        .notification-footer a {
            color: #ffd700;  /* ✅ Yellow color */
            font-size: 12px;
            text-decoration: none;
        }

        .notification-footer a:hover {
            text-decoration: underline;
>>>>>>> 6b3e1247f30e0d61f40e6ce48d469327b3ab9296
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
                
<<<<<<< HEAD
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
=======
                {{-- ✅ Notification Bell - YELLOW --}}
                <div class="notification-wrapper">
                    <div class="notification-bell" onclick="toggleNotifications()">
                        <i class="bi bi-bell-fill"></i>
                        <span class="notification-badge" id="notificationBadge">0</span>
                    </div>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h4>Notifications</h4>
                            <a href="#" class="mark-all" onclick="markAllRead()">Mark all as read</a>
                        </div>
                        <div class="notification-list" id="notificationList">
                            <div class="notification-empty">
                                <div class="icon">🔕</div>
                                <p>No notifications</p>
                            </div>
                        </div>
                        <div class="notification-footer">
                            <a href="{{ route('notifications.page') }}">View all notifications →</a>
                        </div>
                    </div>
                </div>

                <div class="profile-circle">
                    <i class="bi bi-person-badge"></i>
>>>>>>> 6b3e1247f30e0d61f40e6ce48d469327b3ab9296
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
    <script src="{{ asset('js/notifications.js') }}"></script>
</body>
</html>