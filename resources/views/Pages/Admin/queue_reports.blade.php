@extends('Layout.admin-layout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .stat-card {
        transition: transform 0.3s ease;
        cursor: pointer;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-waiting {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-in-progress {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .status-completed {
        background: #d4edda;
        color: #155724;
    }
    
    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }
    
    .filter-box {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }
    
    .filter-box:focus {
        outline: none;
        border-color: #00d4ff;
        box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
    }
    
    .table-wrapper {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    
    .btn-export {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40,167,69,0.3);
    }
    
    .date-range {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .date-range .separator {
        color: #666;
        font-weight: bold;
    }
</style>

<div style="padding: 20px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="color: #0b2e33; margin-bottom: 5px;">Queue Reports</h1>
            <p style="color: #666;">Monitor and analyze queue performance metrics</p>
        </div>
        <button onclick="exportReport()" class="btn-export">
            <i class="fas fa-download"></i> Export Report
        </button>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 15px; color: white;">
            <i class="fas fa-users" style="font-size: 35px; opacity: 0.9;"></i>
            <div style="font-size: 32px; font-weight: bold; margin-top: 10px;">{{ $totalPatients }}</div>
            <div style="opacity: 0.9;">Total Patients</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 20px; border-radius: 15px; color: white;">
            <i class="fas fa-check-circle" style="font-size: 35px; opacity: 0.9;"></i>
            <div style="font-size: 32px; font-weight: bold; margin-top: 10px;">{{ $completedToday }}</div>
            <div style="opacity: 0.9;">Completed Today</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #00d4ff 0%, #0b2e33 100%); padding: 20px; border-radius: 15px; color: white;">
            <i class="fas fa-clock" style="font-size: 35px; opacity: 0.9;"></i>
            <div style="font-size: 32px; font-weight: bold; margin-top: 10px;">{{ round($avgWaitingTime) }} min</div>
            <div style="opacity: 0.9;">Avg Waiting Time</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #00c4ff 100%); padding: 20px; border-radius: 15px; color: white;">
            <i class="fas fa-chart-line" style="font-size: 35px; opacity: 0.9;"></i>
            <div style="font-size: 32px; font-weight: bold; margin-top: 10px;">{{ round($avgServiceTime) }} min</div>
            <div style="opacity: 0.9;">Avg Service Time</div>
        </div>
    </div>

    <!-- Charts Row -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
        <!-- Department Distribution Chart -->
        <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
            <h3 style="color: #0b2e33; margin-bottom: 15px;"><i class="fas fa-chart-pie"></i> Department Distribution</h3>
            <canvas id="departmentChart" style="max-height: 300px;"></canvas>
        </div>
        
        <!-- Daily Trend Chart -->
        <div style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
            <h3 style="color: #0b2e33; margin-bottom: 15px;"><i class="fas fa-chart-line"></i> Last 7 Days Trend</h3>
            <canvas id="trendChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Status Summary -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
        <div style="background: #fff3cd; padding: 15px; border-radius: 10px; text-align: center;">
            <div style="font-size: 24px; font-weight: bold;">{{ $statusStats['waiting'] ?? 0 }}</div>
            <div style="color: #856404;">⏳ Waiting</div>
        </div>
        <div style="background: #d1ecf1; padding: 15px; border-radius: 10px; text-align: center;">
            <div style="font-size: 24px; font-weight: bold;">{{ $statusStats['in_progress'] ?? 0 }}</div>
            <div style="color: #0c5460;">🔄 In Progress</div>
        </div>
        <div style="background: #d4edda; padding: 15px; border-radius: 10px; text-align: center;">
            <div style="font-size: 24px; font-weight: bold;">{{ $statusStats['completed'] ?? 0 }}</div>
            <div style="color: #155724;">✅ Completed</div>
        </div>
        <div style="background: #f8d7da; padding: 15px; border-radius: 10px; text-align: center;">
            <div style="font-size: 24px; font-weight: bold;">{{ $statusStats['cancelled'] ?? 0 }}</div>
            <div style="color: #721c24;">❌ Cancelled</div>
        </div>
    </div>

    <!-- Filters Form -->
    <div style="background: white; border-radius: 15px; padding: 20px; margin-bottom: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
        <form method="GET" action="{{ route('admin.queue-reports.index') }}" id="filterForm">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; color: #666; font-weight: 500;">
                        <i class="fas fa-calendar"></i> From Date
                    </label>
                    <input type="date" name="from_date" class="filter-box" 
                           value="{{ request('from_date') }}" style="width: 100%;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; color: #666; font-weight: 500;">
                        <i class="fas fa-calendar"></i> To Date
                    </label>
                    <input type="date" name="to_date" class="filter-box" 
                           value="{{ request('to_date') }}" style="width: 100%;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; color: #666; font-weight: 500;">
                        <i class="fas fa-building"></i> Department
                    </label>
                    <select name="department" class="filter-box" style="width: 100%;">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; color: #666; font-weight: 500;">
                        <i class="fas fa-tag"></i> Status
                    </label>
                    <select name="status" class="filter-box" style="width: 100%;">
                        <option value="">All Status</option>
                        <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>⏳ Waiting</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>🔄 In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                    </select>
                </div>
            </div>
            <div style="margin-top: 15px; display: flex; gap: 10px;">
                <button type="submit" style="background: #00d4ff; color: white; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer;">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
                <a href="{{ route('admin.queue-reports.index') }}" style="background: #6c757d; color: white; border: none; padding: 10px 25px; border-radius: 8px; text-decoration: none; display: inline-block;">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Reports Table -->
    <div class="table-wrapper">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #0b2e33; color: white;">
                    <th style="padding: 15px; text-align: left;">Token #</th>
                    <th style="padding: 15px; text-align: left;">Patient Name</th>
                    <th style="padding: 15px; text-align: left;">Doctor</th>
                    <th style="padding: 15px; text-align: left;">Department</th>
                    <th style="padding: 15px; text-align: left;">Status</th>
                    <th style="padding: 15px; text-align: left;">Waiting Time</th>
                    <th style="padding: 15px; text-align: left;">Service Time</th>
                    <th style="padding: 15px; text-align: left;">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px;"><strong>#{{ $report->token_number }}</strong></td>
                    <td style="padding: 15px;">{{ $report->patient_name }}</td>
                    <td style="padding: 15px;">Dr. {{ $report->doctor_name }}</td>
                    <td style="padding: 15px;">{{ $report->department }}</td>
                    <td style="padding: 15px;">
                        <span class="status-badge status-{{ str_replace('_', '-', $report->status) }}">
                            @if($report->status == 'waiting') ⏳ Waiting
                            @elseif($report->status == 'in_progress') 🔄 In Progress
                            @elseif($report->status == 'completed') ✅ Completed
                            @else ❌ Cancelled @endif
                        </span>
                    </td>
                    <td style="padding: 15px;">{{ $report->waiting_time }} min</td>
                    <td style="padding: 15px;">{{ $report->service_time }} min</td>
                    <td style="padding: 15px;">{{ \Carbon\Carbon::parse($report->date)->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding: 60px; text-align: center; color: #999;">
                        <i class="fas fa-chart-line" style="font-size: 48px;"></i>
                        <h3 style="margin-top: 15px;">No Reports Found</h3>
                        <p>No queue data available for the selected filters.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div style="margin-top: 20px;">
        {{ $reports->links() }}
    </div>
</div>

<script>
// Export report function
function exportReport() {
    let form = document.getElementById('filterForm');
    let action = '{{ route("admin.queue-reports.export") }}';
    let params = new URLSearchParams(new FormData(form)).toString();
    window.location.href = action + '?' + params;
}

// Department Distribution Chart
const deptCtx = document.getElementById('departmentChart').getContext('2d');
const deptData = @json($departmentStats);
if(deptData.length > 0) {
    new Chart(deptCtx, {
        type: 'pie',
        data: {
            labels: deptData.map(item => item.department),
            datasets: [{
                data: deptData.map(item => item.total),
                backgroundColor: ['#667eea', '#00d4ff', '#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6f42c1', '#fd7e14'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
}

// Daily Trend Chart
const trendCtx = document.getElementById('trendChart').getContext('2d');
const trendData = @json($dailyStats);
if(trendData.length > 0) {
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendData.map(item => item.date),
            datasets: [{
                label: 'Patients',
                data: trendData.map(item => item.total),
                borderColor: '#00d4ff',
                backgroundColor: 'rgba(0, 212, 255, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#00d4ff',
                pointBorderColor: '#fff',
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
}

// If no data, show message
if(trendData.length === 0) {
    trendCtx.fillStyle = '#f0f0f0';
    trendCtx.fillRect(0, 0, trendCtx.canvas.width, trendCtx.canvas.height);
    trendCtx.fillStyle = '#999';
    trendCtx.font = '14px Poppins';
    trendCtx.fillText('No data available', trendCtx.canvas.width/2 - 60, trendCtx.canvas.height/2);
}
</script>
@endsection