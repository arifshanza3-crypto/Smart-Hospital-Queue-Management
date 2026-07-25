/**
 * Notifications System - JavaScript
 * Handles notification bell, dropdown, and real-time updates
 */

(function() {
    'use strict';

    // ============================================ //
    // TOGGLE NOTIFICATIONS DROPDOWN                //
    // ============================================ //

    window.toggleNotifications = function() {
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown) {
            dropdown.classList.toggle('active');
            if (dropdown.classList.contains('active')) {
                fetchNotifications();
            }
        }
    };

    // ============================================ //
    // FETCH NOTIFICATIONS                          //
    // ============================================ //

    function fetchNotifications() {
        fetch('/notifications')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderNotifications(data.notifications);
                    updateBadge(data.unread_count);
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }

    // ============================================ //
    // RENDER NOTIFICATIONS IN DROPDOWN            //
    // ============================================ //

    function renderNotifications(notifications) {
        const list = document.getElementById('notificationList');
        
        if (!list) return;

        if (!notifications || notifications.length === 0) {
            list.innerHTML = `
                <div class="notification-empty">
                    <div class="icon">🔕</div>
                    <p>No notifications</p>
                </div>
            `;
            return;
        }

        let html = '';
        // Show only latest 5 notifications in dropdown
        const latest = notifications.slice(0, 5);
        
        latest.forEach(notification => {
            const unreadClass = notification.is_read ? '' : 'unread';
            const time = new Date(notification.created_at).toLocaleTimeString();
            
            let icon = getNotificationIcon(notification.type);
            
            html += `
                <div class="notification-item ${unreadClass}" onclick="markAsRead(${notification.id})">
                    <div class="notification-icon">${icon}</div>
                    <div class="notification-content">
                        <div class="notification-title">${notification.title}</div>
                        <div class="notification-message">${notification.message}</div>
                        ${notification.token_number ? `<div class="notification-token">🎫 Token: #${notification.token_number}</div>` : ''}
                        <div class="notification-time">${time}</div>
                    </div>
                    ${!notification.is_read ? '<div class="notification-status"><span class="unread-dot">●</span></div>' : ''}
                </div>
            `;
        });

        list.innerHTML = html;
    }

    // ============================================ //
    // GET NOTIFICATION ICON                       //
    // ============================================ //

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

    // ============================================ //
    // UPDATE BADGE                                //
    // ============================================ //

    window.updateBadge = function(count) {
        const badge = document.getElementById('notificationBadge');
        if (!badge) return;
        
        if (count && count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'block';
        } else {
            badge.style.display = 'none';
        }
    };

    // ============================================ //
    // MARK AS READ                                //
    // ============================================ //

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
                getUnreadCount();
            }
        })
        .catch(error => console.error('Error:', error));
    };

    // ============================================ //
    // MARK ALL AS READ                            //
    // ============================================ //

    window.markAllRead = function() {
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
                getUnreadCount();
            }
        })
        .catch(error => console.error('Error:', error));
    };

    // ============================================ //
    // GET UNREAD COUNT                            //
    // ============================================ //

    function getUnreadCount() {
        fetch('/notifications/unread-count')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.updateBadge(data.count);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // ============================================ //
    // CLOSE DROPDOWN ON OUTSIDE CLICK             //
    // ============================================ //

    document.addEventListener('click', function(event) {
        const wrapper = document.querySelector('.notification-wrapper');
        if (wrapper && !wrapper.contains(event.target)) {
            const dropdown = document.getElementById('notificationDropdown');
            if (dropdown) {
                dropdown.classList.remove('active');
            }
        }
    });

    // ============================================ //
    // AUTO-REFRESH NOTIFICATIONS                  //
    // ============================================ //

    document.addEventListener('DOMContentLoaded', function() {
        getUnreadCount();
        // Check for new notifications every 10 seconds
        setInterval(getUnreadCount, 10000);
    });

    // ============================================ //
    // EXPOSE FUNCTIONS TO GLOBAL SCOPE            //
    // ============================================ //

    window.fetchNotifications = fetchNotifications;
    window.getUnreadCount = getUnreadCount;

})();