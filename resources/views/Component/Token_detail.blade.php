<link rel="stylesheet" href="{{ asset('css/Token_detail.css') }}"></link>
<section class="token-status-section">
    <div class="token-display-card">
        <div class="token-header">
            <span class="status-badge pulse">Live Status</span>
            <h2 class="card-title text-white-50 mt-3">Your Queue Position</h2>
        </div>

        <div class="token-main-content">
            <div class="token-number-wrapper">
                <div class="token-ring"></div>
                <div class="token-data">
                    <span class="token-label">Token Number</span>
                    <h1 class="token-value" id="patientToken">A-124</h1>
                </div>
            </div>

            <div class="status-grid">
                <div class="time-box">
                    <div class="time-value" id="remainingTime">12:24</div>
                    <div class="time-label">Mins Remaining</div>
                </div>
                
                <div class="info-box">
                    <div class="info-item mb-2">
                        <span class="info-label">Patients Ahead</span>
                        <span class="info-value">04</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Doctor Room</span>
                        <span class="info-value">OPD-02</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="{{ asset('js/Token_detail.js') }}"></script>