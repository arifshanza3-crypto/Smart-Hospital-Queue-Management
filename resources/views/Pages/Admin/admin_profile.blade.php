@extends('Layout.admin-layout')

@section('page-title', 'My Profile')
@section('breadcrumb', 'Profile Settings')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* ============================================
       PROFILE PAGE - YOUR COLOR THEME
       ============================================ */
    
    :root {
        --primary-dark: #0c1220;
        --primary-mid: #0f1a2e;
        --primary-light: #142a42;
        --accent-1: #0ea5e9;
        --accent-2: #3b82f6;
        --accent-3: #06b6d4;
        --accent-gradient: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 50%, #06b6d4 100%);
        --bg-primary: #f0f4f8;
        --bg-card: #ffffff;
        --text-primary: #1a202c;
        --text-secondary: #4a5568;
        --text-muted: #a0aec0;
        --border-color: #e2e8f0;
        --shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
        --shadow-hover: 0 20px 60px rgba(0, 0, 0, 0.1);
        --success: #10b981;
        --danger: #ef4444;
    }

    .profile-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px;
    }

    /* ===== PROFILE CARD ===== */
    .profile-card {
        background: var(--bg-card);
        border-radius: 24px;
        box-shadow: var(--shadow);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
    }

    .profile-card:hover {
        box-shadow: var(--shadow-hover);
    }

    /* ===== PROFILE HEADER ===== */
    .profile-header {
        background: linear-gradient(180deg, var(--primary-dark) 0%, var(--primary-mid) 50%, var(--primary-light) 100%);
        padding: 50px 40px 40px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    /* Animated Background Glows */
    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(ellipse at 30% 20%, rgba(14, 165, 233, 0.08) 0%, transparent 60%);
        pointer-events: none;
        animation: glow-float 20s ease-in-out infinite;
    }

    .profile-header::after {
        content: '';
        position: absolute;
        bottom: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(ellipse at 70% 80%, rgba(6, 182, 212, 0.08) 0%, transparent 60%);
        pointer-events: none;
        animation: glow-float 25s ease-in-out infinite reverse;
    }

    @keyframes glow-float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(10%, -10%) scale(1.1); }
        66% { transform: translate(-10%, 10%) scale(0.9); }
    }

    .profile-avatar-wrapper {
        position: relative;
        display: inline-block;
        z-index: 1;
    }

    .profile-avatar {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: var(--accent-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        border: 4px solid rgba(14, 165, 233, 0.3);
        box-shadow: 0 10px 40px rgba(14, 165, 233, 0.2);
        overflow: hidden;
        position: relative;
        transition: all 0.3s ease;
    }

    .profile-avatar:hover {
        transform: scale(1.02);
        border-color: rgba(14, 165, 233, 0.6);
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-avatar .initials {
        font-size: 52px;
        color: white;
        font-weight: 700;
        text-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        letter-spacing: 2px;
    }

    .avatar-upload-btn {
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent-1);
        border: 3px solid white;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 2;
    }

    .avatar-upload-btn:hover {
        transform: scale(1.1);
        background: var(--accent-gradient);
        color: white;
    }

    .avatar-upload-btn input[type="file"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 3;
    }

    /* ===== USERNAME - SMALLER SIZE ===== */
    .profile-name {
        color: white;
        margin-top: 18px;
        font-size: 22px;
        font-weight: 600;
        position: relative;
        z-index: 1;
        letter-spacing: -0.3px;
    }

    .profile-email {
        color: rgba(255, 255, 255, 0.7);
        font-size: 14px;
        position: relative;
        z-index: 1;
    }

    .profile-badges {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 12px;
        position: relative;
        z-index: 1;
        flex-wrap: wrap;
    }

    .profile-badge {
        padding: 5px 18px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.08);
        color: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.06);
        backdrop-filter: blur(10px);
    }

    .profile-badge i {
        margin-right: 6px;
    }

    .profile-badge.role {
        background: rgba(14, 165, 233, 0.2);
        border-color: rgba(14, 165, 233, 0.2);
        color: #0ea5e9;
    }

    .profile-badge.active {
        color: #10b981;
        border-color: rgba(16, 185, 129, 0.2);
        background: rgba(16, 185, 129, 0.1);
    }

    .profile-member-since {
        color: rgba(255, 255, 255, 0.4);
        font-size: 12px;
        margin-top: 12px;
        position: relative;
        z-index: 1;
    }

    .profile-member-since i {
        margin-right: 4px;
    }

    /* ===== PROFILE BODY ===== */
    .profile-body {
        padding: 35px 40px 40px;
    }

    /* ===== TABS ===== */
    .profile-tabs {
        display: flex;
        gap: 8px;
        background: #f7fafc;
        padding: 6px;
        border-radius: 14px;
        margin-bottom: 30px;
        border: 1px solid var(--border-color);
    }

    .profile-tab {
        flex: 1;
        padding: 12px 20px;
        border: none;
        background: transparent;
        font-weight: 600;
        font-size: 14px;
        color: var(--text-secondary);
        cursor: pointer;
        border-radius: 10px;
        transition: all 0.3s ease;
        font-family: 'Inter', system-ui, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .profile-tab:hover:not(.active) {
        background: rgba(14, 165, 233, 0.05);
        color: var(--text-primary);
    }

    .profile-tab.active {
        background: var(--accent-gradient);
        color: white;
        box-shadow: 0 4px 20px rgba(14, 165, 233, 0.3);
    }

    .profile-tab i {
        font-size: 16px;
    }

    /* ===== TAB CONTENT ===== */
    .tab-content {
        display: none;
        animation: fadeSlide 0.4s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeSlide {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ===== FORM ELEMENTS ===== */
    .form-group {
        margin-bottom: 22px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 14px;
    }

    .form-group label i {
        margin-right: 8px;
        color: var(--accent-1);
        width: 18px;
    }

    .form-group label .required {
        color: var(--danger);
        margin-left: 2px;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        transition: all 0.3s ease;
        font-size: 14px;
        font-family: 'Inter', system-ui, sans-serif;
        background: #f7fafc;
        color: var(--text-primary);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent-1);
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.08);
        background: white;
    }

    .form-control.is-invalid {
        border-color: var(--danger);
    }

    .text-muted {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    /* ===== BUTTONS ===== */
    .btn-primary {
        background: var(--accent-gradient);
        color: white;
        padding: 12px 32px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Inter', system-ui, sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 20px rgba(14, 165, 233, 0.25);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(14, 165, 233, 0.35);
    }

    /* ===== LOGOUT BUTTON - FULL WIDTH CENTERED ===== */
    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 14px 32px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Inter', system-ui, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.2);
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(239, 68, 68, 0.35);
    }

    /* ===== ALERTS ===== */
    .alert-modern {
        padding: 14px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-left: 4px solid;
        background: #f7fafc;
    }

    .alert-modern.success {
        border-color: var(--success);
        color: #065f46;
    }

    .alert-modern.success i {
        color: var(--success);
    }

    .alert-modern.error {
        border-color: var(--danger);
        color: #991b1b;
    }

    .alert-modern.error i {
        color: var(--danger);
    }

    /* ===== LOGOUT SECTION - FULL WIDTH ===== */
    .logout-section {
        margin-top: 30px;
        padding-top: 24px;
        border-top: 2px solid var(--border-color);
    }

    .logout-section form {
        display: block;
        width: 100%;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .profile-container {
            padding: 16px;
        }

        .profile-header {
            padding: 35px 20px 30px;
        }

        .profile-body {
            padding: 24px 20px;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
        }

        .profile-avatar .initials {
            font-size: 38px;
        }

        .profile-name {
            font-size: 20px;
        }

        .profile-tabs {
            flex-direction: column;
        }

        .profile-tab {
            padding: 10px;
        }

        .profile-badges {
            flex-direction: column;
            align-items: center;
        }

        .btn-danger {
            padding: 12px 24px;
            font-size: 14px;
        }
    }

    @media (max-width: 576px) {
        .profile-container {
            padding: 12px;
        }

        .profile-header {
            padding: 28px 16px 24px;
        }

        .profile-body {
            padding: 16px;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
        }

        .profile-avatar .initials {
            font-size: 30px;
        }

        .profile-name {
            font-size: 18px;
        }

        .btn-primary {
            width: 100%;
            justify-content: center;
        }

        .btn-danger {
            padding: 12px 20px;
            font-size: 13px;
        }

        .avatar-upload-btn {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-card">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar-wrapper">
                <div class="profile-avatar">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name ?? $user->full_name }}">
                    @else
                        <span class="initials">{{ strtoupper(substr($user->name ?? $user->full_name, 0, 2)) }}</span>
                    @endif
                </div>
                <div class="avatar-upload-btn">
                    <i class="fas fa-camera"></i>
                    <input type="file" name="avatar" accept="image/*" form="profileForm">
                </div>
            </div>

            <div class="profile-name">{{ $user->name ?? $user->full_name }}</div>
            <div class="profile-email">{{ $user->email }}</div>

            <div class="profile-badges">
                <span class="profile-badge role">
                    <i class="fas fa-user-shield"></i> {{ ucfirst($user->role ?? 'Admin') }}
                </span>
                <span class="profile-badge active">
                    <i class="fas fa-circle" style="font-size: 8px;"></i> Active
                </span>
                <span class="profile-badge">
                    <i class="fas fa-calendar-alt"></i> {{ $user->created_at->format('d M Y') }}
                </span>
            </div>

            <div class="profile-member-since">
                <i class="fas fa-clock"></i> Member since {{ $user->created_at->format('F d, Y') }}
            </div>
        </div>

        <!-- Profile Body -->
        <div class="profile-body">
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert-modern success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-modern error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-modern error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul style="margin: 4px 0 0 20px; padding: 0;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Tabs -->
            <div class="profile-tabs">
                <button class="profile-tab active" onclick="showTab('edit-profile', this)">
                    <i class="fas fa-user-edit"></i> Edit Profile
                </button>
                <button class="profile-tab" onclick="showTab('change-password', this)">
                    <i class="fas fa-key"></i> Change Password
                </button>
            </div>

            <!-- Tab 1: Edit Profile -->
            <div id="tab-edit-profile" class="tab-content active">
                <form id="profileForm" method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-user"></i> Full Name <span class="required">*</span>
                        </label>
                        <input type="text" name="name" class="form-control" 
                               id="name" value="{{ $user->name ?? $user->full_name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email Address <span class="required">*</span>
                        </label>
                        <input type="email" name="email" class="form-control" 
                               id="email" value="{{ $user->email }}" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">
                            <i class="fas fa-phone"></i> Phone Number
                        </label>
                        <input type="text" name="phone" class="form-control" 
                               id="phone" value="{{ $user->phone ?? '' }}" placeholder="Enter phone number">
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </form>
            </div>

            <!-- Tab 2: Change Password -->
            <div id="tab-change-password" class="tab-content">
                <form method="POST" action="{{ route('admin.profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="current_password">
                            <i class="fas fa-lock"></i> Current Password <span class="required">*</span>
                        </label>
                        <input type="password" name="current_password" class="form-control" 
                               id="current_password" placeholder="Enter current password" required>
                    </div>

                    <div class="form-group">
                        <label for="new_password">
                            <i class="fas fa-key"></i> New Password <span class="required">*</span>
                        </label>
                        <input type="password" name="new_password" class="form-control" 
                               id="new_password" placeholder="Enter new password" required>
                        <div class="text-muted">Password must be at least 8 characters</div>
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation">
                            <i class="fas fa-check-circle"></i> Confirm Password <span class="required">*</span>
                        </label>
                        <input type="password" name="new_password_confirmation" class="form-control" 
                               id="new_password_confirmation" placeholder="Confirm new password" required>
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Change Password
                    </button>
                </form>
            </div>

            <!-- Logout Section - Full Width Centered -->
            <div class="logout-section">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // ============================================
    // TAB SWITCHING
    // ============================================
    function showTab(tabId, button) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });

        // Remove active class from all buttons
        document.querySelectorAll('.profile-tab').forEach(btn => {
            btn.classList.remove('active');
        });

        // Show selected tab
        document.getElementById('tab-' + tabId).classList.add('active');

        // Add active class to clicked button
        button.classList.add('active');
    }

    // ============================================
    // AVATAR UPLOAD PREVIEW
    // ============================================
    document.querySelector('input[name="avatar"]').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const container = document.querySelector('.profile-avatar');
                const initials = container.querySelector('.initials');
                let img = container.querySelector('img');
                if (!img) {
                    img = document.createElement('img');
                    container.appendChild(img);
                }
                img.src = e.target.result;
                if (initials) {
                    initials.remove();
                }
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection