@extends('Layout.staff_app')
@section('title', 'Staff Portal - Smart Queue Management')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/Staff.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <section class="hero-header">
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
                    <h2 id="stat-total">{{ $totalQueue ?? 0 }}</h2>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Now Serving</span>
                    <h2 id="stat-serving">{{ $nowServingToken ?? '--' }}</h2>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Total Pending Wait</span>
                    <h2 id="stat-avg-time">{{ $avgWaitTime ?? 0 }}m</h2>
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
                <tbody id="queue-body">
                    @if(isset($tokens) && $tokens->count() > 0)
                        @foreach($tokens as $token)
                        <tr id="token-row-{{ $token->id }}">
                            <td><strong>{{ $token->token_number }}</strong></td>
                            <td>
                                <div>{{ $token->patient_name ?? 'N/A' }}</div>
                                <small style="color: rgba(11, 46, 51, 0.5);">{{ $token->phone ?? '' }}</small>
                            </td>
                            <td>{{ ucfirst($token->type ?? 'online') }}</td>
                            <td>{{ $token->estimated_time ?? 0 }} min</td>
                            <td class="status-td">
                                <span class="status-badge {{ $token->status }}">
                                    {{ ucfirst($token->status) }}
                                </span>
                            </td>
                            <td class="action-td">
                                @if($token->status == 'waiting')
                                    <button onclick="updateTokenStatus({{ $token->id }}, 'calling')" class="btn-sm btn-call">
                                        <i class="fas fa-phone"></i> Call
                                    </button>
                                    <button onclick="updateTokenStatus({{ $token->id }}, 'cancelled')" class="btn-sm btn-cancel">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>

                                @elseif($token->status == 'calling')
                                    <button onclick="updateTokenStatus({{ $token->id }}, 'serving')" class="btn-sm btn-start">
                                        <i class="fas fa-play"></i> Start
                                    </button>
                                    <button onclick="updateTokenStatus({{ $token->id }}, 'cancelled')" class="btn-sm btn-cancel">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>

                                @elseif($token->status == 'serving')
                                    <button onclick="updateTokenStatus({{ $token->id }}, 'completed')" class="btn-sm btn-complete">
                                        <i class="fas fa-check"></i> Complete
                                    </button>

                                @elseif($token->status == 'completed')
                                    <span class="badge-served">
                                        <i class="fas fa-check-circle"></i> Served
                                    </span>

                                @elseif($token->status == 'cancelled')
                                    <span class="badge-cancelled">
                                        <i class="fas fa-times-circle"></i> Cancelled
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr id="empty-row">
                            <td colspan="6" style="text-align: center; padding: 40px; color: rgba(11, 46, 51, 0.4);">
                                <i class="fas fa-inbox" style="font-size: 40px; display: block; margin-bottom: 10px;"></i>
                                No patients in queue
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </main>

    <!-- Patient Modal -->
    <div id="patientModal" class="modal">
        <div class="modal-content">
            <h3>Add New Patient</h3>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" id="p_name" placeholder="Enter name..." required>
            </div>
            <div class="form-group">
                <label>Mobile Number</label>
                <input type="tel" id="p_mobile" placeholder="03XX-XXXXXXX" required maxlength="11">
                <small id="mobileError" style="color: #ff4b2b; display: none;">Please enter a valid 11-digit number starting with 03</small>
            </div>
            <div class="modal-footer">
                <button class="btn btn-text" onclick="closeModal('patientModal')">Cancel</button>
                <button class="btn btn-primary" onclick="submitPatient()">Add to Queue</button>
            </div>
        </div>
    </div>

    <!-- Time Modal -->
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

    <!-- External Script File -->
    <script src="{{ asset('js/Staff.js') }}"></script>
@endsection