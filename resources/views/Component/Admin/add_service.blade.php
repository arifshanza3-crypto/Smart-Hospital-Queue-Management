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
            padding: 40px 20px;
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
        
        .alert-danger {
            background: #fee;
            border-left: 4px solid #e74c3c;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .alert-danger li {
            color: #c0392b;
            font-size: 14px;
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
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 212, 255, 0.3);
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
        
        .btn-cancel:hover {
            background: #7f8c8d;
            color: white;
        }
        
        @media (max-width: 768px) {
            .row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .card-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
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
                <div class="alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
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
                            <label><i class="fas fa-clock"></i>Duration (minutes)</label>
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
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
    
    <script>
        // Image preview
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        
        imageInput.addEventListener('change', function(e) {
            if(e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(e.target.files[0]);
            } else {
                imagePreview.style.display = 'none';
            }
        });
    </script>
</body>
</html>