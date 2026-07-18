@extends('Layout.staff_app')
@section('title', 'Staff Portal - Smart Queue Management')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/Staff.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
        {{-- ✅ Department Tabs --}}
        <div class="department-tabs" style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
            <button class="dept-tab active" data-dept="all" onclick="switchDepartment('all')" style="padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; background: #00d4ff; color: #0b2e33; font-weight: bold;">All Departments</button>
            <button class="dept-tab" data-dept="OPD" onclick="switchDepartment('OPD')" style="padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; background: rgba(255,255,255,0.1); color: white;">🏥 OPD</button>
            <button class="dept-tab" data-dept="Pharmacy" onclick="switchDepartment('Pharmacy')" style="padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; background: rgba(255,255,255,0.1); color: white;">💊 Pharmacy</button>
            <button class="dept-tab" data-dept="Radiology" onclick="switchDepartment('Radiology')" style="padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; background: rgba(255,255,255,0.1); color: white;">🩻 Radiology</button>
        </div>

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

    <!-- ✅ Timer Modal with Patient Arrived Button -->
    <div id="timerModal" class="modal" style="display: none;">
        <div class="modal-content" style="text-align: center;">
            <h3>⏰ Waiting for Patient</h3>
            <div id="timerDisplay" style="font-size: 48px; font-weight: bold; margin: 20px 0;">05:00</div>
            <p>Patient has <span id="minutesLeft">5</span> minutes to arrive</p>
            <div class="modal-footer" style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
                <button class="btn btn-success" onclick="patientArrived()" style="background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: bold;">✅ Patient Arrived - Start Service</button>
                <button class="btn btn-primary" onclick="extendTimer()" style="background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: bold;">⏰ Extend (2 min)</button>
                <button class="btn btn-danger" onclick="cancelCurrentPatient()" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: bold;">❌ Cancel & Next</button>
            </div>
        </div>
    </div>

    <!-- Patient Modal -->
    <div id="patientModal" class="modal">
        <div class="modal-content">
            <h3>Add New Patient</h3>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" id="p_name" placeholder="Enter name..." required>
            </div>
            <div class="form-group">
                <label>Department</label>
                <select id="p_department" style="width: 100%; padding: 12px; margin-top: 8px; border: 1px solid #00d4ff; border-radius: 8px; background: #0b2e33; color: #ffffff; outline: none;">
                    <option value="OPD">OPD</option>
                    <option value="Pharmacy">Pharmacy</option>
                    <option value="Radiology">Radiology</option>
                </select>
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
            <div id="detail-content" style="margin-top:20px; line-height: 1.8;"></div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="closeModal('detailModal')">Got it</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/Staff.js') }}"></script>
@endsection