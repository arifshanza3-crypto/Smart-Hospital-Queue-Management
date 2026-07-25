/**
 * Doctor Management - JavaScript
 * Handles delete, search, filter, and notifications
 */

document.addEventListener('DOMContentLoaded', function() {
    // ============================================
    // SEARCH & FILTER
    // ============================================
    const searchInput = document.getElementById('search');
    const filterStatus = document.getElementById('filterStatus');

    if (searchInput) {
        searchInput.addEventListener('keyup', filterTable);
    }

    if (filterStatus) {
        filterStatus.addEventListener('change', filterTable);
    }

    function filterTable() {
        let searchValue = document.getElementById('search').value.toLowerCase();
        let statusValue = document.getElementById('filterStatus').value;
        let rows = document.querySelectorAll('#tableBody tr');
        let visibleCount = 0;
        
        rows.forEach(row => {
            if(row.querySelector('td')) {
                let text = row.textContent.toLowerCase();
                let statusCell = row.querySelector('.status-badge-modern');
                let status = '';
                
                if(statusCell) {
                    let statusText = statusCell.textContent.trim().toLowerCase();
                    if(statusText.includes('active')) status = 'active';
                    if(statusText.includes('on duty')) status = 'on_duty';
                    if(statusText.includes('inactive')) status = 'inactive';
                }
                
                let matchesSearch = text.includes(searchValue);
                let matchesStatus = !statusValue || status === statusValue;
                
                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            }
        });
        
        // Show/hide no results message
        let noResultsMsg = document.getElementById('noResultsMsg');
        if (visibleCount === 0 && rows.length > 0) {
            if (!noResultsMsg) {
                let tbody = document.getElementById('tableBody');
                let msgRow = document.createElement('tr');
                msgRow.id = 'noResultsMsg';
                msgRow.innerHTML = `
                    <td colspan="7" style="padding: 40px; text-align: center;">
                        <div class="empty-state" style="padding: 20px;">
                            <i class="fas fa-search" style="font-size: 40px;"></i>
                            <h3 style="color: var(--text-primary); font-weight: 600;">No Matching Doctors</h3>
                            <p style="color: var(--text-secondary);">Try adjusting your search or filter criteria</p>
                        </div>
                    </td>
                `;
                tbody.appendChild(msgRow);
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }

    // ============================================
    // RESET FILTERS
    // ============================================
    window.resetFilters = function() {
        document.getElementById('search').value = '';
        document.getElementById('filterStatus').value = '';
        filterTable();
    }

    // ============================================
    // DELETE DOCTOR
    // ============================================
    window.deleteDoctor = function(id) {
        if(confirm('⚠️ Are you sure you want to delete this doctor?\n\nThis action cannot be undone!')) {
            showLoader();
            
            fetch('/admin/doctors/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoader();
                if(data.success) {
                    showNotification('success', data.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('error', data.message || 'Error deleting doctor');
                }
            })
            .catch(error => {
                hideLoader();
                showNotification('error', 'Network error. Please try again.');
                console.error('Error:', error);
            });
        }
    }

    // ============================================
    // LOADER
    // ============================================
    function showLoader() {
        const loader = document.getElementById('loader');
        if (loader) loader.classList.add('show');
    }

    function hideLoader() {
        const loader = document.getElementById('loader');
        if (loader) loader.classList.remove('show');
    }

    // ============================================
    // NOTIFICATION SYSTEM
    // ============================================
    function showNotification(type, message) {
        let notification = document.createElement('div');
        let bgColor = type === 'success' ? '#d1fae5' : '#fee2e2';
        let borderColor = type === 'success' ? '#10b981' : '#ef4444';
        let textColor = type === 'success' ? '#065f46' : '#991b1b';
        let icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        notification.style.cssText = `
            position: fixed;
            top: 80px;
            right: 24px;
            padding: 16px 24px;
            background: ${bgColor};
            border-left: 4px solid ${borderColor};
            color: ${textColor};
            border-radius: 12px;
            z-index: 9999;
            animation: slideIn 0.3s ease;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            font-size: 14px;
            min-width: 280px;
        `;
        notification.innerHTML = `<i class="fas ${icon}"></i> ${message}`;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }

    // ============================================
    // TOOLTIP FOR ACTION BUTTONS
    // ============================================
    document.querySelectorAll('.action-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Don't close tooltip on click
            e.stopPropagation();
        });
    });

    // ============================================
    // KEYBOARD SHORTCUTS
    // ============================================
    document.addEventListener('keydown', function(e) {
        // Ctrl + F for search focus
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            const searchInput = document.getElementById('search');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }
    });
});

// ============================================
// STYLES FOR ANIMATIONS
// ============================================
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);