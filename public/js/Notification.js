/**
 * Global Notifications Helper Script
 */

let globalNotifications = [];

window.toggleNotifications = function(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    const dropdown = document.getElementById('notificationDropdown');
    if (dropdown) {
        const isShown = dropdown.classList.contains('show');
        document.querySelectorAll('.profile-dropdown, .notification-dropdown').forEach(el => el.classList.remove('show'));
        if (!isShown) {
            dropdown.classList.add('show');
            if (typeof fetchNotifications === 'function') {
                fetchNotifications();
            }
        }
    }
};

window.markAllNotificationsAsRead = function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    fetch('/notifications/mark-all-read', {
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
            if (typeof fetchNotifications === 'function') fetchNotifications();
            if (typeof checkNewNotifications === 'function') checkNewNotifications();
        }
    })
    .catch(err => console.error('Mark all read error:', err));
};

document.addEventListener('DOMContentLoaded', function() {
    const bell = document.getElementById('notificationBell');
    if (bell && !bell.hasAttribute('onclick')) {
        bell.addEventListener('click', window.toggleNotifications);
    }
});