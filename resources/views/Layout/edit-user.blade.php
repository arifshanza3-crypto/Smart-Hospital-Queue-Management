<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container { max-width: 800px; margin: 0 auto; }
        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #0b2e33, #1a4a50);
            padding: 25px 30px;
            color: white;
        }
        .card-header h2 {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-body { padding: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }
        .form-group label i { margin-right: 8px; color: #00d4ff; }
        .required { color: #e74c3c; margin-left: 4px; }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e8ed;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
        }
        .form-control:focus {
            outline: none;
            border-color: #00d4ff;
        }
        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .btn-submit {
            background: linear-gradient(135deg, #00d4ff, #0b2e33);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-cancel {
            background: #95a5a6;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 10px;
            display: block;
            text-align: center;
            margin-top: 15px;
        }
        .alert-danger {
            background: #fee;
            border-left: 4px solid #e74c3c;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .current-image {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 10px;
        }
        .current-image img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        .help-text {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        @media (max-width: 768px) {
            .row { grid-template-columns: 1fr; gap: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-user-edit"></i> Edit User</h2>
                <p>Update user account information</p>
            </div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-envelope"></i> Email <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group">
                            <label><i class="fas fa-lock"></i> New Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                            <div class="help-text">Only fill if you want to change password</div>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-lock"></i> Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group">
                            <label><i class="fas fa-tag"></i> Role <span class="required">*</span></label>
                            <select name="role" class="form-control" required>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Regular User</option>
                                <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-phone"></i> Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-location-dot"></i> Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $user->address) }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="form-group">
                            <label><i class="fas fa-image"></i> Profile Image</label>
                            <input type="file" name="profile_image" class="form-control" accept="image/*" id="imageInput">
                            @if($user->profile_image)
                            <div class="current-image">
                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Current">
                                <small>Current image</small>
                            </div>
                            @endif
                            <div id="imagePreview" class="current-image" style="display: none;">
                                <img id="previewImg" src="#">
                                <small>New image preview</small>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-toggle-on"></i> Status <span class="required">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Update User
                    </button>
                    
                    <a href="{{ route('admin.users.index') }}" class="btn-cancel">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            if(e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    preview.style.display = 'flex';
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
</body>
</html>