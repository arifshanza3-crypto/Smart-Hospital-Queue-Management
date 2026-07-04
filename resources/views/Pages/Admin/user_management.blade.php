@extends('Layout.admin-layout')

@section('page-title', 'User Management')
@section('breadcrumb', 'Manage Users')

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .stat-card .number {
        font-size: 28px;
        font-weight: 700;
        color: #0b2e33;
    }
    .stat-card .label {
        color: #666;
        font-size: 12px;
        margin-top: 4px;
    }
    .stat-card i {
        font-size: 30px;
        margin-bottom: 8px;
        display: block;
    }

    .table-wrapper {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th {
        background: #0b2e33;
        color: white;
        padding: 12px 15px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
    }
    td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        font-size: 14px;
    }
    tr:hover {
        background: #f8f9fa;
    }

    .role-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    .role-admin {
        background: #e74c3c;
        color: white;
    }
    .role-staff {
        background: #3498db;
        color: white;
    }
    .role-user {
        background: #95a5a6;
        color: white;
    }

    .status-active {
        background: #d4edda;
        color: #155724;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    .status-inactive {
        background: #f8d7da;
        color: #721c24;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .btn-action {
        padding: 5px 10px;
        border-radius: 5px;
        border: none;
        font-size: 12px;
        cursor: pointer;
        transition: 0.3s;
        display: inline-block;
        text-decoration: none;
    }
    .btn-edit {
        background: #00d4ff;
        color: white;
    }
    .btn-edit:hover {
        background: #0b2e33;
    }
    .btn-delete {
        background: #dc3545;
        color: white;
    }
    .btn-delete:hover {
        background: #c82333;
    }
    .btn-status {
        background: #17a2b8;
        color: white;
    }
    .btn-status:hover {
        background: #138496;
    }

    .search-box {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .search-input {
        flex: 1;
        min-width: 250px;
        padding: 12px 18px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        transition: 0.3s;
    }
    .search-input:focus {
        outline: none;
        border-color: #00d4ff;
        box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
    }

    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .btn-add {
        background: linear-gradient(135deg, #00d4ff, #0b2e33);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
    }
    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,212,255,0.3);
    }

    .alert {
        padding: 12px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        border-left: 4px solid;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left-color: #28a745;
    }
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border-left-color: #dc3545;
    }

    .empty-state {
        text-align: center;
        padding: 50px;
        color: #999;
    }
    .empty-state i {
        font-size: 48px;
        display: block;
        margin-bottom: 15px;
        color: #ddd;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (max-width: 500px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="header-actions">
    <div>
        <h2 style="color:#0b2e33; margin:0;"><i class="fas fa-users" style="color:#00d4ff;"></i> User Management</h2>
        <p style="color:#666; margin:5px 0 0;">Manage system users and their roles</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-add">
        <i class="fas fa-plus"></i> Add New User
    </a>
</div>

<!-- Alerts -->
@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <i class="fas fa-users" style="color:#667eea;"></i>
        <div class="number">{{ $total ?? 0 }}</div>
        <div class="label">Total Users</div>
    </div>
    <div class="stat-card">
        <i class="fas fa-check-circle" style="color:#28a745;"></i>
        <div class="number">{{ $active ?? 0 }}</div>
        <div class="label">Active</div>
    </div>
    <div class="stat-card">
        <i class="fas fa-times-circle" style="color:#dc3545;"></i>
        <div class="number">{{ $inactive ?? 0 }}</div>
        <div class="label">Inactive</div>
    </div>
    <div class="stat-card">
        <i class="fas fa-crown" style="color:#e74c3c;"></i>
        <div class="number">{{ $admins ?? 0 }}</div>
        <div class="label">Admins</div>
    </div>
    <div class="stat-card">
        <i class="fas fa-user-tie" style="color:#3498db;"></i>
        <div class="number">{{ $staff ?? 0 }}</div>
        <div class="label">Staff</div>
    </div>
    <div class="stat-card">
        <i class="fas fa-user" style="color:#95a5a6;"></i>
        <div class="number">{{ $regular ?? 0 }}</div>
        <div class="label">Regular</div>
    </div>
</div>

<!-- Search -->
<div class="search-box">
    <input type="text" id="search" class="search-input" placeholder="Search by name, email or phone...">
</div>

<!-- Table -->
<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse($users ?? [] as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:35px; height:35px; border-radius:50%; background:linear-gradient(135deg, #00d4ff, #0b2e33); color:white; display:flex; align-items:center; justify-content:center; font-weight:600; font-size:14px;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <strong>{{ $user->name }}</strong>
                    </div>
                </td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone ?? '-' }}</td>
                <td><span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                <td><span class="status-{{ $user->status }}">{{ ucfirst($user->status) }}</span></td>
                <td>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action btn-edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button onclick="deleteUser({{ $user->id }})" class="btn-action btn-delete">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button onclick="toggleStatus({{ $user->id }}, '{{ $user->status }}')" class="btn-action btn-status">
                        <i class="fas {{ $user->status == 'active' ? 'fa-pause' : 'fa-play' }}"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <i class="fas fa-users"></i>
                        <h3>No Users Found</h3>
                        <p>Click "Add New User" to create your first user.</p>
                        <a href="{{ route('admin.users.create') }}" class="btn-add" style="display:inline-flex; margin-top:10px;">
                            <i class="fas fa-plus"></i> Add New User
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function deleteUser(id) {
    if(confirm('Are you sure you want to delete this user?')) {
        fetch('/admin/users/' + id, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(d => { if(d.success) location.reload(); });
    }
}

function toggleStatus(id, currentStatus) {
    let newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    fetch('/admin/users/' + id + '/status/' + newStatus, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(d => { if(d.success) location.reload(); });
}

document.getElementById('search').addEventListener('keyup', function() {
    let v = this.value.toLowerCase();
    document.querySelectorAll('#tableBody tr').forEach(row => {
        if(row.querySelector('td')) {
            row.style.display = row.textContent.toLowerCase().includes(v) ? '' : 'none';
        }
    });
});
</script>
@endsection