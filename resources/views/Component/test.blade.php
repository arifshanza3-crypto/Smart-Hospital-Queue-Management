<link rel="stylesheet" href="{{ asset('css/test.css') }}">
<section class="token-status-section">
    <div class="token-display-card">
        <div class="no-token-state">
            <div class="icon-box-glow mb-4">
                <i class="fas fa-ticket-alt text-accent-cyan" style="font-size: 3rem;"></i>
            </div>
            <h2 class="text-white fw-bold">No Active Token</h2>
            <p class="text-white-50 mb-4">You are not currently in the queue. Join now to get your position and estimated waiting time.</p>
            
            <div class="action-area">
                <a href="/get-token" class="btn-get-token">Join Queue Now</a>
            </div>
            
            <div class="mt-4 pt-3 border-top border-secondary">
                <p class="small text-white-50">Estimated wait time for new patients: <strong>~25 Mins</strong></p>
            </div>
        </div>
    </div>
</section>
<script src="{{ asset('js/test.js') }}"></script>