<nav class="admin-nav">
    <div class="nav-left">
        <div class="nav-title">
            <span class="system-label">System Overview</span>
            <span class="dashboard-title">Admin Dashboard</span>
        </div>
    </div>

    <div class="nav-right">
        <div class="live-status">
            <span class="live-dot"></span>
            Live
        </div>

        <div class="notification-bell">
            <i class="fas fa-bell"></i>
            <span class="notification-badge">3</span>
        </div>

        <div class="admin-profile">
            <div class="profile-icon">AD</div>
        </div>
    </div>
</nav>

<style>
    .admin-nav {
        position: fixed;
        top: 20px;
        left: 300px;
        right: 20px;
        height: 70px;
        background: #0b2e33;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 30px;
        border: 1px solid #3e8686;
        border-radius: 15px;
        z-index: 999;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }

    .nav-left {
        display: flex;
        align-items: center;
    }

    .nav-title {
        display: flex;
        flex-direction: column;
    }

    .system-label {
        font-size: 10px;
        color: #00d4ff;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 700;
    }

    .dashboard-title {
        font-size: 18px;
        font-weight: 800;
        color: #00d4ff;
    }

    .nav-right {
        display: flex;
        align-items: center;
        gap: 25px;
    }

    .live-status {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #00d4ff;
        font-weight: 600;
    }

    .live-dot {
        width: 8px;
        height: 8px;
        background: #00d4ff;
        border-radius: 50%;
        box-shadow: 0 0 8px #00d4ff;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
        100% { opacity: 1; transform: scale(1); }
    }

    .notification-bell {
        position: relative;
        color: #00d4ff;
        cursor: pointer;
    }

    .notification-bell i {
        font-size: 20px;
    }

    .notification-badge {
        position: absolute;
        top: -8px;
        right: -10px;
        background: #ff4d4d;
        color: white;
        font-size: 9px;
        padding: 2px 6px;
        border-radius: 50%;
        font-weight: bold;
    }

    .admin-profile {
        padding-left: 20px;
        border-left: 1px solid rgba(62, 134, 134, 0.5);
    }

    .profile-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 2px solid #00d4ff;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 212, 255, 0.1);
        color: #00d4ff;
        font-weight: bold;
        cursor: pointer;
    }
</style>
