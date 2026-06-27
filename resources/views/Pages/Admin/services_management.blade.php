@extends('Layout.admin-layout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    .service-row {
        transition: background 0.3s ease;
    }
    
    .service-row:hover {
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
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="color: #0b2e33; margin-bottom: 5px;">Services Management</h1>
            <p style="color: #666;">Manage hospital medical services and procedures</p>
        </div>
        <a href="{{ route('admin.services.create') }}" 
           style="background: linear-gradient(135deg, #00d4ff, #0b2e33); color: white; padding: 12px 24px; text-decoration: none; border-radius: 10px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease;">
            <i class="fas fa-plus"></i> Add New Service
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
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 15px; color: white;">
            <i class="fas fa-concierge-bell" style="font-size: 35px; opacity: 0.9;"></i>
            <div style="font-size: 32px; font-weight: bold; margin-top: 10px;">{{ $total ?? 0 }}</div>
            <div style="opacity: 0.9;">Total Services</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #00d4ff 0%, #0b2e33 100%); padding: 20px; border-radius: 15px; color: white;">
            <i class="fas fa-check-circle" style="font-size: 35px; opacity: 0.9;"></i>
            <div style="font-size: 32px; font-weight: bold; margin-top: 10px;">{{ $active ?? 0 }}</div>
            <div style="opacity: 0.9;">Active Services</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 20px; border-radius: 15px; color: white;">
            <i class="fas fa-times-circle" style="font-size: 35px; opacity: 0.9;"></i>
            <div style="font-size: 32px; font-weight: bold; margin-top: 10px;">{{ $inactive ?? 0 }}</div>
            <div style="opacity: 0.9;">Inactive Services</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 20px; border-radius: 15px; color: white;">
            <i class="fas fa-dollar-sign" style="font-size: 35px; opacity: 0.9;"></i>
            <div style="font-size: 32px; font-weight: bold; margin-top: 10px;">${{ number_format($totalRevenue ?? 0, 0) }}</div>
            <div style="opacity: 0.9;">Revenue Potential</div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 15px; flex-wrap: wrap;">
        <div style="position: relative; flex: 1; max-width: 400px;">
            <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999;"></i>
            <input type="text" id="search" placeholder="Search services by name, department..." class="search-box" style="padding-left: 40px; width: 100%;">
        </div>
        <div>
            <select id="filterStatus" class="search-box" style="width: auto;">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <!-- Loader -->
    <div id="loader" class="loader">
        <i class="fas fa-spinner fa-spin" style="font-size: 24px; color: #00d4ff;"></i> Loading...
    </div>

    <!-- Services Table -->
    <div class="table-wrapper">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr class="table-header">
                    <th style="padding: 15px; text-align: left; width: 5%;">ID</th>
                    <th style="padding: 15px; text-align: left; width: 25%;">Service Name</th>
                    <th style="padding: 15px; text-align: left; width: 15%;">Department</th>
                    <th style="padding: 15px; text-align: left; width: 10%;">Price</th>
                    <th style="padding: 15px; text-align: left; width: 10%;">Duration</th>
                    <th style="padding: 15px; text-align: left; width: 10%;">Status</th>
                    <th style="padding: 15px; text-align: left; width: 25%;">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($services as $service)
                <tr class="service-row" style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px;">{{ $service->id }}</td>
                    <td style="padding: 15px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            @if($service->icon)
                                <i class="{{ $service->icon }}" style="font-size: 24px; color: #00d4ff;"></i>
                            @else
                                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #00d4ff, #0b2e33); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white;">
                                    <i class="fas fa-stethoscope"></i>
                                </div>
                            @endif
                            <div>
                                <strong>{{ $service->name }}</strong>
                                <div style="font-size: 12px; color: #666; margin-top: 3px;">{{ Str::limit($service->description, 60) }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 15px;">
                        <span style="background: #f0f0f0; padding: 4px 8px; border-radius: 5px; font-size: 12px;">
                            {{ $service->department ?? 'General' }}
                        </span>
                    </td>
                    <td style="padding: 15px;">
                        <strong style="color: #0b2e33;">{{ $service->formatted_price }}</strong>
                    </td>
                    <td style="padding: 15px;">
                        {{ $service->formatted_duration }}
                    </td>
                    <td style="padding: 15px;">
                        <span class="status-badge status-{{ $service->status }}">
                            <i class="fas {{ $service->status == 'active' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ ucfirst($service->status) }}
                        </span>
                    </td>
                    <td style="padding: 15px;">
                        <a href="{{ route('admin.services.edit', $service->id) }}" class="btn-action btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button onclick="deleteService({{ $service->id }})" class="btn-action btn-delete">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                        <button onclick="toggleStatus({{ $service->id }}, '{{ $service->status }}')" class="btn-action" style="background: #17a2b8; color: white;">
                            <i class="fas {{ $service->status == 'active' ? 'fa-pause' : 'fa-play' }}"></i>
                            {{ $service->status == 'active' ? 'Deactivate' : 'Activate' }}
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 60px; text-align: center;">
                        <i class="fas fa-concierge-bell" style="font-size: 48px; color: #ccc;"></i>
                        <h3 style="color: #666; margin-top: 15px;">No Services Found</h3>
                        <p style="color: #999;">Click "Add New Service" to create your first service.</p>
                        <a href="{{ route('admin.services.create') }}" style="display: inline-block; margin-top: 15px; background: #00d4ff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                            <i class="fas fa-plus"></i> Add New Service
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
// Delete service function
function deleteService(id) {
    if(confirm('⚠️ Are you sure you want to delete this service?\n\nThis action cannot be undone!')) {
        showLoader();
        
        fetch('/admin/services/' + id, {
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
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showNotification('error', data.message || 'Error deleting service');
            }
        })
        .catch(error => {
            hideLoader();
            showNotification('error', 'Network error. Please try again.');
            console.error('Error:', error);
        });
    }
}

// Toggle status function
function toggleStatus(id, currentStatus) {
    let newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    let action = newStatus === 'active' ? 'activate' : 'deactivate';
    
    if(confirm(`Are you sure you want to ${action} this service?`)) {
        showLoader();
        
        fetch(`/admin/services/${id}/status/${newStatus}`, {
            method: 'PATCH',
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
                showNotification('success', `Service ${action}d successfully!`);
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showNotification('error', data.message || 'Error updating status');
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
document.getElementById('search').addEventListener('keyup', function() {
    filterTable();
});

document.getElementById('filterStatus').addEventListener('change', function() {
    filterTable();
});

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
                <p>No matching services found</p>
            	</td>`;
            tbody.appendChild(msgRow);
        }
    } else if (noResultsMsg) {
        noResultsMsg.remove();
    }
}

// Show notification
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

// Show/hide loader
function showLoader() {
    const loader = document.getElementById('loader');
    if (loader) loader.classList.add('show');
}

function hideLoader() {
    const loader = document.getElementById('loader');
    if (loader) loader.classList.remove('show');
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('Services management page loaded');
});
</script>
@endsection