@extends('Layout.admin-layout')

@section('page-title', 'Queue Reports')
@section('breadcrumb', 'Analytics & Reports')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* ============================================
       QUEUE REPORTS - LIGHT THEME
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

    .reports-wrapper {
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

    .btn-export {
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 14px;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.25);
    }

    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(16, 185, 129, 0.35);
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

    .stat-card.purple::before { background: var(--accent-gradient); }
    .stat-card.purple .stat-icon { color: #6366f1; }

    .stat-card.green::before { background: linear-gradient(135deg, #10b981, #34d399); }
    .stat-card.green .stat-icon { color: #10b981; }

    .stat-card.blue::before { background: linear-gradient(135deg, #3b82f6, #0ea5e9); }
    .stat-card.blue .stat-icon { color: #3b82f6; }

    .stat-card.cyan::before { background: linear-gradient(135deg, #0ea5e9, #06b6d4); }
    .stat-card.cyan .stat-icon { color: #0ea5e9; }

    /* ===== STATUS SUMMARY ===== */
    .status-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .status-item {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px 20px;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: var(--shadow);
    }

    .status-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
    }

    .status-item .status-number {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-primary);
    }

    .status-item .status-label {
        font-size: 13px;
        color: var(--text-secondary);
        margin-top: 2px;
    }

    .status-item .status-icon {
        font-size: 20px;
        display: block;
        margin-bottom: 4px;
    }

    .status-item.waiting { border-left: 4px solid #f59e0b; }
    .status-item.in-progress { border-left: 4px solid #0ea5e9; }
    .status-item.completed { border-left: 4px solid #10b981; }
    .status-item.cancelled { border-left: 4px solid #ef4444; }

    /* ===== CHARTS ===== */
    .charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 28px;
    }

    .chart-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
    }

    .chart-card:hover {
        box-shadow: var(--shadow-hover);
    }

    .chart-card .chart-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-card .chart-title i {
        color: var(--accent-1);
    }

    .chart-card canvas {
        max-height: 280px;
        width: 100% !important;
    }

    /* ===== FILTERS ===== */
    .filters-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 20px;
        box-shadow: var(--shadow);
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .filter-group label {
        display: block;
        font-weight: 600;
        font-size: 13px;
        color: var(--text-secondary);
        margin-bottom: 6px;
    }

    .filter-group label i {
        color: var(--accent-1);
        margin-right: 6px;
    }

    .filter-control {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-size: 14px;
        transition: all 0.3s ease;
        outline: none;
    }

    .filter-control:focus {
        border-color: var(--accent-1);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
        background: white;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        margin-top: 16px;
        flex-wrap: wrap;
    }

    .btn-filter {
        background: var(--accent-gradient);
        color: white;
        padding: 10px 25px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
    }

    .btn-reset {
        background: #e2e8f0;
        color: var(--text-primary);
        padding: 10px 25px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-reset:hover {
        background: #cbd5e1;
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

    .token-badge {
        font-weight: 700;
        color: var(--accent-1);
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

    .status-badge.waiting {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .status-badge.in-progress {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    .status-badge.completed {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .status-badge.cancelled {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    /* ===== PAGINATION ===== */
    .pagination-wrapper {
        margin-top: 20px;
    }

    .pagination-wrapper .pagination {
        justify-content: center;
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
        margin-bottom: 0;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1200px) {
        .stats-grid, .status-summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 992px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header-left h1 {
            font-size: 22px;
        }
    }

    @media (max-width: 768px) {
        .reports-wrapper {
            padding: 16px;
        }

        .stats-grid, .status-summary-grid {
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

        .filters-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 576px) {
        .reports-wrapper {
            padding: 12px;
        }

        .stats-grid, .status-summary-grid {
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

        .btn-export {
            padding: 10px 20px;
            font-size: 13px;
        }

        .filters-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="reports-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                <i class="fas fa-chart-line"></i> Queue Reports
            </h1>
            <p><i class="fas fa-arrow-trend-up" style="color: var(--accent-1);"></i> Monitor and analyze queue performance metrics</p>
        </div>
        <button onclick="exportReport()" class="btn-export">
            <i class="fas fa-download"></i> Export Report
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-number">{{ $totalPatients ?? 0 }}</div>
            <div class="stat-label">Total Patients</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number">{{ $completedToday ?? 0 }}</div>
            <div class="stat-label">Completed Today</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-number">{{ round($avgWaitingTime ?? 0) }} min</div>
            <div class="stat-label">Average Waiting Time</div>
        </div>
        <div class="stat-card cyan">
            <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
            <div class="stat-number">{{ round($avgServiceTime ?? 0) }} min</div>
            <div class="stat-label">Average Service Time</div>
        </div>
    </div>

    <!-- Status Summary -->
    <div class="status-summary-grid">
        <div class="status-item waiting">
            <span class="status-icon">⏳</span>
            <div class="status-number">{{ $statusStats['waiting'] ?? 0 }}</div>
            <div class="status-label">Waiting</div>
        </div>
        <div class="status-item in-progress">
            <span class="status-icon">🔄</span>
            <div class="status-number">{{ $statusStats['in_progress'] ?? 0 }}</div>
            <div class="status-label">In Progress</div>
        </div>
        <div class="status-item completed">
            <span class="status-icon">✅</span>
            <div class="status-number">{{ $statusStats['completed'] ?? 0 }}</div>
            <div class="status-label">Completed</div>
        </div>
        <div class="status-item cancelled">
            <span class="status-icon">❌</span>
            <div class="status-number">{{ $statusStats['cancelled'] ?? 0 }}</div>
            <div class="status-label">Cancelled</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="charts-grid">
        <!-- Department Distribution Chart -->
        <div class="chart-card">
            <h4 class="chart-title">
                <i class="fas fa-chart-pie"></i> Department Distribution
            </h4>
            <canvas id="departmentChart"></canvas>
        </div>
        
        <!-- Daily Trend Chart -->
        <div class="chart-card">
            <h4 class="chart-title">
                <i class="fas fa-chart-line"></i> Last 7 Days Trend
            </h4>
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <form method="GET" action="{{ route('admin.queue-reports.index') }}" id="filterForm">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="from_date"><i class="fas fa-calendar"></i> From Date</label>
                    <input type="date" name="from_date" class="filter-control" 
                           value="{{ request('from_date') }}" id="from_date">
                </div>
                <div class="filter-group">
                    <label for="to_date"><i class="fas fa-calendar"></i> To Date</label>
                    <input type="date" name="to_date" class="filter-control" 
                           value="{{ request('to_date') }}" id="to_date">
                </div>
                <div class="filter-group">
                    <label for="department"><i class="fas fa-building"></i> Department</label>
                    <select name="department" class="filter-control" id="department">
                        <option value="">All Departments</option>
                        @foreach($departments ?? [] as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="status"><i class="fas fa-tag"></i> Status</label>
                    <select name="status" class="filter-control" id="status">
                        <option value="">All Status</option>
                        <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>⏳ Waiting</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>🔄 In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
                <a href="{{ route('admin.queue-reports.index') }}" class="btn-reset">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Token #</th>
                    <th>Patient Name</th>
                    <th>Doctor</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Waiting Time</th>
                    <th>Service Time</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports ?? [] as $report)
                <tr>
                    <td><span class="token-badge">#{{ $report->token_number ?? $report->id }}</span></td>
                    <td>{{ $report->patient_name ?? 'N/A' }}</td>
                    <td>{{ $report->doctor_name ?? 'N/A' }}</td>
                    <td>{{ $report->department ?? 'General' }}</td>
                    <td>
                        <span class="status-badge {{ str_replace('_', '-', $report->status ?? 'waiting') }}">
                            @if(($report->status ?? 'waiting') == 'waiting') ⏳ Waiting
                            @elseif(($report->status ?? 'waiting') == 'in_progress') 🔄 In Progress
                            @elseif(($report->status ?? 'waiting') == 'completed') ✅ Completed
                            @else ❌ Cancelled @endif
                        </span>
                    </td>
                    <td>{{ $report->waiting_time ?? 0 }} min</td>
                    <td>{{ $report->service_time ?? 0 }} min</td>
                    <td>{{ isset($report->date) ? \Carbon\Carbon::parse($report->date)->format('d M Y') : 'N/A' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-chart-line"></i>
                            <h3>No Reports Found</h3>
                            <p>No queue data available for the selected filters.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $reports->links() ?? '' }}
    </div>
</div>

<script>
// ============================================
// EXPORT REPORT
// ============================================
function exportReport() {
    let form = document.getElementById('filterForm');
    let action = '{{ route("admin.queue-reports.export") }}';
    let params = new URLSearchParams(new FormData(form)).toString();
    window.location.href = action + '?' + params;
}

// ============================================
// DEPARTMENT DISTRIBUTION CHART
// ============================================
const deptCtx = document.getElementById('departmentChart');
const deptData = @json($departmentStats ?? []);

if (deptData.length > 0) {
    new Chart(deptCtx, {
        type: 'pie',
        data: {
            labels: deptData.map(item => item.department),
            datasets: [{
                data: deptData.map(item => item.total),
                backgroundColor: ['#6366f1', '#3b82f6', '#10b981', '#ef4444', '#f59e0b', '#0ea5e9', '#8b5cf6', '#ec4899'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            }
        }
    });
} else {
    deptCtx.parentElement.innerHTML = '<p style="text-align:center;color:#94a3b8;padding:40px 0;">No department data available</p>';
}

// ============================================
// DAILY TREND CHART
// ============================================
const trendCtx = document.getElementById('trendChart');
const trendData = @json($dailyStats ?? []);

if (trendData.length > 0) {
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendData.map(item => item.date),
            datasets: [{
                label: 'Patients',
                data: trendData.map(item => item.total),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { 
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
} else {
    trendCtx.parentElement.innerHTML = '<p style="text-align:center;color:#94a3b8;padding:40px 0;">No trend data available</p>';
}
</script>
@endsection