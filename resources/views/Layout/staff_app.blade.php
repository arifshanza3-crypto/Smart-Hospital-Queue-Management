<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <script src="{{ asset('js/notifications.js') }}"></script>
</body>
</html>