/**
 * Patient Token Status - JavaScript
 * Handles fetching and updating token status in real-time
 */

(function() {
    'use strict';

    // Get token from Blade (passed via PHP)
    const tokenNumberElement = document.getElementById('patientTokenNumber');
    const tokenNumber = tokenNumberElement ? tokenNumberElement.textContent : null;

    // Store initial values
    let initialEstimatedTime = 0;
    let tokenStatus = 'waiting';

    /**
     * Main function to fetch token status from server
     */
    function fetchTokenStatus() {
        console.log('🔄 Fetching token status...');

        if (!tokenNumber || tokenNumber === '--' || tokenNumber === 'N/A') {
            const badge = document.getElementById('tokenBadge');
            if (badge) {
                badge.textContent = '✕ Invalid';
                badge.style.background = '#dc3545';
                badge.style.color = 'white';
            }
            return;
        }

        fetch(`/patient/token-status?token=${encodeURIComponent(tokenNumber)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updatePatientUI(data);
                } else {
                    showError('Token not found');
                }
            })
            .catch(error => {
                console.error('Error fetching token status:', error);
                showError('Error loading');
            });
    }

    /**
     * Update UI with token data
     */
    function updatePatientUI(data) {
        console.log('📦 Token Data:', data);

        // Update basic info
        setElementText('patientTokenNumber', data.token_number || '--');
        setElementText('patientName', data.patient_name || '--');
        setElementText('patientPosition', '#' + (data.position || '--'));

        // Update estimated time
        initialEstimatedTime = data.estimated_time || 0;
        updateWaitTimeDisplay();

        setElementText('patientServing', data.serving || '--');

        // Update status badge
        updateStatusBadge(data.status || 'waiting');
        tokenStatus = data.status || 'waiting';
    }

    /**
     * Update waiting time dynamically
     */
    function updateWaitTimeDisplay() {
        const timeElement = document.getElementById('patientWaitTime');
        if (!timeElement) return;

        // Get generated time
        const generatedTimeStr = document.getElementById('patientTime')?.textContent || '';
        let remainingMinutes = initialEstimatedTime;

        if (generatedTimeStr && generatedTimeStr !== 'N/A') {
            const now = new Date();
            const generated = parseTimeString(generatedTimeStr);
            
            if (generated) {
                const elapsedMinutes = Math.floor((now - generated) / (1000 * 60));
                remainingMinutes = Math.max(0, initialEstimatedTime - elapsedMinutes);
            }
        }

        // Update display
        if (remainingMinutes > 0) {
            timeElement.textContent = Math.ceil(remainingMinutes) + ' min';
        } else {
            timeElement.textContent = '0 min';
        }

        // Animation effect
        timeElement.classList.add('changed');
        setTimeout(() => {
            timeElement.classList.remove('changed');
        }, 500);
    }

    /**
     * Parse time string (HH:MM AM/PM) to Date object
     */
    function parseTimeString(timeStr) {
        const parts = timeStr.match(/(\d+):(\d+)\s*(AM|PM)/);
        if (!parts) return null;

        let hours = parseInt(parts[1]);
        const minutes = parseInt(parts[2]);
        const ampm = parts[3];

        if (ampm === 'PM' && hours !== 12) hours += 12;
        if (ampm === 'AM' && hours === 12) hours = 0;

        const date = new Date();
        date.setHours(hours, minutes, 0, 0);
        return date;
    }

    /**
     * Update status badge
     */
    function updateStatusBadge(status) {
        const statusBadge = document.getElementById('patientStatus');
        const badge = document.getElementById('tokenBadge');

        if (!statusBadge) return;

        statusBadge.className = 'value status-badge';
        const statusLower = status.toLowerCase();

        switch (statusLower) {
            case 'serving':
            case 'calling':
                statusBadge.classList.add('status-serving');
                statusBadge.textContent = 'In Progress';
                if (badge) {
                    badge.textContent = '● In Progress';
                    badge.className = 'badge status-serving';
                }
                break;
            case 'completed':
                statusBadge.classList.add('status-completed');
                statusBadge.textContent = 'Completed';
                if (badge) {
                    badge.textContent = '● Completed';
                    badge.className = 'badge status-completed';
                }
                break;
            case 'cancelled':
                statusBadge.classList.add('status-cancelled');
                statusBadge.textContent = 'Cancelled';
                if (badge) {
                    badge.textContent = '● Cancelled';
                    badge.className = 'badge status-cancelled';
                }
                break;
            default:
                statusBadge.classList.add('status-waiting');
                statusBadge.textContent = 'Waiting';
                if (badge) {
                    badge.textContent = '● Waiting';
                    badge.className = 'badge status-waiting';
                }
                break;
        }
    }

    /**
     * Helper: Set element text
     */
    function setElementText(id, text) {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = text;
        }
    }

    /**
     * Show error message
     */
    function showError(message) {
        const statusBadge = document.getElementById('patientStatus');
        if (statusBadge) {
            statusBadge.textContent = message;
            statusBadge.className = 'value status-badge status-cancelled';
        }

        const badge = document.getElementById('tokenBadge');
        if (badge) {
            badge.textContent = '✕ Error';
            badge.className = 'badge status-cancelled';
        }
    }

    /**
     * Manual refresh function
     */
    window.refreshStatus = function() {
        console.log('🔄 Refresh button clicked!');
        location.reload();
    };

    // ============================================ //
    // AUTO-REFRESH SETUP                          //
    // ============================================ //

    // Initial fetch
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            // Set generated time (only once)
            const timeElement = document.getElementById('patientTime');
            if (timeElement && timeElement.textContent === '--') {
                const now = new Date();
                const hours12 = now.getHours() % 12 || 12;
                const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
                timeElement.textContent = hours12 + ':' + 
                    String(now.getMinutes()).padStart(2, '0') + ' ' + ampm;
            }
            fetchTokenStatus();
        });
    } else {
        fetchTokenStatus();
    }

    // Auto-update every 10 seconds
    setInterval(function() {
        fetchTokenStatus();
        updateWaitTimeDisplay();
    }, 10000);

    // Full page refresh every 60 seconds (if waiting)
    setInterval(function() {
        if (tokenStatus === 'waiting' || tokenStatus === 'calling') {
            location.reload();
        }
    }, 60000);

})();