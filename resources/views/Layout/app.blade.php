<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SMART QUEUE')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary-teal: #00d4ff;
            --dark-bg: #0a0a0a;
            --accent-cyan: #00d4ff;
            --nav-bg-opaque: rgba(11, 46, 51, 0.85); 
        }

        html { scroll-behavior: smooth; }
        body { background-color: #3e8686; font-family: 'Segoe UI', sans-serif; margin: 0; padding: 0; }

        /* --- FULL WIDTH STATIC NAVIGATION --- */
        .navbar-wrapper {
            padding: 25px 0;
            display: flex;
            justify-content: center;
            position: absolute; 
            width: 100%;
            top: 0;
            z-index: 1030;
        }

        .navbar {
            background: var(--nav-bg-opaque) !important; 
            backdrop-filter: blur(10px); 
            border-radius: 100px; 
            padding: 0px 0px !important;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.15);
            width: 100%;
            max-width: 95%;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
        }

        .nav-logo-img {
            height: 80px;
            width: auto;
            transition: transform 0.3s ease;
            filter: drop-shadow(0 0 5px rgba(0, 212, 255, 0.3));
        }

        .nav-logo-img:hover {
            transform: scale(1.05);
        }

        .nav-link {
            color: rgba(255,255,255,0.7) !important;
            margin: 0 12px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
            position: relative;
            padding: 12px 0 !important;
            transition: 0.3s;
            overflow: hidden; 
        }

        .nav-link.active { color: #ffffff !important; }

        .nav-link.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--accent-cyan);
            box-shadow: 0 0 10px var(--accent-cyan);
            border-radius: 10px;
            animation: slideLeftRight 2s infinite linear;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--accent-cyan);
            box-shadow: 0 0 10px var(--accent-cyan);
            border-radius: 10px;
            animation: slideRightLeft 2s infinite linear;
        }

        @keyframes slideLeftRight {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        @keyframes slideRightLeft {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        .btn-pill {
            border-radius: 50px;
            padding: 8px 22px;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            transition: 0.3s;
            border: none;
            margin-left: 8px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-book { background-color: var(--primary-teal); color: white; border: 1px solid rgba(255,255,255,0.2); }
        .btn-login { background-color: transparent; color: white; border: 1px solid rgba(255,255,255,0.3); }
        .btn-pill:hover { opacity: 0.9; transform: translateY(-2px); color: white; }

        main { padding-top: 140px; min-height: 80vh; }

        /* ============================================ */
        /* ✅ AUTH BUTTONS - RIGHT SIDE                 */
        /* ============================================ */
        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ============================================ */
        /* ✅ NOTIFICATION BELL                         */
        /* ============================================ */
        .notification-wrapper {
            position: relative;
            display: inline-block;
        }

        .notification-bell {
            cursor: pointer;
            color: #ffd700;
            font-size: 18px;
            transition: all 0.3s ease;
            position: relative;
            background: rgba(255, 215, 0, 0.1);
            padding: 8px 12px;
            border-radius: 50px;
            border: 1px solid rgba(255, 215, 0, 0.15);
            display: inline-flex;
            align-items: center;
            gap: 4px;
            text-decoration: none;
        }

        .notification-bell:hover {
            transform: scale(1.05);
            background: rgba(255, 215, 0, 0.2);
            border-color: rgba(255, 215, 0, 0.3);
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.1);
        }

        .notification-bell .bell-icon {
            font-size: 18px;
        }

        .notification-badge {
            position: absolute;
            top: -6px;
            right: -4px;
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
            border: 2px solid rgba(11, 46, 51, 0.85);
        }

        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .notification-dropdown {
            position: absolute;
            top: 45px;
            right: 0;
            width: 380px;
            max-height: 450px;
            background: var(--nav-bg-opaque);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
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
            color: #ffd700;
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
            background: #ffd700;
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
            border-left: 3px solid #ffd700;
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
            color: #ffd700;
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
            color: #ffd700;
            font-size: 12px;
            text-decoration: none;
        }

        .notification-footer a:hover {
            text-decoration: underline;
        }

        /* ============================================ */
        /* ✅ PROFILE DROPDOWN - NAV BAR COLOR          */
        /* ============================================ */
        .profile-wrapper {
            position: relative;
            display: inline-block;
            margin-left: 5px;
        }

        .profile-btn {
            cursor: pointer;
            color: #ffffff;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
            padding: 6px 12px 6px 6px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .profile-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.02);
        }

        .profile-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00d4ff, #0088cc);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name-display {
            font-size: 13px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85);
            max-width: 100px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-chevron {
            color: rgba(255, 255, 255, 0.4);
            font-size: 10px;
            transition: transform 0.3s ease;
        }

        .profile-btn:hover .profile-chevron {
            transform: rotate(180deg);
            color: rgba(255, 255, 255, 0.7);
        }

        .profile-dropdown {
            position: absolute;
            top: 45px;
            right: 0;
            width: 260px;
            background: var(--nav-bg-opaque);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow: hidden;
            display: none;
        }

        .profile-dropdown.active {
            display: block !important;
            animation: slideDown 0.3s ease;
        }

        .profile-dropdown .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-size: 14px;
            font-weight: 500;
        }

        .profile-dropdown .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }

        .profile-dropdown .dropdown-item i {
            width: 20px;
            color: rgba(255, 255, 255, 0.3);
            font-size: 16px;
        }

        .profile-dropdown .dropdown-item:hover i {
            color: #00d4ff;
        }

        .profile-dropdown .dropdown-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.05);
            margin: 4px 16px;
        }

        .profile-dropdown .dropdown-item.logout-item {
            color: #f87171;
        }

        .profile-dropdown .dropdown-item.logout-item i {
            color: #ef4444;
        }

        .profile-dropdown .dropdown-item.logout-item:hover {
            background: rgba(239, 68, 68, 0.08);
            color: #ef4444;
        }

        /* --- FOOTER --- */
        .footer-main {
           background: var(--nav-bg-opaque) !important;
            color: #FFFFFF;
            padding: 80px 0 40px 0;
            border-top: 10px solid #FFFFFF;
        }

        .footer-logo-text {
            font-size: 24px;
            font-weight: 800;
            color: #FFFFFF;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .footer-desc {
            color: rgba(255,255,255,0.5);
            font-size: 14px;
            margin-bottom: 25px;
        }

        .social-container { display: flex; align-items: center; gap: 15px; }
        .social-links a { 
            width: 35px; height: 35px;
            background: rgba(255,255,255,0.05);
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 50%; color: #FFFFFF; transition: 0.3s; text-decoration: none;
        }
        .social-links a:hover { background: var(--primary-teal); transform: translateY(-3px); }

        .footer-bottom {
            background-color: #000000;
            padding: 20px 0;
            font-size: 13px;
            color: rgba(255,255,255,0.3);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .navbar {
                max-width: 100%;
                border-radius: 0;
            }
            .navbar-wrapper {
                padding: 0;
            }
            main {
                padding-top: 80px;
            }
            .profile-name-display {
                display: none;
            }
            .auth-buttons {
                flex-wrap: wrap;
                gap: 5px;
            }
        }

        @media (max-width: 576px) {
            .btn-pill {
                padding: 6px 14px;
                font-size: 10px;
            }
            .nav-logo-img {
                height: 50px;
            }
            .notification-dropdown {
                width: 320px;
                right: -20px;
            }
            .profile-dropdown {
                width: 220px;
                right: -20px;
            }
        }
    </style>
