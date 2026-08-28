/**
 * Notification System - For All Users (Admin, Staff, User)
 */

let allNotifications = [];
let currentFilter = 'all';
let previousBadgeCount = 0;
let isFetching = false;

// ============================================ //
// TOGGLE NOTIFICATIONS                        //
// ============================================ //

window.toggleNotifications = function(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    const dropdown = document.getElementById('notificationDropdown');
    if (dropdown) {
        dropdown.classList.toggle('active');
        if (dropdown.classList.contains('active')) {
            fetchNotifications();
        }
    }
};

// ============================================ //
// FETCH NOTIFICATIONS                         //
// ============================================ //

function fetchNotifications() {
    if (isFetching) return;
    isFetching = true;

    fetch('/notifications/json', {
        headers: { 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            allNotifications = data.notifications;
            renderDropdownNotifications(data.notifications);
            renderFullPageNotificationsIfPresent(data.notifications);
            const unreadCount = data.unread_count || 0;
            updateBadgeCount(unreadCount);
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
                    <i class="fas ${icon}"></i>
                </div>
                <div class="notification-content">
                    <p class="notification-text"><strong>${notification.title}</strong></p>
                    <p class="notification-text" style="font-size: 12px; color: #94a3b8;">${notification.message}</p>
                    ${notification.token_number ? `<div style="font-size: 11px; color: #00d4ff;">🎫 Token: #${notification.token_number}</div>` : ''}
                    <span class="notification-time">${timeStr}</span>
                </div>
                ${!notification.is_read ? '<div style="color: #0ea5e9; font-size: 8px;">●</div>' : ''}
            </div>
        `;
    });

    list.innerHTML = html;
}

function renderFullPageNotificationsIfPresent(notifications) {
    const list = document.getElementById('notificationsList');
    if (!list) return;

    // Filter based on active tab
    let filtered = notifications;
    if (currentFilter === 'unread') {
        filtered = notifications.filter(n => !n.is_read);
    } else if (currentFilter === 'read') {
        filtered = notifications.filter(n => n.is_read);
    }

    // Dynamic Counts Update
    const totalCount = notifications.length;
    const unreadCount = notifications.filter(n => !n.is_read).length;
    const readCount = totalCount - unreadCount;

    const elTotal = document.getElementById('totalCount');
    const elCountAll = document.getElementById('countAll');
    const elCountUnread = document.getElementById('countUnread');
    const elCountRead = document.getElementById('countRead');
    const elShownCount = document.getElementById('shownCount');

    if (elTotal) elTotal.textContent = totalCount;
    if (elCountAll) elCountAll.textContent = totalCount;
    if (elCountUnread) elCountUnread.textContent = unreadCount;
    if (elCountRead) elCountRead.textContent = readCount;
    if (elShownCount) elShownCount.textContent = filtered.length;

    if (!filtered || filtered.length === 0) {
        list.innerHTML = `
            <div class="empty-state">
                <div class="empty-icon">🔕</div>
                <h3>No Notifications</h3>
                <p>You don't have any notifications yet.</p>
            </div>
        `;
        return;
    }

    let html = '';
    filtered.forEach(notification => {
        const isRead = notification.is_read;
        const readClass = isRead ? 'read' : 'unread';
        const icon = getNotificationIcon(notification.type);
        const timeStr = new Date(notification.created_at).toLocaleString();

        html += `
            <div class="notification-item ${readClass}" data-id="${notification.id}" onclick="markNotificationAsRead(${notification.id})">
                <div class="notification-icon">
                    <i class="fas ${icon}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">
                        ${notification.title}
                        ${!isRead ? '<span class="new-tag">New</span>' : ''}
                    </div>
                    <div class="notification-message">${notification.message}</div>
                    ${notification.token_number ? `<div style="font-size: 12px; color: #00d4ff; margin-top: 4px;">🎫 Token: #${notification.token_number}</div>` : ''}
                    <div class="notification-time">${timeStr}</div>
                </div>
                <div class="notification-status">
                    ${!isRead ? '<span class="unread-dot">●</span>' : '<span class="read-check">✓</span>'}
                </div>
            </div>
        `;
    });

    list.innerHTML = html;
}

window.filterNotifications = function(filter) {
    currentFilter = filter;
    document.querySelectorAll('.filter-btn').forEach(btn => {
        if (btn.getAttribute('data-filter') === filter) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
    renderFullPageNotificationsIfPresent(allNotifications);
};

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
        'system_alert': 'fa-exclamation-triangle',
        'doctor_added': 'fa-user-md',
        'doctor_updated': 'fa-user-edit',
        'doctor_deleted': 'fa-user-times'
    };
    return icons[type] || 'fa-bell';
}

window.markNotificationAsRead = function(id) {
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
            checkUnreadCount();
        }
    })
    .catch(error => console.error('Error:', error));
};

window.markAllRead = function() {
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
            checkUnreadCount();
            updateBadgeCount(0);
            previousBadgeCount = 0;
        }
    })
    .catch(error => console.error('Error:', error));
};

function updateBadgeCount(count) {
    const badge = document.getElementById('notificationBadge');
    if (badge) {
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.add('show');
        } else {
            badge.classList.remove('show');
        }
    }
}

function checkUnreadCount() {
    fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const currentCount = data.count;
                updateBadgeCount(currentCount);

                if (currentCount > previousBadgeCount && currentCount > 0) {
                    playNotificationSound();
                }
                previousBadgeCount = currentCount;
            }
        })
        .catch(error => console.warn('Error checking notifications:', error));
}

document.addEventListener('DOMContentLoaded', function() {
    fetchNotifications();
});