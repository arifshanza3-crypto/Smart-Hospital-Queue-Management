@extends('Layout.admin-layout')

@section('page-title', 'Services Management')
@section('breadcrumb', 'Manage Services')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* ============================================
       SERVICES MANAGEMENT - LIGHT THEME
       ============================================ */
    
    :root {
        --bg-primary: #f8fafc;
        --bg-card: #ffffff;
        --bg-table: #ffffff;
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
        --warning: #f59e0b;
        --info: #0ea5e9;
    }

    .services-management-wrapper {
        padding: 24px 28px;
        background: var(--bg-primary);
        min-height: 100vh;
    }

    /* ===== HEADER ===== */
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

    /* ===== STATISTICS CARDS ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px 24px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-hover);
        border-color: #dbeafe;
    }

    .stat-card .stat-icon {
        font-size: 28px;
        opacity: 0.8;
    }

    .stat-card .stat-number {
        font-size: 32px;
        font-weight: 800;
        color: var(--text-primary);
        margin: 8px 0 2px;
        line-height: 1.2;
    }

    .stat-card .stat-label {
        color: var(--text-secondary);
        font-size: 13px;
        font-weight: 500;
    }

    .stat-card .stat-trend {
        position: absolute;
        top: 16px;
        right: 16px;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .stat-trend.up {
        background: #d1fae5;
        color: #065f46;
    }

    .stat-trend.down {
        background: #fee2e2;
        color: #991b1b;
    }

    .stat-card.purple::before { background: var(--accent-gradient); }
    .stat-card.purple .stat-icon { color: #6366f1; }

    .stat-card.blue::before { background: linear-gradient(135deg, #3b82f6, #0ea5e9); }
    .stat-card.blue .stat-icon { color: #3b82f6; }

    .stat-card.green::before { background: linear-gradient(135deg, #10b981, #34d399); }
    .stat-card.green .stat-icon { color: #10b981; }

    .stat-card.orange::before { background: linear-gradient(135deg, #f59e0b, #f97316); }
    .stat-card.orange .stat-icon { color: #f59e0b; }

    /* ===== SEARCH & FILTER ===== */
    .search-filter-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        background: var(--bg-card);
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
    }

    .search-wrapper {
        position: relative;
        flex: 1;
        max-width: 400px;
    }

    .search-wrapper i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }

    .search-wrapper input {
        width: 100%;
        padding: 10px 16px 10px 44px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 14px;
        transition: all 0.3s ease;
        outline: none;
    }

    .search-wrapper input::placeholder {
        color: var(--text-muted);
    }

    .search-wrapper input:focus {
        border-color: var(--accent-1);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
        background: white;
    }

    .filter-wrapper {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .filter-wrapper select {
        padding: 10px 16px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 13px;
        cursor: pointer;
        outline: none;
        min-width: 140px;
        transition: all 0.3s ease;
    }

    .filter-wrapper select:focus {
        border-color: var(--accent-1);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
    }

    .btn-reset {
        padding: 10px 16px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
    }

    .btn-reset:hover {
        background: #f1f5f9;
        color: var(--text-primary);
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

    /* ===== TABLE ===== */
    .table-container {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .table-container table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-container thead {
        background: #f8fafc;
        border-bottom: 1px solid var(--border-color);
    }

    .table-container thead th {
        padding: 14px 20px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-secondary);
        white-space: nowrap;
    }

    .table-container tbody td {
        padding: 14px 20px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        color: var(--text-primary);
        font-size: 14px;
    }

    .table-container tbody tr {
        transition: all 0.3s ease;
    }

    .table-container tbody tr:hover {
        background: #f8fafc;
    }

    .table-container tbody tr:last-child td {
        border-bottom: none;
    }

    /* Service Info Cell */
    .service-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .service-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--accent-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        flex-shrink: 0;
    }

    .service-name {
        font-weight: 600;
        color: var(--text-primary);
    }

    .service-description {
        font-size: 12px;
        color: var(--text-secondary);
        margin-top: 2px;
    }

    /* Department Badge */
    .department-badge {
        background: #eef2ff;
        border: 1px solid #e0e7ff;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        color: #4338ca;
        display: inline-block;
    }

    /* Price */
    .price-text {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 15px;
    }

    /* Duration */
    .duration-text {
        color: var(--text-secondary);
        font-size: 13px;
    }

    /* Status Badges - Light Theme */
    .status-badge-modern {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge-modern.active {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .status-badge-modern.inactive {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .status-badge-modern .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-badge-modern.active .status-dot {
        background: #10b981;
    }

    .status-badge-modern.inactive .status-dot {
        background: #ef4444;
    }

    /* ===== ACTION BUTTONS - ICON ONLY ===== */
    .action-group {
        display: flex;
        gap: 6px;
        align-items: center;
        justify-content: center;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .action-btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transition: all 0.5s ease;
        transform: translate(-50%, -50%);
    }

    .action-btn:hover::after {
        width: 200%;
        height: 200%;
    }

    .action-btn:hover {
        transform: translateY(-2px) scale(1.05);
    }

    /* Edit Button */
    .action-btn.edit {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    .action-btn.edit:hover {
        background: #1e40af;
        color: white;
        box-shadow: 0 4px 16px rgba(30, 64, 175, 0.3);
    }

    /* Delete Button */
    .action-btn.delete {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .action-btn.delete:hover {
        background: #991b1b;
        color: white;
        box-shadow: 0 4px 16px rgba(153, 27, 27, 0.3);
    }

    /* Toggle Status Button */
    .action-btn.toggle {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .action-btn.toggle:hover {
        background: #d97706;
        color: white;
        box-shadow: 0 4px 16px rgba(217, 119, 6, 0.3);
    }

    .action-btn.toggle.active-btn {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .action-btn.toggle.active-btn:hover {
        background: #10b981;
        color: white;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
    }

    /* Tooltip for action buttons */
    .action-btn {
        position: relative;
    }

    .action-btn .tooltip-text {
        visibility: hidden;
        opacity: 0;
        width: 70px;
        background: #1e293b;
        color: white;
        text-align: center;
        border-radius: 6px;
        padding: 4px 8px;
        position: absolute;
        z-index: 1;
        bottom: 110%;
        left: 50%;
        transform: translateX(-50%);
        font-size: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .action-btn .tooltip-text::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border-width: 5px;
        border-style: solid;
        border-color: #1e293b transparent transparent transparent;
    }

    .action-btn:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 56px;
        color: var(--text-muted);
        display: block;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: var(--text-secondary);
        margin-bottom: 20px;
    }

    /* ===== LOADER ===== */
    .loader {
        display: none;
        text-align: center;
        padding: 40px;
    }

    .loader.show {
        display: block;
    }

    .loader i {
        font-size: 32px;
        color: var(--accent-1);
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 992px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header-left h1 {
            font-size: 22px;
        }

        .search-filter-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-wrapper {
            max-width: 100%;
        }

        .filter-wrapper {
            flex-wrap: wrap;
        }

        .filter-wrapper select {
            flex: 1;
            min-width: 120px;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .stat-card .stat-number {
            font-size: 24px;
        }

        .table-container {
            overflow-x: auto;
        }

        .table-container table {
            font-size: 13px;
            min-width: 700px;
        }
    }

    @media (max-width: 576px) {
        .services-management-wrapper {
            padding: 12px;
        }

        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .stat-card {
            padding: 14px 16px;
        }

        .stat-card .stat-number {
            font-size: 20px;
        }

        .page-header-left h1 {
            font-size: 18px;
        }

        .btn-primary-gradient {
            padding: 10px 20px;
            font-size: 13px;
        }

        .filter-wrapper select {
            min-width: 100%;
        }
    }
</style>

<div class="services-management-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                <i class="fas fa-concierge-bell"></i> Services Management
            </h1>
            <p><i class="fas fa-arrow-trend-up" style="color: var(--accent-1);"></i> Manage hospital medical services and procedures</p>
        </div>
        <div>
            <a href="{{ route('admin.services.create') }}" class="btn-primary-gradient">
                <i class="fas fa-plus"></i> Add New Service
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
        $total = $services->count();
        $active = $services->where('status', 'active')->count();
        $inactive = $services->where('status', 'inactive')->count();
        $totalRevenue = $services->sum('price') ?? 0;
    @endphp

    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="fas fa-concierge-bell"></i></div>
            <div class="stat-number">{{ $total }}</div>
            <div class="stat-label">Total Services</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ $active }}</div>
            <div class="stat-label">Active Services</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
            <div class="stat-number">{{ $inactive }}</div>
            <div class="stat-label">Inactive Services</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-rupee-sign"></i></div>
            <div class="stat-number">PKR {{ number_format($totalRevenue, 0) }}</div>
            <div class="stat-label">Revenue Potential</div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="search-filter-bar">
        <div class="search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" id="search" placeholder="Search services by name, department...">
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
                    <th style="width: 25%;">Service</th>
                    <th style="width: 15%;">Department</th>
                    <th style="width: 10%;">Price</th>
                    <th style="width: 10%;">Duration</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 25%; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($services as $service)
                <tr>
                    <td>
                        <span style="color: var(--text-secondary); font-weight: 500;">#{{ $service->id }}</span>
                    </td>
                    <td>
                        <div class="service-cell">
                            <div class="service-icon">
                                <i class="{{ $service->icon ?? 'fas fa-stethoscope' }}"></i>
                            </div>
                            <div>
                                <div class="service-name">{{ $service->name }}</div>
                                <div class="service-description">{{ Str::limit($service->description, 60) }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="department-badge">{{ $service->department ?? 'General' }}</span>
                    </td>
                    <td>
                        <span class="price-text">PKR {{ number_format($service->price, 0) }}</span>
                    </td>
                    <td>
                        <span class="duration-text">{{ $service->duration ?? '30 mins' }}</span>
                    </td>
                    <td>
                        <span class="status-badge-modern {{ $service->status }}">
                            <span class="status-dot"></span>
                            {{ ucfirst($service->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-group">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.services.edit', $service->id) }}" class="action-btn edit" title="Edit Service">
                                <i class="fas fa-edit"></i>
                                <span class="tooltip-text">Edit</span>
                            </a>
                            
                            <!-- Toggle Status Button -->
                            <button onclick="toggleStatus({{ $service->id }}, '{{ $service->status }}')" 
                                    class="action-btn toggle {{ $service->status == 'active' ? 'active-btn' : '' }}" 
                                    title="{{ $service->status == 'active' ? 'Deactivate' : 'Activate' }}">
                                <i class="fas {{ $service->status == 'active' ? 'fa-pause' : 'fa-play' }}"></i>
                                <span class="tooltip-text">{{ $service->status == 'active' ? 'Deactivate' : 'Activate' }}</span>
                            </button>
                            
                            <!-- Delete Button -->
                            <button onclick="deleteService({{ $service->id }})" class="action-btn delete" title="Delete Service">
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
                            <i class="fas fa-concierge-bell"></i>
                            <h3>No Services Found</h3>
                            <p>Get started by adding your first service to the system.</p>
                            <a href="{{ route('admin.services.create') }}" class="btn-primary-gradient" style="display: inline-flex;">
                                <i class="fas fa-plus"></i> Add New Service
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
    // ============================================
    // DELETE SERVICE
    // ============================================
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
                    setTimeout(() => location.reload(), 1000);
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

    // ============================================
    // TOGGLE STATUS
    // ============================================
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
                    setTimeout(() => location.reload(), 1000);
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

    // ============================================
    // SEARCH & FILTER
    // ============================================
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
                let statusCell = row.querySelector('.status-badge-modern');
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
                msgRow.innerHTML = `
                    <td colspan="7" style="padding: 40px; text-align: center;">
                        <div class="empty-state" style="padding: 20px;">
                            <i class="fas fa-search" style="font-size: 40px;"></i>
                            <h3 style="color: var(--text-primary); font-weight: 600;">No Matching Services</h3>
                            <p style="color: var(--text-secondary);">Try adjusting your search or filter criteria</p>
                        </div>
                    </td>
                `;
                tbody.appendChild(msgRow);
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }

    // ============================================
    // RESET FILTERS
    // ============================================
    function resetFilters() {
        document.getElementById('search').value = '';
        document.getElementById('filterStatus').value = '';
        filterTable();
    }

    // ============================================
    // NOTIFICATION SYSTEM
    // ============================================
    function showNotification(type, message) {
        let notification = document.createElement('div');
        let bgColor = type === 'success' ? '#d1fae5' : '#fee2e2';
        let borderColor = type === 'success' ? '#10b981' : '#ef4444';
        let textColor = type === 'success' ? '#065f46' : '#991b1b';
        let icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        notification.style.cssText = `
            position: fixed;
            top: 80px;
            right: 24px;
            padding: 16px 24px;
            background: ${bgColor};
            border-left: 4px solid ${borderColor};
            color: ${textColor};
            border-radius: 12px;
            z-index: 9999;
            animation: slideIn 0.3s ease;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            font-size: 14px;
            min-width: 280px;
        `;
        notification.innerHTML = `<i class="fas ${icon}"></i> ${message}`;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }

    // ============================================
    // LOADER
    // ============================================
    function showLoader() {
        const loader = document.getElementById('loader');
        if (loader) loader.classList.add('show');
    }

    function hideLoader() {
        const loader = document.getElementById('loader');
        if (loader) loader.classList.remove('show');
    }

    // ============================================
    // STYLES FOR ANIMATIONS
    // ============================================
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