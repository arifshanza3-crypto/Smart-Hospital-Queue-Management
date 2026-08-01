<nav class="admin-nav">
    <div class="nav-left">
        <button class="mobile-toggle" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="nav-title">
            <span class="system-label">
                <i class="fas fa-arrow-trend-up"></i> 
                @auth
                    @if(auth()->user()->role === 'admin')
                        Admin Panel
                    @elseif(auth()->user()->role === 'staff')
                        Staff Panel
                    @else
                        Dashboard
                    @endif
                @else
                    Dashboard
                @endauth
            </span>
            <h1 class="dashboard-title">
                @auth
                    @if(auth()->user()->role === 'admin')
                        Admin Dashboard
                    @elseif(auth()->user()->role === 'staff')
                        Staff Dashboard
                    @else
                        Dashboard
                    @endif
                @else
                    Dashboard
                @endauth
            </h1>
        </div>
    </div>

    <div class="nav-right">
        <!-- Notification Bell -->
        <div class="notification-bell" onclick="toggleNotifications()" id="notificationBell">
            <i class="fas fa-bell"></i>
            <span class="notification-badge" id="notificationBadge">3</span>
        </div>

        <!-- Notification Dropdown -->
        <div class="notification-dropdown" id="notificationDropdown">
            <div class="notification-header">
                <span class="notification-title">Notifications</span>
                <span class="notification-mark-all">Mark all read</span>
            </div>
            <div class="notification-list">
                <div class="notification-item unread">
                    <div class="notification-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="notification-content">
                        <p class="notification-text">New user registered</p>
                        <span class="notification-time">5 min ago</span>
                    </div>
                </div>
                <div class="notification-item unread">
                    <div class="notification-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="notification-content">
                        <p class="notification-text">Queue is getting longer</p>
                        <span class="notification-time">15 min ago</span>
                    </div>
                </div>
                <div class="notification-item">
                    <div class="notification-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="notification-content">
                        <p class="notification-text">Service completed</p>
                        <span class="notification-time">1 hour ago</span>
                    </div>
                </div>
            </div>
            <div class="notification-footer">
                <a href="{{ route('notifications.page') }}" class="view-all-btn">View All Notifications</a>
            </div>
        </div>

        <!-- Profile Section -->
        <div class="admin-profile" style="position: relative;">
            <div class="profile-wrapper" onclick="toggleProfileDropdown()">
                <div class="profile-icon">
                    @auth
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Profile">
                        @else
                            {{ strtoupper(substr(auth()->user()->name ?? 'User', 0, 2)) }}
                        @endif
                    @else
                        U
                    @endauth
                </div>
                <div class="profile-info">
                    <span class="profile-name">{{ auth()->user()->name ?? 'User' }}</span>
                    <span class="profile-role">{{ ucfirst(auth()->user()->role ?? 'User') }}</span>
                </div>
                <i class="fas fa-chevron-down profile-arrow"></i>
            </div>

            <!-- Profile Dropdown -->
            <div class="profile-dropdown" id="profileDropdown">
                <div class="dropdown-header">
                    <div class="dropdown-avatar">
                        @auth
                            @if(auth()->user()->avatar)
                                <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Profile">
                            @else
                                {{ strtoupper(substr(auth()->user()->name ?? 'User', 0, 2)) }}
                            @endif
                        @else
                            U
                        @endauth
                    </div>
                    <div class="dropdown-user-info">
                        <div class="dropdown-name">{{ auth()->user()->name ?? 'User' }}</div>
                        <div class="dropdown-email">{{ auth()->user()->email ?? 'user@example.com' }}</div>
                        <div class="dropdown-role">
                            <i class="fas fa-user-shield"></i>
                            {{ ucfirst(auth()->user()->role ?? 'User') }}
                        </div>
                    </div>
                </div>

                <div class="dropdown-divider"></div>

                @auth
                    <a href="{{ route('profile.index') }}" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        <span>My Profile</span>
                        <i class="fas fa-chevron-right item-arrow"></i>
                    </a>
                @endauth

                @auth
                    @if(in_array(auth()->user()->role, ['admin', 'staff']))
                        <a href="{{ route('staff.dashboard') }}" class="dropdown-item">
                            <i class="fas fa-users-cog"></i>
                            <span>Staff Dashboard</span>
                            <i class="fas fa-chevron-right item-arrow"></i>
                        </a>
                    @endif
                @endauth

                <a href="{{ route('home') }}" class="dropdown-item">
                    <i class="fas fa-globe"></i>
                    <span>My Website</span>
                    <i class="fas fa-chevron-right item-arrow"></i>
                </a>

                <div class="dropdown-divider"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item logout-item">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                        <i class="fas fa-chevron-right item-arrow"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    /* ============================================
       NAVBAR - EXACT SAME COLORS AS SIDEBAR
       ============================================ */
    
    .admin-nav {
        position: fixed;
        top: 0;
        left: var(--sidebar-width, 280px);
        right: 0;
        height: 68px;
        background: linear-gradient(180deg, #0c1220 0%, #0f1a2e 50%, #142a42 100%);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 28px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        z-index: 999;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .admin-nav::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(ellipse at 30% 20%, rgba(14, 165, 233, 0.05) 0%, transparent 60%);
        pointer-events: none;
        animation: glow-float 20s ease-in-out infinite;
    }

    .admin-nav::after {
        content: '';
        position: absolute;
        bottom: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(ellipse at 70% 80%, rgba(6, 182, 212, 0.05) 0%, transparent 60%);
        pointer-events: none;
        animation: glow-float 25s ease-in-out infinite reverse;
    }

    @keyframes glow-float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(10%, -10%) scale(1.1); }
        66% { transform: translate(-10%, 10%) scale(0.9); }
    }

    .nav-left {
        display: flex;
        align-items: center;
        gap: 16px;
        position: relative;
        z-index: 1;
    }

    .mobile-toggle {
        display: none;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.06);
        color: #94a3b8;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
        align-items: center;
        justify-content: center;
    }

    .mobile-toggle:hover {
        background: rgba(14, 165, 233, 0.08);
        border-color: rgba(14, 165, 233, 0.15);
        color: #0ea5e9;
    }

    .nav-title {
        display: flex;
        flex-direction: column;
    }

    .system-label {
        font-size: 10px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .system-label i {
        color: #0ea5e9;
        font-size: 10px;
    }

    .dashboard-title {
        font-size: 20px;
        font-weight: 800;
        color: #ffffff;
        margin: 0;
        line-height: 1.2;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, #ffffff 0%, #93c5fd 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .nav-right {
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 1;
    }

    /* ===== NOTIFICATION BELL ===== */
    .notification-bell {
        position: relative;
        color: #94a3b8;
        cursor: pointer;
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .notification-bell:hover {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(255, 255, 255, 0.06);
        color: #e2e8f0;
    }

    .notification-bell i {
        font-size: 20px;
    }

    .notification-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        background: #ef4444;
        color: white;
        font-size: 9px;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 10px;
        border: 2px solid #0f1a2e;
        min-width: 18px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    /* ===== NOTIFICATION DROPDOWN ===== */
    .notification-dropdown {
        display: none;
        position: absolute;
        top: 58px;
        right: 70px;
        background: linear-gradient(180deg, #0c1220 0%, #0f1a2e 50%, #142a42 100%);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 16px;
        min-width: 360px;
        max-width: 400px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        z-index: 1000;
        overflow: hidden;
    }

    .notification-dropdown.show {
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
        padding: 16px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .notification-title {
        font-weight: 700;
        color: #e2e8f0;
        font-size: 14px;
    }

    .notification-mark-all {
        font-size: 11px;
        color: #0ea5e9;
        cursor: pointer;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .notification-mark-all:hover {
        color: #38bdf8;
    }

    .notification-list {
        max-height: 320px;
        overflow-y: auto;
    }

    .notification-list::-webkit-scrollbar {
        width: 3px;
    }
    .notification-list::-webkit-scrollbar-thumb {
        background: #0ea5e9;
        border-radius: 10px;
    }

    .notification-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 20px;
        transition: all 0.3s ease;
        cursor: pointer;
        border-left: 2px solid transparent;
    }

    .notification-item:hover {
        background: rgba(255, 255, 255, 0.02);
    }

    .notification-item.unread {
        border-left-color: #0ea5e9;
        background: rgba(14, 165, 233, 0.03);
    }

    .notification-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(14, 165, 233, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0ea5e9;
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

    .notification-time {
        font-size: 11px;
        color: #64748b;
    }

    .notification-footer {
        padding: 12px 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        text-align: center;
    }

    .view-all-btn {
        color: #0ea5e9;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .view-all-btn:hover {
        color: #38bdf8;
    }

    /* ===== PROFILE SECTION ===== */
    .admin-profile {
        padding-left: 12px;
        border-left: 1px solid rgba(255, 255, 255, 0.06);
    }

    .profile-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        padding: 4px 12px 4px 4px;
        border-radius: 12px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .profile-wrapper:hover {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(255, 255, 255, 0.06);
    }

    .profile-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 2px solid rgba(14, 165, 233, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(14, 165, 233, 0.1);
        color: #0ea5e9;
        font-weight: 700;
        font-size: 14px;
        overflow: hidden;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .profile-wrapper:hover .profile-icon {
        border-color: rgba(14, 165, 233, 0.6);
    }

    .profile-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-info {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .profile-name {
        font-size: 13px;
        font-weight: 600;
        color: #e2e8f0;
    }

    .profile-role {
        font-size: 10px;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }

    .profile-arrow {
        color: #64748b;
        font-size: 11px;
        transition: all 0.3s ease;
    }

    .profile-wrapper:hover .profile-arrow {
        color: #e2e8f0;
        transform: rotate(180deg);
    }

    /* ===== PROFILE DROPDOWN ===== */
    .profile-dropdown {
        display: none;
        position: absolute;
        right: 0;
        top: 54px;
        background: linear-gradient(180deg, #0c1220 0%, #0f1a2e 50%, #142a42 100%);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 16px;
        min-width: 280px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        z-index: 1000;
        overflow: hidden;
        padding: 8px 0;
    }

    .profile-dropdown.show {
        display: block;
        animation: slideDown 0.3s ease;
    }

    .dropdown-header {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 20px 16px 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .dropdown-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: 2px solid rgba(14, 165, 233, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(14, 165, 233, 0.1);
        color: #0ea5e9;
        font-weight: 700;
        font-size: 16px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .dropdown-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .dropdown-user-info {
        flex: 1;
        min-width: 0;
    }

    .dropdown-name {
        font-size: 14px;
        font-weight: 600;
        color: #e2e8f0;
    }

    .dropdown-email {
        font-size: 12px;
        color: #94a3b8;
    }

    .dropdown-role {
        font-size: 11px;
        color: #0ea5e9;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 2px;
    }

    .dropdown-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.06);
        margin: 6px 16px;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 20px;
        color: #cbd5e1;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        font-family: 'Inter', sans-serif;
    }

    .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.04);
        color: #e2e8f0;
    }

    .dropdown-item i:first-child {
        width: 20px;
        color: #64748b;
        font-size: 15px;
    }

    .dropdown-item:hover i:first-child {
        color: #0ea5e9;
    }

    .dropdown-item .item-arrow {
        margin-left: auto;
        font-size: 11px;
        color: #64748b;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .dropdown-item:hover .item-arrow {
        opacity: 1;
        transform: translateX(4px);
        color: #0ea5e9;
    }

    .logout-item {
        color: #f87171;
    }

    .logout-item i:first-child {
        color: #ef4444;
    }

    .logout-item:hover {
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
    }

    .logout-item:hover i:first-child {
        color: #ef4444;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1200px) {
        .admin-nav {
            left: var(--sidebar-width, 280px);
        }
    }

    @media (max-width: 992px) {
        .admin-nav {
            left: 0;
            padding: 0 20px;
            height: 62px;
        }

        .mobile-toggle {
            display: flex;
        }

        .profile-info {
            display: none;
        }

        .profile-arrow {
            display: none;
        }

        .admin-profile {
            padding-left: 8px;
        }

        .profile-wrapper {
            padding: 4px;
        }

        .notification-dropdown {
            right: 60px;
            min-width: 320px;
        }
    }

    @media (max-width: 768px) {
        .admin-nav {
            padding: 0 16px;
            height: 56px;
        }

        .dashboard-title {
            font-size: 16px;
        }

        .system-label {
            font-size: 9px;
        }

        .notification-bell {
            width: 36px;
            height: 36px;
        }

        .notification-bell i {
            font-size: 17px;
        }

        .profile-icon {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }

        .notification-dropdown {
            right: 40px;
            min-width: 280px;
        }

        .profile-dropdown {
            min-width: 240px;
            right: -10px;
        }
    }

    @media (max-width: 576px) {
        .admin-nav {
            padding: 0 12px;
            height: 50px;
        }

        .dashboard-title {
            font-size: 14px;
        }

        .system-label {
            font-size: 8px;
            letter-spacing: 1px;
        }

        .system-label i {
            display: none;
        }

        .notification-badge {
            font-size: 8px;
            min-width: 15px;
            padding: 0 5px;
        }

        .notification-dropdown {
            right: 20px;
            min-width: 260px;
            top: 45px;
        }

        .profile-dropdown {
            min-width: 220px;
            right: -20px;
            top: 44px;
        }
    }
</style>

<script>
    // ============================================
    // ✅ NOTIFICATION SOUND
    // ============================================
    
    let notificationAudio = null;
    let previousBadgeCount = parseInt(document.getElementById('notificationBadge')?.textContent || 0);

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

    // ✅ Check for new notifications every 10 seconds
    function checkNewNotifications() {
        fetch('/notifications/unread-count')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const currentCount = data.count;
                    const badge = document.getElementById('notificationBadge');
                    
                    if (currentCount > previousBadgeCount && currentCount > 0) {
                        playNotificationSound();
                    }
                    previousBadgeCount = currentCount;
                    
                    if (badge) {
                        badge.textContent = currentCount > 99 ? '99+' : currentCount;
                        badge.style.display = currentCount > 0 ? 'block' : 'none';
                    }
                }
            })
            .catch(error => console.warn('Error checking notifications:', error));
    }

    // ============================================
    // TOGGLE FUNCTIONS
    // ============================================
    
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        const isOpen = dropdown.classList.contains('show');
        
        closeAllDropdowns();
        
        if (!isOpen) {
            dropdown.classList.add('show');
        }
    }

    function toggleNotifications() {
        const dropdown = document.getElementById('notificationDropdown');
        const isOpen = dropdown.classList.contains('show');
        
        closeAllDropdowns();
        
        if (!isOpen) {
            dropdown.classList.add('show');
            // Fetch notifications when opened
            fetchNotifications();
        }
    }

    function closeAllDropdowns() {
        document.querySelectorAll('.profile-dropdown, .notification-dropdown').forEach(el => {
            el.classList.remove('show');
        });
    }

    // ============================================
    // FETCH NOTIFICATIONS
    // ============================================

    function fetchNotifications() {
        fetch('/notifications')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderNotifications(data.notifications);
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }

    function renderNotifications(notifications) {
        const list = document.querySelector('.notification-list');
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
            const time = new Date(notification.created_at).toLocaleTimeString();
            let icon = getNotificationIcon(notification.type);
            
            html += `
                <div class="notification-item ${unreadClass}" onclick="markAsRead(${notification.id})">
                    <div class="notification-icon">
                        <i class="fas ${icon}"></i>
                    </div>
                    <div class="notification-content">
                        <p class="notification-text">${notification.title}</p>
                        <span class="notification-time">${time}</span>
                    </div>
                    ${!notification.is_read ? '<div style="color: #0ea5e9; font-size: 8px;">●</div>' : ''}
                </div>
            `;
        });

        list.innerHTML = html;
    }

    function getNotificationIcon(type) {
        const icons = {
            'token_generated': 'fa-ticket-alt',
            'token_called': 'fa-phone',
            'token_arrived': 'fa-check-circle',
            'token_completed': 'fa-check-double',
            'token_cancelled': 'fa-times-circle',
            'physical_patient_added': 'fa-user-plus',
            'staff_registered': 'fa-user-check',
            'staff_approved': 'fa-user-check',
            'staff_rejected': 'fa-user-times',
            'account_approved': 'fa-check-circle',
            'system_alert': 'fa-exclamation-triangle'
        };
        return icons[type] || 'fa-bell';
    }

    // ============================================
    // MARK AS READ
    // ============================================

    window.markAsRead = function(id) {
        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchNotifications();
                checkNewNotifications();
            }
        })
        .catch(error => console.error('Error:', error));
    };

    // ============================================
    // MARK ALL AS READ
    // ============================================

    document.querySelector('.notification-mark-all')?.addEventListener('click', function() {
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchNotifications();
                checkNewNotifications();
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // ============================================
    // EVENT LISTENERS
    // ============================================

    document.addEventListener('click', function(event) {
        const isClickInside = event.target.closest('.admin-profile') || 
                             event.target.closest('.notification-bell') ||
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

    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.classList.toggle('active');
        }
    }

    // ============================================
    // INIT
    // ============================================

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize sound
        initAudio();
        
        // Check for new notifications
        setTimeout(() => {
            checkNewNotifications();
        }, 500);
        
        // Auto check every 15 seconds
        setInterval(checkNewNotifications, 15000);
    });
</script>