@extends('Layout.staff_app')
@section('title', 'Staff Portal - Smart Queue Management')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/Staff.css') }}">

    {{-- ✅ CHECK: Agar URL mein token hai toh patient view --}}
    @php
        $isPatientView = request()->has('token');
        $patientToken = request()->query('token');
    @endphp

    @if($isPatientView)
        {{-- ============================================ --}}
        {{-- ✅ PATIENT VIEW - Sirf Apna Token --}}
        {{-- ============================================ --}}
        <section class="hero-header" style="background-color: #0b2e33;">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <span class="badge-top">Patient Portal</span>
                        <h1>Your Token Status</h1>
                        <p>Real-time update of your queue position</p>
                    </div>
                </div>
            </div>
        </section>

        <main class="container">
            <div class="data-card">
                <div id="patient-token-display">
                    <div class="patient-token-card">
                        <div class="token-header">
                            <span class="token-label">Your Token</span>
                            <span class="token-number" id="patientTokenNumber">{{ $patientToken ?? '--' }}</span>
                        </div>
                        <div class="token-details">
                            <div class="detail-row">
                                <span class="label">Patient Name</span>
                                <span class="value" id="patientName">--</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Department</span>
                                <span class="value" id="patientDepartment">--</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Status</span>
                                <span class="value status-badge" id="patientStatus">Waiting</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Position in Queue</span>
                                <span class="value" id="patientPosition">--</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Estimated Wait</span>
                                <span class="value" id="patientWaitTime">--</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Currently Serving</span>
                                <span class="value" id="patientServing">--</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Token Generated</span>
                                <span class="value" id="patientTime">--</span>
                            </div>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar">
                                <div class="progress-fill" id="patientProgress" style="width: 0%"></div>
                            </div>
                            <span class="progress-text" id="progressText">0%</span>
                        </div>
                        <div class="token-actions">
                            <button class="btn-refresh" onclick="fetchTokenStatus()">🔄 Refresh</button>
                            <a href="/" class="btn-home">🏠 Back to Home</a>
                        </div>
                        <div class="last-updated">
                            Last updated: <span id="lastUpdated">--</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        {{-- ✅ Patient View JavaScript --}}
        <script>
            const tokenNumber = '{{ $patientToken }}';
            
            function fetchTokenStatus() {
                if (!tokenNumber) {
                    document.getElementById('patientTokenNumber').textContent = 'No Token';
                    return;
                }

                fetch(`/patient/token-status?token=${tokenNumber}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updatePatientUI(data);
                        } else {
                            document.getElementById('patientStatus').textContent = 'Not Found';
                            document.getElementById('patientStatus').className = 'value status-badge status-completed';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('patientStatus').textContent = 'Error Loading';
                    });
            }

            function updatePatientUI(data) {
                document.getElementById('patientName').textContent = data.patient_name || '--';
                document.getElementById('patientDepartment').textContent = data.department || '--';
                document.getElementById('patientPosition').textContent = data.position || '--';
                document.getElementById('patientWaitTime').textContent = data.estimated_time ? data.estimated_time + ' min' : '--';
                document.getElementById('patientServing').textContent = data.serving || '--';
                document.getElementById('patientTime').textContent = data.created_at || '--';
                
                // Update status badge
                const statusBadge = document.getElementById('patientStatus');
                const status = data.status || 'waiting';
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                statusBadge.className = 'value status-badge';
                if (status === 'serving' || status === 'calling') {
                    statusBadge.classList.add('status-serving');
                } else if (status === 'completed' || status === 'cancelled') {
                    statusBadge.classList.add('status-completed');
                } else {
                    statusBadge.classList.add('status-waiting');
                }

                // Update progress
                const progress = data.progress || 0;
                document.getElementById('patientProgress').style.width = progress + '%';
                document.getElementById('progressText').textContent = progress + '%';

                // Update last updated time
                document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString();
            }

            // Initial fetch
            fetchTokenStatus();

            // Auto-refresh every 10 seconds
            setInterval(fetchTokenStatus, 10000);
        </script>

        {{-- ✅ Patient CSS --}}
        <style>
            .patient-token-card {
                max-width: 550px;
                margin: 0 auto;
                background: white;
                border-radius: 16px;
                padding: 30px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            }
            
            .token-header {
                text-align: center;
                padding-bottom: 20px;
                border-bottom: 2px solid #f0f0f0;
                margin-bottom: 20px;
            }
            
            .token-label {
                display: block;
                font-size: 14px;
                color: #888;
                margin-bottom: 5px;
            }
            
            .token-number {
                font-size: 36px;
                font-weight: bold;
                color: #0b2e33;
            }
            
            .detail-row {
                display: flex;
                justify-content: space-between;
                padding: 12px 0;
                border-bottom: 1px solid #f5f5f5;
            }
            
            .detail-row:last-child {
                border-bottom: none;
            }
            
            .detail-row .label {
                color: #666;
                font-weight: 500;
            }
            
            .detail-row .value {
                font-weight: 600;
                color: #333;
            }
            
            .status-badge {
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 14px;
                display: inline-block;
            }
            
            .status-waiting {
                background: #fff3cd;
                color: #856404;
            }
            
            .status-serving {
                background: #cce5ff;
                color: #004085;
            }
            
            .status-completed {
                background: #d4edda;
                color: #155724;
            }
            
            .progress-container {
                margin: 20px 0;
                display: flex;
                align-items: center;
                gap: 15px;
            }
            
            .progress-bar {
                flex: 1;
                height: 8px;
                background: #e0e0e0;
                border-radius: 10px;
                overflow: hidden;
            }
            
            .progress-fill {
                height: 100%;
                background: linear-gradient(90deg, #0b2e33, #1a7a82);
                transition: width 0.5s ease;
                border-radius: 10px;
            }
            
            .progress-text {
                font-weight: 600;
                color: #0b2e33;
                min-width: 45px;
            }
            
            .token-actions {
                display: flex;
                gap: 10px;
                margin-top: 20px;
            }
            
            .btn-refresh, .btn-home {
                flex: 1;
                padding: 12px;
                border: none;
                border-radius: 8px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.3s ease;
                text-align: center;
                text-decoration: none;
                font-size: 14px;
            }
            
            .btn-refresh {
                background: #0b2e33;
                color: white;
            }
            
            .btn-refresh:hover {
                background: #1a4a52;
                transform: scale(1.02);
            }
            
            .btn-home {
                background: #e0e0e0;
                color: #333;
            }
            
            .btn-home:hover {
                background: #d0d0d0;
                transform: scale(1.02);
            }
            
            .last-updated {
                text-align: center;
                margin-top: 15px;
                color: #999;
                font-size: 12px;
            }

            #patient-token-display {
                animation: fadeIn 0.5s ease;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }

            /* Responsive */
            @media (max-width: 600px) {
                .patient-token-card {
                    padding: 20px;
                }
                .token-number {
                    font-size: 28px;
                }
                .detail-row {
                    flex-direction: column;
                    gap: 5px;
                }
                .token-actions {
                    flex-direction: column;
                }
            }
        </style>

    @else
        {{-- ============================================ --}}
        {{-- ✅ STAFF VIEW - Full Table (Existing Code) --}}
        {{-- ============================================ --}}
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

        {{-- Modals --}}
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
                <div id="detail-content" style="margin-top:20px; line-height: 1.8;"></div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="closeModal('detailModal')">Got it</button>
                </div>
            </div>
        </div>

        <script src="{{ asset('js/Staff.js') }}"></script>
    @endif
@endsection