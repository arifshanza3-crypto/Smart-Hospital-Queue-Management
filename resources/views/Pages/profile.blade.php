@extends('Layout.app')

@section('title', 'My Profile')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    * {
        font-family: 'Inter', 'Segoe UI', sans-serif;
        box-sizing: border-box;
    }

    .profile-container {
        max-width: 1200px;
        margin: 30px auto;
        padding: 0 20px;
    }

    /* Alert Messages */
    .alert {
        padding: 14px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
        animation: slideDown 0.3s ease;
    }
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    /* Profile Header */
    .profile-header {
        background: white;
        border-radius: 16px;
        padding: 30px 35px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #6366f1, #8b5cf6, #a855f7);
    }

    .profile-avatar-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #e5e7eb;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .profile-avatar:hover {
        border-color: #6366f1;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-avatar .initials {
        font-size: 36px;
        font-weight: 700;
        color: white;
        text-transform: uppercase;
    }

    .avatar-upload-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #6366f1;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid white;
        transition: all 0.3s ease;
    }

    .avatar-upload-btn:hover {
        transform: scale(1.1);
        background: #4f46e5;
    }

    .avatar-upload-btn i {
        color: white;
        font-size: 14px;
    }

    .profile-info {
        flex: 1;
        min-width: 200px;
    }

    .profile-info h1 {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px 0;
    }

    .profile-info .email {
        color: #6b7280;
        font-size: 14px;
        margin: 0 0 10px 0;
    }

    .profile-badges {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* Badge Styles */
    .badge {
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-admin {
        background: #e0e7ff;
        color: #4338ca;
    }

    .badge-staff {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .badge-user {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-status {
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .profile-actions {
        margin-left: auto;
        display: flex;
        gap: 10px;
    }

    .btn-edit {
        background: #6366f1;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-edit:hover {
        background: #4f46e5;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
        color: white;
        text-decoration: none;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px 24px;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transform: translateY(-2px);
    }

    .stat-card .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-bottom: 10px;
    }

    .stat-icon-purple {
        background: #e0e7ff;
        color: #4338ca;
    }

    .stat-icon-blue {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .stat-icon-green {
        background: #d1fae5;
        color: #065f46;
    }

    .stat-icon-yellow {
        background: #fef3c7;
        color: #92400e;
    }

    .stat-icon-red {
        background: #fee2e2;
        color: #991b1b;
    }

    .stat-card .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .stat-card .stat-label {
        font-size: 14px;
        color: #6b7280;
        margin: 4px 0 0 0;
    }

    /* Profile Content */
    .profile-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 28px 30px;
        border: 1px solid #e5e7eb;
    }

    .profile-card.full-width {
        grid-column: 1 / -1;
    }

    .profile-card .card-title {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 2px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .profile-card .card-title i {
        color: #6366f1;
        font-size: 18px;
        width: 24px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f3f4f6;
        align-items: center;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-row .label {
        color: #6b7280;
        font-size: 13px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-row .label i {
        color: #9ca3af;
        font-size: 14px;
        width: 18px;
    }

    .info-row .value {
        color: #111827;
        font-weight: 500;
        font-size: 14px;
        text-align: right;
        max-width: 60%;
        word-break: break-word;
    }

    .info-row .value.text-muted {
        color: #9ca3af;
        font-weight: 400;
    }

    /* Permissions */
    .permissions-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 4px;
    }

    .permission-pill {
        background: #f3f4f6;
        color: #374151;
        padding: 4px 14px;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .permission-pill i {
        color: #6366f1;
        font-size: 11px;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .profile-content {
            grid-template-columns: 1fr;
        }
        
        .profile-actions {
            margin-left: 0;
            width: 100%;
        }
        
        .btn-edit {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
            padding: 24px 20px;
        }
        
        .profile-badges {
            justify-content: center;
        }
        
        .profile-info {
            text-align: center;
        }
        
        .profile-actions {
            flex-direction: column;
        }
        
        .profile-card {
            padding: 20px;
        }
        
        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }
        
        .info-row .value {
            text-align: left;
            max-width: 100%;
        }
    }

    @media (max-width: 576px) {
        .profile-container {
            padding: 0 12px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        
        .stat-card {
            padding: 16px;
        }
        
        .stat-card .stat-number {
            font-size: 20px;
        }
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="profile-container">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <ul style="margin:0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar-wrapper">
            <div class="profile-avatar">
                @if($profile->avatar)
                    <img src="{{ Storage::url($profile->avatar) }}" alt="{{ $profile->full_name }}" id="profileAvatar">
                @else
                    <span class="initials">{{ strtoupper(substr($profile->full_name, 0, 2)) }}</span>
                @endif
            </div>
            <div class="avatar-upload-btn" onclick="document.getElementById('avatarInput').click()" title="Change Avatar">
                <i class="fas fa-camera"></i>
            </div>
            <input type="file" id="avatarInput" style="display:none" accept="image/*" onchange="uploadAvatar(this)">
        </div>

        <div class="profile-info">
            <h1>{{ $profile->full_name }}</h1>
            <p class="email">{{ $user->email }}</p>
            <div class="profile-badges">
                <span class="badge {{ $roleData['badgeClass'] }}">
                    <i class="fas {{ $roleData['icon'] }}"></i> {{ $roleData['badge'] }}
                </span>
                <span class="badge-status {{ $profile->getStatusBadgeClass() }}">
                    <i class="fas {{ $profile->getStatusIcon() }}"></i>
                    {{ ucfirst($profile->status) }}
                </span>
                <span style="color: #6b7280; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                    <i class="far fa-calendar-alt"></i>
                    Joined {{ $profile->join_date ? Carbon\Carbon::parse($profile->join_date)->format('M d, Y') : 'N/A' }}
                </span>
            </div>
        </div>

        <div class="profile-actions">
            <a href="{{ route('profile.edit') }}" class="btn-edit">
                <i class="fas fa-pen"></i> Edit Profile
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-purple">
                <i class="fas fa-calendar-check"></i>
            </div>
            <p class="stat-number">{{ $stats['appointments'] ?? 0 }}</p>
            <p class="stat-label">Total Appointments</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <p class="stat-number">{{ $stats['tokens'] ?? 0 }}</p>
            <p class="stat-label">Total Tokens</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <i class="fas fa-check-circle"></i>
            </div>
            <p class="stat-number">{{ $stats['completed'] ?? 0 }}</p>
            <p class="stat-label">Completed</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-yellow">
                <i class="fas fa-clock"></i>
            </div>
            <p class="stat-number">{{ $stats['pending'] ?? 0 }}</p>
            <p class="stat-label">Pending</p>
        </div>
        @if($user->isAdmin())
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">
                <i class="fas fa-users"></i>
            </div>
            <p class="stat-number">{{ $stats['patients'] ?? 0 }}</p>
            <p class="stat-label">Total Patients</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-red">
                <i class="fas fa-user-tie"></i>
            </div>
            <p class="stat-number">{{ $stats['staff'] ?? 0 }}</p>
            <p class="stat-label">Total Staff</p>
        </div>
        @endif
    </div>

    <!-- Profile Content -->
    <div class="profile-content">
        <!-- Personal Information -->
        <div class="profile-card">
            <div class="card-title">
                <i class="fas fa-user-circle"></i>
                Personal Information
            </div>
            
            <div class="info-row">
                <span class="label"><i class="fas fa-user"></i> Full Name</span>
                <span class="value">{{ $profile->full_name }}</span>
            </div>
            <div class="info-row">
                <span class="label"><i class="fas fa-envelope"></i> Email</span>
                <span class="value">{{ $user->email }}</span>
            </div>
            <div class="info-row">
                <span class="label"><i class="fas fa-tag"></i> Role</span>
                <span class="value">
                    <span class="badge {{ $roleData['badgeClass'] }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="label"><i class="fas fa-phone"></i> Phone</span>
                <span class="value {{ !$profile->phone ? 'text-muted' : '' }}">
                    {{ $profile->phone ?? 'Not provided' }}
                </span>
            </div>
            <div class="info-row">
                <span class="label"><i class="fas fa-calendar"></i> Joined</span>
                <span class="value">{{ $profile->join_date ? Carbon\Carbon::parse($profile->join_date)->format('M d, Y') : 'N/A' }}</span>
            </div>
            @if($profile->bio)
            <div class="info-row" style="align-items: flex-start; padding-top: 12px;">
                <span class="label"><i class="fas fa-align-left"></i> Bio</span>
                <span class="value" style="font-weight:400; font-size:13px; text-align:right;">{{ $profile->bio }}</span>
            </div>
            @endif
        </div>

        <!-- Account & Role Information -->
        <div class="profile-card">
            <div class="card-title">
                <i class="fas fa-shield-alt"></i>
                Account Information
            </div>
            
            <div class="info-row">
                <span class="label"><i class="fas fa-circle"></i> Account Status</span>
                <span class="value">
                    <span class="badge-status {{ $profile->getStatusBadgeClass() }}">
                        <i class="fas {{ $profile->getStatusIcon() }}"></i>
                        {{ ucfirst($profile->status) }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="label"><i class="fas fa-clock"></i> Last Login</span>
                <span class="value">{{ $profile->last_login ? Carbon\Carbon::parse($profile->last_login)->diffForHumans() : 'First login' }}</span>
            </div>
            
            <!-- Staff/Admin specific fields -->
            @if($user->isStaff() || $user->isAdmin())
            <div class="info-row">
                <span class="label"><i class="fas fa-id-badge"></i> Employee ID</span>
                <span class="value {{ !$profile->employee_id ? 'text-muted' : '' }}">
                    {{ $profile->employee_id ?? 'Not assigned' }}
                </span>
            </div>
            <div class="info-row">
                <span class="label"><i class="fas fa-building"></i> Department</span>
                <span class="value {{ !$profile->department ? 'text-muted' : '' }}">
                    {{ $profile->department ?? 'Not assigned' }}
                </span>
            </div>
            <div class="info-row">
                <span class="label"><i class="fas fa-briefcase"></i> Designation</span>
                <span class="value {{ !$profile->designation ? 'text-muted' : '' }}">
                    {{ $profile->designation ?? 'Not assigned' }}
                </span>
            </div>
            @endif

            <!-- User specific fields -->
            @if($user->isUser())
            <div class="info-row">
                <span class="label"><i class="fas fa-map-marker-alt"></i> Location</span>
                <span class="value {{ !$profile->location ? 'text-muted' : '' }}">
                    {{ $profile->location ?? 'Not provided' }}
                </span>
            </div>
            <div class="info-row">
                <span class="label"><i class="fas fa-city"></i> City</span>
                <span class="value {{ !$profile->city ? 'text-muted' : '' }}">
                    {{ $profile->city ?? 'Not provided' }}
                </span>
            </div>
            <div class="info-row">
                <span class="label"><i class="fas fa-flag"></i> Country</span>
                <span class="value {{ !$profile->country ? 'text-muted' : '' }}">
                    {{ $profile->country ?? 'Not provided' }}
                </span>
            </div>
            <div class="info-row">
                <span class="label"><i class="fas fa-home"></i> Address</span>
                <span class="value {{ !$profile->address ? 'text-muted' : '' }}">
                    {{ $profile->address ?? 'Not provided' }}
                </span>
            </div>
            @if($profile->hostel)
            <div class="info-row">
                <span class="label"><i class="fas fa-hotel"></i> Hostel</span>
                <span class="value">{{ $profile->hostel }}</span>
            </div>
            @endif
            @endif
        </div>

        <!-- Permissions -->
        <div class="profile-card full-width">
            <div class="card-title">
                <i class="fas fa-key"></i>
                Access Permissions - <span style="font-weight: 400; font-size: 14px; color: #6b7280;">{{ $roleData['title'] }}</span>
            </div>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 12px;">
                <i class="fas fa-info-circle"></i> {{ $roleData['description'] }}
            </p>
            <div class="permissions-list">
                @foreach($roleData['permissions'] as $permission)
                    <span class="permission-pill"><i class="fas fa-check-circle"></i> {{ $permission }}</span>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    // Upload Avatar via AJAX
    function uploadAvatar(input) {
        if (!input.files || !input.files[0]) return;

        const file = input.files[0];
        const formData = new FormData();
        formData.append('avatar', file);

        const avatarDiv = document.querySelector('.profile-avatar');
        avatarDiv.style.opacity = '0.6';

        fetch('{{ route("profile.avatar.update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const img = avatarDiv.querySelector('img');
                const initials = avatarDiv.querySelector('.initials');
                
                if (img) {
                    img.src = data.avatar_url + '?t=' + Date.now();
                    img.style.display = 'block';
                    if (initials) initials.style.display = 'none';
                } else {
                    const newImg = document.createElement('img');
                    newImg.src = data.avatar_url + '?t=' + Date.now();
                    newImg.alt = 'Profile Avatar';
                    const initialsElement = avatarDiv.querySelector('.initials');
                    if (initialsElement) {
                        initialsElement.style.display = 'none';
                        avatarDiv.insertBefore(newImg, initialsElement);
                    }
                }
                showMessage('Avatar updated successfully!', 'success');
            } else {
                showMessage(data.message || 'Error updating avatar', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error uploading avatar', 'error');
        })
        .finally(() => {
            avatarDiv.style.opacity = '1';
            input.value = '';
        });
    }

    // Show Message
    function showMessage(message, type) {
        const existing = document.querySelectorAll('.alert');
        existing.forEach(el => el.remove());

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            ${message}
        `;
        document.querySelector('.profile-container').prepend(alertDiv);

        setTimeout(() => {
            alertDiv.style.transition = 'opacity 0.3s ease';
            alertDiv.style.opacity = '0';
            setTimeout(() => alertDiv.remove(), 300);
        }, 5000);
    }
</script>
@endsection