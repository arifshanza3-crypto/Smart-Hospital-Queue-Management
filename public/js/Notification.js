/**
 * Notification System
 */

let allNotifications = [];
let currentFilter = 'all';

function loadNotifications() {
    const list = document.getElementById('notificationsList');
    if (!list) return;

    fetch('/notifications', {
        headers: { 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            allNotifications = data.notifications || [];
            document.getElementById('totalCount').textContent = allNotifications.length;
            document.getElementById('countAll').textContent = allNotifications.length;
            
            const unread = allNotifications.filter(n => !n.is_read).length;
            document.getElementById('countUnread').textContent = unread;
            document.getElementById('countRead').textContent = allNotifications.length - unread;
            
            renderNotifications(allNotifications);
            updateBadge(unread);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function renderNotifications(notifications) {
    const list = document.getElementById('notificationsList');
    const shownCount = document.getElementById('shownCount');

    if (!list) return;

    if (!notifications || notifications.length === 0) {
        list.innerHTML = `
            <div class="empty-state">
                <div class="empty-icon">🔕</div>
                <h3>No Notifications</h3>
                <p>You don't have any notifications yet.</p>
            </div>
        `;
        if (shownCount) shownCount.textContent = '0';
        return;
    }

    let html = '';
    notifications.forEach(notification => {
        const isRead = notification.is_read;
        const readClass = isRead ? 'read' : 'unread';
        const icon = notification.data?.icon || 'fa-bell';
        const newTag = !isRead ? `<span class="new-tag">New</span>` : '';

        html += `
            <div class="notification-item ${readClass}" data-id="${notification.id}" onclick="markAsRead(${notification.id})">
                <div class="notification-icon">
                    <i class="fas ${icon}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${notification.title} ${newTag}</div>
                    <div class="notification-message">${notification.message}</div>
                    <div class="notification-time">${new Date(notification.created_at).toLocaleString()}</div>
                </div>
                <div class="notification-status">
                    ${!isRead ? '<span class="unread-dot">●</span>' : '<span class="read-check">✓</span>'}
                </div>
            </div>
        `;
    });

    list.innerHTML = html;
    if (shownCount) shownCount.textContent = notifications.length;
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
            const notification = allNotifications.find(n => n.id === id);
            if (notification) {
                notification.is_read = true;
            }
            const unread = allNotifications.filter(n => !n.is_read).length;
            document.getElementById('countUnread').textContent = unread;
            document.getElementById('countRead').textContent = allNotifications.length - unread;
            filterNotifications(currentFilter);
            updateBadge(unread);
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAllRead() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const btn = document.getElementById('markAllBtn');
    
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Please wait...';
    }
    
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
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-double"></i> Mark all as read';
        }
        if (data.success) {
            allNotifications.forEach(n => n.is_read = true);
            document.getElementById('countUnread').textContent = 0;
            document.getElementById('countRead').textContent = allNotifications.length;
            filterNotifications(currentFilter);
            updateBadge(0);
            showToast('✅ All notifications marked as read!', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-double"></i> Mark all as read';
        }
        showToast('❌ Error marking all as read', 'error');
    });
}

function updateBadge(unreadCount) {
    const badge = document.getElementById('notificationBadge');
    if (badge) {
        if (unreadCount > 0) {
            badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
            badge.style.display = 'block';
        } else {
            badge.style.display = 'none';
        }
    }
    // ✅ Update navbar badge too (in admin_nav)
    const navBadge = document.getElementById('notificationBadge');
    if (navBadge) {
        if (unreadCount > 0) {
            navBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
            navBadge.classList.add('show');
        } else {
            navBadge.classList.remove('show');
        }
    }
}

function showToast(message, type = 'success') {
    const existing = document.querySelector('.toast-message');
    if (existing) existing.remove();
    
    const toast = document.createElement('div');
    toast.className = `toast-message toast-${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(20px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 400);
    }, 3500);
}

// ============================================ //
// ✅ CHECK FOR NEW NOTIFICATIONS (EVERY 10 SECONDS)
// ============================================ //

let previousCount = 0;

function checkUnreadCount() {
    fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const count = data.count;
                updateBadge(count);
                
                if (count > previousCount && count > 0) {
                    playNotificationSound();
                }
                previousCount = count;
            }
        })
        .catch(error => console.error('Error checking unread count:', error));
}

// ============================================ //
// SOUND
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
                .catch(() => console.log('🔇 Sound play blocked'));
        } catch (error) {
            console.log('🔇 Sound error:', error);
        }
    }
}

// ============================================ //
// INIT
// ============================================ //

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Notification system initialized');
    
    loadNotifications();
    checkUnreadCount();
    
    // Auto refresh every 10 seconds (badge)
    setInterval(checkUnreadCount, 10000);
    
    // Auto refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);
    
    initAudio();
});

// EXPOSE TO GLOBAL SCOPE
window.filterNotifications = filterNotifications;
window.markAsRead = markAsRead;
window.markAllRead = markAllRead;
window.loadNotifications = loadNotifications;
window.checkUnreadCount = checkUnreadCount;