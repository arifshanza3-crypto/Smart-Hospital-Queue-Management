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
@endsection