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
        $active = $doctors->where('status', 'active')->count();
        $inactive = $doctors->where('status', 'inactive')->count();
    @endphp

    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="fas fa-user-md"></i></div>
            <div class="stat-number">{{ $total }}</div>
            <div class="stat-label">Total Doctors</div>
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
                            {{ ucfirst($doctor->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-group">
                            {{-- ✅ Status Update Buttons --}}
                            <button onclick="updateStatus({{ $doctor->id }}, 'active')" 
                                    class="action-btn status-active {{ $doctor->status == 'active' ? 'active' : '' }}" 
                                    title="Set Active">
                                <i class="fas fa-check-circle"></i>
                            </button>
                            <button onclick="updateStatus({{ $doctor->id }}, 'inactive')" 
                                    class="action-btn status-inactive {{ $doctor->status == 'inactive' ? 'active' : '' }}" 
                                    title="Set Inactive">
                                <i class="fas fa-times-circle"></i>
                            </button>
                            
                            <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="action-btn edit" title="Edit Doctor">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteDoctor({{ $doctor->id }})" class="action-btn delete" title="Delete Doctor">
                                <i class="fas fa-trash"></i>
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

<script>
// ✅ Update Doctor Status
function updateStatus(id, status) {
    if (!confirm('Are you sure you want to change status to ' + status + '?')) return;
    
    fetch('/admin/doctors/' + id + '/status/' + status, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error updating status');
    });
}

// ✅ Delete Doctor
function deleteDoctor(id) {
    if (!confirm('Are you sure you want to delete this doctor?')) return;
    
    fetch('/admin/doctors/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error deleting doctor');
    });
}

// ✅ Search Filter
document.getElementById('search').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    const rows = document.querySelectorAll('#tableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
});

// ✅ Status Filter
document.getElementById('filterStatus').addEventListener('change', function() {
    const status = this.value;
    const rows = document.querySelectorAll('#tableBody tr');
    
    rows.forEach(row => {
        if (status === '') {
            row.style.display = '';
        } else {
            const statusText = row.querySelector('.status-badge-modern')?.textContent?.toLowerCase().trim() || '';
            row.style.display = statusText.includes(status) ? '' : 'none';
        }
    });
});

// ✅ Reset Filters
function resetFilters() {
    document.getElementById('search').value = '';
    document.getElementById('filterStatus').value = '';
    document.querySelectorAll('#tableBody tr').forEach(row => {
        row.style.display = '';
    });
}
</script>

<style>
/* Additional Styles */
.action-group {
    display: flex;
    gap: 4px;
    justify-content: center;
    flex-wrap: wrap;
}

.action-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    background: rgba(255,255,255,0.05);
    color: rgba(255,255,255,0.4);
    text-decoration: none;
}

.action-btn:hover {
    transform: translateY(-2px);
}

.action-btn.status-active {
    color: #28a745;
    background: rgba(40, 167, 69, 0.1);
}

.action-btn.status-active:hover {
    background: #28a745;
    color: #fff;
}

.action-btn.status-active.active {
    background: #28a745;
    color: #fff;
}

.action-btn.status-inactive {
    color: #6c757d;
    background: rgba(108, 117, 125, 0.1);
}

.action-btn.status-inactive:hover {
    background: #6c757d;
    color: #fff;
}

.action-btn.status-inactive.active {
    background: #6c757d;
    color: #fff;
}

.action-btn.edit {
    color: #00d4ff;
    background: rgba(0, 212, 255, 0.1);
}

.action-btn.edit:hover {
    background: #00d4ff;
    color: #fff;
}

.action-btn.delete {
    color: #dc3545;
    background: rgba(220, 53, 69, 0.1);
}

.action-btn.delete:hover {
    background: #dc3545;
    color: #fff;
}

.status-badge-modern.active {
    background: rgba(40, 167, 69, 0.15);
    color: #28a745;
}

.status-badge-modern.inactive {
    background: rgba(108, 117, 125, 0.15);
    color: #6c757d;
}

.status-badge-modern.on_duty {
    background: rgba(0, 123, 255, 0.15);
    color: #007bff;
}

.status-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 6px;
}

.status-badge-modern.active .status-dot {
    background: #28a745;
}

.status-badge-modern.inactive .status-dot {
    background: #6c757d;
}

.status-badge-modern.on_duty .status-dot {
    background: #007bff;
}

.stat-card.purple { border-left: 4px solid #6f42c1; }
.stat-card.green { border-left: 4px solid #28a745; }
.stat-card.red { border-left: 4px solid #dc3545; }
</style>

@endsection