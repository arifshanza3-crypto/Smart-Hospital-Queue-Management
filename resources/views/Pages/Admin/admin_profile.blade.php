@extends('Layout.admin-layout')

@section('page-title', 'My Profile')
@section('breadcrumb', 'Profile Settings')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    .profile-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 0 15px;
    }

    .profile-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 30px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .profile-header {
        background: linear-gradient(135deg, #0b2e33, #1a4a50);
        padding: 40px;
        text-align: center;
        position: relative;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #00d4ff, #0b2e33);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        border: 4px solid white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        overflow: hidden;
        position: relative;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-avatar .initials {
        font-size: 48px;
        color: white;
        font-weight: 600;
    }

    .profile-name {
        color: white;
        margin-top: 15px;
        font-size: 24px;
        font-weight: 600;
    }

    .profile-email {
        color: #a0d4d9;
        font-size: 14px;
    }

    .profile-role {
        display: inline-block;
        background: #00d4ff;
        color: #0b2e33;
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 8px;
    }

    .profile-body {
        padding: 30px 40px;
    }

    .profile-tabs {
        display: flex;
        gap: 10px;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .profile-tab {
        padding: 10px 20px;
        border: none;
        background: transparent;
        font-weight: 600;
        color: #666;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .profile-tab.active {
        background: linear-gradient(135deg, #00d4ff, #0b2e33);
        color: white;
    }

    .profile-tab:hover:not(.active) {
        background: #f5f5f5;
    }

    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #0b2e33;
        font-size: 14px;
    }

    .form-group label i {
        margin-right: 8px;
        color: #00d4ff;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        transition: all 0.3s ease;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
    }

    .form-control:focus {
        outline: none;
        border-color: #00d4ff;
        box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
    }

    .btn-save {
        background: linear-gradient(135deg, #00d4ff, #0b2e33);
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,212,255,0.3);
    }

    .btn-danger {
        background: #dc3545;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .avatar-upload {
        position: relative;
        display: inline-block;
    }

    .avatar-upload input[type="file"] {
        position: absolute;
        bottom: 0;
        right: 0;
        opacity: 0;
        width: 36px;
        height: 36px;
        cursor: pointer;
        z-index: 2;
    }

    .avatar-upload .upload-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 36px;
        height: 36px;
        background: #00d4ff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border: 2px solid white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        cursor: pointer;
        z-index: 1;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 12px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #28a745;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        padding: 12px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .text-muted {
        font-size: 12px;
        color: #999;
        margin-top: 5px;
    }

    @media (max-width: 768px) {
        .profile-body {
            padding: 20px;
        }
        .profile-tabs {
            flex-direction: column;
        }
        .profile-tab {
            text-align: center;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-card">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name ?? $user->full_name }}">
                @else
                    <span class="initials">{{ strtoupper(substr($user->name ?? $user->full_name, 0, 2)) }}</span>
                @endif
                <div class="avatar-upload">
                    <div class="upload-btn">
                        <i class="fas fa-camera"></i>
                    </div>
                    <input type="file" name="avatar" accept="image/*" form="profileForm">
                </div>
            </div>
            <div class="profile-name">{{ $user->name ?? $user->full_name }}</div>
            <div class="profile-email">{{ $user->email }}</div>
            <div class="profile-role">
                <i class="fas fa-user-shield"></i> {{ ucfirst($user->role ?? 'Admin') }}
            </div>
            <div style="color: #a0d4d9; font-size: 12px; margin-top: 5px;">
                <i class="fas fa-clock"></i> Member since {{ $user->created_at->format('d M Y') }}
            </div>
        </div>

        <!-- Profile Body -->
        <div class="profile-body">
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Tabs -->
            <div class="profile-tabs">
                <button class="profile-tab active" onclick="showTab('edit-profile')">
                    <i class="fas fa-user-edit"></i> Edit Profile
                </button>
                <button class="profile-tab" onclick="showTab('change-password')">
                    <i class="fas fa-key"></i> Change Password
                </button>
            </div>

            <!-- Tab 1: Edit Profile -->
            <div id="tab-edit-profile" class="tab-content active">
                <form id="profileForm" method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name ?? $user->full_name }}" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ $user->phone ?? '' }}" placeholder="Enter phone number">
                    </div>

                    <button type="submit" class="btn-save">
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
                        <label><i class="fas fa-lock"></i> Current Password</label>
                        <input type="password" name="current_password" class="form-control" placeholder="Enter current password" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-key"></i> New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
                        <div class="text-muted">Password must be at least 8 characters</div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-check-circle"></i> Confirm Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control" placeholder="Confirm new password" required>
                    </div>

                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Change Password
                    </button>
                </form>
            </div>

            <!-- Logout Button -->
            <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #f0f0f0;">
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
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
    function showTab(tabId) {
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
        event.target.classList.add('active');
    }

    // Avatar upload preview
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
                if (initials) initials.remove();
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection