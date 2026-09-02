@extends('Layout.app')

@section('title', 'Notifications - Smart Queue')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Notification.css') }}">

<div class="notifications-page">
    <div class="container">

        {{-- HEADER --}}
        <div class="notifications-header">
            <div class="header-left">
                <h1>🔔 Notifications</h1>
                @php
                    $totalCount = isset($notifications) ? $notifications->count() : 0;
                    $unreadCount = $unreadCount ?? 0;
                    $readCount = max(0, $totalCount - $unreadCount);
                @endphp
                <span class="notification-count" id="totalCount">{{ $totalCount }}</span>
                @auth
                    <span class="user-role-badge">
                        <span class="role-tag {{ auth()->user()->role }}">
                            <i class="fas {{ auth()->user()->role === 'admin' ? 'fa-user-shield' : (auth()->user()->role === 'staff' ? 'fa-user-tie' : 'fa-user') }}"></i>
                            {{ ucfirst(auth()->user()->role ?? 'User') }}
                        </span>
                    </span>
                @endauth
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="notifications-filter">
            <button class="filter-btn active" data-filter="all" onclick="filterNotifications('all')">
                All <span class="count" id="countAll">{{ $totalCount }}</span>
            </button>
            <button class="filter-btn" data-filter="unread" onclick="filterNotifications('unread')">
                Unread <span class="count" id="countUnread">{{ $unreadCount }}</span>
            </button>
            <button class="filter-btn" data-filter="read" onclick="filterNotifications('read')">
                Read <span class="count" id="countRead">{{ $readCount }}</span>
            </button>
        </div>

        {{-- NOTIFICATIONS LIST --}}
        <div class="notifications-list" id="notificationsList">
            @if($totalCount > 0)
                @foreach($notifications as $notification)
                    @php
                        $data = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;
                        $icon = $data['icon'] ?? 'fa-bell';
                        $isRead = !is_null($notification->read_at);
                        $readClass = $isRead ? 'read' : 'unread';
                    @endphp
                    <div class="notification-item {{ $readClass }}" data-id="{{ $notification->id }}" onclick="markAsRead({{ $notification->id }})">
                        <div class="notification-icon">
                            <i class="fas {{ $icon }}"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">
                                <span>{{ $notification->title }}</span>
                                @if(!$isRead)
                                    <span class="new-tag">NEW</span>
                                @endif
                            </div>
                            <div class="notification-message">{{ $notification->message }}</div>
                            @if(isset($data['token']))
                                <div class="notification-token">Token: {{ $data['token'] }}</div>
                            @endif
                            <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="notification-status">
                            @if(!$isRead)
                                <span class="unread-dot">●</span>
                            @else
                                <span class="read-check">✓</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-icon">🔕</div>
                    <h3>No Notifications</h3>
                    <p>You don't have any notifications yet.</p>
                </div>
            @endif
        </div>

        {{-- FOOTER --}}
        <div class="notifications-footer">
            Showing <span id="shownCount">{{ $totalCount }}</span> notifications
        </div>

        {{-- BACK BUTTON --}}
        <div class="text-center mt-3">
            @php
                $role = auth()->check() ? auth()->user()->role : null;
                $dashboardUrl = match($role) {
                    'admin' => '/admin/dashboard',
                    'staff' => '/staff/dashboard',
                    default => '/'
                };
            @endphp
            <a href="{{ $dashboardUrl }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

    </div>
</div>

<script src="{{ asset('js/Notification.js') }}"></script>

<style>
    /* DIRECT OVERRIDE FOR TEXT VISIBILITY */
    .notifications-list .notification-item {
        background-color: #ffffff !important;
        opacity: 1 !important;
    }

    .notifications-list .notification-item.unread {
        background-color: #f4fafb !important;
        border-left: 5px solid #0b2e33 !important;
    }

    .notifications-list .notification-title,
    .notifications-list .notification-title span {
        color: #0b2e33 !important;
        font-weight: 700 !important;
        font-size: 16px !important;
    }

    .notifications-list .notification-message {
        color: #1a3c40 !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        opacity: 1 !important;
    }

    .notifications-list .notification-time {
        color: #4a6b70 !important;
        font-weight: 500 !important;
        font-size: 12px !important;
        opacity: 1 !important;
    }

    .notifications-list .notification-token {
        background-color: #0b2e33 !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        padding: 2px 10px !important;
        border-radius: 6px !important;
        display: inline-block !important;
    }

    /* Toast Messages */
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
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        font-size: 14px;
        border: 1px solid rgba(11, 46, 51, 0.1);
    }
    
    .toast-success {
        background: #e8f0f2;
        color: #0b2e33;
        border-left: 4px solid #1a7a4a;
    }
    
    .toast-error {
        background: #f0dcdc;
        color: #8a3030;
        border-left: 4px solid #b03030;
    }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .new-tag {
        font-size: 9px;
        background: #0b2e33 !important;
        color: #ffffff !important;
        padding: 2px 12px !important;
        border-radius: 20px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        margin-left: 8px !important;
    }
</style>
@endsection@extends('Layout.app')

