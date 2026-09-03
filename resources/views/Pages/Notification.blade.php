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
    .notifications-list {
        background: transparent !important;
        padding: 0 !important;
        border: none !important;
    }

    .notification-item {
        display: flex !important;
        align-items: flex-start !important;
        border-radius: 12px !important;
        padding: 16px 20px !important;
        margin-bottom: 12px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        transition: all 0.2s ease !important;
    }

    /* UNREAD CARD */
    .notification-item.unread {
        background: #0d3b42 !important;
        border-left: 5px solid #00d2d3 !important;
    }

    /* READ CARD */
    .notification-item.read {
        background: #144850 !important;
        border-left: 5px solid transparent !important;
    }

    /* ICONS */
    .notification-icon {
        color: #ffffff !important;
        font-size: 18px !important;
        margin-right: 16px !important;
        margin-top: 2px !important;
        min-width: 24px !important;
    }

    /* TITLES & HIGH-CONTRAST TEXT FIX */
    .notification-title {
        color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 15px !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    .notification-title span {
        color: #ffffff !important;
    }

    .notification-message {
        color: #ffffff !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        margin-top: 4px !important;
        margin-bottom: 6px !important;
        opacity: 0.95 !important;
    }

    .notification-token {
        background: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
        padding: 2px 10px !important;
        border-radius: 20px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        display: inline-block !important;
        margin-bottom: 6px !important;
    }

    .notification-time {
        color: #80f6f7 !important;
        font-size: 12px !important;
        font-weight: 600 !important;
    }

    /* BADGES & DOTS */
    .new-tag {
        background: #00d2d3 !important;
        color: #0d3b42 !important;
        padding: 2px 8px !important;
        border-radius: 12px !important;
        font-size: 10px !important;
        font-weight: 800 !important;
    }

    .unread-dot {
        color: #00d2d3 !important;
        font-size: 12px !important;
    }

    .read-check {
        color: #80f6f7 !important;
        font-size: 16px !important;
    }
</style>
@endsection