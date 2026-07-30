document.addEventListener('DOMContentLoaded', function() {
    
    // ✅ Database se doctors data lein
    const doctors = window.doctorsData || [];
    
    console.log('✅ Doctors loaded in JS:', doctors);
    console.log('✅ Total doctors:', doctors.length);

    const grid = document.getElementById('doctorsGrid');
    const modal = document.getElementById('customDrModal');

    // ============================================
    // ✅ Helper Functions
    // ============================================
    function getStatusColor(status) {
        const colors = {
            'active': '#10b981',
            'on_duty': '#0ea5e9',
            'inactive': '#ef4444',
            'ACTIVE': '#10b981',
            'ON_DUTY': '#0ea5e9',
            'INACTIVE': '#ef4444'
        };
        return colors[status] || '#f59e0b';
    }

    function getStatusText(status) {
        if (!status) return 'Active';
        const s = status.toLowerCase();
        if (s === 'on_duty') return 'On Duty';
        if (s === 'active') return 'Active';
        if (s === 'inactive') return 'Inactive';
        return status;
    }

    function getDoctorDisplayName(dr) {
        if (dr.name && dr.name.trim() !== '' && dr.name !== 'null' && dr.name !== 'undefined') {
            return `Dr. ${dr.name.trim()}`;
        } else if (dr.specialization && dr.specialization.trim() !== '') {
            return dr.specialization.trim();
        } else {
            return 'Doctor';
        }
    }

    function getAvatarName(dr) {
        if (dr.name && dr.name.trim() !== '' && dr.name !== 'null' && dr.name !== 'undefined') {
            return dr.name.trim();
        } else if (dr.specialization && dr.specialization.trim() !== '') {
            return dr.specialization.trim();
        } else {
            return 'D';
        }
    }

    // ============================================
    // ✅ Render Function
    // ============================================
    function render(list) {
        if (!grid) {
            console.error('❌ Grid element not found!');
            return;
        }
        
        grid.innerHTML = '';

        if (!list || list.length === 0) {
            grid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-user-md" style="font-size: 48px; color: rgba(255,255,255,0.3);"></i>
                        <h3 style="color: #fff; margin-top: 16px;">No Doctors Found</h3>
                        <p style="color: rgba(255,255,255,0.5);">Please add doctors from admin panel.</p>
                    </div>
                </div>
            `;
            return;
        }

        // ✅ Show all doctors
        list.forEach(dr => {
            const statusColor = getStatusColor(dr.status);
            const statusText = getStatusText(dr.status);
            const displayName = getDoctorDisplayName(dr);
            const avatarName = getAvatarName(dr);

            grid.innerHTML += `
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="dr-item-card" onclick="openDrModal(${dr.id})">
                        <div class="dr-card-img">
                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(avatarName)}&background=00d4ff&color=fff&size=200" 
                                 alt="${displayName}" 
                                 onerror="this.src='https://ui-avatars.com/api/?name=D&background=00d4ff&color=fff&size=200'">
                            <span class="status-badge" style="background:${statusColor}">${statusText}</span>
                        </div>
                        <div class="dr-card-body text-center">
                            <h5 class="mb-1 text-white">${displayName}</h5>
                            <p class="small text-info">${dr.specialization || 'General'}</p>
                            ${dr.qualification ? `<p class="small text-white-50">${dr.qualification}</p>` : ''}
                            ${dr.experience ? `<p class="small text-white-50">${dr.experience} years experience</p>` : ''}
                            ${dr.fee ? `<p class="small text-success">Fee: $${dr.fee}</p>` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
        
        console.log(`✅ Rendered ${list.length} doctors`);
    }

    // ============================================
    // ✅ Modal Function
    // ============================================
    window.openDrModal = function(id) {
        const dr = doctors.find(d => d.id === id);
        if (!dr) {
            console.warn('❌ Doctor not found with ID:', id);
            return;
        }

        const displayName = getDoctorDisplayName(dr);
        document.getElementById('modalName').innerText = displayName;
        document.getElementById('modalEdu').innerText = dr.qualification || 'N/A';
        document.getElementById('modalProf').innerText = dr.specialization || 'General';
        document.getElementById('modalTime').innerText = dr.availability || dr.shift || '09:00 AM - 05:00 PM';
        
        const statusColor = getStatusColor(dr.status);
        const statusText = getStatusText(dr.status);
        document.getElementById('modalStatus').innerHTML = `<span style="color: ${statusColor}; font-weight: 600;">● ${statusText}</span>`;
        
        const modalImg = document.getElementById('modalImg');
        const loader = document.getElementById('modalLoader');
        const avatarName = getAvatarName(dr);
        
        modalImg.style.display = 'none';
        loader.style.display = 'block';
        
        modalImg.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(avatarName)}&background=00d4ff&color=fff&size=400`;
        modalImg.onload = function() {
            loader.style.display = 'none';
            modalImg.style.display = 'block';
        };
        modalImg.onerror = function() {
            loader.style.display = 'none';
            modalImg.src = 'https://ui-avatars.com/api/?name=D&background=00d4ff&color=fff&size=400';
            modalImg.style.display = 'block';
        };
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };

    // ============================================
    // ✅ Close Modal
    // ============================================
    const closeBtn = document.querySelector('.dr-popup-close');
    if (closeBtn) {
        closeBtn.onclick = function() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        };
    }

    // Click outside to close
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });

    // ESC key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });

    // ============================================
    // ✅ Initial Render
    // ============================================
    render(doctors);
});