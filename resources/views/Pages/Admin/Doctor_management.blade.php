@extends('Layout.admin-layout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    .pending-section {
        background: #16213e;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    .badge-pending {
        background: #ffc107;
        color: #000;
        padding: 5px 10px;
        border-radius: 5px;
    }
    .btn-approve {
        background: #28a745;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 5px;
        cursor: pointer;
    }
    .btn-reject {
        background: #dc3545;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 5px;
        cursor: pointer;
    }
</style>

<div style="padding: 20px;">

    <!-- ✅ Pending Staff Approvals Section -->
    @if(isset($pendingStaff) && $pendingStaff->count() > 0)
    <div class="pending-section">
        <h3 style="color: #ff6b6b;">
            <i class="fas fa-users"></i> Pending Staff Approvals
            <span style="background: red; color: white; padding: 5px 10px; border-radius: 50%; margin-left: 10px;">
                {{ $pendingStaff->count() }}
            </span>
        </h3>
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="background: #0f3460; color: white; text-align: left;">
                    <th style="padding: 12px;">Employee Name</th>
                    <th style="padding: 12px;">Email</th>
                    <th style="padding: 12px;">Employee ID</th>
                    <th style="padding: 12px;">Status</th>
                    <th style="padding: 12px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingStaff as $staff)
                <tr style="border-bottom: 1px solid #333;">
                    <td style="padding: 12px;">{{ $staff->full_name }}</td>
                    <td style="padding: 12px;">{{ $staff->email }}</td>
                    <td style="padding: 12px;">{{ $staff->employee_id ?? 'N/A' }}</td>
                    <td style="padding: 12px;">
                        <span class="badge-pending">Pending</span>
                    </td>
                    <td style="padding: 12px;">
                        <form method="POST" action="{{ route('admin.approve-staff', $staff->id) }}" style="display:inline">
                            @csrf
                            <button type="submit" class="btn-approve">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.reject-staff', $staff->id) }}" style="display:inline">
                            @csrf
                            <button type="submit" class="btn-reject">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="pending-section">
        <h3 style="color: #28a745;">
            <i class="fas fa-check-circle"></i> No Pending Staff Approvals
        </h3>
    </div>
    @endif

    <!-- Doctor Management Section -->
    <h1 style="color: #00d4ff;">Doctor Management</h1>
    <p>Manage hospital doctors</p>

    @if(isset($doctors) && $doctors->count() > 0)
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="background: #0f3460; color: white;">
                    <th style="padding: 10px;">ID</th>
                    <th style="padding: 10px;">Name</th>
                    <th style="padding: 10px;">Specialization</th>
                    <th style="padding: 10px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($doctors as $doctor)
                <tr style="border-bottom: 1px solid #333;">
                    <td style="padding: 10px;">#{{ $doctor->id }}</td>
                    <td style="padding: 10px;">Dr. {{ $doctor->name }}</td>
                    <td style="padding: 10px;">{{ $doctor->specialization }}</td>
                    <td style="padding: 10px;">
                        <span class="badge" style="background: #28a745; color: white; padding: 5px 10px; border-radius: 5px;">
                            {{ ucfirst($doctor->status ?? 'Active') }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No doctors found.</p>
    @endif

</div>
@endsection