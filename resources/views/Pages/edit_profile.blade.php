@extends('Layout.app')

@section('title', 'Edit Profile')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    * {
        font-family: 'Inter', 'Segoe UI', sans-serif;
        box-sizing: border-box;
    }

    .edit-profile-container {
        max-width: 800px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .edit-profile-card {
        background: white;
        border-radius: 16px;
        padding: 35px 40px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }

    .edit-profile-card .header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }

    .edit-profile-card .header h2 {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .edit-profile-card .header h2 i {
        color: #6366f1;
    }

    .btn-back {
        background: #f3f4f6;
        color: #6b7280;
        border: none;
        padding: 8px 20px;
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

    .btn-back:hover {
        background: #e5e7eb;
        color: #374151;
        text-decoration: none;
    }

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

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
        font-size: 14px;
    }

    .form-group label i {
        color: #6366f1;
        margin-right: 8px;
        width: 18px;
    }

    .form-group .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
        background: white;
    }

    .form-group .form-control:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .form-group .form-control.is-invalid {
        border-color: #ef4444;
    }

    .form-group .invalid-feedback {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }

    .form-group .help-text {
        color: #9ca3af;
        font-size: 12px;
        margin-top: 4px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .avatar-upload-section {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 15px 0;
    }

    .avatar-preview {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #e5e7eb;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-preview .initials {
        font-size: 28px;
        font-weight: 700;
        color: white;
        text-transform: uppercase;
    }

    .avatar-upload-info {
        flex: 1;
    }

    .avatar-upload-info p {
        margin: 0 0 4px 0;
        font-weight: 500;
        color: #111827;
    }

    .avatar-upload-info small {
        color: #9ca3af;
        font-size: 12px;
    }

    .btn-avatar-upload {
        background: #6366f1;
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 4px;
    }

    .btn-avatar-upload:hover {
        background: #4f46e5;
    }

    .divider {
        border: none;
        border-top: 2px solid #f3f4f6;
        margin: 25px 0;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #f3f4f6;
    }

    .form-actions .btn {
        padding: 10px 30px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: 'Inter', sans-serif;
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #6b7280;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
    }

    .btn-submit {
        background: #6366f1;
        color: white;
        flex: 1;
        justify-content: center;
    }

    .btn-submit:hover {
        background: #4f46e5;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
    }

    @media (max-width: 768px) {
        .edit-profile-card {
            padding: 24px 20px;
        }
        
        .edit-profile-card .header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
        
        .avatar-upload-section {
            flex-direction: column;
            text-align: center;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .form-actions .btn {
            width: 100%;
            justify-content: center;
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

<div class="edit-profile-container">
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

    <!-- Edit Profile Form -->
    <div class="edit-profile-card">
        <div class="header">
            <h2><i class="fas fa-user-edit"></i> Edit Profile</h2>
            <a href="{{ route('profile.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Profile
            </a>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Avatar Upload -->
            <div class="form-group">
                <label><i class="fas fa-image"></i> Profile Picture</label>
                <div class="avatar-upload-section">
                    <div class="avatar-preview">
                        @if($profile->avatar)
                            <img src="{{ Storage::url($profile->avatar) }}" alt="{{ $profile->full_name }}">
                        @else
                            <span class="initials">{{ strtoupper(substr($profile->full_name, 0, 2)) }}</span>
                        @endif
                    </div>
                    <div class="avatar-upload-info">
                        <p>Change Profile Picture</p>
                        <small>Upload a new image (JPEG, PNG, JPG, GIF, WebP)</small>
                        <div>
                            <input type="file" name="avatar" id="avatarInput" style="display:none" accept="image/*">
                            <button type="button" class="btn-avatar-upload" onclick="document.getElementById('avatarInput').click()">
                                <i class="fas fa-upload"></i> Choose Image
                            </button>
                        </div>
                        <small style="display: block; margin-top: 4px;">Max file size: 2MB</small>
                        @error('avatar')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="divider">

            <!-- Personal Information -->
            <h4 style="color: #111827; font-weight: 600; margin-bottom: 20px;">
                <i class="fas fa-user-circle" style="color: #6366f1;"></i> Personal Information
            </h4>

            <div class="form-group">
                <label><i class="fas fa-user"></i> Full Name</label>
                <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $profile->full_name) }}" required>
                @error('full_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label><i class="fas fa-phone"></i> Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $profile->phone) }}" placeholder="+92 300 1234567">
                @error('phone')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Bio</label>
                <textarea name="bio" class="form-control" rows="3" placeholder="Tell us about yourself...">{{ old('bio', $profile->bio) }}</textarea>
                @error('bio')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <hr class="divider">

            <!-- Address Information (For Users) -->
            @if($user->isUser())
            <h4 style="color: #111827; font-weight: 600; margin-bottom: 20px;">
                <i class="fas fa-map-marker-alt" style="color: #6366f1;"></i> Address Information
            </h4>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-location-dot"></i> Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $profile->location) }}" placeholder="City, Country">
                    @error('location')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label><i class="fas fa-city"></i> City</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $profile->city) }}" placeholder="City name">
                    @error('city')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-flag"></i> Country</label>
                    <input type="text" name="country" class="form-control" value="{{ old('country', $profile->country) }}" placeholder="Country name">
                    @error('country')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label><i class="fas fa-hotel"></i> Hostel</label>
                    <input type="text" name="hostel" class="form-control" value="{{ old('hostel', $profile->hostel) }}" placeholder="Hostel name (if applicable)">
                    @error('hostel')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-home"></i> Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $profile->address) }}" placeholder="Street, Area">
                @error('address')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            @endif

            <!-- Staff Information (For Staff & Admin) -->
            @if($user->isStaff() || $user->isAdmin())
            <hr class="divider">
            <h4 style="color: #111827; font-weight: 600; margin-bottom: 20px;">
                <i class="fas fa-briefcase" style="color: #6366f1;"></i> Professional Information
            </h4>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-id-badge"></i> Employee ID</label>
                    <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id', $profile->employee_id) }}" placeholder="EMP-001">
                    @error('employee_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label><i class="fas fa-building"></i> Department</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department', $profile->department) }}" placeholder="Cardiology">
                    @error('department')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-briefcase"></i> Designation</label>
                <input type="text" name="designation" class="form-control" value="{{ old('designation', $profile->designation) }}" placeholder="Senior Doctor">
                @error('designation')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            @endif

            <!-- Password Change -->
            <hr class="divider">
            <h4 style="color: #111827; font-weight: 600; margin-bottom: 20px;">
                <i class="fas fa-lock" style="color: #6366f1;"></i> Change Password
            </h4>

            <div class="form-group">
                <label><i class="fas fa-key"></i> Current Password</label>
                <input type="password" name="current_password" class="form-control" placeholder="Enter current password">
                @error('current_password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> New Password</label>
                    <input type="password" name="new_password" class="form-control" placeholder="Min 8 characters">
                    @error('new_password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label><i class="fas fa-check"></i> Confirm Password</label>
                    <input type="password" name="new_password_confirmation" class="form-control" placeholder="Confirm new password">
                    @error('new_password_confirmation')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="help-text">
                <i class="fas fa-info-circle"></i> Password must be at least 8 characters long.
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('profile.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-save"></i> Update Profile
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Preview avatar before upload
    document.getElementById('avatarInput').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.querySelector('.avatar-preview');
                const img = preview.querySelector('img');
                const initials = preview.querySelector('.initials');
                
                if (!img) {
                    const newImg = document.createElement('img');
                    newImg.src = e.target.result;
                    newImg.alt = 'Profile Preview';
                    preview.appendChild(newImg);
                } else {
                    img.src = e.target.result;
                }
                
                if (initials) {
                    initials.style.display = 'none';
                }
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endsection