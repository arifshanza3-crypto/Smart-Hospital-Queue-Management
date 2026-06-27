@extends('layout.app')

@section('title', 'Status - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="{{ asset('css/status.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container" style="margin-top: 50px;">
    <div class="token-status-card" style="background: #1a1a2e; border: 1px solid #00d4ff; border-radius: 20px; padding: 30px; max-width: 500px; margin: auto; text-align: center;">
        <h3 style="color: #00d4ff;">Token Status</h3>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin: 20px 0;">
            <div>
                <span style="color: #aaa;">Your Number</span>
                <h2 id="tokenNumber" style="color: #00d4ff;">--</h2>
            </div>
            <div>
                <span style="color: #aaa;">Serving</span>
                <h2 id="serving" style="color: #28a745;">--</h2>
            </div>
            <div>
                <span style="color: #aaa;">Wait Time</span>
                <h2 id="waitTime" style="color: #ffc107;">0m</h2>
            </div>
        </div>
        
        <div>
            <span style="color: #aaa;">Your Position in Queue</span>
            <h3 id="position" style="color: #00d4ff;">0</h3>
        </div>
        
        <div style="margin-top: 15px;">
            <span id="statusBadge" style="padding: 8px 20px; border-radius: 20px; background: #ffc107; color: #000;">Waiting</span>
        </div>
    </div>
</div>

<script>
function loadTokenStatus() {
    fetch('/patient/token-status')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('tokenNumber').innerText = data.token_number || '--';
                document.getElementById('serving').innerText = data.serving || '--';
                document.getElementById('waitTime').innerHTML = (data.estimated_time || 0) + 'm';
                document.getElementById('position').innerText = data.position || '0';
                
                const badge = document.getElementById('statusBadge');
                if (data.status === 'waiting') {
                    badge.innerText = '⏳ Waiting';
                    badge.style.background = '#ffc107';
                } else if (data.status === 'calling') {
                    badge.innerText = '📞 Your Turn!';
                    badge.style.background = '#17a2b8';
                    badge.style.color = '#fff';
                } else if (data.status === 'serving') {
                    badge.innerText = '🩺 Being Served';
                    badge.style.background = '#28a745';
                    badge.style.color = '#fff';
                }
            } else {
                document.getElementById('tokenNumber').innerText = '--';
                document.getElementById('serving').innerText = '--';
                document.getElementById('waitTime').innerHTML = '0m';
                document.getElementById('position').innerText = '0';
                document.getElementById('statusBadge').innerText = 'No Active Token';
                document.getElementById('statusBadge').style.background = '#6c757d';
                document.getElementById('statusBadge').style.color = '#fff';
            }
        })
        .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', function() {
    loadTokenStatus();
    setInterval(loadTokenStatus, 5000);
});
</script>

@endsection