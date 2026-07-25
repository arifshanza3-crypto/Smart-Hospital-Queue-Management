@extends('Layout.admin-layout')

@section('page-title', 'Doctor Management')
@section('breadcrumb', 'Manage Doctors')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Doctor_management.css') }}">

<div class="doctor-management-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                <i class="fas fa-user-md"></i> Doctor Management
            </h1>
            <p><i class="fas fa-arrow-trend-up" style="color: var(--accent-1);"></i> Manage hospital specialized doctors</p>
        </div>
        <div>
            <a href="{{ route('admin.doctors.create') }}" class="btn-primary-gradient">
                <i class="fas fa-plus"></i> Add New Doctor
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

    <!-- Statistics Cards -->
    @php
        $total = $doctors->count();
        $onDuty = $doctors->where('status', 'on_duty')->count();
        $active = $doctors->where('status', 'active')->count();
        $inactive = $doctors->where('status', 'inactive')->count();
    @endphp

    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="fas fa-user-md"></i></div>
            <div class="stat-number">{{ $total }}</div>
            <div class="stat-label">Total Doctors</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-stethoscope"></i></div>
            <div class="stat-number">{{ $onDuty }}</div>
            <div class="stat-label">On Duty</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ $active }}</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card red">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-number">{{ $inactive }}</div>
            <div class="stat-label">Inactive</div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="search-filter-bar">
        <div class="search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" id="search" placeholder="Search doctors by name, specialization, or email...">
        </div>
        <div class="filter-wrapper">
            <select id="filterStatus">
                <option value="">All Status</option>
                <option value="active">🟢 Active</option>
                <option value="on_duty">🔵 On Duty</option>
                <option value="inactive">🔴 Inactive</option>
            </select>
            <button class="btn-reset" onclick="resetFilters()">
                <i class="fas fa-undo"></i> Reset
            </button>
        </div>
    </div>

    <!-- Loader -->
    <div id="loader" class="loader">
        <i class="fas fa-spinner"></i>
        <p style="color: var(--text-secondary); margin-top: 8px;">Loading...</p>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 25%;">Doctor</th>
                    <th style="width: 15%;">Specialization</th>
                    <th style="width: 20%;">Email</th>
                    <th style="width: 15%;">Phone</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 10%; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($doctors as $doctor)
                <tr>
                    <td>
                        <span style="color: var(--text-secondary); font-weight: 500;">#{{ $doctor->id }}</span>
                    </td>
                    <td>
                        <div class="doctor-cell">
                            <div class="doctor-avatar">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div>
                                <div class="doctor-name">Dr. {{ $doctor->name }}</div>
                                @if($doctor->qualification)
                                    <div class="doctor-qualification">{{ $doctor->qualification }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="specialization-badge">{{ $doctor->specialization }}</span>
                    </td>
                    <td style="color: var(--text-secondary);">{{ $doctor->email }}</td>
                    <td style="color: var(--text-secondary);">{{ $doctor->phone }}</td>
                    <td>
                        <span class="status-badge-modern {{ $doctor->status }}">
                            <span class="status-dot"></span>
                            {{ ucfirst(str_replace('_', ' ', $doctor->status)) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="action-btn edit" title="Edit Doctor">
                                <i class="fas fa-edit"></i>
                                <span class="tooltip-text">Edit</span>
                            </a>
                            <button onclick="deleteDoctor({{ $doctor->id }})" class="action-btn delete" title="Delete Doctor">
                                <i class="fas fa-trash"></i>
                                <span class="tooltip-text">Delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-user-md"></i>
                            <h3>No Doctors Found</h3>
                            <p>Get started by adding your first doctor to the system.</p>
                            <a href="{{ route('admin.doctors.create') }}" class="btn-primary-gradient" style="display: inline-flex;">
                                <i class="fas fa-plus"></i> Add New Doctor
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="{{ asset('js/Doctor_management.js') }}"></script>
@endsection