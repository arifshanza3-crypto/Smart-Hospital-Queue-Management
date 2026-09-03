/**
 * Global Notifications Helper & Filtering Script
 */

// 1. FILTERING FUNCTIONALITY (All, Unread, Read)
function filterNotifications(filterType) {
    // Collect all notification cards and filter buttons
    const items = document.querySelectorAll('.notification-item');
    const buttons = document.querySelectorAll('.notifications-filter .filter-btn');

    // Active button styling manage karein
    buttons.forEach(btn => {
        const btnFilter = btn.getAttribute('data-filter');
        if (btnFilter === filterType) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    let visibleCount = 0;

    // Direct DOM show/hide logic
    items.forEach(item => {
        const isUnread = item.classList.contains('unread');
        const isRead = item.classList.contains('read');

        if (filterType === 'all') {
            item.style.setProperty('display', 'flex', 'important');
            visibleCount++;
        } else if (filterType === 'unread') {
            if (isUnread) {
                item.style.setProperty('display', 'flex', 'important');
                visibleCount++;
            } else {
                item.style.setProperty('display', 'none', 'important');
            }
        } else if (filterType === 'read') {
            if (isRead) {
                item.style.setProperty('display', 'flex', 'important');
                visibleCount++;
            } else {
                item.style.setProperty('display', 'none', 'important');
            }
        }
    });

    // Bottom showing count update karein
    const shownCountEl = document.getElementById('shownCount');
    if (shownCountEl) {
        shownCountEl.textContent = visibleCount;
    }
}

// Window object par assign karein taake Inline onclick call ho saky
window.filterNotifications = filterNotifications;

// 2. MARK AS READ FUNCTIONALITY
window.markAsRead = function(id) {
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
            const item = document.querySelector(`.notification-item[data-id="${id}"]`);
            if (item && item.classList.contains('unread')) {
                item.classList.remove('unread');
                item.classList.add('read');

                // Remove NEW badge
                const newTag = item.querySelector('.new-tag');
                if (newTag) newTag.remove();

                // Status icon update to Checkmark
                const statusDot = item.querySelector('.notification-status');
                if (statusDot) statusDot.innerHTML = '<span class="read-check">✓</span>';

                // Badges count decrease/increase
                const unreadBadge = document.getElementById('countUnread');
                if (unreadBadge) {
                    let currentUnread = parseInt(unreadBadge.textContent) || 0;
                    unreadBadge.textContent = Math.max(0, currentUnread - 1);
                }

                const readBadge = document.getElementById('countRead');
                if (readBadge) {
                    let currentRead = parseInt(readBadge.textContent) || 0;
                    readBadge.textContent = currentRead + 1;
                }
            }
        }
    })
    .catch(err => console.error('Mark as read error:', err));
};

// 3. DROPDOWN TOGGLE
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

// 4. EVENT LISTENERS INITIALIZATION
document.addEventListener('DOMContentLoaded', function() {
    // Filter Buttons click binding via Event Listeners
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            if (filter) {
                filterNotifications(filter);
            }
        });
    });

    const bell = document.getElementById('notificationBell');
    if (bell && !bell.hasAttribute('onclick')) {
        bell.addEventListener('click', window.toggleNotifications);
    }
});