</head>
<body>

    <div class="navbar-wrapper">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">
                    <img src="{{ asset('Assert/logo.png') }}" alt="Logo" class="nav-logo-img">
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="/services">Services</a></li>
                        <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
                    </ul>
                    
                    <div class="auth-buttons">
                        {{-- ✅ Book Now Button --}}
                        <a href="/Token_form" class="btn btn-pill btn-book">Book Now</a>
                        
                        {{-- ✅ Check if user is logged in --}}
                        @auth
                            {{-- Profile Dropdown --}}
                            <div class="profile-wrapper">
                                <div class="profile-btn" onclick="toggleProfileDropdown()">
                                    <div class="profile-avatar">
                                        @if(auth()->user()->avatar)
                                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Profile">
                                        @else
                                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                                        @endif
                                    </div>
                                    <span class="profile-name-display">{{ auth()->user()->name ?? 'User' }}</span>
                                    <i class="fas fa-chevron-down profile-chevron"></i>
                                </div>
                                
                                <div class="profile-dropdown" id="profileDropdown">
                                    {{-- My Profile - Always Visible --}}
                                    <a href="{{ route('admin.profile.index') ?? '#' }}" class="dropdown-item">
                                        <i class="fas fa-user"></i> My Profile
                                    </a>
                                    
                                    {{-- Role-based menu items --}}
                                    @if(auth()->user()->role === 'admin')
                                        <a href="/admin/doctor-management" class="dropdown-item">
                                            <i class="fas fa-chart-line"></i> Admin Dashboard
                                        </a>
                                        <a href="/staff/dashboard" class="dropdown-item">
                                            <i class="fas fa-users-cog"></i> Staff Dashboard
                                        </a>
                                        <a href="/" class="dropdown-item">
                                            <i class="fas fa-globe"></i> Website
                                        </a>
                                    @elseif(auth()->user()->role === 'staff')
                                        <a href="/staff/dashboard" class="dropdown-item">
                                            <i class="fas fa-users-cog"></i> Staff Dashboard
                                        </a>
                                        <a href="/" class="dropdown-item">
                                            <i class="fas fa-globe"></i> Website
                                        </a>
                                    @else
                                        <a href="/" class="dropdown-item">
                                            <i class="fas fa-globe"></i> Website
                                        </a>
                                    @endif
                                    
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item logout-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </div>
                            </div>

                            {{-- Notification Bell (Only Icon) --}}
                            <div class="notification-wrapper">
                                <div class="notification-bell" onclick="toggleNotifications()">
                                    <i class="bi bi-bell-fill bell-icon"></i>
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

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @else
                            {{-- ✅ Logged Out User --}}
                            <a href="{{ route('login') }}" class="btn btn-pill btn-login">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <main>
        @yield('content')
    </main>

    <footer class="footer-main">
        <div class="container">
            <div class="row g-4"> 
                <div class="col-lg-5 col-md-6">
                    <a href="/" class="footer-logo-text">
                        <img src="{{ asset('Assert/logo.png') }}" alt="Logo" style="height: 55px; width: auto;">
                        SMART QUEUE
                    </a>
                    <p class="footer-desc">
                        Efficiently managing your time with our advanced digital queuing system. 
                        Experience seamless scheduling and reduced wait times.
                    </p>
                </div>

                <div class="col-lg-2 col-md-6 offset-lg-1">
                    <h6 class="text-white mb-4">Quick Links</h6>
                    <div class="footer-links" style="display:flex; flex-direction:column; gap:10px;">
                        <a href="/" class="text-decoration-none text-white-50">Home</a>
                        <a href="/about" class="text-decoration-none text-white-50">About Us</a>
                        <a href="/services" class="text-decoration-none text-white-50">Services</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h6 class="text-white mb-4">Contact Info</h6>
                    <p class="text-white-50 small">
                        <i class="bi bi-geo-alt me-2"></i> Corporate Plaza, Gujranwala, Pakistan<br>
                        <i class="bi bi-envelope me-2"></i> support@smartqueue.com
                    </p>
                    <div class="social-container">
                        <div class="social-links">
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                            <a href="#"><i class="bi bi-twitter-x"></i></a>
                        </div>
                        <a href="/booking" class="btn btn-pill btn-book">Book Appointment</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div class="footer-bottom text-center">
        <div class="container">
            <span>© 2026 SMART QUEUE. All rights reserved.</span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    
    <script>
        // ============================================
        // TOGGLE FUNCTIONS
        // ============================================
        
        // Toggle Profile Dropdown
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            const isOpen = dropdown.classList.contains('active');
            
            // Close all dropdowns first
            closeAllDropdowns();
            
            if (!isOpen) {
                dropdown.classList.add('active');
            }
        }

        // Toggle Notifications
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            const isOpen = dropdown.classList.contains('active');
            
            // Close all dropdowns first
            closeAllDropdowns();
            
            if (!isOpen) {
                dropdown.classList.add('active');
            }
        }

        // Close all dropdowns
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
        // ACTIVE NAV LINK
        // ============================================
        document.addEventListener("DOMContentLoaded", function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
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
</body>
</html>