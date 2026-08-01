@extends('Layout.app')

@section('title', 'My Profile')

@section('content')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">

<div class="profile-wrapper">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profile Card -->
    <div class="profile-card">
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
                <h2>{{ $profile->full_name}}</h2>
                <p class="email">{{ $user->email}}</p>
                <div class="profile-meta">
                    <span class="role-badge {{ $roleData['badgeClass'] }}">
                        <i class="fas {{ $roleData['icon'] }}"></i> {{ $roleData['badge'] }}
                    </span>
                    <span class="status-badge {{ $profile->getStatusBadgeClass() }}">
                        <i class="fas {{ $profile->getStatusIcon() }}"></i> {{ ucfirst($profile->status) }}
                    </span>
                    <span class="joined-date">
                        <i class="far fa-calendar-alt"></i> Joined {{ $profile->join_date ? Carbon\Carbon::parse($profile->join_date)->format('M d, Y') : 'N/A' }}
                    </span>
                </div>
            </div>

            <div class="profile-actions">
                <a href="{{ route('profile.edit') }}" class="btn-edit">
                    <i class="fas fa-pen"></i> Edit Profile
                </a>
            </div>
        </div>

        <!-- Profile Body -->
        <div class="profile-body">
            <!-- Personal Information -->
            <div class="info-section">
                <h3 class="section-title">
                    <i class="fas fa-user-circle"></i> Personal Information
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-user"></i> FULL NAME</span>
                        <span class="info-value">{{ $profile->full_name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-envelope"></i> EMAIL</span>
                        <span class="info-value">{{ $user->email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-tag"></i> ROLE</span>
                        <span class="info-value">
                            <span class="role-badge {{ $roleData['badgeClass'] }}" style="font-size:11px; padding:2px 10px;">
                                {{ ucfirst($user->role) }}
                            </span>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-phone"></i> PHONE</span>
                        <span class="info-value {{ !$profile->phone ? 'text-muted' : '' }}">
                            {{ $profile->phone ?? 'Not provided' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-calendar"></i> JOINED</span>
                        <span class="info-value">{{ $profile->join_date ? Carbon\Carbon::parse($profile->join_date)->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="info-section">
                <h3 class="section-title">
                    <i class="fas fa-shield-alt"></i> Account Information
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-circle"></i> ACCOUNT STATUS</span>
                        <span class="info-value">
                            <span class="status-badge {{ $profile->getStatusBadgeClass() }}" style="font-size:11px; padding:2px 10px;">
                                <i class="fas {{ $profile->getStatusIcon() }}"></i> {{ ucfirst($profile->status) }}
                            </span>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-clock"></i> LAST LOGIN</span>
                        <span class="info-value">{{ $profile->last_login ? Carbon\Carbon::parse($profile->last_login)->diffForHumans() : 'First login' }}</span>
                    </div>
                    
                    <!-- Staff/Admin specific fields -->
                    @if($user->isStaff() || $user->isAdmin())
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-id-badge"></i> EMPLOYEE ID</span>
                        <span class="info-value {{ !$profile->employee_id ? 'text-muted' : '' }}">
                            {{ $profile->employee_id ?? 'Not assigned' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-building"></i> DEPARTMENT</span>
                        <span class="info-value {{ !$profile->department ? 'text-muted' : '' }}">
                            {{ $profile->department ?? 'Not assigned' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-briefcase"></i> DESIGNATION</span>
                        <span class="info-value {{ !$profile->designation ? 'text-muted' : '' }}">
                            {{ $profile->designation ?? 'Not assigned' }}
                        </span>
                    </div>
                    @endif

                    <!-- User specific fields -->
                    @if($user->isUser())
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-hotel"></i> HOSTEL</span>
                        <span class="info-value {{ !$profile->hostel ? 'text-muted' : '' }}">
                            {{ $profile->hostel ?? 'Not provided' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-map-marker-alt"></i> LOCATION</span>
                        <span class="info-value {{ !$profile->location ? 'text-muted' : '' }}">
                            {{ $profile->location ?? 'Not provided' }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/profile.js') }}"></script>
@endsection