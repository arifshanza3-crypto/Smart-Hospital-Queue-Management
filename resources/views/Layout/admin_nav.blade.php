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

        <!-- ===== PROFILE DROPDOWN ===== -->
        <div class="admin-profile" style="position: relative;">
            <div class="profile-icon" onclick="toggleProfileDropdown()" style="cursor: pointer;">
                @auth
                    @if(auth()->user()->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    @endif
                @else
                    AD
                @endauth
            </div>

            <!-- Dropdown Menu -->
            <div id="profileDropdown" style="
                display: none;
                position: absolute;
                right: 0;
                top: 50px;
                background: #0b2e33;
                border-radius: 12px;
                min-width: 220px;
                padding: 10px 0;
                box-shadow: 0 10px 40px rgba(0,0,0,0.3);
                border: 1px solid #3e8686;
                z-index: 1000;
            ">
                @auth
                <div style="padding: 12px 20px; border-bottom: 1px solid #3e8686;">
                    <div style="color: #00d4ff; font-weight: 600;">{{ auth()->user()->name }}</div>
                    <div style="color: #a0d4d9; font-size: 12px;">{{ auth()->user()->email }}</div>
                    <div style="color: #a0d4d9; font-size: 11px; opacity: 0.7; margin-top: 4px;">
                        <i class="fas fa-user-shield"></i> {{ ucfirst(auth()->user()->role ?? 'Admin') }}
                    </div>
                </div>

                <a href="{{ route('admin.profile.index') }}" style="
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    padding: 10px 20px;
                    color: #a0d4d9;
                    text-decoration: none;
                    transition: all 0.2s ease;
                " onmouseover="this.style.background='rgba(0,212,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <i class="fas fa-user" style="color: #00d4ff; width: 18px;"></i> My Profile
                </a>

                <a href="{{ route('admin.settings.index') }}" style="
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    padding: 10px 20px;
                    color: #a0d4d9;
                    text-decoration: none;
                    transition: all 0.2s ease;
                " onmouseover="this.style.background='rgba(0,212,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <i class="fas fa-cog" style="color: #00d4ff; width: 18px;"></i> Settings
                </a>

                <div style="border-top: 1px solid #3e8686; margin: 5px 15px;"></div>

                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" style="
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        padding: 10px 20px;
                        background: none;
                        border: none;
                        width: 100%;
                        text-align: left;
                        color: #ff4d4d;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        font-size: 14px;
                    " onmouseover="this.style.background='rgba(255,77,77,0.1)'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-sign-out-alt" style="width: 18px;"></i> Logout
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 20px; color: #a0d4d9; text-decoration: none;">
                    <i class="fas fa-sign-in-alt" style="color: #00d4ff;"></i> Login
                </a>
                @endauth
            </div>
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
        overflow: hidden;
        font-size: 14px;
    }

    .profile-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Dropdown hover effect */
    #profileDropdown a:hover,
    #profileDropdown button:hover {
        background: rgba(0, 212, 255, 0.1);
    }
</style>

<script>
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        if (dropdown.style.display === 'none') {
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profileDropdown');
        const profileIcon = document.querySelector('.profile-icon');
        if (dropdown && profileIcon && !profileIcon.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });

    // Close dropdown on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) {
                dropdown.style.display = 'none';
            }
        }
    });
</script>