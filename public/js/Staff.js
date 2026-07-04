let timerInterval = null;
let currentToken = null;
let timeLeft = 300;

// ========== CSRF TOKEN HELPER ==========
function getCSRFToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

// ========== TOAST NOTIFICATION ==========
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed; bottom: 20px; right: 20px;
        background: ${type === 'error' ? '#dc3545' : type === 'warning' ? '#ffc107' : '#28a745'};
        color: ${type === 'warning' ? '#000' : '#fff'};
        padding: 12px 20px; border-radius: 8px;
        z-index: 10000; animation: slideIn 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    `;
    toast.innerHTML = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// ========== TIMER FUNCTIONS ==========
function updateTimerDisplay() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    const display = document.getElementById('timerDisplay');
    if (display) {
        display.innerText = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        document.getElementById('minutesLeft').innerText = Math.ceil(timeLeft / 60);
        if (timeLeft <= 60) display.style.color = '#ff6b6b';
    }
}

function startTimer(tokenId, tokenNumber) {
    currentToken = { id: tokenId, number: tokenNumber };
    timeLeft = 300;
    updateTimerDisplay();
    document.getElementById('timerModal').style.display = 'flex';
    if (timerInterval) clearInterval(timerInterval);
    timerInterval = setInterval(() => {
        if (timeLeft <= 0) { clearInterval(timerInterval); cancelPatientTimeout(); }
        else { timeLeft--; updateTimerDisplay(); }
    }, 1000);
}

function extendTimer() {
    timeLeft += 120;
    updateTimerDisplay();
    showToast('Timer extended by 2 minutes', 'warning');
}

function cancelCurrentPatient() {
    clearInterval(timerInterval);
    document.getElementById('timerModal').style.display = 'none';
    cancelPatientAndCallNext();
}

function cancelPatientTimeout() {
    document.getElementById('timerModal').style.display = 'none';
    showToast('Patient #' + currentToken.number + ' missed their turn!', 'error');
    cancelPatientAndCallNext();
}

function cancelPatientAndCallNext() {
    fetch('/staff/cancel-patient', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCSRFToken() },
        body: JSON.stringify({ token_id: currentToken.id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) { loadQueue(); showToast('Patient cancelled. Next patient called!', 'success'); }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error cancelling patient', 'error');
    });
}

// ========== QUEUE FUNCTIONS ==========
function loadQueue() {
    fetch('/staff/get-queue')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateQueueTable(data.queue);
                updateStats(data.total, data.serving, data.avgWait);
            }
        })
        .catch(error => console.error('Error:', error));
}

function updateQueueTable(queue) {
    const tbody = document.getElementById('queue-body');
    if (!tbody) return;
    tbody.innerHTML = '';
    
    if (!queue || queue.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: rgba(255,255,255,0.4);">
                    No patients in queue
                </td>
            </tr>
        `;
        return;
    }

    queue.forEach(patient => {
        const row = tbody.insertRow();
        let statusClass = '';
        let statusText = patient.status || 'waiting';
        
        if (statusText === 'waiting') statusClass = 'status-waiting';
        else if (statusText === 'calling') statusClass = 'status-calling';
        else if (statusText === 'serving') statusClass = 'status-serving';
        else if (statusText === 'completed') statusClass = 'status-completed';
        else if (statusText === 'missed') statusClass = 'status-missed';

        // TYPE DISPLAY
        let typeDisplay = patient.type || 'online';
        let typeText = '';
        if (typeDisplay === 'physical') {
            typeText = 'Physical';
        } else {
            typeText = 'Online';
        }

        // ✅ Cancel button ka style same rakho
        const cancelButtonStyle = `background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px;`;

        // Actions based on status
        let actionsHtml = '';
        if (statusText === 'waiting') {
            actionsHtml = `
                <button class="btn-queue" onclick="startServing(${patient.id}, '${patient.token_number}')">📞 Calling</button>
                <button class="btn-queue" onclick="cancelToken(${patient.id}, '${patient.token_number}')" style="${cancelButtonStyle}">✕ Cancel</button>
            `;
        } else if (statusText === 'calling') {
            actionsHtml = `
                <button class="btn-queue" onclick="completeService(${patient.id}, '${patient.token_number}')">✅ Complete</button>
                <button class="btn-queue" onclick="cancelToken(${patient.id}, '${patient.token_number}')" style="${cancelButtonStyle}">❌ Cancel</button>
            `;
        } else if (statusText === 'serving') {
            actionsHtml = `<button class="btn-queue" onclick="completeService(${patient.id}, '${patient.token_number}')">✅ Complete</button>`;
        } else {
            actionsHtml = `<span style="color: rgba(255,255,255,0.3);">${statusText}</span>`;
        }

        row.innerHTML = `
            <td><strong style="color: #00d4ff;">#${patient.token_number}</strong></td>
            <td>
                <strong>${patient.patient_name || 'N/A'}</strong>
                ${patient.department ? `<br><small style="color: rgba(255,255,255,0.5);">${patient.department}</small>` : ''}
            </td>
            <td><span class="badge">${typeText}</span></td>
            <td>${patient.estimated_time || 15} min</td>
            <td><span class="status-badge ${statusClass}">${statusText.toUpperCase()}</span></td>
            <td>${actionsHtml}</td>
        `;
    });
}

