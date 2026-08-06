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

        .header-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

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

        .header-divider {
            width: 1px;
            height: 40px;
            background: linear-gradient(to bottom, transparent, rgba(255, 255, 255, 0.1), transparent);
        }

        /* Notification Bell */
        .notification-wrapper {
            position: relative;
            display: inline-block;
        }

        .notification-bell {
            position: relative;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 10px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 215, 0, 0.15);
            background: rgba(255, 215, 0, 0.04);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
        }

        .notification-bell:hover {
            background: rgba(255, 215, 0, 0.1);
            border-color: rgba(255, 215, 0, 0.3);
            transform: scale(1.05);
        }

        .notification-bell i {
            font-size: 20px;
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
            border: 2px solid var(--nav-bg);
            animation: pulse-badge 1.5s ease-in-out infinite;
        }

        .notification-badge.show {
            display: block;
        }

        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }

        /* Notification Dropdown */
        .notification-dropdown {
            display: none;
            position: absolute;
            top: 48px;
            right: 0;
            background: var(--nav-bg);
            border: 1px solid rgba(255, 215, 0, 0.15);
            border-radius: 14px;
            min-width: 380px;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow: hidden;
        }

        .notification-dropdown.active {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .notification-title {
            font-weight: 700;
            color: #e2e8f0;
            font-size: 14px;
        }

        .notification-mark-all {
            font-size: 11px;
            color: var(--gold);
            cursor: pointer;
            font-weight: 500;
            transition: color 0.3s ease;
            background: none;
            border: none;
        }

        .notification-mark-all:hover {
            color: #38bdf8;
            text-decoration: underline;
        }

        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .notification-list::-webkit-scrollbar {
            width: 3px;
        }
        .notification-list::-webkit-scrollbar-thumb {
            background: var(--gold);
            border-radius: 10px;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 18px;
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 2px solid transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background: rgba(255, 215, 0, 0.04);
        }

        .notification-item.unread {
            border-left-color: var(--gold);
            background: rgba(255, 215, 0, 0.04);
        }

        .notification-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255, 215, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            font-size: 14px;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-text {
            color: #e2e8f0;
            font-size: 13px;
            margin: 0 0 2px 0;
            line-height: 1.4;
        }

        .notification-text strong {
            color: #ffffff;
        }

        .notification-time {
            font-size: 11px;
            color: #64748b;
            display: block;
            margin-top: 2px;
        }

        .notification-footer {
            padding: 10px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            text-align: center;
        }

        .view-all-btn {
            color: var(--gold);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .view-all-btn:hover {
            color: #38bdf8;
            text-decoration: underline;
        }

        /* Profile */
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
            font-weight: 600;
        }

        .profile-circle:hover {
            background: rgba(0, 212, 255, 0.15);
            transform: scale(1.05);
            border-color: var(--accent-cyan);
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

        /* Toast */
        .toast-message {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 600;
            z-index: 99999;
            animation: slideUp 0.4s ease;
            max-width: 400px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            font-size: 14px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .toast-success {
            background: #10b981;
            color: #fff;
        }

        .toast-error {
            background: #ef4444;
            color: #fff;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        main {
            padding: 30px 35px;
            min-height: calc(100vh - 70px);
        }

        @media (max-width: 992px) {
            .header-container { padding: 10px 20px; }
            .brand-subtitle { display: none; }
        }

        @media (max-width: 768px) {
            .header-container { padding: 8px 15px; flex-wrap: wrap; gap: 8px; }
            .staff-details { display: none !important; }
            .header-divider { display: none; }
            .brand-title { font-size: 15px; }
            .nav-logo-img { height: 45px; }
            .notification-bell { padding: 6px 10px; }
            .notification-bell i { font-size: 17px; }
            .profile-circle { width: 38px; height: 38px; font-size: 15px; }
            main { padding: 20px 15px; }
            .notification-dropdown { width: 320px; right: -10px; }
            .profile-dropdown { min-width: 210px; right: -10px; }
        }

        @media (max-width: 576px) {
            .header-container { padding: 6px 12px; }
            .brand-title { font-size: 13px; }
            .nav-logo-img { height: 38px; }
            .header-right { gap: 10px; }
            .notification-bell { padding: 5px 8px; }
            .notification-bell i { font-size: 15px; }
            .profile-circle { width: 34px; height: 34px; font-size: 13px; }
            .notification-dropdown { width: 290px; right: -5px; }
            .profile-dropdown { min-width: 190px; right: -5px; }
        }
    </style>
</head>
<body>

    <header class="staff-header">
        <div class="header-container">
            <div class="header-left">
                <a href="/staff" style="text-decoration: none; display: flex; align-items: center; gap: 12px;">
                    <img src="{{ asset('Assert/logo.png') }}" alt="Logo" class="nav-logo-img">
                    <div class="brand-text">
                        <span class="brand-title">SMART <span>QUEUE</span></span>
                        <span class="brand-subtitle">Staff Panel</span>
                    </div>
                </a>
            </div>

            <div class="header-right">
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
                    <div class="notification-bell" onclick="toggleNotifications(event)">
                        <i class="bi bi-bell-fill"></i>
                        <span class="notification-badge" id="notificationBadge">0</span>
                    </div>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <span class="notification-title">Notifications</span>
                            <button class="notification-mark-all" onclick="markAllNotificationsRead()">Mark all read</button>
                        </div>
                        <div class="notification-list" id="notificationList">
                            <div style="text-align: center; padding: 30px 20px; color: #94a3b8; font-size: 14px;">
                                <div style="font-size: 30px; margin-bottom: 8px;">🔕</div>
                                Loading...
                            </div>
                        </div>
                        <div class="notification-footer">
                            <a href="{{ route('notifications.page') }}" class="view-all-btn">View All Notifications</a>
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

                    <div class="profile-dropdown" id="profileDropdown">
                        @auth
                        <div class="dropdown-header-custom">
                            <div class="name">{{ Auth::user()->name ?? 'Staff' }}</div>
                            <div class="email">{{ Auth::user()->email ?? 'staff@example.com' }}</div>
                            <div class="role-badge"><i class="bi bi-person-badge"></i> {{ ucfirst(Auth::user()->role ?? 'Staff') }}</div>
                        </div>

                        <a href="{{ route('staff.profile.index') }}" class="dropdown-item-custom">
                            <i class="bi bi-person"></i> My Profile
                        </a>

                        @if(Auth::user()->role === 'admin')
                            <a href="/admin/doctor-management" class="dropdown-item-custom">
                                <i class="bi bi-grid"></i> Admin Panel
                            </a>
                        @endif

                        <a href="/" class="dropdown-item-custom">
                            <i class="bi bi-globe"></i> My Website
                        </a>
                        
                        <div class="dropdown-divider-custom"></div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- ✅ FAST NOTIFICATION SYSTEM -->
    <script>
        // ============================================ //
        // TOGGLE FUNCTIONS                             //
        // ============================================ //

        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            const isOpen = dropdown.classList.contains('active');
            closeAllDropdowns();
            if (!isOpen) {
                dropdown.classList.add('active');
            }
        }

        function toggleNotifications(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            const dropdown = document.getElementById('notificationDropdown');
            const isOpen = dropdown.classList.contains('active');
            closeAllDropdowns();
            if (!isOpen) {
                dropdown.classList.add('active');
                if (typeof fetchNotifications === 'function') {
                    fetchNotifications();
                }
            }
        }

        function closeAllDropdowns() {
            document.querySelectorAll('.profile-dropdown, .notification-dropdown').forEach(el => {
                el.classList.remove('active');
            });
        }

        document.addEventListener('click', function(event) {
            const isClickInside = event.target.closest('.profile-wrapper') || 
                                 event.target.closest('.notification-wrapper') ||
                                 event.target.closest('.profile-dropdown') ||
                                 event.target.closest('.notification-dropdown');
            if (!isClickInside) {
                closeAllDropdowns();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAllDropdowns();
            }
        });

        // ============================================ //
        // NOTIFICATION FUNCTIONS                       //
        // ============================================ //

        let previousBadgeCount = 0;
        let isFetching = false;

        function fetchNotifications() {
            if (isFetching) return;
            isFetching = true;

            const list = document.getElementById('notificationList');
            if (!list) {
                isFetching = false;
                return;
            }

            fetch('/notifications', {
                headers: { 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderDropdownNotifications(data.notifications);
                    const unreadCount = data.unread_count || 0;
                    updateBadgeCount(unreadCount);
                    
                    // ✅ Play sound if new notification
                    if (unreadCount > previousBadgeCount && unreadCount > 0) {
                        playNotificationSound();
                    }
                    previousBadgeCount = unreadCount;
                }
                isFetching = false;
            })
            .catch(error => {
                console.error('Error fetching notifications:', error);
                isFetching = false;
            });
        }

        function renderDropdownNotifications(notifications) {
            const list = document.getElementById('notificationList');
            if (!list) return;

            if (!notifications || notifications.length === 0) {
                list.innerHTML = `
                    <div style="text-align: center; padding: 30px 20px; color: #94a3b8; font-size: 14px;">
                        <div style="font-size: 30px; margin-bottom: 8px;">🔕</div>
                        No notifications
                    </div>
                `;
                return;
            }

            let html = '';
            const latest = notifications.slice(0, 5);

            latest.forEach(notification => {
                const unreadClass = notification.is_read ? '' : 'unread';
                const time = new Date(notification.created_at);
                const timeStr = time.toLocaleString();
                let icon = getNotificationIcon(notification.type);

                html += `
                    <div class="notification-item ${unreadClass}" onclick="markNotificationAsRead(${notification.id})">
                        <div class="notification-icon">
                            <i class="bi ${icon}"></i>
                        </div>
                        <div class="notification-content">
                            <p class="notification-text"><strong>${notification.title}</strong></p>
                            <p class="notification-text" style="font-size: 12px; color: #94a3b8;">${notification.message}</p>
                            <span class="notification-time">${timeStr}</span>
                        </div>
                        ${!notification.is_read ? '<div style="color: #ffd700; font-size: 8px;">●</div>' : ''}
                    </div>
                `;
            });

            list.innerHTML = html;
        }

        function getNotificationIcon(type) {
            const icons = {
                'token_generated': 'bi-ticket',
                'token_called': 'bi-telephone',
                'token_arrived': 'bi-check-circle',
                'token_completed': 'bi-check2-all',
                'token_cancelled': 'bi-x-circle',
                'physical_patient_added': 'bi-person-plus',
                'staff_registered': 'bi-person-check',
                'staff_approved': 'bi-person-check',
                'staff_rejected': 'bi-person-x',
                'account_approved': 'bi-check-circle',
                'system_alert': 'bi-exclamation-triangle',
                'doctor_added': 'bi-person-badge',
                'doctor_updated': 'bi-pencil-square',
                'doctor_deleted': 'bi-trash',
                'service_added': 'bi-plus-circle',
                'service_updated': 'bi-pencil-square',
                'service_deleted': 'bi-trash'
            };
            return icons[type] || 'bi-bell';
        }

        function markNotificationAsRead(id) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fetchNotifications();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function markAllNotificationsRead() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fetchNotifications();
                    updateBadgeCount(0);
                    previousBadgeCount = 0;
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // ============================================ //
        // UPDATE BADGE COUNT                           //
        // ============================================ //

        function updateBadgeCount(count) {
            const badge = document.getElementById('notificationBadge');
            if (badge) {
                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }
            }
        }

        // ============================================ //
        // SOUND                                         //
        // ============================================ //

        let notificationAudio = null;

        function initAudio() {
            try {
                notificationAudio = new Audio('/Notification%20Sound/notification.wav');
                notificationAudio.volume = 0.8;
                notificationAudio.preload = 'auto';
                
                notificationAudio.onerror = function() {
                    console.warn('⚠️ Sound file not found');
                    notificationAudio = null;
                };
                
                notificationAudio.oncanplaythrough = function() {
                    console.log('✅ Notification sound loaded!');
                };
            } catch (error) {
                console.warn('Audio init failed:', error);
                notificationAudio = null;
            }
        }

        function playNotificationSound() {
            if (notificationAudio) {
                try {
                    notificationAudio.currentTime = 0;
                    notificationAudio.play()
                        .then(() => console.log('🔊 Notification sound played!'))
                        .catch(() => playFallbackSound());
                } catch (error) {
                    playFallbackSound();
                }
            } else {
                playFallbackSound();
            }
        }

        function playFallbackSound() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.frequency.value = 880;
                osc.type = 'sine';
                gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.3);
                osc.start(audioCtx.currentTime);
                osc.stop(audioCtx.currentTime + 0.3);
                console.log('🔊 Fallback sound played!');
            } catch (error) {
                console.warn('Fallback sound failed:', error);
            }
        }

        // ============================================ //
        // ✅ FAST - CHECK EVERY 1 SECOND              //
        // ============================================ //

        function checkNewNotifications() {
            fetch('/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const currentCount = data.count;
                        
                        // ✅ Update badge immediately
                        updateBadgeCount(currentCount);
                        
                        // ✅ Play sound if new notification
                        if (currentCount > previousBadgeCount && currentCount > 0) {
                            playNotificationSound();
                        }
                        previousBadgeCount = currentCount;
                    }
                })
                .catch(error => console.warn('Error checking notifications:', error));
        }

        // ============================================ //
        // INIT                                        //
        // ============================================ //

        document.addEventListener('DOMContentLoaded', function() {
            console.log('✅ Fast notification system initialized');

            initAudio();

            // ✅ Check immediately
            setTimeout(() => {
                checkNewNotifications();
                fetchNotifications();
            }, 100);

            // ✅ Check every 1 second (FAST)
            setInterval(checkNewNotifications, 1000);
            
            // ✅ Refresh dropdown every 5 seconds
            setInterval(fetchNotifications, 5000);
        });

        // ============================================ //
        // EXPOSE TO GLOBAL SCOPE                     //
        // ============================================ //

        window.toggleNotifications = toggleNotifications;
        window.markNotificationAsRead = markNotificationAsRead;
        window.markAllNotificationsRead = markAllNotificationsRead;
        window.fetchNotifications = fetchNotifications;
        window.checkNewNotifications = checkNewNotifications;
    </script>
</body>
</html>