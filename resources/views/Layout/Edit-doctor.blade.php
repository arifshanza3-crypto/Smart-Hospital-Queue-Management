@extends('Layout.admin-layout')

@section('page-title', 'Edit Doctor')
@section('breadcrumb', 'Edit Doctor')

@section('content')
<div class="doctor-management-wrapper">
    <div class="page-header">
        <div class="page-header-left">
            <h1><i class="fas fa-user-md"></i> Edit Doctor</h1>
            <p>Update doctor information</p>
        </div>
        <div>
            <a href="{{ route('admin.doctors.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert-modern error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $doctor->name) }}" required>
                        @error('name') <span class="error-text">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Specialization <span class="required">*</span></label>
                        <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $doctor->specialization) }}" required>
                        @error('specialization') <span class="error-text">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $doctor->email) }}" required>
                        @error('email') <span class="error-text">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Phone <span class="required">*</span></label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $doctor->phone) }}" required>
                        @error('phone') <span class="error-text">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Qualification</label>
                        <input type="text" name="qualification" class="form-control" value="{{ old('qualification', $doctor->qualification) }}">
                        @error('qualification') <span class="error-text">{{ $message }}</span> @enderror
                    </div>

                    {{-- ✅ Status - Only Active/Inactive --}}
                    <div class="form-group">
                        <label>Status <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ old('status', $doctor->status) == 'active' ? 'selected' : '' }}>🟢 Active - Available</option>
                            <option value="inactive" {{ old('status', $doctor->status) == 'inactive' ? 'selected' : '' }}>🔴 Inactive - Not Available</option>
                        </select>
                        @error('status') <span class="error-text">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.doctors.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Update Doctor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.doctor-management-wrapper {
    padding: 24px 28px;
    background: #f8fafc;
    min-height: 100vh;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 16px;
}

.page-header-left h1 {
    font-size: 28px;
    font-weight: 800;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-header-left h1 i {
    color: #0ea5e9;
}

.page-header-left p {
    color: #475569;
    font-size: 14px;
    margin: 4px 0 0 0;
}

.btn-secondary {
    background: #e2e8f0;
    color: #1e293b;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #cbd5e1;
    color: #0f172a;
    text-decoration: none;
}

.alert-modern {
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-modern.error {
    background: rgba(255, 75, 43, 0.1);
    border: 1px solid rgba(255, 75, 43, 0.2);
    color: #dc3545;
}

.card {
    background: white;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    padding: 30px;
    max-width: 700px;
}

.card-body {
    width: 100%;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    margin-bottom: 5px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 6px;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.form-group .required {
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    outline: none;
    background: #f8fafc;
    color: #1e293b;
}

.form-control:focus {
    border-color: #0ea5e9;
    box-shadow: 0 0 0 4px rgba(14,165,233,0.08);
    background: white;
}

.form-control option {
    padding: 8px;
    background: white;
    color: #1e293b;
}

select.form-control {
    appearance: none;
    -webkit-appearance: none;
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23475569' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 12px;
    padding-right: 36px;
}

.error-text {
    color: #dc3545;
    font-size: 12px;
    margin-top: 4px;
    display: block;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.btn-submit {
    padding: 12px 30px;
    background: linear-gradient(135deg, #0ea5e9, #3b82f6);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 16px rgba(14,165,233,0.25);
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(14,165,233,0.35);
}

.btn-cancel {
    padding: 12px 24px;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-cancel:hover {
    background: #e2e8f0;
    color: #0f172a;
    text-decoration: none;
}

@media (max-width: 768px) {
    .doctor-management-wrapper {
        padding: 16px;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .page-header-left h1 {
        font-size: 22px;
    }

    .card {
        padding: 16px;
    }

    .form-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .form-actions {
        flex-direction: column;
    }

    .form-actions a, .form-actions button {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection