@extends('Layout.admin-layout')

@section('page-title', 'Dashboard')
@section('breadcrumb', 'Doctor Management')

@section('content')
<style>
    /* ===== STATS CARDS ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-card {
        background: white;
        padding: 25px 20px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        border-left: 4px solid #00d4ff;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    }
    .stat-card .icon {
        font-size: 32px;
        margin-bottom: 8px;
        display: block;
    }
    .stat-card .number {
        font-size: 32px;
        font-weight: 700;
        color: #0b2e33;
    }
    .stat-card .label {
        color: #666;
        font-size: 13px;
        margin-top: 4px;
    }
    .stat-card .sub {
        font-size: 11px;
        color: #999;
        margin-top: 2px;
    }
    .stat-card:nth-child(1) { border-left-color: #667eea; }
    .stat-card:nth-child(2) { border-left-color: #00d4ff; }
    .stat-card:nth-child(3) { border-left-color: #28a745; }
    .stat-card:nth-child(4) { border-left-color: #dc3545; }

    /* ===== ACTION BAR ===== */
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .action-bar .title h2 {
        color: #0b2e33;
        font-size: 20px;
        margin: 0;
    }
    .action-bar .title p {
        color: #666;
        font-size: 14px;
        margin: 0;
    }
    .btn-primary {
        background: linear-gradient(135deg, #00d4ff, #0b2e33);
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,212,255,0.3);
        color: white;
    }

    /* ===== SEARCH ===== */
    .search-box {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .search-box input {
        flex: 1;
        min-width: 250px;
        padding: 12px 18px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    .search-box input:focus {
        outline: none;
        border-color: #00d4ff;
        box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
    }
    .search-box select {
        padding: 12px 18px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        background: white;
        cursor: pointer;
        min-width: 150px;
    }
    .search-box select:focus {
        outline: none;
        border-color: #00d4ff;
    }

    /* ===== TABLE ===== */
    .table-wrapper {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th {
        background: #0b2e33;
        color: white;
        padding: 14px 20px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    td {
        padding: 14px 20px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
        vertical-align: middle;
    }
    tr:hover {
        background: #f8f9fa;
    }
    tr:last-child td {
        border-bottom: none;
    }

    /* ===== STATUS BADGES ===== */
    .status-badge {
        padding: 4px 14px;
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

    /* ===== ACTION BUTTONS ===== */
    .btn-action {
        padding: 5px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
    }
    .btn-action:hover {
        transform: translateY(-1px);
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

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }
    .empty-state i {
        font-size: 60px;
        color: #ddd;
        display: block;
        margin-bottom: 15px;
    }
    .empty-state h3 {
        color: #666;
        margin-bottom: 8px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .action-bar {
            flex-direction: column;
            align-items: stretch;
        }
        .search-box {
            flex-direction: column;
        }
        .search-box input,
        .search-box select {
            width: 100%;
        }
        th, td {
            padding: 10px 12px;
            font-size: 12px;
        }
    }
    @media (max-width: 500px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- ===== STATS ===== -->
@php
    $total = $doctors->count();
    $onDuty = $doctors->where('status', 'on_duty')->count();
    $active = $doctors->where('status', 'active')->count();
    $inactive = $doctors->where('status', 'inactive')->count();
@endphp

<div class="stats-grid">
    <div class="stat-card">
        <span class="icon">👨‍⚕️</span>
        <div class="number">{{ $total }}</div>
        <div class="label">Total Doctors</div>
    </div>
    <div class="stat-card">
        <span class="icon">🔄</span>
        <div class="number">{{ $onDuty }}</div>
        <div class="label">On Duty</div>
        <div class="sub">Currently working</div>
    </div>
    <div class="stat-card">
        <span class="icon">✅</span>
        <div class="number">{{ $active }}</div>
        <div class="label">Active</div>
        <div class="sub">Available for appointments</div>
    </div>
    <div class="stat-card">
        <span class="icon">⏸️</span>
        <div class="number">{{ $inactive }}</div>
        <div class="label">Inactive</div>
        <div class="sub">Not available</div>
    </div>
</div>

<!-- ===== SEARCH ===== -->
<div class="search-box">
    <input type="text" id="searchInput" placeholder="🔍 Search by name, specialization, email...">
    <select id="statusFilter">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="on_duty">On Duty</option>
        <option value="inactive">Inactive</option>
    </select>
</div>

<!-- ===== TABLE ===== -->
<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Doctor</th>
                <th>Specialization</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th style="text-align:center;">Actions</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse($doctors as $doctor)
            <tr>
                <td><strong>#{{ $doctor->id }}</strong></td>
                <td>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg, #00d4ff, #0b2e33); color:white; display:flex; align-items:center; justify-content:center; font-weight:600; font-size:16px; flex-shrink:0;">
                            {{ strtoupper(substr($doctor->name, 0, 2)) }}
                        </div>
                        <div>
                            <strong>Dr. {{ $doctor->name }}</strong>
                            @if($doctor->qualification)
                                <div style="font-size:11px; color:#999;">{{ $doctor->qualification }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    <span style="background:#f0f0f0; padding:4px 12px; border-radius:12px; font-size:12px;">
                        {{ $doctor->specialization }}
                    </span>
                </td>
                <td>{{ $doctor->email }}</td>
                <td>{{ $doctor->phone }}</td>
                <td>
                    <span class="status-badge status-{{ $doctor->status }}">
                        {{ ucfirst($doctor->status) }}
                    </span>
                </td>
                <td style="text-align:center;">
                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn-action btn-edit" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button onclick="deleteDoctor({{ $doctor->id }})" class="btn-action btn-delete" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <i class="fas fa-user-md"></i>
                        <h3>No Doctors Found</h3>
                        <p>Click the "Add New Doctor" button to add your first doctor.</p>
                        <a href="{{ route('admin.doctors.create') }}" class="btn-primary" style="display:inline-flex; margin-top:10px;">
                            <i class="fas fa-plus-circle"></i> Add New Doctor
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ===== SCRIPTS ===== -->
<script>
function deleteDoctor(id) {
    if(confirm('⚠️ Are you sure you want to delete this doctor?\n\nThis action cannot be undone!')) {
        fetch('/admin/doctors/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showNotification('✅ Doctor deleted successfully!');
                setTimeout(() => location.reload(), 800);
            } else {
                showNotification('❌ Error deleting doctor');
            }
        })
        .catch(() => showNotification('❌ Network error. Please try again.'));
    }
}

function showNotification(message) {
    const div = document.createElement('div');
    div.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        background: #0b2e33;
        color: white;
        border-radius: 10px;
        z-index: 9999;
        font-family: 'Poppins', sans-serif;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        animation: slideIn 0.3s ease;
    `;
    div.textContent = message;
    document.body.appendChild(div);
    setTimeout(() => div.remove(), 3000);
}

// Search and Filter
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('#tableBody tr');

    function filterTable() {
        const search = searchInput.value.toLowerCase();
        const status = statusFilter.value;

        rows.forEach(row => {
            if (!row.querySelector('td')) return;
            
            const text = row.textContent.toLowerCase();
            const rowStatus = row.querySelector('.status-badge')?.textContent?.trim().toLowerCase() || '';
            
            const matchSearch = text.includes(search);
            const matchStatus = !status || rowStatus === status;
            
            row.style.display = (matchSearch && matchStatus) ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', filterTable);
    statusFilter.addEventListener('change', filterTable);
});

// Add animation style
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
`;
document.head.appendChild(style);
</script>
@endsection