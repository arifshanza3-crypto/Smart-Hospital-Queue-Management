/**
 * Patient Token Status - JavaScript
 * Handles fetching and updating token status in real-time
 */

(function() {
    'use strict';

    // Get token from Blade (passed via PHP)
    const tokenNumberElement = document.getElementById('patientTokenNumber');
    const tokenNumber = tokenNumberElement ? tokenNumberElement.textContent : null;

    /**
     * Main function to fetch token status from server
     */
    function fetchTokenStatus() {
        console.log('🔄 Fetching token status...');

        // If no token number, show error
        if (!tokenNumber || tokenNumber === '--' || tokenNumber === 'No Token') {
            const badge = document.getElementById('tokenBadge');
            if (badge) {
                badge.textContent = '✕ Invalid';
                badge.style.background = '#dc3545';
                badge.style.color = 'white';
            }
            return;
        }

        // Fetch token status from API
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

        // ✅ Update basic info
        setElementText('patientTokenNumber', data.token_number || '--');
        
        // ✅ PATIENT NAME - FIXED
        const patientNameElement = document.getElementById('patientName');
        if (patientNameElement) {
            patientNameElement.textContent = data.patient_name || '--';
            console.log('✅ Patient Name set to:', data.patient_name || '--');
        }
        
        setElementText('patientDepartment', data.department || '--');
        setElementText('patientPosition', '#' + (data.position || '--'));
        
        // ✅ Dynamic wait time display
        const waitTime = data.estimated_time || 0;
        const waitTimeText = waitTime > 0 ? Math.ceil(waitTime) + 'm' : 'Now';
        setElementText('patientWaitTime', waitTimeText);
        
        setElementText('patientServing', data.serving || '--');

        // ✅ Update status badge
        updateStatusBadge(data.status || 'waiting');
    }

    /**
     * Update status badge based on token status
     */
    function updateStatusBadge(status) {
        const statusBadge = document.getElementById('patientStatus');
        const badge = document.getElementById('tokenBadge');

        if (!statusBadge) return;

        // Reset classes
        statusBadge.className = 'value status-badge';
        if (badge) badge.style.color = 'white';

        // Set status specific styles
        const statusLower = status.toLowerCase();
        switch (statusLower) {
            case 'serving':
            case 'calling':
                statusBadge.classList.add('status-serving');
                statusBadge.textContent = 'In Progress';
                if (badge) {
                    badge.textContent = '● In Progress';
                    badge.style.background = '#4a6cf7';
                }
                break;

            case 'completed':
                statusBadge.classList.add('status-completed');
                statusBadge.textContent = 'Completed';
                if (badge) {
                    badge.textContent = '● Completed';
                    badge.style.background = '#22c55e';
                }
                break;

            case 'cancelled':
                statusBadge.classList.add('status-cancelled');
                statusBadge.textContent = 'Cancelled';
                if (badge) {
                    badge.textContent = '● Cancelled';
                    badge.style.background = '#ef4444';
                }
                break;

            default: // waiting
                statusBadge.classList.add('status-waiting');
                statusBadge.textContent = 'Waiting';
                if (badge) {
                    badge.textContent = '● Waiting';
                    badge.style.background = '#f59e0b';
                }
                break;
        }
    }

    /**
     * Helper: Set element text content safely
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
            badge.style.background = '#dc3545';
            badge.style.color = 'white';
        }
    }

    /**
     * Manual refresh function (called from button)
     */
    window.refreshStatus = function() {
        console.log('🔄 Refresh button clicked!');
        fetchTokenStatus();
    };

    // Make fetchTokenStatus globally accessible for inline onclick
    window.fetchTokenStatus = fetchTokenStatus;

    // ============================================ //
    // AUTO-REFRESH SETUP                          //
    // ============================================ //

    // Initial fetch when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            // ✅ Time set karo (sirf ek baar)
            const timeElement = document.getElementById('patientTime');
            if (timeElement && (!timeElement.textContent || timeElement.textContent === '--')) {
                const now = new Date();
                const hours12 = now.getHours() % 12 || 12;
                const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
                timeElement.textContent = hours12 + ':' + String(now.getMinutes()).padStart(2, '0') + ' ' + ampm;
            }
            fetchTokenStatus();
        });
    } else {
        // ✅ Time set karo (sirf ek baar)
        const timeElement = document.getElementById('patientTime');
        if (timeElement && (!timeElement.textContent || timeElement.textContent === '--')) {
            const now = new Date();
            const hours12 = now.getHours() % 12 || 12;
            const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
            timeElement.textContent = hours12 + ':' + String(now.getMinutes()).padStart(2, '0') + ' ' + ampm;
        }
        fetchTokenStatus();
    }

    // Auto-refresh every 10 seconds
    setInterval(fetchTokenStatus, 10000);

})();