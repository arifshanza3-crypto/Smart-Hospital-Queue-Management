/**
 * Doctors Page - JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // ✅ Get doctors from window object
    // ============================================
    const doctors = window.doctorsData || [];
    
    console.log('✅ Doctors loaded from window:', doctors);
    console.log('✅ Total doctors:', doctors.length);
    
    // ============================================
    // ✅ Elements
    // ============================================
    const grid = document.getElementById('doctorsGrid');
    const searchInput = document.getElementById('drSearchInput');
    const resultCount = document.getElementById('resultCount');
    
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
    
    // ============================================
    // ✅ Get Doctor Display Name
    // ============================================
    function getDoctorDisplayName(dr) {
        // Priority: 1. Name, 2. Specialization, 3. Default
        if (dr.name && dr.name.toString().trim() !== '' && dr.name !== 'null' && dr.name !== 'undefined') {
            return `Dr. ${dr.name.toString().trim()}`;
        } else if (dr.specialization && dr.specialization.toString().trim() !== '') {
            return dr.specialization.toString().trim();
        } else {
            return 'Doctor';
        }
    }
    
    function getAvatarName(dr) {
        if (dr.name && dr.name.toString().trim() !== '' && dr.name !== 'null' && dr.name !== 'undefined') {
            return dr.name.toString().trim();
        } else if (dr.specialization && dr.specialization.toString().trim() !== '') {
            return dr.specialization.toString().trim();
        } else {
            return 'D';
        }
    }
    
    // ============================================
    // ✅ Render Doctors
    // ============================================
    function renderDoctors(list) {
        if (!grid) {
            console.error('❌ doctorsGrid element not found!');
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
            if (resultCount) resultCount.textContent = '0 specialists';
            return;
        }
        
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
                            <h5 class="mb-1" style="color: #ffffff; font-weight: 600;">${displayName}</h5>
                            <p class="small text-info" style="color: #00d4ff !important;">${dr.specialization || 'General'}</p>
                            ${dr.experience ? `<p class="small text-white-50">${dr.experience} years experience</p>` : ''}
                            ${dr.fee ? `<p class="small text-success" style="color: #10b981 !important;">Fee: $${dr.fee}</p>` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
        
        if (resultCount) {
            resultCount.textContent = `Showing ${list.length} specialists`;
        }
        
        console.log(`✅ Rendered ${list.length} doctors successfully`);
    }
    
    // ============================================
    // ✅ Search Function
    // ============================================
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const term = this.value.trim().toLowerCase();
            if (!term) {
                renderDoctors(doctors);
                return;
            }
            const filtered = doctors.filter(d => 
                (d.name && d.name.toString().toLowerCase().includes(term)) || 
                (d.specialization && d.specialization.toString().toLowerCase().includes(term))
            );
            renderDoctors(filtered);
        });
    }
    
    // ============================================
    // ✅ Open Modal
    // ============================================
    window.openDrModal = function(id) {
        const dr = doctors.find(d => d.id === id);
        if (!dr) {
            console.warn('❌ Doctor not found with id:', id);
            return;
        }
        
        const modal = document.getElementById('customDrModal');
        if (!modal) return;
        
        const displayName = getDoctorDisplayName(dr);
        document.getElementById('modalName').textContent = displayName;
        document.getElementById('modalEdu').textContent = dr.qualification || 'N/A';
        document.getElementById('modalProf').textContent = dr.specialization || 'General';
        
        let shiftText = '09:00 AM - 05:00 PM';
        if (dr.availability) {
            if (typeof dr.availability === 'object') {
                shiftText = dr.availability.start_time || dr.availability.start || '09:00 AM';
                shiftText += ' - ' + (dr.availability.end_time || dr.availability.end || '05:00 PM');
            } else if (typeof dr.availability === 'string') {
                shiftText = dr.availability;
            }
        }
        document.getElementById('modalTime').textContent = shiftText;
        
        const statusColor = getStatusColor(dr.status);
        const statusText = getStatusText(dr.status);
        document.getElementById('modalStatus').innerHTML = `<span style="color: ${statusColor}; font-weight: 600;">● ${statusText}</span>`;
        
        const avatarName = getAvatarName(dr);
        document.getElementById('modalImg').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(avatarName)}&background=00d4ff&color=fff&size=400`;
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };
    
    // ============================================
    // ✅ Close Modal
    // ============================================
    function closeModal() {
        const modal = document.getElementById('customDrModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
    
    document.querySelector('.dr-popup-close')?.addEventListener('click', closeModal);
    document.getElementById('customDrModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
    
    // ============================================
    // ✅ Join Queue Button
    // ============================================
    document.getElementById('generateQueueBtn')?.addEventListener('click', function() {
        window.location.href = '/Token_form';
    });
    
    // ============================================
    // ✅ Auto Slider
    // ============================================
    const slides = document.querySelectorAll('.slide');
    let cur = 0;
    
    if (slides.length > 0) {
        setInterval(() => {
            slides[cur].classList.remove('active');
            cur = (cur + 1) % slides.length;
            slides[cur].classList.add('active');
        }, 5000);
    }
    
    // ============================================
    // ✅ Initial Render
    // ============================================
    renderDoctors(doctors);
    
    // ============================================
    // ✅ Debug Info
    // ============================================
    console.log('=== DOCTORS DEBUG INFO ===');
    console.log('Total doctors:', doctors.length);
    if (doctors.length > 0) {
        console.log('First doctor:', doctors[0]);
        console.log('Doctor name:', doctors[0]?.name);
        console.log('Doctor specialization:', doctors[0]?.specialization);
        console.log('Display name will be:', getDoctorDisplayName(doctors[0]));
    }
    console.log('===========================');
});