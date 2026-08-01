@extends('Layout.app')

@section('title', 'Notifications - Smart Queue')

@section('content')
<style>
    /* ============================================ */
    /* NOTIFICATIONS PAGE - MODERN DESIGN          */
    /* ============================================ */

    .notif-page {
        background: linear-gradient(135deg, #071a1c 0%, #0b2e33 50%, #0f3a40 100%);
        min-height: 100vh;
        padding: 40px 0;
        position: relative;
        overflow: hidden;
    }

    .notif-page::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(ellipse at 30% 20%, rgba(0, 212, 255, 0.03) 0%, transparent 60%);
        animation: notifFloat 25s ease-in-out infinite;
    }

    @keyframes notifFloat {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(5%, -5%) scale(1.05); }
        66% { transform: translate(-5%, 5%) scale(0.95); }
    }

    .notif-container {
        max-width: 880px;
        margin: 0 auto;
        padding: 0 24px;
        position: relative;
        z-index: 1;
    }

    /* ===== HEADER ===== */
    .notif-header {
        background: rgba(255, 255, 255, 0.04);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        padding: 24px 30px;
        margin-bottom: 28px;
        border: 1px solid rgba(255, 255, 255, 0.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .notif-header-left {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .notif-header-left .icon-box {
        width: 50px;
        height: 50px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.15), rgba(0, 212, 255, 0.05));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        border: 1px solid rgba(0, 212, 255, 0.1);
    }

    .notif-header-left h1 {
        color: #fff;
        font-size: 24px;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.5px;
    }

    .notif-header-left h1 span {
        color: #00d4ff;
    }

    .notif-badge {
        background: linear-gradient(135deg, #00d4ff, #0ea5e9);
        color: #071a1c;
        padding: 4px 16px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        min-width: 32px;
        text-align: center;
        box-shadow: 0 4px 16px rgba(0, 212, 255, 0.25);
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 16px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid;
    }

    .role-badge.admin {
        background: rgba(239, 68, 68, 0.12);
        color: #ef4444;
        border-color: rgba(239, 68, 68, 0.2);
    }

    .role-badge.staff {
        background: rgba(59, 130, 246, 0.12);
        color: #3b82f6;
        border-color: rgba(59, 130, 246, 0.2);
    }

    .role-badge.user {
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
        border-color: rgba(16, 185, 129, 0.2);
    }

    .role-badge i {
        font-size: 12px;
    }

    .notif-header-right {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-notif {
        padding: 10px 22px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-notif-primary {
        background: linear-gradient(135deg, #00d4ff, #0ea5e9);
        color: #071a1c;
        box-shadow: 0 4px 16px rgba(0, 212, 255, 0.25);
    }

    .btn-notif-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(0, 212, 255, 0.35);
    }

    .btn-notif-secondary {
        background: rgba(255, 255, 255, 0.06);
        color: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .btn-notif-secondary:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        transform: translateY(-2px);
    }

    /* ===== FILTERS ===== */
    .notif-filters {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        flex-wrap: wrap;
        background: rgba(255, 255, 255, 0.03);
        padding: 8px;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.04);
    }

    .filter-btn {
        padding: 8px 22px;
        border: none;
        border-radius: 10px;
        background: transparent;
        color: rgba(255, 255, 255, 0.4);
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 13px;
    }

    .filter-btn:hover {
        color: rgba(255, 255, 255, 0.7);
        background: rgba(255, 255, 255, 0.04);
    }

    .filter-btn.active {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.15), rgba(0, 212, 255, 0.05));
        color: #00d4ff;
        box-shadow: 0 0 20px rgba(0, 212, 255, 0.05);
    }

    .filter-btn .count {
        background: rgba(255, 255, 255, 0.06);
        padding: 1px 10px;
        border-radius: 20px;
        font-size: 11px;
        margin-left: 6px;
        color: rgba(255, 255, 255, 0.3);
    }

    .filter-btn.active .count {
        background: rgba(0, 212, 255, 0.15);
        color: #00d4ff;
    }

    /* ===== NOTIFICATION LIST ===== */
    .notif-list {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.04);
        min-height: 300px;
    }

    .notif-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 18px 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .notif-item:last-child {
        border-bottom: none;
    }

    .notif-item:hover {
        background: rgba(255, 255, 255, 0.03);
    }

    .notif-item.unread {
        background: rgba(0, 212, 255, 0.03);
    }

    .notif-item.unread::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, #00d4ff, #0ea5e9);
        border-radius: 0 3px 3px 0;
    }

    .notif-item.read {
        opacity: 0.55;
    }

    .notif-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: rgba(0, 212, 255, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        border: 1px solid rgba(0, 212, 255, 0.05);
    }

    .notif-content {
        flex: 1;
        min-width: 0;
    }

    .notif-content .title {
        color: #fff;
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .notif-content .title .new-tag {
        font-size: 9px;
        background: #00d4ff;
        color: #071a1c;
        padding: 1px 10px;
        border-radius: 20px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .notif-content .message {
        color: rgba(255, 255, 255, 0.5);
        font-size: 14px;
        line-height: 1.5;
    }

    .notif-content .meta {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 8px;
        flex-wrap: wrap;
    }

    .notif-content .meta .time {
        color: rgba(255, 255, 255, 0.2);
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .notif-content .meta .type-tag {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: rgba(255, 255, 255, 0.15);
        background: rgba(255, 255, 255, 0.04);
        padding: 2px 12px;
        border-radius: 20px;
    }

    .notif-status {
        flex-shrink: 0;
        padding-top: 4px;
    }

    .unread-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #00d4ff;
        display: inline-block;
        box-shadow: 0 0 16px rgba(0, 212, 255, 0.3);
        animation: pulse-dot 2s ease-in-out infinite;
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(0.8); }
    }

    .read-check {
        color: rgba(255, 255, 255, 0.1);
        font-size: 16px;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 70px 20px;
    }

    .empty-state .icon-box {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.03);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        margin: 0 auto 20px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .empty-state h3 {
        color: rgba(255, 255, 255, 0.5);
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .empty-state p {
        color: rgba(255, 255, 255, 0.2);
        font-size: 14px;
    }

    /* ===== LOADING ===== */
    .loading-state {
        text-align: center;
        padding: 50px 20px;
        color: rgba(255, 255, 255, 0.2);
    }

    .loading-state .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid rgba(0, 212, 255, 0.1);
        border-top-color: #00d4ff;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin: 0 auto 12px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* ===== FOOTER ===== */
    .notif-footer {
        text-align: center;
        padding: 18px;
        color: rgba(255, 255, 255, 0.12);
        font-size: 13px;
        border-top: 1px solid rgba(255, 255, 255, 0.03);
        margin-top: 8px;
    }

    .notif-footer span {
        color: rgba(255, 255, 255, 0.2);
    }

    /* ===== BACK BUTTON ===== */
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-top: 30px;
        padding: 12px 28px;
        background: rgba(255, 255, 255, 0.04);
        color: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 14px;
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }

    .back-btn i {
        color: #00d4ff;
    }

    .text-center {
        text-align: center;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .notif-header {
            padding: 18px 20px;
            flex-direction: column;
            align-items: stretch;
        }

        .notif-header-left {
            flex-wrap: wrap;
        }

        .notif-header-right {
            width: 100%;
        }

        .btn-notif {
            flex: 1;
            justify-content: center;
            padding: 10px 16px;
            font-size: 12px;
        }

        .notif-item {
            padding: 14px 16px;
            gap: 12px;
        }

        .notif-icon {
            width: 38px;
            height: 38px;
            font-size: 16px;
        }

        .notif-content .title {
            font-size: 14px;
        }

        .notif-content .message {
            font-size: 13px;
        }

        .notif-container {
            padding: 0 12px;
        }

        .filter-btn {
            padding: 6px 14px;
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {
        .notif-header-left h1 {
            font-size: 20px;
        }

        .notif-badge {
            font-size: 11px;
            padding: 3px 12px;
        }

        .role-badge {
            font-size: 10px;
            padding: 3px 12px;
        }

        .notif-item {
            padding: 12px 14px;
        }

        .notif-content .meta {
            gap: 10px;
        }

        .back-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="notif-page">
    <div class="notif-container">

        {{-- HEADER --}}
        <div class="notif-header">
            <div class="notif-header-left">
                <div class="icon-box">
                    <i class="fas fa-bell"></i>
                </div>
                <h1>Notifications</h1>
                <span class="notif-badge" id="totalCount">0</span>
                @auth
                    <span class="role-badge {{ auth()->user()->role }}">
                        <i class="fas {{ auth()->user()->role === 'admin' ? 'fa-user-shield' : (auth()->user()->role === 'staff' ? 'fa-user-tie' : 'fa-user') }}"></i>
                        {{ ucfirst(auth()->user()->role ?? 'User') }}
                    </span>
                @endauth
            </div>
            <div class="notif-header-right">
                <button class="btn-notif btn-notif-primary" onclick="markAllRead()">
                    <i class="fas fa-check-double"></i> Mark all read
                </button>
                <button class="btn-notif btn-notif-secondary" onclick="loadNotifications()">
                    <i class="fas fa-sync"></i> Refresh
                </button>
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="notif-filters">
            <button class="filter-btn active" data-filter="all" onclick="filterNotifications('all')">
                All <span class="count" id="countAll">0</span>
            </button>
            <button class="filter-btn" data-filter="unread" onclick="filterNotifications('unread')">
                Unread <span class="count" id="countUnread">0</span>
            </button>
            <button class="filter-btn" data-filter="read" onclick="filterNotifications('read')">
                Read <span class="count" id="countRead">0</span>
            </button>
        </div>

        {{-- NOTIFICATIONS LIST --}}
        <div class="notif-list" id="notificationsList">
            <div class="loading-state">
                <div class="spinner"></div>
                <p>Loading notifications...</p>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="notif-footer">
            Showing <span id="shownCount">0</span> notifications
        </div>

        {{-- BACK BUTTON --}}
        <div class="text-center">
            <a href="{{ auth()->user()->role === 'admin' ? '/admin/dashboard' : (auth()->user()->role === 'staff' ? '/staff/dashboard' : '/') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

    </div>
</div>

<script>
    let allNotifications = [];
    let currentFilter = 'all';

    function loadNotifications() {
        const list = document.getElementById('notificationsList');
        list.innerHTML = `<div class="loading-state"><div class="spinner"></div><p>Loading...</p></div>`;

        fetch('/notifications')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    allNotifications = data.notifications || [];
                    updateCounts();
                    renderNotifications(allNotifications);
                    updateBadge();
                } else {
                    list.innerHTML = `<div class="empty-state"><div class="icon-box">❌</div><h3>Error</h3><p>Failed to load notifications</p></div>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                list.innerHTML = `<div class="empty-state"><div class="icon-box">⚠️</div><h3>Error</h3><p>Unable to load notifications</p></div>`;
            });
    }

    function renderNotifications(notifications) {
        const list = document.getElementById('notificationsList');
        const shownCount = document.getElementById('shownCount');

        if (!notifications || notifications.length === 0) {
            list.innerHTML = `
                <div class="empty-state">
                    <div class="icon-box">🔕</div>
                    <h3>All Caught Up!</h3>
                    <p>You don't have any notifications yet.</p>
                </div>
            `;
            shownCount.textContent = '0';
            return;
        }

        let html = '';
        notifications.forEach(notification => {
            const isRead = notification.is_read;
            const readClass = isRead ? 'read' : 'unread';
            const time = new Date(notification.created_at);
            const timeStr = time.toLocaleDateString() + ' ' + time.toLocaleTimeString();

            let icon = getNotificationIcon(notification.type);
            const newTag = !isRead ? `<span class="new-tag">New</span>` : '';

            html += `
                <div class="notif-item ${readClass}" data-id="${notification.id}" onclick="markAsRead(${notification.id})">
                    <div class="notif-icon">${icon}</div>
                    <div class="notif-content">
                        <div class="title">${notification.title} ${newTag}</div>
                        <div class="message">${notification.message}</div>
                        <div class="meta">
                            <span class="time"><i class="far fa-clock"></i> ${timeStr}</span>
                            <span class="type-tag">${notification.type || 'general'}</span>
                        </div>
                    </div>
                    <div class="notif-status">
                        ${!isRead ? '<span class="unread-dot"></span>' : '<span class="read-check">✓</span>'}
                    </div>
                </div>
            `;
        });

        list.innerHTML = html;
        shownCount.textContent = notifications.length;
    }

    function getNotificationIcon(type) {
        const icons = {
            'token_generated': '🎫',
            'token_called': '📞',
            'token_arrived': '✅',
            'token_completed': '✔️',
            'token_cancelled': '❌',
            'physical_patient_added': '👤',
            'staff_registered': '📝',
            'staff_approved': '✅',
            'staff_rejected': '❌',
            'account_approved': '✅',
            'system_alert': '⚠️',
            'doctor_added': '👨‍⚕️',
            'doctor_updated': '📝',
            'doctor_deleted': '🗑️'
        };
        return icons[type] || '📢';
    }

    function updateCounts() {
        const total = allNotifications.length;
        const unread = allNotifications.filter(n => !n.is_read).length;
        const read = total - unread;

        document.getElementById('totalCount').textContent = total;
        document.getElementById('countAll').textContent = total;
        document.getElementById('countUnread').textContent = unread;
        document.getElementById('countRead').textContent = read;
    }

    function filterNotifications(filter) {
        currentFilter = filter;
        
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.filter === filter) {
                btn.classList.add('active');
            }
        });

        let filtered = allNotifications;
        if (filter === 'unread') {
            filtered = allNotifications.filter(n => !n.is_read);
        } else if (filter === 'read') {
            filtered = allNotifications.filter(n => n.is_read);
        }

        renderNotifications(filtered);
    }

    function markAsRead(id) {
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
                const notification = allNotifications.find(n => n.id === id);
                if (notification) {
                    notification.is_read = true;
                }
                updateCounts();
                filterNotifications(currentFilter);
                updateBadge();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function markAllRead() {
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
                allNotifications.forEach(n => n.is_read = true);
                updateCounts();
                filterNotifications(currentFilter);
                updateBadge();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function updateBadge() {
        const unreadCount = allNotifications.filter(n => !n.is_read).length;
        const badge = document.getElementById('notificationBadge');
        if (badge) {
            if (unreadCount > 0) {
                badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                badge.style.display = 'block';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadNotifications();
        setInterval(loadNotifications, 30000);
    });

    window.filterNotifications = filterNotifications;
    window.markAsRead = markAsRead;
    window.markAllRead = markAllRead;
    window.loadNotifications = loadNotifications;
</script>

@endsection