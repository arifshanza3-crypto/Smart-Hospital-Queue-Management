/**
 * Notification System with Sound
 */

// ============================================ //
// TOGGLE NOTIFICATIONS DROPDOWN                //
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
// RENDER NOTIFICATIONS                         //
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
                    <div class="notification-time">${time}</div>
                </div>
                ${!notification.is_read ? '<div class="unread-dot">●</div>' : ''}
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
        badge.classList.add('show');
    } else {
        badge.classList.remove('show');
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
// GET UNREAD COUNT & PLAY SOUND               //
// ============================================ //

let previousCount = 0;

function getUnreadCount() {
    fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // ✅ Play sound if new notification arrived
                if (data.count > previousCount && data.count > 0) {
                    playNotificationSound();
                }
                previousCount = data.count;
                updateBadge(data.count);
            }
        })
        .catch(error => console.error('Error:', error));
}

// ============================================ //
// NOTIFICATION SOUND                          //
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
// INIT                                        //
// ============================================ //

document.addEventListener('DOMContentLoaded', function() {
    initAudio();
    getUnreadCount();
    setInterval(getUnreadCount, 10000);
});

// ============================================ //
// EXPOSE FUNCTIONS TO GLOBAL SCOPE            //
// ============================================ //

window.fetchNotifications = fetchNotifications;
window.getUnreadCount = getUnreadCount;