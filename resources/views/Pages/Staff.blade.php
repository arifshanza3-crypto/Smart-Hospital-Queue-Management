<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Smart Queue</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            min-height: 100vh;
        }

        /* ===== HEADER ===== */
        .staff-header {
            background: linear-gradient(135deg, #0b2e33, #1a4a50);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 20px rgba(0,0,0,0.2);
        }
        .staff-header .logo h2 { color: #00d4ff; font-size: 22px; }
        .staff-header .logo h2 i { margin-right: 10px; }
        .staff-header .logo span { color: #a0d4d9; font-size: 12px; }
        .staff-header .nav-links { display: flex; align-items: center; gap: 25px; }
        .staff-header .nav-links a { color: #a0d4d9; text-decoration: none; font-size: 14px; padding: 8px 16px; border-radius: 8px; transition: 0.3s; }
        .staff-header .nav-links a:hover { background: rgba(0,212,255,0.1); color: #00d4ff; }
        .staff-header .nav-links .logout { color: #ff6b6b; }
        .staff-header .nav-links .logout:hover { background: rgba(255,107,107,0.1); }

        /* ===== MAIN CONTENT ===== */
        .staff-main { padding: 30px; max-width: 1400px; margin: 0 auto; }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 2px 16px rgba(0,0,0,0.06);
            transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 30px rgba(0,0,0,0.1); }
        .stat-card .icon { font-size: 35px; margin-bottom: 10px; display: block; }
        .stat-card .number { font-size: 32px; font-weight: 700; color: #0b2e33; }
        .stat-card .label { color: #666; font-size: 13px; }
        .stat-card .sub { font-size: 11px; color: #999; margin-top: 4px; }

        /* ===== ADD PATIENT BUTTON ===== */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .action-bar .title h2 { color: #0b2e33; }
        .action-bar .title p { color: #666; font-size: 14px; }
        .btn-primary {
            background: linear-gradient(135deg, #00d4ff, #0b2e33);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 20px rgba(0,212,255,0.3); }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-warning { background: #ffc107; color: #0b2e33; }
        .btn-warning:hover { background: #e0a800; }

        /* ===== TABLE ===== */
        .table-wrapper {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(0,0,0,0.06);
        }
        table { width: 100%; border-collapse: collapse; }
        th {
            background: #0b2e33;
            color: white;
            padding: 15px 20px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
        }
        td {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }
        tr:hover { background: #f8f9fa; }
        .status-badge {
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        .status-waiting { background: #fff3cd; color: #856404; }
        .status-serving { background: #d1ecf1; color: #0c5460; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .type-badge {
            padding: 2px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        .type-online { background: #d1ecf1; color: #0c5460; }
        .type-physical { background: #e8daef; color: #6c3483; }
        .btn-sm { padding: 5px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; transition: 0.3s; }
        .btn-sm:hover { transform: translateY(-1px); }

        /* ===== MODAL ===== */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 999;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.show { display: flex; }
        .modal {
            background: white;
            border-radius: 20px;
            padding: 35px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .modal h2 { color: #0b2e33; margin-bottom: 5px; }
        .modal p { color: #666; margin-bottom: 20px; }
        .modal .form-group { margin-bottom: 18px; }
        .modal .form-group label { display: block; font-weight: 600; color: #0b2e33; margin-bottom: 5px; font-size: 14px; }
        .modal .form-group label i { color: #00d4ff; margin-right: 8px; }
        .modal .form-control {
            width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 10px;
            font-size: 14px; transition: 0.3s;
        }
        .modal .form-control:focus { outline: none; border-color: #00d4ff; }
        .modal .btn-row { display: flex; gap: 12px; margin-top: 20px; }
        .modal .btn-row .btn { flex: 1; padding: 12px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; }
        .modal .btn-row .btn-cancel { background: #6c757d; color: white; }
        .modal .btn-row .btn-cancel:hover { background: #5a6268; }
        .modal .btn-row .btn-submit { background: linear-gradient(135deg, #00d4ff, #0b2e33); color: white; }
        .modal .btn-row .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 5px 20px rgba(0,212,255,0.3); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .staff-header { flex-direction: column; gap: 10px; padding: 15px; }
            .staff-header .nav-links { flex-wrap: wrap; justify-content: center; }
            .action-bar { flex-direction: column; align-items: stretch; }
            .modal { padding: 25px; }
            table { font-size: 12px; }
            th, td { padding: 10px 12px; }
        }
        @media (max-width: 500px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- ===== HEADER ===== -->
<header class="staff-header">
    <div class="logo">
        <h2><i class="fas fa-hospital-user"></i>Smart Queue</h2>
        <span>Staff Portal</span>
    </div>
    <nav class="nav-links">
        <a href="/Staff" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="#" onclick="openModal()"><i class="fas fa-user-plus"></i> Add Patient</a>
        <a href="/queue-reports"><i class="fas fa-chart-line"></i> Reports</a>
        <a href="/logout" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
</header>

<!-- ===== MAIN ===== -->
<main class="staff-main">

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <span class="icon">👥</span>
            <div class="number">{{ $totalQueue ?? 0 }}</div>
            <div class="label">Total in Queue</div>
        </div>
        <div class="stat-card">
            <span class="icon">🔄</span>
            <div class="number">{{ $nowServing ?? 0 }}</div>
            <div class="label">Now Serving</div>
            <div class="sub">Token #{{ $nowServingToken ?? 'N/A' }}</div>
        </div>
        <div class="stat-card">
            <span class="icon">⏳</span>
            <div class="number">{{ $avgWaitTime ?? 0 }}m</div>
            <div class="label">Avg Wait Time</div>
        </div>
        <div class="stat-card">
            <span class="icon">✅</span>
            <div class="number">{{ $completedToday ?? 0 }}</div>
            <div class="label">Completed Today</div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="action-bar">
        <div class="title">
            <h2><i class="fas fa-list" style="color:#00d4ff;"></i> Queue List</h2>
            <p>Real-time patient queue management</p>
        </div>
        <button class="btn-primary" onclick="openModal()">
            <i class="fas fa-user-plus"></i> Add Physical Patient
        </button>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Token #</th>
                    <th>Patient Name</th>
                    <th>Type</th>
                    <th>Est. Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients ?? [] as $patient)
                <tr>
                    <td><strong>#{{ $patient->token_number }}</strong></td>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:35px;height:35px;border-radius:50%;background:linear-gradient(135deg,#00d4ff,#0b2e33);color:white;display:flex;align-items:center;justify-content:center;font-weight:600;">
                                {{ strtoupper(substr($patient->patient_name, 0, 1)) }}
                            </div>
                            <div>
                                <strong>{{ $patient->patient_name }}</strong>
                                <div style="font-size:11px;color:#999;">{{ $patient->department ?? 'General' }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="type-badge type-{{ $patient->type ?? 'physical' }}">{{ ucfirst($patient->type ?? 'Physical') }}</span></td>
                    <td>{{ $patient->waiting_time ?? 0 }} min</td>
                    <td><span class="status-badge status-{{ $patient->status }}">{{ ucfirst($patient->status ?? 'waiting') }}</span></td>
                    <td>
                        <div style="display:flex; gap:6px; flex-wrap:wrap;">
                            <button onclick="servePatient({{ $patient->id }})" class="btn-sm btn-success"><i class="fas fa-play"></i></button>
                            <button onclick="completePatient({{ $patient->id }})" class="btn-sm btn-primary"><i class="fas fa-check"></i></button>
                            <button onclick="cancelPatient({{ $patient->id }})" class="btn-sm btn-danger"><i class="fas fa-times"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:40px;color:#999;">
                        <i class="fas fa-inbox" style="font-size:48px;display:block;margin-bottom:10px;"></i>
                        No patients in queue. <a href="#" onclick="openModal()" style="color:#00d4ff;">Add a patient</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</main>

<!-- ===== MODAL ===== -->
<div class="modal-overlay" id="patientModal">
    <div class="modal">
        <h2><i class="fas fa-user-plus" style="color:#00d4ff;"></i> Add Physical Patient</h2>
        <p>Register a walk-in patient to the queue</p>

        <form id="patientForm">
            @csrf
            <div class="form-group">
                <label><i class="fas fa-user"></i> Patient Name <span style="color:#dc3545;">*</span></label>
                <input type="text" name="patient_name" class="form-control" placeholder="Enter full name" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" class="form-control" placeholder="patient@email.com">
            </div>
            <div class="form-group">
                <label><i class="fas fa-phone"></i> Phone</label>
                <input type="text" name="phone" class="form-control" placeholder="+92 123 4567890">
            </div>
            <div class="form-group">
                <label><i class="fas fa-stethoscope"></i> Department <span style="color:#dc3545;">*</span></label>
                <select name="department" class="form-control" required>
                    <option value="General">General</option>
                    <option value="Cardiology">Cardiology</option>
                    <option value="Neurology">Neurology</option>
                    <option value="Pediatrics">Pediatrics</option>
                    <option value="Orthopedics">Orthopedics</option>
                    <option value="Dermatology">Dermatology</option>
                    <option value="Ophthalmology">Ophthalmology</option>
                </select>
            </div>
            <div class="form-group">
                <label><i class="fas fa-clock"></i> Priority</label>
                <select name="priority" class="form-control">
                    <option value="normal">Normal</option>
                    <option value="high">High Priority</option>
                    <option value="emergency">Emergency</option>
                </select>
            </div>

            <div class="btn-row">
                <button type="button" class="btn btn-cancel" onclick="closeModal()"><i class="fas fa-times"></i> Cancel</button>
                <button type="submit" class="btn btn-submit"><i class="fas fa-save"></i> Add Patient</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== SCRIPTS ===== -->
<script>
    // Modal Functions
    function openModal() {
        document.getElementById('patientModal').classList.add('show');
    }
    function closeModal() {
        document.getElementById('patientModal').classList.remove('show');
        document.getElementById('patientForm').reset();
    }
    // Close modal on outside click
    document.getElementById('patientModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    // Add Patient Form Submit
    document.getElementById('patientForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        
        fetch('/staff/add-patient', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Patient added successfully! Token: ' + data.token);
                closeModal();
                location.reload();
            } else {
                alert('❌ Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('❌ Error adding patient');
        });
    });

    // Patient Actions
    function servePatient(id) {
        if (confirm('Start serving this patient?')) {
            fetch('/staff/patient/' + id + '/serve', {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(r => r.json())
            .then(d => { if(d.success) location.reload(); });
        }
    }
    function completePatient(id) {
        if (confirm('Mark this patient as completed?')) {
            fetch('/staff/patient/' + id + '/complete', {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(r => r.json())
            .then(d => { if(d.success) location.reload(); });
        }
    }
    function cancelPatient(id) {
        if (confirm('Cancel this patient?')) {
            fetch('/staff/patient/' + id + '/cancel', {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(r => r.json())
            .then(d => { if(d.success) location.reload(); });
        }
    }
</script>

</body>
</html>
=======
@extends('Layout.staff_app')
@section('title', 'Staff Portal - Smart Queue Management')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/Staff.css') }}">

    <section class="hero-header" style="background-color: #0b2e33;">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <span class="badge-top">Staff Portal</span>
                    <h1>Smart Queue Management</h1>
                    <p>Real-time oversight of physical walk-ins and digital bookings. Optimize patient flow with a single click.</p>
                </div>
                <div class="hero-actions">
                    <button class="btn btn-primary" onclick="openModal('patientModal')">+ Add Physical Patient</button>
                    <button class="btn btn-secondary" onclick="openModal('timeModal')">⏱ Set Global Time</button>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-label">Total in Queue</span>
                    <h2 id="stat-total">0</h2>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Now Serving</span>
                    <h2 id="stat-serving">--</h2>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Total Pending Wait</span>
                    <h2 id="stat-avg-time">0m</h2>
                </div>
            </div>
        </div>
    </section>

    <main class="container">
        <div class="data-card">
            <table class="queue-table">
                <thead>
                    <tr>
                        <th>Token #</th>
                        <th>Patient Info</th>
                        <th>Type</th>
                        <th>Est. Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="queue-body"></tbody>
            </table>
        </div>
    </main>

    <div id="patientModal" class="modal">
        <div class="modal-content">
            <h3>Add New Patient</h3>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" id="p_name" placeholder="Enter name...">
            </div>
            <div class="form-group">
                <label>Age</label>
                <input type="number" id="p_age" placeholder="Enter age...">
            </div>
            <div class="modal-footer">
                <button class="btn btn-text" onclick="closeModal('patientModal')">Cancel</button>
                <button class="btn btn-primary" onclick="submitPatient()">Add to Queue</button>
            </div>
        </div>
    </div>

    <div id="timeModal" class="modal">
        <div class="modal-content">
            <h3>Set Global Est. Time</h3>
            <div class="form-group">
                <label>Minutes per patient</label>
                <input type="number" id="global_min" value="15">
            </div>
            <div class="modal-footer">
                <button class="btn btn-text" onclick="closeModal('timeModal')">Cancel</button>
                <button class="btn btn-primary" onclick="submitGlobalTime()">Update All</button>
            </div>
        </div>
    </div>

    <div id="detailModal" class="modal">
        <div class="modal-content">
            <h3>Queue Positioning</h3>
            <div id="detail-content" style="margin-top:20px; line-height: 1.8;">
                </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="closeModal('detailModal')">Got it</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/Staff.js') }}"></script>
@endsection
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
