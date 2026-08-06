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
                <span class="notification-count" id="totalCount">0</span>
                @auth
                    <span class="user-role-badge">
                        <span class="role-tag {{ auth()->user()->role }}">
                            <i class="fas {{ auth()->user()->role === 'admin' ? 'fa-user-shield' : (auth()->user()->role === 'staff' ? 'fa-user-tie' : 'fa-user') }}"></i>
                            {{ ucfirst(auth()->user()->role ?? 'User') }}
                        </span>
                    </span>
                @endauth
            </div>
            <div class="header-right">
                <button class="btn-mark-all" id="markAllBtn" onclick="markAllRead()">
                    <i class="fas fa-check-double"></i> Mark all as read
                </button>
                <button class="btn-refresh" onclick="loadNotifications()">
                    <i class="fas fa-sync"></i> Refresh
                </button>
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="notifications-filter">
            <button class="filter-btn active" data-filter="all" onclick="filterNotifications('all')">
                All <span class="count" id="countAll">0</span>
            </button>
            <button class="filter-btn" data-filter="unread" onclick="filterNotifications('unread')">
                Unread <span class="count" id="countUnread">0</span>
            </button>
            <button class="filter-btn" data-filter="read" onclick="filterNotifications('read')">
                Read <span class="count" id="countRead">0</span>
            </button>
        </div>

        {{-- NOTIFICATIONS LIST --}}
        <div class="notifications-list" id="notificationsList">
            <div class="empty-state">
                <div class="empty-icon">🔕</div>
                <h3>No Notifications</h3>
                <p>You don't have any notifications yet.</p>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="notifications-footer">
            Showing <span id="shownCount">0</span> notifications
        </div>

        {{-- BACK BUTTON --}}
        <div class="text-center mt-3">
            <a href="{{ auth()->user()->role === 'admin' ? '/admin/dashboard' : (auth()->user()->role === 'staff' ? '/staff/dashboard' : '/') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        {{-- DEBUG --}}
        <div class="debug-info">
            <strong>Debug:</strong> User ID: {{ auth()->id() }} | Role: {{ auth()->user()->role ?? 'N/A' }} | Notifications: <span id="debugCount">0</span> | Unread: <span id="debugUnread">0</span>
        </div>

    </div>
</div>

<script src="{{ asset('js/Notification.js') }}"></script>

<style>
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
        box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        font-size: 14px;
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .toast-success {
        background: #10b981;
        color: #fff;
    }
    
    .toast-error {
        background: #ef4444;
        color: #fff;
    }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .btn-mark-all:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .debug-info {
        margin-top: 20px;
        padding: 12px 20px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 10px;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.2);
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    .debug-info strong {
        color: rgba(255, 255, 255, 0.3);
    }

    .new-tag {
        font-size: 9px;
        background: #00d4ff;
        color: #071a1c;
        padding: 1px 10px;
        border-radius: 20px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-left: 8px;
    }
</style>

@endsection