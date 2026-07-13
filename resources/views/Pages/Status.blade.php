@extends('Layout.staff_app')
@section('title', 'Staff Portal - Smart Queue Management')
@section('content')
    {{-- CSS for Patient Status --}}
    <link rel="stylesheet" href="{{ asset('css/status.css') }}">

    @php
        $isPatientView = request()->has('token');
        $patientToken = request()->query('token');
    @endphp

    @if($isPatientView)
        {{-- ============================================ --}}
        {{-- ✅ PATIENT VIEW - Modern Blue/Teal Theme --}}
        {{-- ============================================ --}}
        <section class="hero-header" style="background: linear-gradient(135deg, #0b2e33 0%, #1a5a63 50%, #2aaab5 100%);">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <span class="badge-top" style="background: rgba(0,212,255,0.15); color: #00d4ff;">✦ Patient Portal</span>
                        <h1 style="color: white; text-shadow: 0 2px 20px rgba(0,212,255,0.1);">Your Token Status</h1>
                        <p style="color: rgba(255,255,255,0.7);">Real-time update of your queue position</p>
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

        {{-- ✅ Patient JavaScript --}}
        <script src="{{ asset('js/status.js') }}"></script>

    @else
        {{-- ============================================ --}}
        {{-- ✅ STAFF VIEW - Full Table --}}
        {{-- ============================================ --}}
        <section class="hero-header" style="background: linear-gradient(135deg, #0b2e33 0%, #1a5a63 50%, #2aaab5 100%);">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text">
                        <span class="badge-top" style="background: rgba(0,212,255,0.15); color: #00d4ff;">Staff Portal</span>
                        <h1 style="color: white;">Smart Queue Management</h1>
                        <p style="color: rgba(255,255,255,0.7);">Real-time oversight of physical walk-ins and digital bookings.</p>
                    </div>
                    <div class="hero-actions">
                        <button class="btn btn-primary" onclick="openModal('patientModal')" style="background: linear-gradient(135deg, #00d4ff, #0099cc); color: #0b2e33; border: none; font-weight: 700;">+ Add Physical Patient</button>
                        <button class="btn btn-secondary" onclick="openModal('timeModal')" style="background: rgba(255,255,255,0.08); color: white; border: 1px solid rgba(0,212,255,0.3);">⏱ Set Global Time</button>
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
                    <button class="btn btn-primary" onclick="submitPatient()" style="background: linear-gradient(135deg, #00d4ff, #0099cc); color: #0b2e33; border: none; font-weight: 700;">Add to Queue</button>
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
                    <button class="btn btn-primary" onclick="submitGlobalTime()" style="background: linear-gradient(135deg, #00d4ff, #0099cc); color: #0b2e33; border: none; font-weight: 700;">Update All</button>
                </div>
            </div>
        </div>

        <div id="detailModal" class="modal">
            <div class="modal-content">
                <h3>Queue Positioning</h3>
                <div id="detail-content" style="margin-top:20px; line-height: 1.8;"></div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="closeModal('detailModal')" style="background: linear-gradient(135deg, #00d4ff, #0099cc); color: #0b2e33; border: none; font-weight: 700;">Got it</button>
                </div>
            </div>
        </div>

        <script src="{{ asset('js/Staff.js') }}"></script>
    @endif
@endsection