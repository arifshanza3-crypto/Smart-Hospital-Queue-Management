@extends('Layout.staff_app')
@section('title', 'Staff Portal - Smart Queue Management')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/Staff.css') }}">

    @php
        $isPatientView = request()->has('token');
        $patientToken = request()->query('token');
    @endphp

    @if($isPatientView)
        {{-- ============================================ --}}
        {{-- ✅ PATIENT VIEW - Compact & Matching Style --}}
        {{-- ============================================ --}}
        <section class="hero-header" style="background: linear-gradient(135deg, #0b2e33 0%, #1a5a63 100%);">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <span class="badge-top" style="background: rgba(255,255,255,0.15); color: #8ab4b8;">Patient Portal</span>
                        <h1 style="color: white;">Your Token Status</h1>
                        <p style="color: rgba(255,255,255,0.8);">Real-time update of your queue position</p>
                    </div>
                </div>
            </div>
        </section>

        <main class="container">
            <div class="data-card" style="background: transparent; box-shadow: none; padding: 0;">
                <div id="patient-token-display">
                    <div class="patient-token-card">
                        {{-- Token Header --}}
                        <div class="token-header">
                            <span class="token-label">Your Token</span>
                            <span class="token-number" id="patientTokenNumber">{{ $patientToken ?? '--' }}</span>
                            <span class="token-badge" id="tokenBadge">● Active</span>
                        </div>

                        {{-- Token Details - Compact Grid --}}
                        <div class="token-details-grid">
                            <div class="detail-item">
                                <span class="label">Patient</span>
                                <span class="value" id="patientName">--</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Department</span>
                                <span class="value" id="patientDepartment">--</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Status</span>
                                <span class="value status-badge" id="patientStatus">Waiting</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Position</span>
                                <span class="value" id="patientPosition">--</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Est. Wait</span>
                                <span class="value" id="patientWaitTime">--</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Serving</span>
                                <span class="value" id="patientServing">--</span>
                            </div>
                            <div class="detail-item full-width">
                                <span class="label">Generated</span>
                                <span class="value" id="patientTime">--</span>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="progress-container">
                            <div class="progress-bar">
                                <div class="progress-fill" id="patientProgress" style="width: 0%"></div>
                            </div>
                            <span class="progress-text" id="progressText">0%</span>
                        </div>

                        {{-- Buttons --}}
                        <div class="token-actions">
                            <button class="btn-refresh" onclick="fetchTokenStatus()"> Refresh</button>
                            <a href="/" class="btn-home">Home</a>
                        </div>

                        {{-- Last Updated --}}
                        <div class="last-updated">
                            Last updated: <span id="lastUpdated">--</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        {{-- ✅ JavaScript --}}
        <script>
            const tokenNumber = '{{ $patientToken }}';
            
            function fetchTokenStatus() {
                if (!tokenNumber) {
                    document.getElementById('patientTokenNumber').textContent = 'No Token';
                    document.getElementById('tokenBadge').textContent = '● Invalid';
                    document.getElementById('tokenBadge').style.color = '#dc3545';
                    return;
                }

                fetch(`/patient/token-status?token=${tokenNumber}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updatePatientUI(data);
                        } else {
                            document.getElementById('patientTokenNumber').textContent = tokenNumber;
                            document.getElementById('patientStatus').textContent = 'Not Found';
                            document.getElementById('patientStatus').className = 'value status-badge status-cancelled';
                            document.getElementById('tokenBadge').textContent = '● Invalid';
                            document.getElementById('tokenBadge').style.color = '#dc3545';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('patientStatus').textContent = 'Error';
                        document.getElementById('patientStatus').className = 'value status-badge status-cancelled';
                    });
            }

            function updatePatientUI(data) {
                document.getElementById('patientTokenNumber').textContent = data.token_number || '--';
                document.getElementById('patientName').textContent = data.patient_name || '--';
                document.getElementById('patientDepartment').textContent = data.department || '--';
                document.getElementById('patientPosition').textContent = '#' + (data.position || '--');
                document.getElementById('patientWaitTime').textContent = data.estimated_time ? data.estimated_time + 'm' : '--';
                document.getElementById('patientServing').textContent = data.serving || '--';
                document.getElementById('patientTime').textContent = data.created_at || '--';
                
                // Update status
                const statusBadge = document.getElementById('patientStatus');
                const status = data.status || 'waiting';
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                statusBadge.className = 'value status-badge';
                const badge = document.getElementById('tokenBadge');
                
                if (status === 'serving' || status === 'calling') {
                    statusBadge.classList.add('status-serving');
                    badge.textContent = '● In Progress';
                    badge.style.color = '#28a745';
                } else if (status === 'completed') {
                    statusBadge.classList.add('status-completed');
                    badge.textContent = '● Completed';
                    badge.style.color = '#28a745';
                } else if (status === 'cancelled') {
                    statusBadge.classList.add('status-cancelled');
                    badge.textContent = '● Cancelled';
                    badge.style.color = '#dc3545';
                } else {
                    statusBadge.classList.add('status-waiting');
                    badge.textContent = '● Waiting';
                    badge.style.color = '#f57c00';
                }

                // Progress
                const progress = data.progress || 0;
                document.getElementById('patientProgress').style.width = progress + '%';
                document.getElementById('progressText').textContent = progress + '%';

                document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString();
            }

            fetchTokenStatus();
            setInterval(fetchTokenStatus, 10000);
        </script>

        {{-- ✅ Compact CSS --}}
        <style>
            .patient-token-card {
                max-width: 480px;
                margin: 0 auto;
                background: rgba(255, 255, 255, 0.08);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border-radius: 18px;
                padding: 25px 28px 20px;
                border: 1px solid rgba(255, 255, 255, 0.12);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            }

            .token-header {
                text-align: center;
                padding-bottom: 15px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
                margin-bottom: 15px;
            }

            .token-label {
                display: block;
                font-size: 11px;
                color: rgba(255, 255, 255, 0.5);
                font-weight: 600;
                letter-spacing: 2px;
                text-transform: uppercase;
                margin-bottom: 4px;
            }

            .token-number {
                font-size: 34px;
                font-weight: 800;
                color: #ffffff;
                letter-spacing: 1px;
                display: block;
            }

            .token-badge {
                display: inline-block;
                font-size: 11px;
                font-weight: 600;
                color: #f57c00;
                margin-top: 4px;
                letter-spacing: 0.5px;
            }

            .token-details-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 6px 20px;
                margin-bottom: 15px;
            }

            .detail-item {
                display: flex;
                flex-direction: column;
                padding: 5px 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }

            .detail-item.full-width {
                grid-column: 1 / -1;
            }

            .detail-item .label {
                font-size: 10px;
                color: rgba(255, 255, 255, 0.4);
                font-weight: 500;
                letter-spacing: 0.5px;
                text-transform: uppercase;
            }

            .detail-item .value {
                font-size: 14px;
                font-weight: 600;
                color: rgba(255, 255, 255, 0.9);
                margin-top: 1px;
            }

            .status-badge {
                padding: 2px 12px;
                border-radius: 12px;
                font-size: 12px;
                display: inline-block;
                font-weight: 600;
                width: fit-content;
            }

            .status-waiting {
                background: rgba(255, 193, 7, 0.2);
                color: #ffc107;
                border: 1px solid rgba(255, 193, 7, 0.3);
            }

            .status-serving {
                background: rgba(33, 150, 243, 0.2);
                color: #42a5f5;
                border: 1px solid rgba(33, 150, 243, 0.3);
            }

            .status-completed {
                background: rgba(76, 175, 80, 0.2);
                color: #66bb6a;
                border: 1px solid rgba(76, 175, 80, 0.3);
            }

            .status-cancelled {
                background: rgba(244, 67, 54, 0.2);
                color: #ef5350;
                border: 1px solid rgba(244, 67, 54, 0.3);
            }

            .progress-container {
                display: flex;
                align-items: center;
                gap: 12px;
                margin: 12px 0 15px;
                padding: 10px 14px;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 10px;
            }

            .progress-bar {
                flex: 1;
                height: 5px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 10px;
                overflow: hidden;
            }

            .progress-fill {
                height: 100%;
                background: linear-gradient(90deg, #1a7a82, #2aaab5, #4dd0e1);
                transition: width 0.6s ease;
                border-radius: 10px;
            }

            .progress-text {
                font-size: 12px;
                font-weight: 700;
                color: rgba(255, 255, 255, 0.7);
                min-width: 38px;
                text-align: right;
            }

            .token-actions {
                display: flex;
                gap: 10px;
                margin-top: 3px;
            }

            .btn-refresh, .btn-home {
                flex: 1;
                padding: 10px 16px;
                border: none;
                border-radius: 10px;
                font-weight: 600;
                font-size: 13px;
                cursor: pointer;
                transition: all 0.3s ease;
                text-align: center;
                text-decoration: none;
            }

            .btn-refresh {
                background: rgba(255, 255, 255, 0.12);
                color: white;
                border: 1px solid rgba(255, 255, 255, 0.15);
            }

            .btn-refresh:hover {
                background: rgba(255, 255, 255, 0.2);
                transform: translateY(-1px);
            }

            .btn-home {
                background: rgba(255, 255, 255, 0.06);
                color: rgba(255, 255, 255, 0.7);
                border: 1px solid rgba(255, 255, 255, 0.08);
            }

            .btn-home:hover {
                background: rgba(255, 255, 255, 0.12);
                color: white;
                transform: translateY(-1px);
            }

            .last-updated {
                text-align: center;
                margin-top: 12px;
                color: rgba(255, 255, 255, 0.3);
                font-size: 10px;
                font-weight: 500;
                letter-spacing: 0.3px;
            }

            #patient-token-display {
                animation: fadeUp 0.5s ease;
            }

            @keyframes fadeUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Mobile */
            @media (max-width: 520px) {
                .patient-token-card {
                    padding: 20px 16px 16px;
                    margin: 8px;
                    border-radius: 14px;
                }

                .token-number {
                    font-size: 28px;
                }

                .token-details-grid {
                    grid-template-columns: 1fr 1fr;
                    gap: 4px 12px;
                }

                .detail-item .value {
                    font-size: 13px;
                }

                .token-actions {
                    flex-direction: column;
                    gap: 8px;
                }

                .btn-refresh, .btn-home {
                    padding: 10px;
                    font-size: 13px;
                }
            }
        </style>

    @else
        {{-- ============================================ --}}
        {{-- ✅ STAFF VIEW - Full Table --}}
        {{-- ============================================ --}}
        <section class="hero-header" style="background: linear-gradient(135deg, #0b2e33 0%, #1a5a63 100%);">
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