/**
 * Staff Dashboard Logic
 */

document.addEventListener('DOMContentLoaded', function() {
    const mobileInput = document.getElementById('p_mobile');
    const mobileError = document.getElementById('mobileError');

    if (mobileInput) {
        mobileInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 11) {
                this.value = this.value.slice(0, 11);
            }

            const isValid = /^(03)\d{9}$/.test(this.value);
            if (this.value.length > 0 && !isValid) {
                if (mobileError) mobileError.style.display = 'block';
                this.style.border = "1px solid #ff4b2b";
            } else {
                if (mobileError) mobileError.style.display = 'none';
                this.style.border = "1px solid rgba(11, 46, 51, 0.12)";
            }
        });
    }
});

// Helper for CSRF Token
function getCsrfToken() {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    return tokenMeta ? tokenMeta.content : '';
}

// Open Modal
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
        modal.style.display = 'flex';
    }
}

// Close Modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
    }
}

// Close Modal on Overlay Click
document.addEventListener('click', function(event) {
    document.querySelectorAll('.modal').forEach(modal => {
        if (event.target === modal) {
            closeModal(modal.id);
        }
    });
});

// Close Modal on ESC Key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('.modal.show').forEach(modal => {
            closeModal(modal.id);
        });
    }
});

// AJAX Token Status Update
function updateTokenStatus(id, status) {
    let endpoint = '';

    switch(status) {
        case 'calling':
            endpoint = '/staff/start-serving';
            break;
        case 'serving':
            endpoint = '/staff/start-service';
            break;
        case 'completed':
            endpoint = '/staff/complete-service';
            break;
        case 'cancelled':
            endpoint = '/staff/cancel-token';
            if (!confirm('Are you sure you want to cancel this token?')) return;
            break;
        default:
            return;
    }

    fetch(endpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify({ token_id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove or update row UI
            renderTokenRowStatus(id, status);

            // Update stats
            if (data.totalQueue !== undefined) document.getElementById('stat-total').textContent = data.totalQueue;
            if (data.nowServingToken !== undefined) document.getElementById('stat-serving').textContent = data.nowServingToken;
            if (data.avgWaitTime !== undefined) document.getElementById('stat-avg-time').textContent = data.avgWaitTime + 'm';
        } else {
            alert('❌ Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error updating token status');
    });
}

// Dynamic Row Updates & Removal
function renderTokenRowStatus(id, status) {
    const row = document.getElementById(`token-row-${id}`);
    if (!row) return;

    // Cancel hone par queue row se remove kar do
    if (status === 'cancelled') {
        row.style.transition = 'all 0.3s ease';
        row.style.opacity = '0';
        setTimeout(() => {
            row.remove();

            const queueBody = document.getElementById('queue-body');
            if (queueBody && queueBody.querySelectorAll('tr').length === 0) {
                queueBody.innerHTML = `
                    <tr id="empty-row">
                        <td colspan="6" style="text-align: center; padding: 40px; color: rgba(11, 46, 51, 0.4);">
                            <i class="fas fa-inbox" style="font-size: 40px; display: block; margin-bottom: 10px;"></i>
                            No patients in queue
                        </td>
                    </tr>
                `;
            }
        }, 300);
        return;
    }

    const statusTd = row.querySelector('.status-td');
    const actionTd = row.querySelector('.action-td');

    if (statusTd) {
        statusTd.innerHTML = `<span class="status-badge ${status}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
    }

    if (actionTd) {
        if (status === 'calling') {
            actionTd.innerHTML = `
                <button onclick="updateTokenStatus(${id}, 'serving')" class="btn-sm btn-start">
                    <i class="fas fa-play"></i> Start
                </button>
                <button onclick="updateTokenStatus(${id}, 'cancelled')" class="btn-sm btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </button>
            `;
        } else if (status === 'serving') {
            actionTd.innerHTML = `
                <button onclick="updateTokenStatus(${id}, 'completed')" class="btn-sm btn-complete">
                    <i class="fas fa-check"></i> Complete
                </button>
            `;
        } else if (status === 'completed') {
            actionTd.innerHTML = `
                <span class="badge-served">
                    <i class="fas fa-check-circle"></i> Served
                </span>
            `;
        }
    }
}

// Add Patient Request
function submitPatient() {
    const name = document.getElementById('p_name')?.value?.trim();
    const mobile = document.getElementById('p_mobile')?.value?.trim();

    if (!name || !mobile) {
        alert('Please fill out all fields');
        return;
    }

    const isValid = /^(03)\d{9}$/.test(mobile);
    if (!isValid) {
        alert('Please enter a valid 11-digit mobile number starting with 03');
        return;
    }

    fetch('/staff/add-patient', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify({ name: name, mobile_number: mobile })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('patientModal');
            document.getElementById('p_name').value = '';
            document.getElementById('p_mobile').value = '';
            window.location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error adding patient');
    });
}

// Global Time Update
function submitGlobalTime() {
    const minutes = document.getElementById('global_min')?.value;
    if (minutes) {
        alert('⏱ Global time set to ' + minutes + ' minutes');
        closeModal('timeModal');
    }
}