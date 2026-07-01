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
        {{-- ✅ PATIENT VIEW - Yellow/Golden Theme --}}
        {{-- ============================================ --}}
        <section class="hero-header" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <span class="badge-top" style="background: rgba(255,215,0,0.15); color: #ffd700;">✦ Patient Portal</span>
                        <h1 style="color: white; text-shadow: 0 2px 20px rgba(255,215,0,0.1);">Your Token Status</h1>
                        <p style="color: rgba(255,255,255,0.6);">Real-time update of your queue position</p>
                    </div>
                </div>
            </div>
        </section>

        <main class="container">
            <div class="data-card" style="background: transparent; box-shadow: none; padding: 0; max-width: 420px; margin: 0 auto;">
                <div id="patient-token-display">
                    <div class="patient-token-card">
                        {{-- Token Header --}}
                        <div class="token-header">
                            <span class="token-label">✦ YOUR TOKEN</span>
                            <span class="token-number" id="patientTokenNumber">{{ $patientToken ?? '--' }}</span>
                            <span class="token-badge" id="tokenBadge">● Waiting</span>
                        </div>

                        {{-- Token Details --}}
                        <div class="token-details-grid">
                            <div class="detail-item">
                                <span class="label">👤 Patient</span>
                                <span class="value" id="patientName">--</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">🏥 Department</span>
                                <span class="value" id="patientDepartment">--</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">📊 Status</span>
                                <span class="value status-badge" id="patientStatus">Waiting</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">📍 Position</span>
                                <span class="value" id="patientPosition">--</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">⏱ Est. Wait</span>
                                <span class="value" id="patientWaitTime">--</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">🔄 Serving</span>
                                <span class="value" id="patientServing">--</span>
                            </div>
                            <div class="detail-item full-width">
                                <span class="label">📅 Generated</span>
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
                            <button class="btn-refresh" onclick="fetchTokenStatus()">⟳ Refresh</button>
                            <a href="/" class="btn-home">⌂ Home</a>
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
                    document.getElementById('tokenBadge').textContent = '✕ Invalid';
                    document.getElementById('tokenBadge').style.background = '#dc3545';
                    document.getElementById('tokenBadge').style.color = 'white';
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
                            document.getElementById('tokenBadge').textContent = '✕ Invalid';
                            document.getElementById('tokenBadge').style.background = '#dc3545';
                            document.getElementById('tokenBadge').style.color = 'white';
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
                
                const statusBadge = document.getElementById('patientStatus');
                const status = data.status || 'waiting';
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                statusBadge.className = 'value status-badge';
                const badge = document.getElementById('tokenBadge');
                badge.style.color = 'white';
                
                if (status === 'serving' || status === 'calling') {
                    statusBadge.classList.add('status-serving');
                    badge.textContent = '● In Progress';
                    badge.style.background = '#4a6cf7';
                } else if (status === 'completed') {
                    statusBadge.classList.add('status-completed');
                    badge.textContent = '● Completed';
                    badge.style.background = '#22c55e';
                } else if (status === 'cancelled') {
                    statusBadge.classList.add('status-cancelled');
                    badge.textContent = '● Cancelled';
                    badge.style.background = '#ef4444';
                } else {
                    statusBadge.classList.add('status-waiting');
                    badge.textContent = '● Waiting';
                    badge.style.background = '#f59e0b';
                }

                const progress = data.progress || 0;
                document.getElementById('patientProgress').style.width = progress + '%';
                document.getElementById('progressText').textContent = progress + '%';

                document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString();
            }

            fetchTokenStatus();
            setInterval(fetchTokenStatus, 10000);
        </script>

        {{-- ✅ Yellow/Golden Theme CSS --}}
        <style>
            .patient-token-card {
                max-width: 100%;
                background: linear-gradient(145deg, #ffffff, #fffbeb);
                border-radius: 16px;
                padding: 24px 28px 20px;
                box-shadow: 0 4px 25px rgba(255, 215, 0, 0.12);
                border: 1px solid rgba(255, 215, 0, 0.15);
                position: relative;
                overflow: hidden;
            }

            /* Golden top border */
            .patient-token-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 3px;
                background: linear-gradient(90deg, #f59e0b, #ffd700, #f59e0b);
                background-size: 200% 100%;
                animation: goldShimmer 3s ease infinite;
            }

            @keyframes goldShimmer {
                0%, 100% { background-position: 0% 0%; }
                50% { background-position: 100% 0%; }
            }

            .token-header {
                text-align: center;
                padding-bottom: 14px;
                border-bottom: 1px solid rgba(255, 215, 0, 0.15);
                margin-bottom: 14px;
            }

            .token-label {
                display: block;
                font-size: 10px;
                color: #b8860b;
                font-weight: 700;
                letter-spacing: 3px;
                text-transform: uppercase;
                margin-bottom: 2px;
            }

            .token-number {
                font-size: 32px;
                font-weight: 800;
                color: #1a1a2e;
                display: block;
                letter-spacing: 1px;
                background: linear-gradient(135deg, #b8860b, #f59e0b, #ffd700);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .token-badge {
                display: inline-block;
                padding: 2px 16px;
                border-radius: 12px;
                font-size: 11px;
                font-weight: 600;
                color: white;
                background: #f59e0b;
                margin-top: 4px;
                box-shadow: 0 2px 10px rgba(245, 158, 11, 0.3);
            }

            .token-details-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 2px 20px;
                margin-bottom: 12px;
            }

            .detail-item {
                display: flex;
                flex-direction: column;
                padding: 5px 0;
                border-bottom: 1px solid rgba(255, 215, 0, 0.08);
            }

            .detail-item.full-width {
                grid-column: 1 / -1;
            }

            .detail-item .label {
                font-size: 9px;
                color: #b8860b;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .detail-item .value {
                font-size: 14px;
                font-weight: 600;
                color: #1a1a2e;
                margin-top: 1px;
            }

            .status-badge {
                padding: 1px 12px;
                border-radius: 10px;
                font-size: 11px;
                display: inline-block;
                font-weight: 600;
                width: fit-content;
            }

            .status-waiting {
                background: #fef3c7;
                color: #92400e;
                border: 1px solid #f59e0b;
            }

            .status-serving {
                background: #dbeafe;
                color: #1e40af;
                border: 1px solid #3b82f6;
            }

            .status-completed {
                background: #d1fae5;
                color: #065f46;
                border: 1px solid #10b981;
            }

            .status-cancelled {
                background: #fee2e2;
                color: #991b1b;
                border: 1px solid #ef4444;
            }

            .progress-container {
                display: flex;
                align-items: center;
                gap: 10px;
                margin: 10px 0 12px;
                padding: 8px 12px;
                background: #fffbeb;
                border-radius: 8px;
                border: 1px solid rgba(255, 215, 0, 0.1);
            }

            .progress-bar {
                flex: 1;
                height: 5px;
                background: #fef3c7;
                border-radius: 10px;
                overflow: hidden;
            }

            .progress-fill {
                height: 100%;
                background: linear-gradient(90deg, #b8860b, #f59e0b, #ffd700);
                transition: width 0.6s ease;
                border-radius: 10px;
                position: relative;
            }

            .progress-fill::after {
                content: '';
                position: absolute;
                top: -2px;
                left: 0;
                right: 0;
                bottom: -2px;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                animation: shimmer 2s infinite;
            }

            @keyframes shimmer {
                0% { transform: translateX(-100%); }
                100% { transform: translateX(100%); }
            }

            .progress-text {
                font-size: 12px;
                font-weight: 700;
                color: #b8860b;
                min-width: 35px;
                text-align: right;
            }

            .token-actions {
                display: flex;
                gap: 8px;
                margin-top: 2px;
            }

            .btn-refresh, .btn-home {
                flex: 1;
                padding: 8px 14px;
                border: none;
                border-radius: 8px;
                font-weight: 600;
                font-size: 12px;
                cursor: pointer;
                transition: all 0.2s ease;
                text-align: center;
                text-decoration: none;
            }

            .btn-refresh {
                background: linear-gradient(135deg, #b8860b, #f59e0b);
                color: white;
                box-shadow: 0 2px 12px rgba(245, 158, 11, 0.25);
            }

            .btn-refresh:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 20px rgba(245, 158, 11, 0.35);
            }

            .btn-home {
                background: #fffbeb;
                color: #b8860b;
                border: 1px solid rgba(255, 215, 0, 0.2);
            }

            .btn-home:hover {
                background: #fef3c7;
                border-color: #f59e0b;
            }

            .last-updated {
                text-align: center;
                margin-top: 10px;
                color: #b8860b;
                font-size: 10px;
                font-weight: 500;
                opacity: 0.6;
            }

            #patient-token-display {
                animation: fadeIn 0.4s ease;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            /* Mobile */
            @media (max-width: 480px) {
                .patient-token-card {
                    padding: 18px 16px 16px;
                    margin: 0 4px;
                    border-radius: 14px;
                }

                .token-number {
                    font-size: 26px;
                }

                .token-details-grid {
                    gap: 0 12px;
                }

                .detail-item .value {
                    font-size: 13px;
                }

                .token-actions {
                    flex-direction: column;
                    gap: 6px;
                }

                .btn-refresh, .btn-home {
                    padding: 8px;
                }
            }
        </style>

    @else
        {{-- ============================================ --}}
        {{-- ✅ STAFF VIEW - Full Table --}}
        {{-- ============================================ --}}
        <section class="hero-header" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <span class="badge-top" style="background: rgba(255,215,0,0.15); color: #ffd700;">Staff Portal</span>
                        <h1 style="color: white;">Smart Queue Management</h1>
                        <p style="color: rgba(255,255,255,0.6);">Real-time oversight of physical walk-ins and digital bookings.</p>
                    </div>
                    <div class="hero-actions">
                        <button class="btn btn-primary" onclick="openModal('patientModal')" style="background: linear-gradient(135deg, #f59e0b, #ffd700); color: #1a1a2e; border: none; font-weight: 700;">+ Add Physical Patient</button>
                        <button class="btn btn-secondary" onclick="openModal('timeModal')" style="background: rgba(255,255,255,0.08); color: white; border: 1px solid rgba(255,215,0,0.2);">⏱ Set Global Time</button>
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
                    <button class="btn btn-primary" onclick="submitPatient()" style="background: linear-gradient(135deg, #f59e0b, #ffd700); color: #1a1a2e; border: none; font-weight: 700;">Add to Queue</button>
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
                    <button class="btn btn-primary" onclick="submitGlobalTime()" style="background: linear-gradient(135deg, #f59e0b, #ffd700); color: #1a1a2e; border: none; font-weight: 700;">Update All</button>
                </div>
            </div>
        </div>

        <div id="detailModal" class="modal">
            <div class="modal-content">
                <h3>Queue Positioning</h3>
                <div id="detail-content" style="margin-top:20px; line-height: 1.8;"></div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="closeModal('detailModal')" style="background: linear-gradient(135deg, #f59e0b, #ffd700); color: #1a1a2e; border: none; font-weight: 700;">Got it</button>
                </div>
            </div>
        </div>

        <script src="{{ asset('js/Staff.js') }}"></script>
    @endif
@endsection