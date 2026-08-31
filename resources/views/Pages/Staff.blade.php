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
                        <tr>
                            <td><strong>{{ $token->token_number }}</strong></td>
                            <td>
                                <div>{{ $token->patient_name ?? 'N/A' }}</div>
                                <small style="color: rgba(255,255,255,0.4);">{{ $token->phone ?? '' }}</small>
                            </td>
                            <td>{{ ucfirst($token->type ?? 'online') }}</td>
                            <td>{{ $token->estimated_time ?? 0 }} min</td>
                            <td>
                                <span class="status-badge {{ $token->status }}">
                                    {{ ucfirst($token->status) }}
                                </span>
                            </td>
                            <td>
                                @if($token->status == 'waiting')
                                    <button onclick="callPatient({{ $token->id }})" class="btn-sm btn-call">Call</button>
                                @elseif($token->status == 'calling')
                                    <button onclick="startService({{ $token->id }})" class="btn-sm btn-start">Start</button>
                                @elseif($token->status == 'serving')
                                    <button onclick="completeService({{ $token->id }})" class="btn-sm btn-complete">Complete</button>
                                @endif
                                <button onclick="cancelToken({{ $token->id }})" class="btn-sm btn-cancel">Cancel</button>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: rgba(255,255,255,0.3);">
                                <i class="fas fa-inbox" style="font-size: 40px; display: block; margin-bottom: 10px;"></i>
                                No patients in queue
                            </td>
                        </tr>
                    @endif
                </tbody>
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

    <!-- Patient Modal - Updated with Mobile Number -->
    <div id="patientModal" class="modal">
        <div class="modal-content">
            <h3>Add New Patient</h3>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" id="p_name" placeholder="Enter name..." required>
            </div>
            {{-- ✅ NEW: Mobile Number Field --}}
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

    <style>
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-badge.waiting {
            background: rgba(245, 124, 0, 0.2);
            color: #f57c00;
        }
        .status-badge.calling {
            background: rgba(13, 71, 161, 0.2);
            color: #0d47a1;
        }
        .status-badge.serving {
            background: rgba(27, 94, 32, 0.2);
            color: #1b5e20;
        }
        .status-badge.completed {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }
        .btn-sm {
            padding: 4px 12px;
            border: none;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            cursor: pointer;
            margin: 2px;
            transition: all 0.3s ease;
        }
        .btn-sm:hover {
            transform: scale(1.05);
        }
        .btn-call { background: #007bff; color: white; }
        .btn-start { background: #28a745; color: white; }
        .btn-complete { background: #17a2b8; color: white; }
        .btn-cancel { background: #dc3545; color: white; }
    </style>

    <script src="{{ asset('js/Staff.js') }}"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileInput = document.getElementById('p_mobile');
        const mobileError = document.getElementById('mobileError');

        if (mobileInput) {
            mobileInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length > 11) {
                    this.value = this.value.slice(0, 11);
                }

                const isValid = /^(03)\d{9}$/.test(this.value);
                if (this.value.length > 0 && !isValid) {
                    mobileError.style.display = 'block';
                    this.style.border = "1px solid #ff4b2b";
                } else {
                    mobileError.style.display = 'none';
                    this.style.border = "1px solid rgba(255,255,255,0.1)";
                }
            });
        }
    });

    // ✅ Override submitPatient function to include mobile number
    function submitPatient() {
        const name = document.getElementById('p_name')?.value?.trim();
        const mobile = document.getElementById('p_mobile')?.value?.trim();

        if (!name) {
            alert('Please enter patient name');
            return;
        }

        if (!mobile) {
            alert('Please enter mobile number');
            return;
        }

        // Validate mobile number
        const isValid = /^(03)\d{9}$/.test(mobile);
        if (!isValid) {
            document.getElementById('mobileError').style.display = 'block';
            document.getElementById('p_mobile').style.border = "1px solid #ff4b2b";
            return;
        }

        fetch('/staff/add-patient', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                name: name,
                mobile_number: mobile
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeModal('patientModal');
                document.getElementById('p_name').value = '';
                document.getElementById('p_mobile').value = '';
                location.reload();
                alert('✅ ' + data.message);
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error adding patient');
        });
    }

    // ✅ Call Patient
    function callPatient(id) {
        fetch('/staff/start-serving', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ token_id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) location.reload();
            else alert('Error: ' + data.message);
        });
    }

    // ✅ Start Service
    function startService(id) {
        fetch('/staff/start-service', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ token_id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) location.reload();
            else alert('Error: ' + data.message);
        });
    }

    // ✅ Complete Service
    function completeService(id) {
        fetch('/staff/complete-service', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ token_id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) location.reload();
            else alert('Error: ' + data.message);
        });
    }

    // ✅ Cancel Token
    function cancelToken(id) {
        if (!confirm('Are you sure you want to cancel this token?')) return;
        fetch('/staff/cancel-token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ token_id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) location.reload();
            else alert('Error: ' + data.message);
        });
    }
    </script>
@endsection