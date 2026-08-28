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
                loadQueue();
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
    </script>
@endsection