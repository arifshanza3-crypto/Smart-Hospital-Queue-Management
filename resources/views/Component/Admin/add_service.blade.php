<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Add New Service - Admin</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fb;
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: #0b2e33;
            color: #00d4ff;
            padding: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            border-right: 1px solid #3e8686;
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
            filter: drop-shadow(0px 0px 8px #00d4ff);
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
            color: #a0d4d9;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            text-decoration: none;
            color: #00d4ff;
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
            background-color: #00d4ff !important;
            color: #0b2e33 !important;
            font-weight: 700;
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.4);
        }

        .divider {
            height: 1px;
            background: #3e8686;
            margin: 15px 20px;
            opacity: 0.3;
        }

        .logout-wrapper {
            margin-top: auto;
            padding-bottom: 20px;
        }

        .logout-btn {
            border: 1px solid #3e8686;
        }

        .logout-btn:hover {
            background-color: #00b8e6;
            color: #0b2e33 !important;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 40px 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #0b2e33, #1a4a50);
            padding: 25px 30px;
            color: white;
        }

        .card-header h2 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header h2 i {
            color: #00d4ff;
        }

        .card-header p {
            margin: 8px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .card-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }

        .form-group label i {
            margin-right: 8px;
            color: #00d4ff;
        }

        .required {
            color: #e74c3c;
            margin-left: 4px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e8ed;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #00d4ff;
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            cursor: pointer;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .help-text {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .image-preview {
            margin-top: 10px;
            display: none;
        }

        .image-preview img {
            max-width: 120px;
            border-radius: 8px;
            border: 2px solid #e1e8ed;
            padding: 3px;
        }

        .error-box {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }

        .error-box small {
            display: block;
            margin: 3px 0;
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
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 212, 255, 0.3);
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 10px;
            display: block;
            text-align: center;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            .sidebar .logo-text,
            .sidebar .menu-label,
            .sidebar .nav-item span {
                display: none;
            }
            .sidebar .nav-item {
                justify-content: center;
                padding: 12px;
            }
            .sidebar .nav-item i {
                margin-right: 0;
            }
            .main-content {
                margin-left: 70px;
            }
            .row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .card-body {
                padding: 20px;
            }
        }

        @media (max-width: 500px) {
            .card-header h2 {
                font-size: 20px;
            }
            .card-body {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

    <!-- ===== SIDEBAR ===== -->
    <div class="sidebar">
        <div class="logo-section">
            <img src="{{ asset('Assert/logo.png') }}" alt="Logo">
            <div class="logo-text">
                <span class="brand-name">Smart Queue</span>
                <span class="brand-sub">Management System</span>
            </div>
        </div>

        <a href="{{ url('/admin/doctor-management') }}" class="nav-item {{ request()->is('*doctor-management*') ? 'active' : '' }}">
            <i class="fas fa-user-md"></i> <span>Doctors Management</span>
        </a>

        <a href="{{ url('/admin/services-management') }}" class="nav-item {{ request()->is('*services-management*') ? 'active' : '' }}">
            <i class="fas fa-hand-holding-medical"></i> <span>Services Management</span>
        </a>

        <a href="{{ url('/admin/user-management') }}" class="nav-item {{ request()->is('*user-management*') ? 'active' : '' }}">
            <i class="fas fa-user-gear"></i> <span>User Management</span>
        </a>

        <div class="divider"></div>

        <div class="menu-label">Data Analytics</div>
        
        <a href="{{ url('/admin/report') }}" class="nav-item {{ request()->is('*report*') ? 'active' : '' }}">
            <i class="fas fa-file-chart-column"></i> <span>Queue Reports</span>
        </a>

        <a href="{{ url('/admin/settings') }}" class="nav-item {{ request()->is('*settings*') ? 'active' : '' }}">
            <i class="fas fa-gears"></i> <span>Settings</span>
        </a>

        <div class="logout-wrapper">
            <a href="#" class="nav-item logout-btn">
                <i class="fas fa-power-off"></i> <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-plus-circle"></i>
                        Add New Service
                    </h2>
                    <p>Fill in the details to add a new medical service</p>
                </div>

                <div class="card-body">
                    @if($errors->any())
                    <div class="error-box">
                        <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
                        @foreach($errors->all() as $error)
                            <small>• {{ $error }}</small>
                        @endforeach
                    </div>
                    @endif

                    <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label><i class="fas fa-tag"></i> Service Name <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g., Cardiology Consultation" value="{{ old('name') }}" required>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label><i class="fas fa-dollar-sign"></i> Price ($)</label>
                                <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01" value="{{ old('price') }}">
                                <div class="help-text">Leave empty for free/quote</div>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-clock"></i> Duration (minutes)</label>
                                <input type="number" name="duration" class="form-control" placeholder="30" value="{{ old('duration') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label><i class="fas fa-building"></i> Department</label>
                                <input type="text" name="department" class="form-control" placeholder="e.g., Cardiology" value="{{ old('department') }}">
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-sort"></i> Display Order</label>
                                <input type="number" name="display_order" class="form-control" placeholder="0" value="{{ old('display_order', 0) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-align-left"></i> Description <span class="required">*</span></label>
                            <textarea name="description" class="form-control" placeholder="Describe the service in detail..." required>{{ old('description') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label><i class="fas fa-icons"></i> Icon Class</label>
                                <input type="text" name="icon" class="form-control" placeholder="fas fa-heartbeat" value="{{ old('icon') }}">
                                <div class="help-text">Font Awesome icon (e.g., fas fa-heartbeat)</div>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-image"></i> Service Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                                <div id="imagePreview" class="image-preview">
                                    <img id="previewImg" src="#" alt="Preview">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-toggle-on"></i> Status <span class="required">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>🟢 Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>🔴 Inactive</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Save Service
                        </button>

                        <a href="{{ route('admin.services.index') }}" class="btn-cancel">
                            <i class="fas fa-arrow-left"></i> Back to Services
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Image preview
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(e.target.files[0]);
            } else {
                preview.style.display = 'none';
            }
        });
    </script>
</body>
</html>