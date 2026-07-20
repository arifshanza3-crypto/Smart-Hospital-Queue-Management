@extends('Layout.admin-layout')

@section('page-title', 'Add New Service')
@section('breadcrumb', 'Create Service')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* ============================================
       ADD SERVICE - LIGHT THEME
       ============================================ */
    
    :root {
        --bg-primary: #f8fafc;
        --bg-card: #ffffff;
        --text-primary: #1e293b;
        --text-secondary: #475569;
        --text-muted: #94a3b8;
        --border-color: #e2e8f0;
        --shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        --shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.06);
        --accent-1: #3b82f6;
        --accent-2: #6366f1;
        --accent-gradient: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
        --success: #10b981;
        --danger: #ef4444;
    }

    .add-service-wrapper {
        padding: 24px 28px;
        background: var(--bg-primary);
        min-height: 100vh;
    }

    /* ===== PAGE HEADER ===== */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-header-left {
        display: flex;
        flex-direction: column;
    }

    .page-header-left h1 {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-header-left h1 i {
        color: var(--accent-1);
        font-size: 28px;
    }

    .page-header-left p {
        color: var(--text-secondary);
        font-size: 14px;
        margin: 4px 0 0 0;
    }

    .btn-secondary-gradient {
        background: #e2e8f0;
        color: var(--text-primary);
        padding: 12px 28px;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-secondary-gradient:hover {
        background: #cbd5e1;
        color: var(--text-primary);
        text-decoration: none;
    }

    .btn-primary-gradient {
        background: var(--accent-gradient);
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.25);
    }

    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(59, 130, 246, 0.35);
        color: white;
        text-decoration: none;
    }

    /* ===== FORM CARD ===== */
    .form-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow);
        max-width: 900px;
        margin: 0 auto;
    }

    .form-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        background: #f8fafc;
    }

    .form-card-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-card-header h3 i {
        color: var(--accent-1);
    }

    .form-card-body {
        padding: 24px;
    }

    /* ===== FORM ELEMENTS ===== */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        font-size: 14px;
        color: var(--text-primary);
        display: block;
        margin-bottom: 6px;
    }

    .form-group label .required {
        color: var(--danger);
        margin-left: 2px;
    }

    .form-control {
        width: 100%;
        padding: 10px 16px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 14px;
        transition: all 0.3s ease;
        outline: none;
    }

    .form-control:focus {
        border-color: var(--accent-1);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
        background: white;
    }

    .form-control.is-invalid {
        border-color: var(--danger);
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .invalid-feedback {
        font-size: 12px;
        color: var(--danger);
        margin-top: 4px;
    }

    /* ===== ALERTS ===== */
    .alert-modern {
        padding: 14px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-left: 4px solid;
        background: var(--bg-card);
        box-shadow: var(--shadow);
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    .alert-modern.success {
        border-color: var(--success);
        color: #065f46;
    }

    .alert-modern.success i {
        color: var(--success);
    }

    .alert-modern.error {
        border-color: var(--danger);
        color: #991b1b;
    }

    .alert-modern.error i {
        color: var(--danger);
    }

    .alert-modern i {
        font-size: 18px;
    }

    /* ===== FORM ACTIONS ===== */
    .form-actions {
        display: flex;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        margin-top: 8px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .add-service-wrapper {
            padding: 16px;
        }

        .page-header-left h1 {
            font-size: 22px;
        }

        .form-card-body {
            padding: 16px;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .add-service-wrapper {
            padding: 12px;
        }

        .page-header-left h1 {
            font-size: 18px;
        }

        .btn-primary-gradient,
        .btn-secondary-gradient {
            padding: 10px 20px;
            font-size: 13px;
        }
    }
</style>

<div class="add-service-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                <i class="fas fa-plus-circle"></i> Add New Service
            </h1>
            <p><i class="fas fa-arrow-trend-up" style="color: var(--accent-1);"></i> Create a new medical service</p>
        </div>
        <div>
            <a href="{{ route('admin.services.index') }}" class="btn-secondary-gradient">
                <i class="fas fa-arrow-left"></i> Back to Services
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert-modern success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-modern error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-modern error">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <h3>
                <i class="fas fa-concierge-bell"></i> Service Information
            </h3>
        </div>

        <div class="form-card-body">
            <form action="{{ route('admin.services.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Service Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Service Name <span class="required">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" placeholder="Enter service name" 
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Department -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="department">Department <span class="required">*</span></label>
                            <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                   id="department" name="department" placeholder="Enter department name" 
                                   value="{{ old('department') }}" required>
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Price -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price">Price (PKR) <span class="required">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" placeholder="Enter price in PKR" 
                                   value="{{ old('price') }}" step="0.01" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status <span class="required">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" 
                                      placeholder="Enter service description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary-gradient">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary-gradient">
                        <i class="fas fa-save"></i> Create Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection