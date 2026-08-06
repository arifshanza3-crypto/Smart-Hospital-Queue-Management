@extends('Layout.app')

@section('title', 'Edit Profile')

@section('content')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">

<div class="edit-profile-wrapper">
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

    <div class="edit-profile-card">
        <!-- Header -->
        <div class="edit-header">
            <h2><i class="fas fa-user-edit"></i> Edit Profile</h2>
            <a href="{{ route('profile.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Profile
            </a>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="edit-form">
            @csrf
            @method('PUT')

            <!-- Avatar Upload -->
            <div class="form-section">
                <h4 class="form-section-title">
                    <i class="fas fa-image"></i> Profile Picture
                </h4>
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
            <div class="form-section">
                <h4 class="form-section-title">
                    <i class="fas fa-user-circle"></i> Personal Information
                </h4>

                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                    <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" 
                           value="{{ old('full_name', $profile->full_name) }}" required>
                    @error('full_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Phone Number</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                           value="{{ old('phone', $profile->phone) }}" placeholder="+92 300 1234567">
                    @error('phone')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Bio</label>
                    <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" 
                              rows="3" placeholder="Tell us about yourself...">{{ old('bio', $profile->bio) }}</textarea>
                    @error('bio')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Address Information (For Users) -->
            @if($user->isUser())
            <hr class="divider">
            <div class="form-section">
                <h4 class="form-section-title">
                    <i class="fas fa-map-marker-alt"></i> Address Information
                </h4>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-location-dot"></i> Location</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" 
                               value="{{ old('location', $profile->location) }}" placeholder="City, Country">
                        @error('location')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-city"></i> City</label>
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                               value="{{ old('city', $profile->city) }}" placeholder="City name">
                        @error('city')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-flag"></i> Country</label>
                        <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" 
                               value="{{ old('country', $profile->country) }}" placeholder="Country name">
                        @error('country')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-hotel"></i> Hostel</label>
                        <input type="text" name="hostel" class="form-control @error('hostel') is-invalid @enderror" 
                               value="{{ old('hostel', $profile->hostel) }}" placeholder="Hostel name (if applicable)">
                        @error('hostel')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-home"></i> Address</label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                           value="{{ old('address', $profile->address) }}" placeholder="Street, Area">
                    @error('address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            @endif

            <!-- Professional Information (For Staff & Admin) -->
            @if($user->isStaff() || $user->isAdmin())
            <hr class="divider">
            <div class="form-section">
                <h4 class="form-section-title">
                    <i class="fas fa-briefcase"></i> Professional Information
                </h4>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-id-badge"></i> Employee ID</label>
                        <input type="text" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" 
                               value="{{ old('employee_id', $profile->employee_id) }}" placeholder="EMP-001">
                        @error('employee_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-building"></i> Department</label>
                        <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" 
                               value="{{ old('department', $profile->department) }}" placeholder="Cardiology">
                        @error('department')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-briefcase"></i> Designation</label>
                    <input type="text" name="designation" class="form-control @error('designation') is-invalid @enderror" 
                           value="{{ old('designation', $profile->designation) }}" placeholder="Senior Doctor">
                    @error('designation')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            @endif

            <!-- Password Change -->
            <hr class="divider">
            <div class="form-section">
                <h4 class="form-section-title">
                    <i class="fas fa-lock"></i> Change Password
                </h4>

                <div class="form-group">
                    <label><i class="fas fa-key"></i> Current Password</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" 
                           placeholder="Enter current password">
                    @error('current_password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> New Password</label>
                        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" 
                               placeholder="Min 8 characters">
                        @error('new_password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-check"></i> Confirm Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control" 
                               placeholder="Confirm new password">
                    </div>
                </div>
                <div class="help-text">
                    <i class="fas fa-info-circle"></i> Password must be at least 8 characters long.
                </div>
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