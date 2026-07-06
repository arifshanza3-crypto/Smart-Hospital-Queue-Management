<div class="sidebar">
    <div class="logo-section">
        <img src="{{ asset('Assert/logo.png') }}" alt="Logo">
        <div class="logo-text">
            <span class="brand-name">Smart Queue</span>
            <span class="brand-sub">Management System</span>
        </div>
    </div>

    <a href="{{ url('/admin/doctor-management') }}" class="nav-item {{ request()->is('*doctor-management*') ? 'active' : '' }}">
        <i class="fas fa-user-md"></i> Doctors Management
    </a>

    <a href="{{ url('/admin/services-management') }}" class="nav-item {{ request()->is('*services-management*') ? 'active' : '' }}">
        <i class="fas fa-hand-holding-medical"></i> Services Management
    </a>

    <a href="{{ url('/admin/user-management') }}" class="nav-item {{ request()->is('*user-management*') ? 'active' : '' }}">
        <i class="fas fa-user-gear"></i> User Management
    </a>

    <div class="divider"></div>

    <div class="menu-label">Data Analytics</div>
    
    <a href="{{ url('/admin/report') }}" class="nav-item {{ request()->is('*report*') ? 'active' : '' }}">
        <i class="fas fa-file-chart-column"></i> Queue Reports
    </a>

    <a href="{{ url('/admin/settings') }}" class="nav-item {{ request()->is('*settings*') ? 'active' : '' }}">
        <i class="fas fa-gears"></i> Settings
    </a>

    <div class="logout-wrapper">
        <a href="#" class="nav-item logout-btn">
            <i class="fas fa-power-off"></i> Logout
        </a>
    </div>
</div>

<style>
    :root {
        --sidebar-width: 280px;
        --primary-bg: #0b2e33;
        --accent-blue: #00d4ff;
        --border-color: #3e8686;
    }

    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        background-color: var(--primary-bg);
        color: var(--accent-blue);
        display: flex;
        flex-direction: column;
        padding: 20px 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        position: fixed;
        left: 0;
        top: 0;
        overflow-y: auto;
        border-right: 1px solid var(--border-color);
        z-index: 1000;
    }

    .logo-section {
        padding: 10px 15px 35px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 12px;
    }

    .logo-section img {
        width: 120px;
        height: auto;
        filter: drop-shadow(0px 0px 8px var(--accent-blue));
    }

    .logo-text {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .brand-name {
        font-size: 22px;
        font-weight: 800;
        letter-spacing: 0.5px;
        line-height: 1.1;
         color: white; 
    }

    .brand-sub {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 600;
        opacity: 0.8;
        margin-top: 4px;
        color: white;
    }

    .menu-label {
        padding: 10px 25px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        opacity: 0.8;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .nav-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        text-decoration: none;
        color: var(--accent-blue);
        transition: all 0.3s ease;
        margin: 4px 15px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 500;
    }

    .nav-item i {
        margin-right: 15px;
        width: 20px;
        text-align: center;
        font-size: 18px;
    }

    .nav-item:hover {
        background-color: rgba(0, 212, 255, 0.1);
        padding-left: 25px;
    }

    .nav-item.active {
        background-color: var(--accent-blue) !important;
        color: var(--primary-bg) !important;
        font-weight: 700;
        box-shadow: 0 0 15px rgba(0, 212, 255, 0.4);
    }

    .divider {
        height: 1px;
        background: var(--border-color);
        margin: 15px 20px;
        opacity: 0.3;
    }

    .logout-wrapper {
        margin-top: auto;
        padding-bottom: 20px;
    }

    .logout-btn {
        border: 1px solid var(--border-color);
    }

    .logout-btn:hover {
        background-color: #00b8e6;
        color: var(--primary-bg) !important;
    }
</style>