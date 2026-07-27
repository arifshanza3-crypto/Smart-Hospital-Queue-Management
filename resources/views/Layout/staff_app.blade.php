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
            --gold: #ffd700;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            background-color: #f4f6f9; 
            font-family: 'Poppins', 'Segoe UI', sans-serif; 
            margin: 0; 
            padding: 0; 
        }

        /* ============================================ */
        /* STAFF HEADER - ADMIN STYLE */
        /* ============================================ */
        .staff-header {
            background: var(--nav-bg);
            border-bottom: 2px solid rgba(0, 212, 255, 0.15);
            padding: 0;
            position: sticky; 
            top: 0;
            width: 100%;
            z-index: 1030;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.4);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 35px;
            max-width: 100%;
            margin: 0 auto;
        }

        /* ============================================ */
        /* LEFT SECTION - LOGO */
        /* ============================================ */
        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-logo-img {
            height: 55px;
            width: auto;
            filter: drop-shadow(0 0 15px rgba(0, 212, 255, 0.2));
            transition: transform 0.3s ease;
        }

        .nav-logo-img:hover {
            transform: scale(1.05);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .brand-title {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        .brand-title span {
            color: var(--accent-cyan);
        }

        .brand-subtitle {
            font-size: 9px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.4);
            letter-spacing: 2px;
        }

        /* ============================================ */
        /* RIGHT SECTION - STAFF META */
        /* ============================================ */
        .header-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        /* Staff Details */
        .staff-details {
            text-align: right;
            color: white;
            line-height: 1.3;
            padding-right: 5px;
        }

        .staff-name {
            font-size: 14px;
            font-weight: 600;
            display: block;
            color: #ffffff;
        }

        .staff-role {
            font-size: 10px;
            color: var(--accent-cyan);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 500;
        }

        .staff-id {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.3);
            letter-spacing: 0.5px;
        }

        /* ============================================ */
        /* DIVIDER */
        /* ============================================ */
        .header-divider {
            width: 1px;
            height: 40px;
            background: linear-gradient(to bottom, transparent, rgba(255, 255, 255, 0.1), transparent);
        }

        /* ============================================ */
        /* NOTIFICATION BELL - GOLD */
        /* ============================================ */
        .notification-wrapper {
            position: relative;
            display: inline-block;
        }

        .notification-bell {
            cursor: pointer;
            color: var(--gold);
            font-size: 20px;
            transition: all 0.3s ease;
            position: relative;
            background: rgba(255, 215, 0, 0.06);
            padding: 9px 12px;
            border-radius: 10px;
            border: 1px solid rgba(255, 215, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification-bell:hover {
            transform: scale(1.05);
            background: rgba(255, 215, 0, 0.12);
            border-color: rgba(255, 215, 0, 0.2);
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.05);
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: white;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 50%;
            min-width: 18px;
            text-align: center;
            display: none;
            animation: pulse-badge 1.5s ease-in-out infinite;
            border: 2px solid var(--nav-bg);
        }

        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }

        /* ============================================ */
        /* NOTIFICATION DROPDOWN */
        /* ============================================ */
        .notification-dropdown {
            position: absolute;
            top: 52px;
            right: 0;
            width: 380px;
            max-height: 460px;
            background: var(--nav-bg);
            border: 1px solid rgba(255, 215, 0, 0.15);
            border-radius: 14px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            z-index: 1000;
            overflow: hidden;
            display: none;
        }

        .notification-dropdown.active {
            display: block !important;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .notification-header {
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h4 {
            margin: 0;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
        }

        .notification-header .mark-all {
            color: var(--gold);
            font-size: 11px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
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
            width: 3px;
        }

        .notification-list::-webkit-scrollbar-thumb {
            background: var(--gold);
            border-radius: 10px;
        }

        .notification-item {
            padding: 12px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .notification-item:hover {
            background: rgba(255, 215, 0, 0.04);
        }

        .notification-item.unread {
            background: rgba(255, 215, 0, 0.04);
            border-left: 3px solid var(--gold);
        }

        .notification-item .notification-icon {
            font-size: 16px;
            min-width: 30px;
            color: var(--gold);
        }

        .notification-item .notification-content {
            flex: 1;
        }

        .notification-item .notification-title {
            color: #fff;
            font-weight: 500;
            font-size: 13px;
        }

        .notification-item .notification-message {
            color: rgba(255, 255, 255, 0.5);
            font-size: 12px;
            margin-top: 2px;
        }

        .notification-item .notification-time {
            color: rgba(255, 255, 255, 0.25);
            font-size: 10px;
            margin-top: 3px;
        }

        .notification-empty {
            padding: 35px 20px;
            text-align: center;
            color: rgba(255, 255, 255, 0.3);
        }

        .notification-empty .icon {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .notification-empty p {
            font-size: 13px;
            margin: 0;
        }

        .notification-footer {
            padding: 10px 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            text-align: center;
        }

        .notification-footer a {
            color: var(--gold);
            font-size: 12px;
            text-decoration: none;
            font-weight: 500;
        }

        .notification-footer a:hover {
            text-decoration: underline;
        }

        /* ============================================ */
        /* PROFILE CIRCLE & DROPDOWN */
        /* ============================================ */
        .profile-wrapper {
            position: relative;
            display: inline-block;
        }

        .profile-circle {
            width: 44px;
            height: 44px;
            background: rgba(0, 212, 255, 0.08);
            border: 2px solid rgba(0, 212, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-cyan);
            font-size: 18px;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
            font-weight: 600;
        }

        .profile-circle:hover {
            background: rgba(0, 212, 255, 0.15);
            transform: scale(1.05);
            border-color: var(--accent-cyan);
            box-shadow: 0 0 30px rgba(0, 212, 255, 0.1);
        }

        .profile-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-circle .initials {
            font-size: 16px;
            font-weight: 600;
            color: var(--accent-cyan);
        }

        /* Profile Dropdown */
        .profile-dropdown {
            position: absolute;
            right: 0;
            top: 52px;
            background: var(--nav-bg);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px;
            min-width: 240px;
            padding: 8px 0;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            z-index: 1000;
            display: none;
            overflow: hidden;
        }

        .profile-dropdown.active {
            display: block;
            animation: slideDown 0.3s ease;
        }

        .dropdown-header-custom {
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .dropdown-header-custom .name {
            font-weight: 600;
            color: #ffffff;
            font-size: 14px;
        }

        .dropdown-header-custom .email {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
        }

        .dropdown-header-custom .role-badge {
            font-size: 10px;
            color: var(--accent-cyan);
            background: rgba(0, 212, 255, 0.08);
            padding: 2px 12px;
            border-radius: 12px;
            display: inline-block;
            margin-top: 4px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
        }

        .dropdown-item-custom:hover {
            background: rgba(255, 255, 255, 0.04);
            color: #ffffff;
        }

        .dropdown-item-custom i {
            color: rgba(255, 255, 255, 0.3);
            width: 20px;
            font-size: 15px;
        }

        .dropdown-item-custom:hover i {
            color: var(--accent-cyan);
        }

        .dropdown-item-custom.logout {
            color: #f87171;
        }

        .dropdown-item-custom.logout i {
            color: #ef4444;
        }

        .dropdown-item-custom.logout:hover {
            background: rgba(239, 68, 68, 0.06);
            color: #ef4444;
        }

        .dropdown-divider-custom {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            margin: 4px 15px;
        }

        /* ============================================ */
        /* MAIN CONTENT */
        /* ============================================ */
        main {
            padding: 30px 35px;
            min-height: calc(100vh - 70px);
        }

        /* ============================================ */
        /* RESPONSIVE */
        /* ============================================ */
        @media (max-width: 992px) {
            .header-container {
                padding: 10px 20px;
            }
            .brand-subtitle {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .header-container {
                padding: 8px 15px;
                flex-wrap: wrap;
                gap: 8px;
            }
            
            .staff-details {
                display: none !important;
            }
            
            .header-divider {
                display: none;
            }
            
            .brand-title {
                font-size: 15px;
            }
            
            .nav-logo-img {
                height: 45px;
            }
            
            .notification-bell {
                padding: 6px 10px;
                font-size: 17px;
            }
            
            .profile-circle {
                width: 38px;
                height: 38px;
                font-size: 15px;
            }
            
            main {
                padding: 20px 15px;
            }
            
            .notification-dropdown {
                width: 320px;
                right: -10px;
            }
            
            .profile-dropdown {
                min-width: 210px;
                right: -10px;
            }
        }

        @media (max-width: 576px) {
            .header-container {
                padding: 6px 12px;
            }
            
            .brand-title {
                font-size: 13px;
            }
            
            .nav-logo-img {
                height: 38px;
            }
            
            .header-right {
                gap: 10px;
            }
            
            .notification-bell {
                padding: 5px 8px;
                font-size: 15px;
            }
            
            .profile-circle {
                width: 34px;
                height: 34px;
                font-size: 13px;
            }
            
            .notification-dropdown {
                width: 290px;
                right: -5px;
            }
            
            .profile-dropdown {
                min-width: 190px;
                right: -5px;
            }
        }
    </style>
</head>
<body>

    <header class="staff-header">
        <div class="header-container">
            <!-- Left Section -->
            <div class="header-left">
                <a href="/staff" style="text-decoration: none; display: flex; align-items: center; gap: 12px;">
                    <img src="{{ asset('Assert/logo.png') }}" alt="Logo" class="nav-logo-img">
                    <div class="brand-text">
                        <span class="brand-title">SMART <span>QUEUE</span></span>
                        <span class="brand-subtitle">Staff Panel</span>
                    </div>
                </a>
            </div>

            <!-- Right Section -->
            <div class="header-right">
                <!-- Staff Details -->
                <div class="staff-details">
                    <span class="staff-name">{{ Auth::user()->name ?? 'Staff Member' }}</span>
                    <span class="staff-role">{{ ucfirst(Auth::user()->role ?? 'Operator') }}</span>
                    @if(Auth::user()->employee_id)
                        <span class="staff-id">ID: {{ Auth::user()->employee_id }}</span>
                    @endif
                </div>

                <div class="header-divider"></div>

                <!-- Notification Bell -->
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

                <!-- Profile -->
                <div class="profile-wrapper">
                    <div class="profile-circle" onclick="toggleProfileDropdown()">
                        @auth
                            @if(Auth::user()->avatar)
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                            @else
                                <span class="initials">{{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 2)) }}</span>
                            @endif
                        @else
                            <i class="bi bi-person-badge"></i>
                        @endauth
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="profile-dropdown" id="profileDropdown">
                        @auth
                        <div class="dropdown-header-custom">
                            <div class="name">{{ Auth::user()->name ?? 'Staff' }}</div>
                            <div class="email">{{ Auth::user()->email ?? 'staff@example.com' }}</div>
                            <div class="role-badge"><i class="bi bi-person-badge"></i> {{ ucfirst(Auth::user()->role ?? 'Staff') }}</div>
                        </div>

                        <!-- My Profile - Always Visible -->
                        <a href="{{ route('staff.profile.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-person"></i> My Profile
                        </a>

                        <!-- ✅ Admin Panel - ONLY SHOW IF USER IS ADMIN -->
                        @if(Auth::user()->role === 'admin')
                            <a href="/admin/doctor-management" class="dropdown-item-custom">
                                <i class="bi bi-grid"></i> Admin Panel
                            </a>
                        @endif

                        <!-- My Website - Always Visible -->
                        <a href="/" class="dropdown-item-custom">
                            <i class="bi bi-globe"></i> My Website
                        </a>
                        
                        <div class="dropdown-divider-custom"></div>

                        <!-- Logout -->
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
            </div>
        </div>
    </header>

    <main>
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    <script>
        // ============================================
        // TOGGLE PROFILE DROPDOWN
        // ============================================
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            const isOpen = dropdown.classList.contains('active');
            
            // Close all dropdowns first
            closeAllDropdowns();
            
            if (!isOpen) {
                dropdown.classList.add('active');
            }
        }

        // ============================================
        // TOGGLE NOTIFICATIONS
        // ============================================
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            const isOpen = dropdown.classList.contains('active');
            
            // Close all dropdowns first
            closeAllDropdowns();
            
            if (!isOpen) {
                dropdown.classList.add('active');
            }
        }

        // ============================================
        // CLOSE ALL DROPDOWNS
        // ============================================
        function closeAllDropdowns() {
            document.querySelectorAll('.profile-dropdown, .notification-dropdown').forEach(el => {
                el.classList.remove('active');
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInside = event.target.closest('.profile-wrapper') || 
                                 event.target.closest('.notification-wrapper') ||
                                 event.target.closest('.profile-dropdown') ||
                                 event.target.closest('.notification-dropdown');
            
            if (!isClickInside) {
                closeAllDropdowns();
            }
        });

        // Close dropdowns on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAllDropdowns();
            }
        });

        // ============================================
        // NOTIFICATION MARK ALL READ
        // ============================================
        function markAllRead() {
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
            });
            const badge = document.getElementById('notificationBadge');
            if (badge) {
                badge.textContent = '0';
                badge.style.display = 'none';
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
</body>
</html>