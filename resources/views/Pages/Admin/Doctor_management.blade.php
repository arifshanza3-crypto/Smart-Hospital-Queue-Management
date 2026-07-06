@extends('Layout.admin-layout')

@section('page-title', 'Doctor Management')
@section('breadcrumb', 'Manage Doctors')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    .doctor-row {
        transition: background 0.3s ease;
    }
    .doctor-row:hover {
        background: #f8f9fa;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    .status-active {
        background: #d4edda;
        color: #155724;
    }
    .status-on-duty {
        background: #d1ecf1;
        color: #0c5460;
    }
    .status-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .search-box {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 10px 15px;
        width: 300px;
        transition: all 0.3s ease;
    }
    .search-box:focus {
        outline: none;
        border-color: #00d4ff;
        box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
    }

    .btn-action {
        padding: 6px 12px;
        margin: 0 3px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        transition: all 0.3s ease;
        display: inline-block;
        cursor: pointer;
    }
    .btn-edit {
        background: #00d4ff;
        color: white;
        border: none;
    }
    .btn-edit:hover {
        background: #0b2e33;
        transform: translateY(-2px);
    }
    .btn-delete {
        background: #dc3545;
        color: white;
        border: none;
    }
    .btn-delete:hover {
        background: #c82333;
        transform: translateY(-2px);
    }

    .stat-card {
        transition: transform 0.3s ease;
        cursor: pointer;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }

    .table-wrapper {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    .table-header {
        background: #0b2e33;
        color: white;
    }

    .loader {
        display: none;
        text-align: center;
        padding: 20px;
    }
    .loader.show {
        display: block;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #28a745;
    }
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }
    .empty-state i {
        font-size: 48px;
        color: #ccc;
        display: block;
        margin-bottom: 15px;
    }
    .empty-state h3 {
        color: #666;
        margin-bottom: 8px;
    }

    @media (max-width: 768px) {
        .search-box {
            width: 100%;
        }
        .table-wrapper {
            overflow-x: auto;
        }
        table {
            font-size: 12px;
        }
        th, td {
            padding: 10px 12px;
        }
    }
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="color: #0b2e33; margin-bottom: 5px;">
                <i class="fas fa-user-md" style="color: #00d4ff;"></i> Doctor Management
            </h1>
            <p style="color: #666;">Manage hospital specialized doctors</p>
        </div>
        <a href="{{ route('admin.doctors.create') }}" 
           style="background: linear-gradient(135deg, #00d4ff, #0b2e33); color: white; padding: 12px 24px; text-decoration: none; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease;">
            <i class="fas fa-plus"></i> Add New Doctor
        </a>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    @php
        $total = $doctors->count();
        $onDuty = $doctors->where('status', 'on_duty')->count();
        $active = $doctors->where('status', 'active')->count();
        $inactive = $doctors->where('status', 'inactive')->count();
    @endphp

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 15px; color: white;">
            <i class="fas fa-user-md" style="font-size: 35px; opacity: 0.9;"></i>
            <div style="font-size: 32px; font-weight: bold; margin-top: 10px;">{{ $total }}</div>
            <div style="opacity: 0.9;">Total Doctors</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #00d4ff 0%, #0b2e33 100%); padding: 20px; border-radius: 15px; color: white;">
            <i class="fas fa-stethoscope" style="font-size: 35px; opacity: 0.9;"></i>
            <div style="font-size: 32px; font-weight: bold; margin-top: 10px;">{{ $onDuty }}</div>
            <div style="opacity: 0.9;">On Duty</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 20px; border-radius: 15px; color: white;">
            <i class="fas fa-check-circle" style="font-size: 35px; opacity: 0.9;"></i>
            <div style="font-size: 32px; font-weight: bold; margin-top: 10px;">{{ $active }}</div>
            <div style="opacity: 0.9;">Active</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); padding: 20px; border-radius: 15px; color: white;">
            <i class="fas fa-clock" style="font-size: 35px; opacity: 0.9;"></i>
            <div style="font-size: 32px; font-weight: bold; margin-top: 10px;">{{ $inactive }}</div>
            <div style="opacity: 0.9;">Inactive</div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 15px; flex-wrap: wrap;">
        <div style="position: relative; flex: 1; max-width: 400px;">
            <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999;"></i>
            <input type="text" id="search" placeholder="Search doctors by name, specialization..." class="search-box" style="padding-left: 40px; width: 100%;">
        </div>
        <div>
            <select id="filterStatus" class="search-box" style="width: auto;">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="on_duty">On Duty</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <!-- Loader -->
    <div id="loader" class="loader">
        <i class="fas fa-spinner fa-spin" style="font-size: 24px; color: #00d4ff;"></i> Loading...
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr class="table-header">
                    <th style="padding: 15px; text-align: left; width: 5%;">ID</th>
                    <th style="padding: 15px; text-align: left; width: 25%;">Doctor</th>
                    <th style="padding: 15px; text-align: left; width: 15%;">Specialization</th>
                    <th style="padding: 15px; text-align: left; width: 20%;">Email</th>
                    <th style="padding: 15px; text-align: left; width: 15%;">Phone</th>
                    <th style="padding: 15px; text-align: left; width: 10%;">Status</th>
                    <th style="padding: 15px; text-align: left; width: 10%;">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($doctors as $doctor)
                <tr class="doctor-row" style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px;">{{ $doctor->id }}</td>
                    <td style="padding: 15px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #00d4ff, #0b2e33); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div>
                                <strong>Dr. {{ $doctor->name }}</strong>
                                @if($doctor->qualification)
                                    <div style="font-size: 11px; color: #999;">{{ $doctor->qualification }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="padding: 15px;">
                        <span style="background: #f0f0f0; padding: 4px 8px; border-radius: 5px; font-size: 12px;">
                            {{ $doctor->specialization }}
                        </span>
                    </td>
                    <td style="padding: 15px;">{{ $doctor->email }}</td>
                    <td style="padding: 15px;">{{ $doctor->phone }}</td>
                    <td style="padding: 15px;">
                        <span class="status-badge status-{{ str_replace('_', '-', $doctor->status) }}">
                            <i class="fas 
                                @if($doctor->status == 'active') fa-check-circle
                                @elseif($doctor->status == 'on_duty') fa-stethoscope
                                @else fa-clock @endif
                            "></i>
                            {{ ucfirst(str_replace('_', ' ', $doctor->status)) }}
                        </span>
                    </td>
                    <td style="padding: 15px;">
                        <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn-action btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button onclick="deleteDoctor({{ $doctor->id }})" class="btn-action btn-delete">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-user-md"></i>
                            <h3>No Doctors Found</h3>
                            <p>Click "Add New Doctor" to create your first doctor.</p>
                            <a href="{{ route('admin.doctors.create') }}" style="display: inline-block; margin-top: 15px; background: #00d4ff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
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
function deleteDoctor(id) {
    if(confirm('⚠️ Are you sure you want to delete this doctor?\n\nThis action cannot be undone!')) {
        showLoader();
        
        fetch('/admin/doctors/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoader();
            if(data.success) {
                showNotification('success', data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('error', data.message || 'Error deleting doctor');
            }
        })
        .catch(error => {
            hideLoader();
            showNotification('error', 'Network error. Please try again.');
            console.error('Error:', error);
        });
    }
}

// Search and filter functionality
document.getElementById('search').addEventListener('keyup', filterTable);
document.getElementById('filterStatus').addEventListener('change', filterTable);

function filterTable() {
    let searchValue = document.getElementById('search').value.toLowerCase();
    let statusValue = document.getElementById('filterStatus').value;
    let rows = document.querySelectorAll('#tableBody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        if(row.querySelector('td')) {
            let text = row.textContent.toLowerCase();
            let statusCell = row.querySelector('td:nth-child(6)');
            let status = '';
            
            if(statusCell) {
                let statusText = statusCell.textContent.trim().toLowerCase();
                if(statusText.includes('active')) status = 'active';
                if(statusText.includes('on duty')) status = 'on_duty';
                if(statusText.includes('inactive')) status = 'inactive';
            }
            
            let matchesSearch = text.includes(searchValue);
            let matchesStatus = !statusValue || status === statusValue;
            
            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        }
    });
    
    // Show/hide no results message
    let noResultsMsg = document.getElementById('noResultsMsg');
    if (visibleCount === 0 && rows.length > 0) {
        if (!noResultsMsg) {
            let tbody = document.getElementById('tableBody');
            let msgRow = document.createElement('tr');
            msgRow.id = 'noResultsMsg';
            msgRow.innerHTML = `<td colspan="7" style="padding: 40px; text-align: center; color: #999;">
                <i class="fas fa-search" style="font-size: 48px;"></i>
                <p>No matching doctors found</p>
            </td>`;
            tbody.appendChild(msgRow);
        }
    } else if (noResultsMsg) {
        noResultsMsg.remove();
    }
}

function showNotification(type, message) {
    let notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${type === 'success' ? '#d4edda' : '#f8d7da'};
        color: ${type === 'success' ? '#155724' : '#721c24'};
        border-left: 4px solid ${type === 'success' ? '#28a745' : '#dc3545'};
        border-radius: 8px;
        z-index: 9999;
        animation: slideIn 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    notification.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${message}`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function showLoader() {
    const loader = document.getElementById('loader');
    if (loader) loader.classList.add('show');
}

function hideLoader() {
    const loader = document.getElementById('loader');
    if (loader) loader.classList.remove('show');
}

const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
</script>
@endsection