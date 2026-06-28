@extends('Layout.admin-layout')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* Same styles as add_service.blade.php */
    .form-container {
        font-family: 'Poppins', sans-serif;
    }
    
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        max-width: 900px;
        margin: 20px auto;
        overflow: hidden;
    }
    
    .form-header {
        background: linear-gradient(135deg, #0b2e33 0%, #1a4a50 100%);
        padding: 30px;
        text-align: center;
    }
    
    .form-header h2 {
        color: #00d4ff;
        margin: 10px 0 0;
        font-size: 28px;
        font-weight: 600;
    }
    
    .form-header i {
        font-size: 60px;
        color: #00d4ff;
    }
    
    .form-body {
        padding: 40px;
    }
    
    .form-group {
        margin-bottom: 25px;
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
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
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
    
    .required {
        color: #dc3545;
        margin-left: 4px;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #00d4ff, #0b2e33);
        color: white;
        padding: 14px 30px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 212, 255, 0.3);
    }
    
    .btn-cancel {
        background: #6c757d;
        color: white;
        padding: 12px 25px;
        text-decoration: none;
        border-radius: 12px;
        display: inline-block;
        text-align: center;
        margin-top: 15px;
        width: 100%;
        transition: all 0.3s ease;
    }
    
    .btn-cancel:hover {
        background: #5a6268;
    }
    
    .error-message {
        background: #f8d7da;
        color: #721c24;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
    }
    
    .current-image {
        margin-top: 10px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .current-image img {
        max-width: 100px;
        border-radius: 8px;
    }
    
    .row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    @media (max-width: 768px) {
        .row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <i class="fas fa-concierge-bell"></i>
            <h2>Edit Service</h2>
            <p style="color: #a0d4d9;">Update the service information</p>
        </div>
        
        <div class="form-body">
            @if($errors->any())
                <div class="error-message">
                    @foreach($errors->all() as $error)
                        <small><i class="fas fa-exclamation-circle"></i> {{ $error }}</small><br>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('admin.services.update', $service->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Service Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., Cardiology Consultation" value="{{ old('name', $service->name) }}" required>
                </div>
                
                <div class="row">
                    <div class="form-group">
                        <label><i class="fas fa-dollar-sign"></i> Price</label>
                        <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01" value="{{ old('price', $service->price) }}">
                        <small style="color: #666;">Leave empty for free services or quote</small>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-hourglass-half"></i> Duration (minutes)</label>
                        <input type="number" name="duration" class="form-control" placeholder="30" value="{{ old('duration', $service->duration) }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group">
                        <label><i class="fas fa-building"></i> Department</label>
                        <input type="text" name="department" class="form-control" placeholder="e.g., Cardiology" value="{{ old('department', $service->department) }}">
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-chart-line"></i> Display Order</label>
                        <input type="number" name="display_order" class="form-control" placeholder="0" value="{{ old('display_order', $service->display_order) }}">
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Description <span class="required">*</span></label>
                    <textarea name="description" class="form-control" placeholder="Describe the service in detail..." required>{{ old('description', $service->description) }}</textarea>
                </div>
                
                <div class="row">
                    <div class="form-group">
                        <label><i class="fas fa-icons"></i> Icon Class</label>
                        <input type="text" name="icon" class="form-control" placeholder="fas fa-heartbeat" value="{{ old('icon', $service->icon) }}">
                        <small style="color: #666;">Font Awesome icon class (e.g., fas fa-heartbeat)</small>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Service Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @if($service->image)
                            <div class="current-image">
                                <small>Current Image:</small><br>
                                <img src="{{ Storage::url($service->image) }}" alt="Service Image">
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-toggle-on"></i> Status <span class="required">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ old('status', $service->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $service->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Update Service
                </button>
                
                <a href="{{ route('admin.services.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </form>
        </div>
    </div>
</div>
@endsection