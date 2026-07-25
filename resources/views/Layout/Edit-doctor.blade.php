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

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $doctor->name }}" required>
                </div>

                <div class="form-group">
                    <label>Specialization</label>
                    <input type="text" name="specialization" class="form-control" value="{{ $doctor->specialization }}" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $doctor->email }}" required>
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ $doctor->phone }}">
                </div>

                <div class="form-group">
                    <label>Qualification</label>
                    <input type="text" name="qualification" class="form-control" value="{{ $doctor->qualification }}">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ $doctor->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="on_duty" {{ $doctor->status == 'on_duty' ? 'selected' : '' }}>On Duty</option>
                        <option value="inactive" {{ $doctor->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Doctor</button>
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

    .card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        padding: 24px;
        max-width: 600px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 6px;
        font-size: 14px;
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

    .btn-primary {
        background: linear-gradient(135deg, #0ea5e9, #3b82f6);
        color: white;
        padding: 12px 28px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(14,165,233,0.25);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(14,165,233,0.35);
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
    }
</style>
@endsection