@section('content')
<div class="notifications-container">
    <!-- Header Section -->
    <div class="notifications-header">
        <div class="header-title-group">
            <h1 class="page-title">Notifications</h1>
            <p class="page-subtitle">Manage and view your system alerts and queue updates</p>
        </div>
        <div class="header-actions">
            <button type="button" class="action-btn mark-all-btn" onclick="markAllNotificationsAsRead()">
                <i class="fas fa-check-double"></i> Mark all read
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-group">
            <button class="filter-btn active" data-filter="all" onclick="filterNotifications('all')">
                All <span class="count-badge" id="countAll">{{ $totalCount ?? 0 }}</span>
            </button>
            <button class="filter-btn" data-filter="unread" onclick="filterNotifications('unread')">
                Unread <span class="count-badge" id="countUnread">{{ $unreadCount ?? 0 }}</span>
            </button>
            <button class="filter-btn" data-filter="read" onclick="filterNotifications('read')">
                Read <span class="count-badge" id="countRead">{{ $readCount ?? 0 }}</span>
            </button>
        </div>
    </div>

    <!-- Notifications List Container -->
    <div class="notifications-list-wrapper">
        <div id="notificationsList" class="notifications-list">
            @if(isset($notifications) && count($notifications) > 0)
                @foreach($notifications as $notification)
                    @php
                        $isRead = !is_null($notification->read_at);
                        $readClass = $isRead ? 'read' : 'unread';
                        
                        $extraData = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;
                        $tokenNum = $notification->token_number ?? ($extraData['token'] ?? null);
                        $title = $notification->title ?? ($extraData['title'] ?? 'Notification');
                        $message = $notification->message ?? ($extraData['message'] ?? '');
                        $type = $notification->type ?? ($extraData['type'] ?? 'general');
                    @endphp
                    <div class="notification-item {{ $readClass }}" data-id="{{ $notification->id }}" onclick="markNotificationAsRead({{ $notification->id }})">
                        <div class="notification-icon">
                            @if(str_contains($type, 'cancel'))
                                <i class="fas fa-times"></i>
                            @elseif(str_contains($type, 'call') || str_contains($type, 'next'))
                                <i class="fas fa-phone"></i>
                            @elseif(str_contains($type, 'complete') || str_contains($type, 'arrive'))
                                <i class="fas fa-check"></i>
                            @else
                                <i class="fas fa-bell"></i>
                            @endif
                        </div>
                        
                        <div class="notification-content">
                            <div class="notification-title-row">
                                <span class="notification-title-text">{{ $title }}</span>
                                @if($tokenNum)
                                    <span class="token-badge">Token #{{ $tokenNum }}</span>
                                @endif
                            </div>
                            <div class="notification-message">{{ $message }}</div>
                            <div class="notification-time">{{ $notification->created_at ? $notification->created_at->diffForHumans() : 'Just now' }}</div>
                        </div>

                        <div class="notification-status">
                            @if(!$isRead)
                                <span class="unread-dot">●</span>
                            @else
                                <span class="read-check">✓</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-icon">🔕</div>
                    <h3>No Notifications Found</h3>
                    <p>You have no notification alerts at this moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .notifications-container {
        padding: 24px;
        max-width: 1100px;
        margin: 0 auto;
    }

    .notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 4px 0;
    }

    .page-subtitle {
        font-size: 14px;
        color: #64748b;
        margin: 0;
    }

    .action-btn {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #0284c7;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-bar {
        background: rgba(255, 255, 255, 0.7);
        padding: 8px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
    }

    .filter-group {
        display: flex;
        gap: 8px;
    }

    .filter-btn {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #475569;
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-btn.active {
        background: #e0f2fe !important;
        color: #0369a1 !important;
        border-color: #7dd3fc !important;
    }

    .count-badge {
        background: #f1f5f9;
        color: #475569;
        font-size: 11px;
        padding: 2px 7px;
        border-radius: 12px;
        font-weight: 700;
    }

    .filter-btn.active .count-badge {
        background: #0284c7 !important;
        color: #ffffff !important;
    }

    .notifications-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .notification-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        cursor: pointer;
    }

    .notification-item.unread {
        border-left: 4px solid #0ea5e9;
    }

    /* Target all Round/Pill Shape Badges */
    .token-badge,
    .token-number-badge,
    .badge,
    [class*="badge"],
    .notification-title-row span:not(.notification-title-text) {
        background-color: #e0f2fe !important;
        color: #0369a1 !important;
        border: 1px solid #bae6fd !important;
        border-radius: 20px !important;
        padding: 3px 12px !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        display: inline-flex !important;
        align-items: center !important;
    }

    .notification-icon {
        width: 36px;
        height: 36px;
        border-radius: 50% !important;
        background-color: #e0f2fe !important;
        color: #0284c7 !important;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-title-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 4px;
    }

    .notification-title-text {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
    }

    .notification-message {
        font-size: 13px;
        color: #475569;
        margin-bottom: 4px;
    }

    .notification-time {
        font-size: 12px;
        color: #94a3b8;
    }

    .unread-dot {
        color: #0ea5e9;
        font-size: 12px;
    }

    .read-check {
        color: #cbd5e1;
        font-size: 14px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
</style>

<script>
    function filterNotifications(filter) {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            if (btn.getAttribute('data-filter') === filter) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        const items = document.querySelectorAll('.notification-item');
        items.forEach(item => {
            if (filter === 'all') {
                item.style.display = 'flex';
            } else if (filter === 'unread') {
                item.style.display = item.classList.contains('unread') ? 'flex' : 'none';
            } else if (filter === 'read') {
                item.style.display = item.classList.contains('read') ? 'flex' : 'none';
            }
        });
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
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const item = document.querySelector(`.notification-item[data-id="${id}"]`);
                if (item) {
                    item.classList.remove('unread');
                    item.classList.add('read');
                    const statusDot = item.querySelector('.notification-status');
                    if (statusDot) statusDot.innerHTML = '<span class="read-check">✓</span>';
                }
            }
        });
    }

    function markAllNotificationsAsRead() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
</script>
@endsection