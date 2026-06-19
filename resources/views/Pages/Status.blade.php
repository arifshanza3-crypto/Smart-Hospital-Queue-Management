@extends('layout.app')

@section('title', 'Status - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="{{ asset('css/status.css') }}">
<<<<<<< HEAD
=======
<meta name="csrf-token" content="{{ csrf_token() }}">
>>>>>>> 3f9cfec078e8d9879dc8f908935c7d8b28740f60

<div class="status-viewport">
    <div class="mesh-bg"></div>
    
    <section class="hero-section">
        <div class="container text-center">
            <div class="hero-badge">Live System</div>
            <h1 class="hero-title">Token Status</h1>
            <p class="hero-subtitle">Monitor your position in the digital queue in real-time.</p>
        </div>
    </section>

<<<<<<< HEAD
    <section class="component-display">
        <div id="mechanism-target" class="container">
            </div>
    </section>
=======
    <!-- Token Status Card -->
    <div class="container">
        <div class="token-status-card" style="background: linear-gradient(135deg, #1a1a2e, #16213e); border-radius: 20px; padding: 30px; margin: 20px auto; max-width: 500px; text-align: center; border: 1px solid #00d4ff;">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                <!-- Your Number -->
                <div class="stat-box">
                    <span style="color: #aaa; font-size: 14px;">Your Number</span>
                    <h2 id="tokenNumber" style="color: #00d4ff; font-size: 48px; margin: 10px 0;">--</h2>
                </div>
                
                <!-- Serving -->
                <div class="stat-box">
                    <span style="color: #aaa; font-size: 14px;">Serving</span>
                    <h2 id="serving" style="color: #28a745; font-size: 48px; margin: 10px 0;">--</h2>
                </div>
                
                <!-- Wait Time -->
                <div class="stat-box">
                    <span style="color: #aaa; font-size: 14px;">Wait Time</span>
                    <h2 id="waitTime" style="color: #ffc107; font-size: 48px; margin: 10px 0;">--</h2>
                </div>
            </div>
            
            <!-- Position in Queue -->
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #333;">
                <span style="color: #aaa;">Your Position in Queue</span>
                <h3 id="position" style="color: #00d4ff; font-size: 24px;">--</h3>
            </div>
            
            <!-- Status Badge -->
            <div style="margin-top: 20px;">
                <span id="statusBadge" class="badge" style="padding: 8px 20px; border-radius: 20px; font-size: 14px;">Waiting</span>
            </div>
        </div>
        
        <!-- Notification Messages -->
        <div id="notificationArea" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;"></div>
    </div>
>>>>>>> 3f9cfec078e8d9879dc8f908935c7d8b28740f60

    <div class="mechanism-demo-trigger">
        <button id="switchStateBtn" class="btn-mechanism">Toggle Generation Logic</button>
    </div>
</div>

<<<<<<< HEAD
<script src="{{ asset('js/status.js') }}"></script>
=======
<script>
    // Token Status Functions
    let notificationInterval = null;
    
    function showNotification(message, type = 'info') {
        const notificationArea = document.getElementById('notificationArea');
        const notification = document.createElement('div');
        notification.className = 'notification-toast';
        notification.style.cssText = `
            background: ${type === 'warning' ? '#ffc107' : type === 'error' ? '#dc3545' : '#28a745'};
            color: ${type === 'warning' ? '#000' : '#fff'};
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 10px;
            animation: slideIn 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        `;
        notification.innerHTML = message;
        notificationArea.appendChild(notification);
        
        setTimeout(() => notification.remove(), 5000);
    }
    
    function loadTokenStatus() {
        fetch('/patient/token-status', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI with data
                document.getElementById('tokenNumber').innerText = data.token_number || '--';
                document.getElementById('serving').innerText = data.serving || '--';
                document.getElementById('waitTime').innerHTML = data.wait_time + 'm' || '0m';
                document.getElementById('position').innerText = data.position || '0';
                
                // Update status badge
                const statusBadge = document.getElementById('statusBadge');
                let badgeColor = '#6c757d';
                let statusText = data.status || 'Waiting';
                
                if (data.status === 'waiting') {
                    badgeColor = '#ffc107';
                    statusText = '⏳ Waiting';
                } else if (data.status === 'calling') {
                    badgeColor = '#17a2b8';
                    statusText = '📞 Your Turn! Please proceed';
                    showNotification('🔔 Your turn is coming! Please reach the counter.', 'warning');
                } else if (data.status === 'serving') {
                    badgeColor = '#28a745';
                    statusText = '🩺 Being Served';
                } else if (data.status === 'completed') {
                    badgeColor = '#6c757d';
                    statusText = '✅ Service Completed';
                }
                
                statusBadge.style.background = badgeColor;
                statusBadge.innerText = statusText;
                
                // If status is calling, play sound (optional)
                if (data.status === 'calling') {
                    // play beep sound
                    const audio = new Audio('/sounds/notification.mp3');
                    audio.play().catch(e => console.log('Audio not supported'));
                }
            } else {
                // No active token
                document.getElementById('tokenNumber').innerText = '--';
                document.getElementById('serving').innerText = '--';
                document.getElementById('waitTime').innerHTML = '0m';
                document.getElementById('position').innerText = '0';
                document.getElementById('statusBadge').innerText = 'No Active Token';
                document.getElementById('statusBadge').style.background = '#6c757d';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    // Toggle mechanism (for demo)
    let isAutomatic = true;
    document.getElementById('switchStateBtn').addEventListener('click', function() {
        isAutomatic = !isAutomatic;
        this.innerText = isAutomatic ? 'Switch to Manual' : 'Switch to Automatic';
        showNotification('Token generation mode changed to ' + (isAutomatic ? 'Automatic' : 'Manual'), 'info');
    });
    
    // Load status on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadTokenStatus();
        // Refresh every 5 seconds
        setInterval(loadTokenStatus, 5000);
    });
    
    // Add CSS animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .stat-box {
            transition: transform 0.3s ease;
        }
        .stat-box:hover {
            transform: translateY(-5px);
        }
    `;
    document.head.appendChild(style);
</script>

>>>>>>> 3f9cfec078e8d9879dc8f908935c7d8b28740f60
@endsection