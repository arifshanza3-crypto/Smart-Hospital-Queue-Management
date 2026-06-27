<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fb;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0b2e33 0%, #1a4a50 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h3 {
            font-size: 20px;
            margin-top: 10px;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-item {
            padding: 12px 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .menu-item:hover, .menu-item.active {
            background: rgba(0, 212, 255, 0.2);
            color: #00d4ff;
        }
        
        .menu-item i {
            width: 20px;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 20px;
        }
        
        .top-bar {
            background: white;
            padding: 15px 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .page-title h1 {
            font-size: 24px;
            color: #0b2e33;
        }
        
        .btn-add {
            background: linear-gradient(135deg, #00d4ff, #0b2e33);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Stats Cards */
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
        }
        
        .stat-card i {
            font-size: 30px;
            margin-bottom: 10px;
        }
        
        .stat-card .number {
            font-size: 28px;
            font-weight: bold;
        }
        
        .stat-card .label {
            color: #666;
            font-size: 12px;
        }
        
        /* Search */
        .search-box {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .search-input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #e1e8ed;
            border-radius: 10px;
            font-size: 14px;
        }
        
        /* Table */
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
            padding: 15px;
            text-align: left;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .role-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .role-admin { background: #e74c3c; color: white; }
        .role-staff { background: #3498db; color: white; }
        .role-user { background: #95a5a6; color: white; }
        
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-active { background: #d4edda; color: #155724; }
        .status-inactive { background: #f8d7da; color: #721c24; }
        
        .btn-action {
            padding: 5px 10px;
            margin: 0 3px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 12px;
            cursor: pointer;
        }
        
        .btn-edit { background: #00d4ff; color: white; }
        .btn-delete { background: #e74c3c; color: white; }
        .btn-status { background: #3498db; color: white; }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-hospital-user" style="font-size: 40px; color: #00d4ff;"></i>
                <h3>Smart Queue Admin</h3>
            </div>
            <div class="sidebar-menu">
                <a href="#" class="menu-item"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="#" class="menu-item"><i class="fas fa-user-md"></i> Doctors Management</a>
                <a href="#" class="menu-item active"><i class="fas fa-users"></i> User Management</a>
                <a href="#" class="menu-item"><i class="fas fa-chart-line"></i> Queue Reports</a>
                <a href="#" class="menu-item"><i class="fas fa-cog"></i> Settings</a>
                <a href="#" class="menu-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="top-bar">
                <div class="page-title">
                    <h1><i class="fas fa-users"></i> User Management</h1>
                    <p>Manage system users and their roles</p>
                </div>
                <a href="{{ route('admin.users.create') }}" class="btn-add">
                    <i class="fas fa-plus"></i> Add New User
                </a>
            </div>
            
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
                <div class="stat-card"><i class="fas fa-users"></i><div class="number">{{ $total }}</div><div class="label">Total Users</div></div>
                <div class="stat-card"><i class="fas fa-check-circle" style="color: #28a745;"></i><div class="number">{{ $active }}</div><div class="label">Active</div></div>
                <div class="stat-card"><i class="fas fa-times-circle" style="color: #dc3545;"></i><div class="number">{{ $inactive }}</div><div class="label">Inactive</div></div>
                <div class="stat-card"><i class="fas fa-crown" style="color: #e74c3c;"></i><div class="number">{{ $admins }}</div><div class="label">Admins</div></div>
                <div class="stat-card"><i class="fas fa-user-tie" style="color: #3498db;"></i><div class="number">{{ $staff }}</div><div class="label">Staff</div></div>
                <div class="stat-card"><i class="fas fa-user" style="color: #95a5a6;"></i><div class="number">{{ $regular }}</div><div class="label">Regular</div></div>
            </div>
            
            <!-- Search -->
            <div class="search-box">
                <input type="text" id="search" class="search-input" placeholder="Search by name, email or phone...">
            </div>
            
            <!-- Users Table -->
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
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <img src="{{ $user->profile_image_url }}" class="user-avatar">
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td><span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                            <td><span class="status-badge status-{{ $user->status }}">{{ ucfirst($user->status) }}</span></td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button onclick="deleteUser({{ $user->id }})" class="btn-action btn-delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                                <button onclick="toggleStatus({{ $user->id }}, '{{ $user->status }}')" class="btn-action btn-status">
                                    <i class="fas {{ $user->status == 'active' ? 'fa-pause' : 'fa-play' }}"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px;">
                                <i class="fas fa-users" style="font-size: 48px; color: #ccc;"></i>
                                <p>No users found</p>
                                <a href="{{ route('admin.users.create') }}" class="btn-add">Add First User</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        function deleteUser(id) {
            if(confirm('Are you sure you want to delete this user?')) {
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
                        location.reload();
                    } else {
                        alert('Error deleting user');
                    }
                });
            }
        }
        
        function toggleStatus(id, currentStatus) {
            let newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            fetch(`/admin/users/${id}/status/${newStatus}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                }
            });
        }
        
        document.getElementById('search').addEventListener('keyup', function() {
            let value = this.value.toLowerCase();
            let rows = document.querySelectorAll('#tableBody tr');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(value) ? '' : 'none';
            });
        });
    </script>
</body>
</html>