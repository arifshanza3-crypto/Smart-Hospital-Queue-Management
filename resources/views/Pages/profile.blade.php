@extends('Layout.app')

@section('title', 'My Profile')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    * { font-family: 'Poppins', sans-serif; }
    .profile-container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 0 20px;
    }
    .profile-header {
        background: linear-gradient(135deg, #0b2e33, #1a4a50);
        border-radius: 20px;
        padding: 40px;
        color: white;
        display: flex;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #00d4ff;
        flex-shrink: 0;
    }
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .profile-avatar .initials {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #00d4ff, #0b2e33);
        font-size: 48px;
        font-weight: 600;
        color: white;
    }
    .profile-info h1 { font-size: 28px; font-weight: 700; }
    .profile-info .role-badge {
        display: inline-block;
        background: #00d4ff;
        color: #0b2e33;
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .profile-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-top: 30px;
    }
    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.06);
    }
    .profile-card h3 {
        color: #0b2e33;
        font-size: 16px;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }
    .profile-card h3 i { color: #00d4ff; margin-right: 10px; }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    .info-row .label { color: #666; }
    .info-row .value { color: #0b2e33; font-weight: 500; }
    .role-permissions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; }
    .permission-badge {
        background: #f0f7ff;
        color: #00d4ff;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .btn-edit {
        background: linear-gradient(135deg, #00d4ff, #0b2e33);
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 15px;
    }
    .btn-edit:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,212,255,0.3); }
    .btn-edit i { margin-right: 8px; }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.show { display: flex; }
    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 35px;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .modal-content h2 { color: #0b2e33; margin-bottom: 20px; }
    .modal-content h2 i { color: #00d4ff; margin-right: 10px; }
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; font-weight: 600; color: #0b2e33; margin-bottom: 5px; font-size: 14px; }
    .form-group label i { color: #00d4ff; margin-right: 8px; }
    .form-control {
        width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 10px;
        font-size: 14px; transition: all 0.3s ease;
    }
    .form-control:focus { outline: none; border-color: #00d4ff; }
    .btn-row { display: flex; gap: 12px; margin-top: 20px; }
    .btn-row .btn { flex: 1; padding: 12px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; }
    .btn-cancel { background: #6c757d; color: white; }
    .btn-cancel:hover { background: #5a6268; }
    .btn-submit { background: linear-gradient(135deg, #00d4ff, #0b2e33); color: white; }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,212,255,0.3); }

    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #28a745;
    }
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
    }

    @media (max-width: 768px) {
        .profile-grid { grid-template-columns: 1fr; }
        .profile-header { flex-direction: column; text-align: center; }
    }
</style>

<div class="profile-container">
    <!-- Alerts -->
    @if(session('success'))
        <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar">
            @if($profile->avatar)
                <img src="{{ Storage::url($profile->avatar) }}" alt="{{ $profile->full_name }}">
            @else
                <div class="initials">{{ strtoupper(substr($profile->full_name, 0, 2)) }}</div>
            @endif
        </div>
        <div class="profile-info">
            <h1>{{ $profile->full_name }}</h1>
            <p style="color: #a0d4d9;">{{ $user->email }}</p>
            <span class="role-badge"><i class="fas {{ $user->role == 'admin' ? 'fa-user-shield' : ($user->role == 'staff' ? 'fa-user-tie' : 'fa-user') }}"></i> {{ ucfirst($user->role) }}</span>
            <span style="color: #a0d4d9; margin-left: 15px;"><i class="fas fa-calendar"></i> Joined {{ $profile->join_date ? \Carbon\Carbon::parse($profile->join_date)->format('M d, Y') : 'N/A' }}</span>
        </div>
    </div>

    <!-- Profile Grid -->
    <div class="profile-grid">
        <!-- Personal Information -->
        <div class="profile-card">
            <h3><i class="fas fa-user"></i> Personal Information</h3>
            <div class="info-row"><span class="label"><i class="fas fa-user"></i> Full Name</span><span class="value">{{ $profile->full_name }}</span></div>
            <div class="info-row"><span class="label"><i class="fas fa-envelope"></i> Email</span><span class="value">{{ $user->email }}</span></div>
            <div class="info-row"><span class="label"><i class="fas fa-tag"></i> Role</span><span class="value"><span class="role-badge" style="font-size:11px;">{{ ucfirst($user->role) }}</span></span></div>
            <div class="info-row"><span class="label"><i class="fas fa-phone"></i> Phone</span><span class="value">{{ $profile->phone ?? 'Not provided' }}</span></div>
            <div class="info-row"><span class="label"><i class="fas fa-calendar"></i> Joined</span><span class="value">{{ $profile->join_date ? \Carbon\Carbon::parse($profile->join_date)->format('M d, Y') : 'N/A' }}</span></div>
            @if($user->role == 'staff')
            <div class="info-row"><span class="label"><i class="fas fa-id-badge"></i> Employee ID</span><span class="value">{{ $profile->employee_id ?? $user->employee_id ?? 'N/A' }}</span></div>
            <div class="info-row"><span class="label"><i class="fas fa-building"></i> Department</span><span class="value">{{ $profile->department ?? 'N/A' }}</span></div>
            @endif
        </div>

        <!-- Account Information -->
        <div class="profile-card">
            <h3><i class="fas fa-shield-alt"></i> Account Information</h3>
            <div class="info-row"><span class="label"><i class="fas fa-circle"></i> Account Status</span><span class="value"><span style="color: #28a745;"><i class="fas fa-check-circle"></i> {{ ucfirst($profile->status ?? 'Active') }}</span></span></div>
            <div class="info-row"><span class="label"><i class="fas fa-clock"></i> Last Login</span><span class="value">{{ $profile->last_login ? \Carbon\Carbon::parse($profile->last_login)->diffForHumans() : 'First login' }}</span></div>
            <div class="info-row"><span class="label"><i class="fas fa-map-marker-alt"></i> Location</span><span class="value">{{ $profile->location ?? 'Not provided' }}</span></div>
            <div class="info-row"><span class="label"><i class="fas fa-city"></i> City</span><span class="value">{{ $profile->city ?? 'Not provided' }}</span></div>
            <div class="info-row"><span class="label"><i class="fas fa-globe"></i> Country</span><span class="value">{{ $profile->country ?? 'Not provided' }}</span></div>
            @if($profile->hostel)
            <div class="info-row"><span class="label"><i class="fas fa-hotel"></i> Hostel</span><span class="value">{{ $profile->hostel }}</span></div>
            @endif
            <div class="info-row"><span class="label"><i class="fas fa-address-card"></i> Address</span><span class="value">{{ $profile->address ?? 'Not provided' }}</span></div>
        </div>
    </div>

    <!-- Role Permissions -->
    <div class="profile-card" style="margin-top:25px;">
        <h3><i class="fas {{ $roleData['icon'] }}"></i> {{ $roleData['title'] }}</h3>
        <p style="color:#666; margin-bottom:10px;">Your access permissions as a {{ ucfirst($user->role) }}:</p>
        <div class="role-permissions">
            @foreach($roleData['permissions'] as $permission)
                <span class="permission-badge"><i class="fas fa-check-circle"></i> {{ $permission }}</span>
            @endforeach
        </div>
    </div>

    <!-- ✅ FIXED: Edit Profile Button with correct route -->
    <button class="btn-edit" onclick="openEditModal()"><i class="fas fa-edit"></i> Edit Profile</button>
</div>

<!-- ===== EDIT PROFILE MODAL ===== -->
<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <h2><i class="fas fa-user-edit"></i> Edit Profile</h2>
        
        <!-- ✅ FIXED: Form action with correct route -->
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label><i class="fas fa-user"></i> Full Name</label>
                <input type="text" name="full_name" class="form-control" value="{{ $profile->full_name }}" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-phone"></i> Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ $profile->phone }}">
            </div>

            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> Address</label>
                <input type="text" name="address" class="form-control" value="{{ $profile->address }}">
            </div>

            <div class="form-group">
                <label><i class="fas fa-city"></i> City</label>
                <input type="text" name="city" class="form-control" value="{{ $profile->city }}">
            </div>

            <div class="form-group">
                <label><i class="fas fa-globe"></i> Country</label>
                <input type="text" name="country" class="form-control" value="{{ $profile->country }}">
            </div>

            <div class="form-group">
                <label><i class="fas fa-hotel"></i> Hostel</label>
                <input type="text" name="hostel" class="form-control" value="{{ $profile->hostel }}">
            </div>

            <div class="form-group">
                <label><i class="fas fa-location-dot"></i> Location</label>
                <input type="text" name="location" class="form-control" value="{{ $profile->location }}">
            </div>

            <div class="form-group">
                <label><i class="fas fa-image"></i> Profile Picture</label>
                <input type="file" name="avatar" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Bio</label>
                <textarea name="bio" class="form-control" rows="2">{{ $profile->bio }}</textarea>
            </div>

            <div class="btn-row">
                <button type="button" class="btn btn-cancel" onclick="closeEditModal()"><i class="fas fa-times"></i> Cancel</button>
                <!-- ✅ FIXED: Submit button with correct route -->
                <button type="submit" class="btn btn-submit"><i class="fas fa-save"></i> Update Profile</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal() {
        document.getElementById('editModal').classList.add('show');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
    }
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });
</script>
@endsection