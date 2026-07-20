@extends('Layout.app')
@section('title', 'Notifications - Smart Queue')

@section('content')
<link rel="stylesheet" href="{{ asset('css/notifications.css') }}">

<div class="notifications-page">
    <div class="container">
        <div class="notifications-header">
            <div class="header-left">
                <h1>🔔 Notifications</h1>
                <span class="notification-count" id="totalCount">0</span>
            </div>
            <div class="header-right">
                <button class="btn-mark-all" onclick="markAllRead()">✅ Mark all as read</button>
                <button class="btn-refresh" onclick="loadNotifications()">🔄 Refresh</button>
            </div>
        </div>

        <div class="notifications-filter">
            <button class="filter-btn active" data-filter="all" onclick="filterNotifications('all')">All</button>
            <button class="filter-btn" data-filter="unread" onclick="filterNotifications('unread')">Unread</button>
            <button class="filter-btn" data-filter="read" onclick="filterNotifications('read')">Read</button>
        </div>

        <div class="notifications-list" id="notificationsList">
            <div class="loading-spinner">Loading...</div>
        </div>

        <div class="notifications-footer">
            <p>Showing <span id="shownCount">0</span> notifications</p>
        </div>
    </div>
</div>

<script>
    let allNotifications = [];
    let currentFilter = 'all';

    function loadNotifications() {
        const list = document.getElementById('notificationsList');
        list.innerHTML = '<div class="loading-spinner">Loading...</div>';

        fetch('/notifications')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    allNotifications = data.notifications;
                    document.getElementById('totalCount').textContent = allNotifications.length;
                    renderNotifications(allNotifications);
                    updateBadge();
                } else {
                    list.innerHTML = '<div class="error-message">Failed to load notifications</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                list.innerHTML = '<div class="error-message">Error loading notifications</div>';
            });
    }

    function renderNotifications(notifications) {
        const list = document.getElementById('notificationsList');
        const shownCount = document.getElementById('shownCount');

        if (!notifications || notifications.length === 0) {
            list.innerHTML = `
                <div class="empty-state">
                    <div class="empty-icon">🔕</div>
                    <h3>No Notifications</h3>
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

            html += `
                <div class="notification-item ${readClass}" data-id="${notification.id}" onclick="markAsRead(${notification.id})">
                    <div class="notification-icon">${icon}</div>
                    <div class="notification-content">
                        <div class="notification-title">${notification.title}</div>
                        <div class="notification-message">${notification.message}</div>
                        ${notification.token_number ? `<div class="notification-token">🎫 Token: #${notification.token_number}</div>` : ''}
                        <div class="notification-time">${timeStr}</div>
                    </div>
                    <div class="notification-status">
                        ${!isRead ? '<span class="unread-dot">●</span>' : '<span class="read-check">✓</span>'}
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
            'system_alert': '⚠️'
        };
        return icons[type] || '📢';
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
    });
</script>

<style>
    .notifications-page {
        padding: 30px 0;
        background: #0b2e33;
        min-height: 100vh;
    }

    .notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .header-left h1 {
        color: #fff;
        font-size: 28px;
        margin: 0;
    }

    .notification-count {
        background: #00d4ff;
        color: #0b2e33;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: bold;
    }

    .header-right {
        display: flex;
        gap: 10px;
    }

    .btn-mark-all,
    .btn-refresh {
        padding: 8px 18px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-mark-all {
        background: #00d4ff;
        color: #0b2e33;
    }

    .btn-mark-all:hover {
        background: #00b8e6;
        transform: translateY(-2px);
    }

    .btn-refresh {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .btn-refresh:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }

    .notifications-filter {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 8px 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        background: transparent;
        color: rgba(255, 255, 255, 0.6);
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .filter-btn:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
    }

    .filter-btn.active {
        background: rgba(0, 212, 255, 0.15);
        border-color: #00d4ff;
        color: #00d4ff;
    }

    .notifications-list {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .notification-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 16px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item:hover {
        background: rgba(255, 255, 255, 0.04);
    }

    .notification-item.unread {
        background: rgba(0, 212, 255, 0.05);
        border-left: 3px solid #00d4ff;
    }

    .notification-item.read {
        opacity: 0.7;
    }

    .notification-icon {
        font-size: 24px;
        min-width: 40px;
        padding-top: 2px;
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        color: #fff;
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 4px;
    }

    .notification-message {
        color: rgba(255, 255, 255, 0.7);
        font-size: 14px;
    }

    .notification-token {
        color: #00d4ff;
        font-size: 13px;
        font-weight: 500;
        margin-top: 4px;
    }

    .notification-time {
        color: rgba(255, 255, 255, 0.3);
        font-size: 12px;
        margin-top: 6px;
    }

    .notification-status {
        min-width: 30px;
        text-align: right;
    }

    .unread-dot {
        color: #00d4ff;
        font-size: 12px;
        animation: pulse 2s ease-in-out infinite;
    }

    .read-check {
        color: rgba(255, 255, 255, 0.2);
        font-size: 14px;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: rgba(255, 255, 255, 0.3);
    }

    .empty-icon {
        font-size: 60px;
        margin-bottom: 15px;
    }

    .empty-state h3 {
        color: #fff;
        font-size: 20px;
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 14px;
    }

    .loading-spinner {
        text-align: center;
        padding: 40px;
        color: rgba(255, 255, 255, 0.4);
    }

    .error-message {
        text-align: center;
        padding: 40px;
        color: #dc3545;
    }

    .notifications-footer {
        text-align: center;
        padding: 15px;
        color: rgba(255, 255, 255, 0.3);
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .notifications-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-right {
            width: 100%;
            flex-wrap: wrap;
        }

        .btn-mark-all, .btn-refresh {
            flex: 1;
            text-align: center;
        }

        .notification-item {
            padding: 12px 15px;
            flex-wrap: wrap;
        }

        .notification-status {
            width: 100%;
            text-align: left;
            padding-left: 55px;
        }
    }

    @media (max-width: 480px) {
        .notifications-filter {
            gap: 6px;
        }

        .filter-btn {
            padding: 6px 14px;
            font-size: 13px;
        }
    }
</style>

@endsection