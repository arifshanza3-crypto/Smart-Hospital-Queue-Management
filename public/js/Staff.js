let timerInterval = null;
let currentToken = null;
let timeLeft = 300;

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
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ token_id: currentToken.id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) { loadQueue(); showToast('Patient cancelled. Next patient called!', 'success'); }
    });
}

// ========== QUEUE FUNCTIONS ==========
function loadQueue() {
    fetch('/staff/get-queue')
        .then(response => response.json())
        .then(data => {
            updateQueueTable(data.queue);
            updateStats(data.total, data.serving, data.avgWait);
        })
        .catch(error => console.error('Error:', error));
}

function updateQueueTable(queue) {
    const tbody = document.getElementById('queue-body');
    if (!tbody) return;
    tbody.innerHTML = '';
    
    queue.forEach(patient => {
        const row = tbody.insertRow();
        let statusClass = '';
        if (patient.status === 'waiting') statusClass = 'status-waiting';
        else if (patient.status === 'calling') statusClass = 'status-calling';
        else if (patient.status === 'serving') statusClass = 'status-serving';
        else if (patient.status === 'completed') statusClass = 'status-completed';
        else if (patient.status === 'missed') statusClass = 'status-missed';

        row.innerHTML = `
            <td><strong>#${patient.token_number}</strong></td>
            <td>${patient.patient_name}<br><small>Age: ${patient.age || 'N/A'}</small></td>
            <td><span class="badge">${patient.type || 'online'}</span></td>
            <td>${patient.estimated_time || 15} min</td>
            <td><span class="status-badge ${statusClass}">${patient.status}</span></td>
            <td>
                ${patient.status === 'waiting' ? 
                    `<button class="btn-queue" onclick="startServing(${patient.id}, '${patient.token_number}')">📞 Call</button>` : ''}
                ${patient.status === 'calling' ? 
                    `<button class="btn-queue" onclick="completeService(${patient.id}, '${patient.token_number}')">✅ Complete</button>
                     <button class="btn-queue" onclick="cancelToken(${patient.id}, '${patient.token_number}')">❌ Cancel</button>` : ''}
                ${patient.status === 'serving' ? 
                    `<button class="btn-queue" onclick="completeService(${patient.id}, '${patient.token_number}')">✅ Complete</button>` : ''}
            </td>
        `;
    });
}

function updateStats(total, serving, avgWait) {
    document.getElementById('stat-total').innerText = total;
    document.getElementById('stat-serving').innerText = serving || '--';
    document.getElementById('stat-avg-time').innerText = avgWait + 'm';
}

// ========== PATIENT FUNCTIONS ==========
function startServing(tokenId, tokenNumber) {
    fetch('/staff/start-serving', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ token_id: tokenId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) { startTimer(tokenId, tokenNumber); loadQueue(); }
    });
}

function completeService(tokenId, tokenNumber) {
    fetch('/staff/complete-service', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ token_id: tokenId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Patient #' + tokenNumber + ' completed!', 'success');
            loadQueue();
        }
    });
}

function cancelToken(tokenId, tokenNumber) {
    if (confirm('Cancel patient #' + tokenNumber + '?')) {
        fetch('/staff/cancel-token', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: JSON.stringify({ token_id: tokenId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Patient #' + tokenNumber + ' cancelled', 'warning');
                loadQueue();
            }
        });
    }
}

// ========== MODAL FUNCTIONS ==========
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = 'flex';
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = 'none';
}

function submitPatient() {
    const name = document.getElementById('p_name').value;
    const age = document.getElementById('p_age').value;
    if (!name) return showToast('Please enter name', 'error');
    
    fetch('/staff/add-patient', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ name: name, age: age })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('patientModal');
            loadQueue();
            showToast('Patient added to queue!', 'success');
            document.getElementById('p_name').value = '';
            document.getElementById('p_age').value = '';
        }
    });
}

function submitGlobalTime() {
    const minutes = document.getElementById('global_min').value;
    fetch('/staff/set-global-time', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: JSON.stringify({ minutes: minutes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('timeModal');
            loadQueue();
            showToast('Global time updated!', 'success');
        }
    });
}

function showQueueDetails(token) {
    // Fetch token details from server
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

// ========== LOAD ON PAGE START ==========
document.addEventListener('DOMContentLoaded', function() {
    loadQueue();
    setInterval(loadQueue, 5000);
});

// Add CSS animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
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