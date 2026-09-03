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
                                    {{-- ✅ Call Button --}}
                                    <button onclick="updateTokenStatus({{ $token->id }}, 'calling')" class="btn-sm btn-call">
                                        <i class="fas fa-phone"></i> Call
                                    </button>
                                    {{-- ✅ Cancel Button --}}
                                    <button onclick="updateTokenStatus({{ $token->id }}, 'cancelled')" class="btn-sm btn-cancel">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>

                                @elseif($token->status == 'calling')
                                    {{-- ✅ Start Serving Button --}}
                                    <button onclick="updateTokenStatus({{ $token->id }}, 'serving')" class="btn-sm btn-start">
                                        <i class="fas fa-play"></i> Start
                                    </button>
                                    {{-- ✅ Cancel Button --}}
                                    <button onclick="updateTokenStatus({{ $token->id }}, 'cancelled')" class="btn-sm btn-cancel">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>

                                @elseif($token->status == 'serving')
                                    {{-- ✅ Complete Button --}}
                                    <button onclick="updateTokenStatus({{ $token->id }}, 'completed')" class="btn-sm btn-complete">
                                        <i class="fas fa-check"></i> Complete
                                    </button>

                                @elseif($token->status == 'completed')
                                    {{-- ✅ Served Badge --}}
                                    <span class="badge-served">
                                        <i class="fas fa-check-circle"></i> Served
                                    </span>

                                @elseif($token->status == 'cancelled')
                                    {{-- ✅ Cancelled Badge --}}
                                    <span class="badge-cancelled">
                                        <i class="fas fa-times-circle"></i> Cancelled
                                    </span>
                                @endif
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
        /* --- Status Badges --- */
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
        .status-badge.cancelled {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        /* --- Action Buttons --- */
        .btn-sm {
            padding: 6px 14px;
            border: none;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 600;
            cursor: pointer;
            margin: 2px 4px;
            transition: all 0.3s ease;
        }
        .btn-sm:hover {
            transform: scale(1.05);
        }
        .btn-sm i {
            margin-right: 4px;
        }

        /* ✅ Call Button - Blue */
        .btn-call {
            background: #3b82f6;
            color: #ffffff;
        }
        .btn-call:hover {
            background: #2563eb;
        }

        /* ✅ Start Button - Green */
        .btn-start {
            background: #22c55e;
            color: #ffffff;
        }
        .btn-start:hover {
            background: #16a34a;
        }

        /* ✅ Complete Button - Purple */
        .btn-complete {
            background: #8b5cf6;
            color: #ffffff;
        }
        .btn-complete:hover {
            background: #7c3aed;
        }

        /* ✅ Cancel Button - Red */
        .btn-cancel {
            background: #ef4444;
            color: #ffffff;
        }
        .btn-cancel:hover {
            background: #dc2626;
        }

        /* --- Badges --- */
        .badge-served {
            display: inline-block;
            padding: 6px 14px;
            background: #dcfce7;
            color: #16a34a;
            border-radius: 8px;
            font-weight: 600;
            font-size: 12px;
        }

        .badge-cancelled {
            display: inline-block;
            padding: 6px 14px;
            background: #fee2e2;
            color: #dc2626;
            border-radius: 8px;
            font-weight: 600;
            font-size: 12px;
        }
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

    // ✅ Unified Function — 1 Button, 3 Functions
    function updateTokenStatus(id, status) {
        let endpoint = '';
        let actionText = '';

        switch(status) {
            case 'calling':
                endpoint = '/staff/start-serving';
                actionText = 'Call';
                break;
            case 'serving':
                endpoint = '/staff/start-service';
                actionText = 'Start Service';
                break;
            case 'completed':
                endpoint = '/staff/complete-service';
                actionText = 'Complete';
                break;
            case 'cancelled':
                endpoint = '/staff/cancel-token';
                actionText = 'Cancel';
                if (!confirm('Are you sure you want to cancel this token?')) return;
                break;
            default:
                return;
        }

        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ token_id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('❌ Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error updating token status');
        });
    }

    // ✅ Add Physical Patient
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

    // ✅ Modal Functions
    function openModal(id) {
        document.getElementById(id).classList.add('show');
        document.getElementById(id).style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        document.getElementById(id).style.display = 'none';
    }

    // ✅ Timer Modal Functions (placeholder)
    function patientArrived() {
        alert('✅ Patient arrived - Starting service');
        closeModal('timerModal');
    }

    function extendTimer() {
        alert('⏰ Timer extended by 2 minutes');
    }

    function cancelCurrentPatient() {
        if (confirm('Cancel current patient?')) {
            closeModal('timerModal');
        }
    }

    function submitGlobalTime() {
        const minutes = document.getElementById('global_min').value;
        alert('⏱ Global time set to ' + minutes + ' minutes');
        closeModal('timeModal');
    }
    </script>
@endsection