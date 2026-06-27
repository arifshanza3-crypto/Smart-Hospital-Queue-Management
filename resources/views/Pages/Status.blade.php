@extends('layout.app')

@section('title', 'Token Status - SMART QUEUE')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container" style="margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card" style="background: #1a1a2e; border: 1px solid #00d4ff; border-radius: 20px; padding: 30px;">
                <div class="text-center">
                    <h3 style="color: #00d4ff;">Token Status</h3>
                    <p style="color: #aaa;">Monitor your position in real-time</p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin: 20px 0; text-align: center;">
                    <div>
                        <span style="color: #aaa; font-size: 12px;">Your Number</span>
                        <h2 id="tokenNumber" style="color: #00d4ff; font-size: 28px; margin: 5px 0;">--</h2>
                    </div>
                    <div>
                        <span style="color: #aaa; font-size: 12px;">Serving</span>
                        <h2 id="serving" style="color: #28a745; font-size: 28px; margin: 5px 0;">--</h2>
                    </div>
                    <div>
                        <span style="color: #aaa; font-size: 12px;">Wait Time</span>
                        <h2 id="waitTime" style="color: #ffc107; font-size: 28px; margin: 5px 0;">0m</h2>
                    </div>
                </div>

                <div style="text-align: center; border-top: 1px solid #333; padding-top: 15px;">
                    <span style="color: #aaa; font-size: 14px;">Your Position in Queue</span>
                    <h3 id="position" style="color: #00d4ff; font-size: 24px; margin: 5px 0;">0</h3>
                </div>

                <div style="text-align: center; margin-top: 15px;">
                    <span id="statusBadge" style="padding: 8px 20px; border-radius: 20px; font-size: 14px; background: #ffc107; color: #000;">Waiting</span>
                </div>
            </div>
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
                    badge.style.color = '#000';
                } else if (data.status === 'calling') {
                    badge.innerText = '📞 Your Turn!';
                    badge.style.background = '#17a2b8';
                    badge.style.color = '#fff';
                } else if (data.status === 'serving') {
                    badge.innerText = '🩺 Being Served';
                    badge.style.background = '#28a745';
                    badge.style.color = '#fff';
                } else if (data.status === 'completed') {
                    badge.innerText = '✅ Completed';
                    badge.style.background = '#6c757d';
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