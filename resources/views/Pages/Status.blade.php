@extends('layout.app')

@section('title', 'Token Status - SMART QUEUE')

@section('content')
<style>
/* Status Page Styles */
.status-container {
    min-height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
    background: #0a1113;
}

.status-card {
    background: rgba(11, 46, 51, 0.85);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 24px;
    padding: 40px;
    max-width: 600px;
    width: 100%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
}

.status-card h2 {
    color: #00d4ff;
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 5px;
}

.status-card h3 {
    color: #fff;
    font-size: 1.8rem;
    font-weight: 800;
    text-align: center;
    margin-bottom: 5px;
}

.status-card .subtitle {
    color: rgba(255, 255, 255, 0.5);
    text-align: center;
    font-size: 0.9rem;
    margin-bottom: 30px;
}

.token-number {
    text-align: center;
    padding: 20px;
    background: rgba(0, 212, 255, 0.05);
    border-radius: 16px;
    margin-bottom: 25px;
    border: 1px solid rgba(0, 212, 255, 0.1);
}

.token-number .label {
    display: block;
    color: rgba(255, 255, 255, 0.4);
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.token-number .value {
    display: block;
    color: #fff;
    font-size: 2.5rem;
    font-weight: 800;
    margin: 5px 0;
}

.token-number .badge {
    display: inline-block;
    padding: 4px 16px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.badge.status-waiting {
    background: rgba(245, 124, 0, 0.2);
    color: #f57c00;
}

.badge.status-calling {
    background: rgba(13, 71, 161, 0.2);
    color: #0d47a1;
}

.badge.status-serving {
    background: rgba(27, 94, 32, 0.2);
    color: #1b5e20;
}

.badge.status-completed {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
}

.status-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 25px;
}

.status-item {
    background: rgba(255, 255, 255, 0.03);
    padding: 12px 16px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.04);
}

.status-item .label {
    display: block;
    color: rgba(255, 255, 255, 0.3);
    font-size: 0.6rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.status-item .value {
    display: block;
    color: #fff;
    font-size: 1rem;
    font-weight: 600;
    margin-top: 2px;
}

.status-item .value.status-waiting {
    color: #f57c00;
}

.status-item .value.status-calling {
    color: #0d47a1;
}

.status-item .value.status-serving {
    color: #1b5e20;
}

.status-item .value.status-completed {
    color: #28a745;
}

.now-serving-value {
    color: #00d4ff !important;
    font-weight: 700 !important;
}

.status-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    margin-top: 10px;
}

.status-actions .btn-refresh,
.status-actions .btn-home {
    padding: 10px 30px;
    border-radius: 12px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.status-actions .btn-refresh {
    background: linear-gradient(135deg, #00d4ff, #0088b3);
    color: #fff;
}

.status-actions .btn-refresh:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 212, 255, 0.3);
}

.status-actions .btn-home {
    background: rgba(255, 255, 255, 0.05);
    color: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.status-actions .btn-home:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
}

@media (max-width: 576px) {
    .status-card {
        padding: 24px 16px;
    }
    
    .status-grid {
        grid-template-columns: 1fr;
    }
    
    .token-number .value {
        font-size: 2rem;
    }
    
    .status-actions {
        flex-direction: column;
    }
    
    .status-actions .btn-refresh,
    .status-actions .btn-home {
        justify-content: center;
    }
}
</style>

<div class="status-container">
    <div class="status-card">
        <h2>Patient Portal</h2>
        <h3>Your Token Status</h3>
        <p class="subtitle">Real-time update of your queue position</p>

        <div class="token-status-display">
            <div class="token-number">
                <span class="label">YOUR TOKEN</span>
                <span class="value">{{ $token->token_number ?? 'N/A' }}</span>
                <span class="badge status-{{ $token->status ?? 'waiting' }}">{{ ucfirst($token->status ?? 'Waiting') }}</span>
            </div>

            <div class="status-grid">
                {{-- ✅ PATIENT NAME --}}
                <div class="status-item">
                    <span class="label">PATIENT</span>
                    <span class="value">{{ $token->patient_name ?? 'N/A' }}</span>
                </div>

                {{-- ✅ STATUS --}}
                <div class="status-item">
                    <span class="label">STATUS</span>
                    <span class="value status-{{ $token->status ?? 'waiting' }}">{{ ucfirst($token->status ?? 'Waiting') }}</span>
                </div>

                {{-- ✅ POSITION NUMBER --}}
                <div class="status-item">
                    <span class="label">POSITION</span>
                    <span class="value">#{{ $token->position ?? 'N/A' }}</span>
                </div>

                {{-- ✅ ESTIMATED WAITING TIME --}}
                <div class="status-item">
                    <span class="label">EST. WAIT</span>
                    <span class="value">{{ $token->estimated_time ?? 'N/A' }} min</span>
                </div>

                {{-- ✅ NOW SERVING --}}
                <div class="status-item">
                    <span class="label">NOW SERVING</span>
                    <span class="value now-serving-value">{{ $nowServing ?? 'N/A' }}</span>
                </div>

                {{-- ✅ GENERATED TIME --}}
                <div class="status-item">
                    <span class="label">GENERATED</span>
                    <span class="value">{{ $token->created_at ? $token->created_at->format('h:i A') : 'N/A' }}</span>
                </div>
            </div>

            <div class="status-actions">
                <button onclick="refreshStatus()" class="btn-refresh">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <a href="/" class="btn-home">Home</a>
            </div>
        </div>
    </div>
</div>

<script>
function refreshStatus() {
    location.reload();
}

// Auto refresh every 30 seconds
setInterval(function() {
    location.reload();
}, 30000);
</script>

@endsection