@extends('Layout.admin-layout')

@section('page-title', 'User Management')
@section('breadcrumb', 'Manage Users')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* ============================================
       USER MANAGEMENT - LIGHT THEME
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
        --warning: #f59e0b;
        --info: #0ea5e9;
    }

    .user-management-wrapper {
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
        grid-template-columns: repeat(6, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 18px 16px;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
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
        margin-bottom: 8px;
    }

    .stat-card .stat-number {
        font-size: 26px;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .stat-card .stat-label {
        color: var(--text-secondary);
        font-size: 12px;
        font-weight: 500;
        margin-top: 4px;
    }

    .stat-card.purple::before { background: var(--accent-gradient); }
    .stat-card.purple .stat-icon { color: #6366f1; }

    .stat-card.green::before { background: linear-gradient(135deg, #10b981, #34d399); }
    .stat-card.green .stat-icon { color: #10b981; }

    .stat-card.red::before { background: linear-gradient(135deg, #ef4444, #f87171); }
    .stat-card.red .stat-icon { color: #ef4444; }

    .stat-card.blue::before { background: linear-gradient(135deg, #3b82f6, #0ea5e9); }
    .stat-card.blue .stat-icon { color: #3b82f6; }

    .stat-card.orange::before { background: linear-gradient(135deg, #f59e0b, #f97316); }
    .stat-card.orange .stat-icon { color: #f59e0b; }

    .stat-card.gray::before { background: linear-gradient(135deg, #94a3b8, #cbd5e1); }
    .stat-card.gray .stat-icon { color: #94a3b8; }

    /* ===== SEARCH ===== */
    .search-box {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .search-wrapper {
        position: relative;
        flex: 1;
        min-width: 250px;
    }

    .search-wrapper i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }

    .search-input {
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

    .search-input:focus {
        border-color: var(--accent-1);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
        background: white;
    }

    .search-input::placeholder {
        color: var(--text-muted);
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
        padding: 14px 18px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-secondary);
        white-space: nowrap;
    }

    .table-container tbody td {
        padding: 14px 18px;
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

    /* User Info Cell */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--accent-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }

    .user-name {
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Role Badges */
    .role-badge {
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .role-badge.admin {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .role-badge.staff {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    .role-badge.user {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .role-badge.warden {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    /* Status Badges */
    .status-badge {
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-badge.active {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .status-badge.inactive {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .status-badge .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-badge.active .status-dot {
        background: #10b981;
    }

    .status-badge.inactive .status-dot {
        background: #ef4444;
    }

    /* ===== ACTION BUTTONS ===== */
    .action-group {
        display: flex;
        gap: 6px;
        align-items: center;
        justify-content: center;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        font-size: 14px;
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

    .action-btn .tooltip-text {
        visibility: hidden;
        opacity: 0;
        width: 60px;
        background: #1e293b;
        color: white;
        text-align: center;
        border-radius: 6px;
        padding: 3px 6px;
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

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
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
    }

    @media (max-width: 768px) {
        .user-management-wrapper {
            padding: 16px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .stat-card .stat-number {
            font-size: 22px;
        }

        .table-container {
            overflow-x: auto;
        }

        .table-container table {
            font-size: 13px;
            min-width: 700px;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            font-size: 12px;
        }
    }

    @media (max-width: 576px) {
        .user-management-wrapper {
            padding: 12px;
        }

        .stats-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .stat-card {
            padding: 12px 10px;
        }

        .stat-card .stat-number {
            font-size: 18px;
        }

        .page-header-left h1 {
            font-size: 18px;
        }

        .btn-primary-gradient {
            padding: 10px 20px;
            font-size: 13px;
        }
    }
</style>

<div class="user-management-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                <i class="fas fa-users"></i> User Management
            </h1>
            <p><i class="fas fa-arrow-trend-up" style="color: var(--accent-1);"></i> Manage system users and their roles</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="btn-primary-gradient">
                <i class="fas fa-plus"></i> Add New User
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
    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-number">{{ $total ?? 0 }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ $active ?? 0 }}</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card red">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
            <div class="stat-number">{{ $inactive ?? 0 }}</div>
            <div class="stat-label">Inactive</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-icon"><i class="fas fa-crown"></i></div>
            <div class="stat-number">{{ $admins ?? 0 }}</div>
            <div class="stat-label">Admins</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
            <div class="stat-number">{{ $staff ?? 0 }}</div>
            <div class="stat-label">Staff</div>
        </div>
        <div class="stat-card gray">
            <div class="stat-icon"><i class="fas fa-user"></i></div>
            <div class="stat-number">{{ $regular ?? 0 }}</div>
            <div class="stat-label">Regular</div>
        </div>
    </div>

    <!-- Search -->
    <div class="search-box">
        <div class="search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" id="search" class="search-input" placeholder="Search by name, email or phone...">
        </div>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 25%;">User</th>
                    <th style="width: 20%;">Email</th>
                    <th style="width: 15%;">Phone</th>
                    <th style="width: 12%;">Role</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 13%; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($users ?? [] as $user)
                <tr>
                    <td>
                        <span style="color: var(--text-secondary); font-weight: 500;">#{{ $user->id }}</span>
                    </td>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="user-name">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color: var(--text-secondary);">{{ $user->email }}</td>
                    <td style="color: var(--text-secondary);">{{ $user->phone ?? '-' }}</td>
                    <td>
                        <span class="role-badge {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td>
                        <span class="status-badge {{ $user->status }}">
                            <span class="status-dot"></span>
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="action-btn edit" title="Edit User">
                                <i class="fas fa-edit"></i>
                                <span class="tooltip-text">Edit</span>
                            </a>
                            <button onclick="toggleStatus({{ $user->id }}, '{{ $user->status }}')" class="action-btn toggle" title="Toggle Status">
                                <i class="fas {{ $user->status == 'active' ? 'fa-pause' : 'fa-play' }}"></i>
                                <span class="tooltip-text">{{ $user->status == 'active' ? 'Deactivate' : 'Activate' }}</span>
                            </button>
                            <button onclick="deleteUser({{ $user->id }})" class="action-btn delete" title="Delete User">
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
                            <i class="fas fa-users"></i>
                            <h3>No Users Found</h3>
                            <p>Get started by creating your first user account.</p>
                            <a href="{{ route('admin.users.create') }}" class="btn-primary-gradient" style="display: inline-flex;">
                                <i class="fas fa-plus"></i> Add New User
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
    // DELETE USER
    // ============================================
    function deleteUser(id) {
        if(confirm('⚠️ Are you sure you want to delete this user?\n\nThis action cannot be undone!')) {
            fetch('/admin/users/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showNotification('✅ User deleted successfully!');
                    setTimeout(() => location.reload(), 800);
                } else {
                    alert('❌ Error deleting user');
                }
            })
            .catch(() => alert('❌ Network error. Please try again.'));
        }
    }

    // ============================================
    // TOGGLE STATUS
    // ============================================
    function toggleStatus(id, currentStatus) {
        let newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        let action = newStatus === 'active' ? 'activate' : 'deactivate';
        
        if(confirm(`Are you sure you want to ${action} this user?`)) {
            fetch('/admin/users/' + id + '/status/' + newStatus, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showNotification(`✅ User ${action}d successfully!`);
                    setTimeout(() => location.reload(), 800);
                } else {
                    alert('❌ Error updating status');
                }
            })
            .catch(() => alert('❌ Network error. Please try again.'));
        }
    }

    // ============================================
    // SEARCH
    // ============================================
    document.getElementById('search').addEventListener('keyup', function() {
        let v = this.value.toLowerCase();
        document.querySelectorAll('#tableBody tr').forEach(row => {
            if(row.querySelector('td')) {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(v) ? '' : 'none';
            }
        });
    });

    // ============================================
    // NOTIFICATION
    // ============================================
    function showNotification(message) {
        const div = document.createElement('div');
        div.style.cssText = `
            position: fixed;
            top: 80px;
            right: 24px;
            padding: 14px 24px;
            background: #1e293b;
            color: white;
            border-radius: 12px;
            z-index: 9999;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            animation: slideIn 0.3s ease;
            font-weight: 500;
            font-size: 14px;
            border-left: 4px solid #10b981;
        `;
        div.textContent = message;
        document.body.appendChild(div);
        setTimeout(() => {
            div.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => div.remove(), 300);
        }, 3000);
    }

    // ============================================
    // ANIMATION STYLES
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