function updateStats(total, serving, avgWait) {
    document.getElementById('stat-total').innerText = total || 0;
    document.getElementById('stat-serving').innerText = serving || '--';
    document.getElementById('stat-avg-time').innerText = (avgWait || 0) + 'm';
}

// ========== PATIENT FUNCTIONS ==========
function startServing(tokenId, tokenNumber) {
    fetch('/staff/start-serving', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCSRFToken() },
        body: JSON.stringify({ token_id: tokenId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) { 
            startTimer(tokenId, tokenNumber); 
            loadQueue(); 
            showToast('Calling patient #' + tokenNumber, 'warning');
        } else {
            showToast(data.message || 'Error calling patient', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error calling patient', 'error');
    });
}

function completeService(tokenId, tokenNumber) {
    fetch('/staff/complete-service', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCSRFToken() },
        body: JSON.stringify({ token_id: tokenId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Patient #' + tokenNumber + ' completed!', 'success');
            loadQueue();
        } else {
            showToast(data.message || 'Error completing service', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error completing service', 'error');
    });
}

function cancelToken(tokenId, tokenNumber) {
    if (confirm('Cancel patient #' + tokenNumber + '?')) {
        fetch('/staff/cancel-token', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCSRFToken() },
            body: JSON.stringify({ token_id: tokenId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Patient #' + tokenNumber + ' cancelled', 'warning');
                loadQueue();
            } else {
                showToast(data.message || 'Error cancelling patient', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error cancelling patient', 'error');
        });
    }
}

// ========== MODAL FUNCTIONS ==========
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'flex';
        if (id === 'patientModal') {
            setTimeout(() => document.getElementById('p_name').focus(), 100);
        }
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = 'none';
}

// ========== SUBMIT PATIENT ==========
function submitPatient() {
    const name = document.getElementById('p_name').value.trim();
    const department = document.getElementById('p_department')?.value || 'OPD';
    
    if (!name) {
        showToast('Please enter patient name', 'error');
        document.getElementById('p_name').focus();
        return;
    }

    const btn = document.querySelector('#patientModal .btn-primary');
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Adding...';
    btn.disabled = true;

    fetch('/staff/add-patient', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken()
        },
        body: JSON.stringify({
            name: name,
            department: department
        })
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = originalText;
        btn.disabled = false;

        if (data.success) {
            closeModal('patientModal');
            loadQueue();
            showToast('Patient added to queue! Token: #' + data.token_number, 'success');
            document.getElementById('p_name').value = '';
        } else {
            showToast(data.message || 'Error adding patient', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.innerHTML = originalText;
        btn.disabled = false;
        showToast('Error adding patient. Please try again.', 'error');
    });
}

function submitGlobalTime() {
    const minutes = document.getElementById('global_min').value;
    if (!minutes || parseInt(minutes) < 1) {
        showToast('Please enter valid minutes', 'error');
        return;
    }

    fetch('/staff/set-global-time', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCSRFToken() },
        body: JSON.stringify({ minutes: minutes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('timeModal');
            loadQueue();
            showToast('Global time updated!', 'success');
        } else {
            showToast(data.message || 'Error updating time', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating time', 'error');
    });
}

function showQueueDetails(token) {
    fetch('/staff/token-detail/' + token)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const detailHtml = `
                    <p><strong>Patient:</strong> ${data.patient_name}</p>
                    <p><strong>Token:</strong> #${data.token_number}</p>
                    <p><strong>Position:</strong> ${data.position}</p>
                    <p><strong>Status:</strong> ${data.status}</p>
                `;
                document.getElementById('detail-content').innerHTML = detailHtml;
                openModal('detailModal');
            }
        });
}

// ========== CLOSE MODAL ON CLICK OUTSIDE ==========
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
});

// ========== LOAD ON PAGE START ==========
document.addEventListener('DOMContentLoaded', function() {
    loadQueue();
    setInterval(loadQueue, 5000);
});

// ========== CSS ANIMATIONS & STYLES ==========
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes scaleIn {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .modal {
        animation: fadeIn 0.3s ease;
    }
    .modal-content {
        animation: scaleIn 0.3s ease;
    }
    .status-badge {
        display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;
    }
    .status-waiting { background: #ffc107; color: #000; }
    .status-calling { background: #17a2b8; color: #fff; }
    .status-serving { background: #28a745; color: #fff; }
    .status-completed { background: #6c757d; color: #fff; }
    .status-missed { background: #dc3545; color: #fff; }
    .badge { background: rgba(255,255,255,0.1); padding: 4px 8px; border-radius: 4px; font-size: 11px; }
`;
document.head.appendChild(style);