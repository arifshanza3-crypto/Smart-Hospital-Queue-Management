/**
 * DOCTORS DETAILS - JavaScript
 * Handles displaying doctors and modal functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Doctors Details JS loaded');
    
<<<<<<< HEAD
    const doctorsGrid = document.getElementById('doctorsGrid');
    
    // Get doctors data from window
    const doctors = window.doctorsData || [];
    
    console.log('✅ Doctors data count:', doctors.length);
    
    // ============================================
    // RENDER DOCTORS
    // ============================================
    function renderDoctors() {
        if (!doctorsGrid) {
            console.error('❌ doctorsGrid element not found!');
            return;
        }
        
        if (!doctors || doctors.length === 0) {
            doctorsGrid.innerHTML = `
                <div class="col-12 text-center">
                    <div style="padding: 60px 0; color: rgba(255,255,255,0.7);">
                        <i class="fas fa-user-md" style="font-size: 48px; color: rgba(255,255,255,0.2);"></i>
                        <h3 style="color: #fff; margin-top: 16px;">No Doctors Available</h3>
                        <p style="color: rgba(255,255,255,0.5);">Please add doctors from the admin panel.</p>
=======
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
>>>>>>> 2eb4cefef4397039eb351be26021243836677662
                    </div>
                </div>
            `;
            return;
        }
<<<<<<< HEAD
        
        let html = '';
        
        doctors.forEach(function(doctor, index) {
            const statusClass = (doctor.status || 'available').toLowerCase();
            const displayName = doctor.name || 'Dr. Unknown';
            const specialty = doctor.specialization || 'General Practitioner';
            const experience = doctor.experience || '5+ Years';
            const rating = doctor.rating || '4.5';
            const imageUrl = doctor.image || `https://ui-avatars.com/api/?name=${encodeURIComponent(displayName)}&background=00d4ff&color=fff&size=300`;
            
            html += `
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="doctor-card" data-doctor-id="${doctor.id || index}">
                        <div class="doctor-card-inner">
                            <div class="doctor-image-wrapper">
                                <img 
                                    src="${imageUrl}" 
                                    alt="${displayName}" 
                                    class="doctor-image"
                                    loading="lazy"
                                    onerror="this.src='https://ui-avatars.com/api/?name=D&background=00d4ff&color=fff&size=300'"
                                >
                                <span class="doctor-status ${statusClass}">
                                    ${doctor.status || 'Available'}
                                </span>
                            </div>
                            <div class="doctor-info">
                                <h3 class="doctor-name">${displayName}</h3>
                                <p class="doctor-specialty">${specialty}</p>
                                <div class="doctor-meta">
                                    <span class="doctor-experience">
                                        <i class="fas fa-briefcase"></i>
                                        ${experience}
                                    </span>
                                    <span class="doctor-rating">
                                        <i class="fas fa-star"></i>
                                        ${rating}
                                    </span>
                                </div>
                                <button class="view-details-btn" onclick="openDoctorModal('${doctor.id || index}')">
                                    View Details
                                </button>
                            </div>
=======

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
>>>>>>> 2eb4cefef4397039eb351be26021243836677662
                        </div>
                    </div>
                </div>
            `;
        });
        
<<<<<<< HEAD
        doctorsGrid.innerHTML = html;
        console.log('✅ Rendered', doctors.length, 'doctors');
=======
        console.log(`✅ Rendered ${list.length} doctors`);
>>>>>>> 2eb4cefef4397039eb351be26021243836677662
    }
    
    // Initial render
    renderDoctors();
});

<<<<<<< HEAD
// ============================================
// OPEN DOCTOR MODAL
// ============================================
function openDoctorModal(doctorId) {
    console.log('🔍 Opening modal for doctor ID:', doctorId);
    
    const doctors = window.doctorsData || [];
    const doctor = doctors.find(function(d) {
        return d.id == doctorId;
    });
    
    if (!doctor) {
        console.error('❌ Doctor not found with ID:', doctorId);
        alert('Doctor details not found.');
        return;
    }
    
    console.log('✅ Found doctor:', doctor.name);
    
    // Get modal elements
    const modal = document.getElementById('customDrModal');
    const loader = document.getElementById('modalLoader');
    const modalImg = document.getElementById('modalImg');
    
    if (!modal) {
        console.error('❌ Modal element not found!');
        return;
    }
    
    // Show modal and loader
    modal.style.display = 'flex';
    loader.style.display = 'flex';
    modalImg.style.display = 'none';
    
    // Populate doctor details
    document.getElementById('modalName').textContent = doctor.name || 'Dr. Unknown';
    document.getElementById('modalEdu').textContent = doctor.education || doctor.qualification || 'MBBS, MD';
    document.getElementById('modalProf').textContent = doctor.specialization || 'General Practitioner';
    document.getElementById('modalTime').textContent = doctor.shift || '09:00 AM - 05:00 PM';
    document.getElementById('modalExperience').textContent = doctor.experience || '5+ Years';
    document.getElementById('modalRating').textContent = (doctor.rating || '4.5') + ' ⭐';
    
    // Set status with color
    const statusSpan = document.getElementById('modalStatus');
    const status = doctor.status || 'available';
    const statusDisplay = status.charAt(0).toUpperCase() + status.slice(1);
    statusSpan.textContent = statusDisplay;
    statusSpan.className = 'status-badge ' + status.toLowerCase();
    
    // Set image
    const imageUrl = doctor.image || `https://ui-avatars.com/api/?name=${encodeURIComponent(doctor.name || 'Doctor')}&background=00d4ff&color=fff&size=400`;
    modalImg.src = imageUrl;
    modalImg.onload = function() {
        loader.style.display = 'none';
        modalImg.style.display = 'block';
    };
    modalImg.onerror = function() {
        modalImg.src = 'https://ui-avatars.com/api/?name=D&background=00d4ff&color=fff&size=400';
        loader.style.display = 'none';
        modalImg.style.display = 'block';
    };
    
    // If image loads instantly
    if (modalImg.complete && modalImg.naturalHeight !== 0) {
        loader.style.display = 'none';
        modalImg.style.display = 'block';
=======
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
>>>>>>> 2eb4cefef4397039eb351be26021243836677662
    }
}

<<<<<<< HEAD
// ============================================
// CLOSE DOCTOR MODAL
// ============================================
function closeDoctorModal() {
    const modal = document.getElementById('customDrModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// ============================================
// JOIN QUEUE
// ============================================
function joinQueue() {
    const doctorName = document.getElementById('modalName').textContent;
    const specialty = document.getElementById('modalProf').textContent;
    
    if (doctorName && doctorName !== '-') {
        window.location.href = `/Token_form?doctor=${encodeURIComponent(doctorName)}&specialty=${encodeURIComponent(specialty)}`;
    } else {
        window.location.href = '/Token_form';
    }
}

// ============================================
// BOOK APPOINTMENT
// ============================================
function bookAppointment() {
    const doctorName = document.getElementById('modalName').textContent;
    const specialty = document.getElementById('modalProf').textContent;
    
    if (doctorName && doctorName !== '-') {
        window.location.href = `/appointment?doctor=${encodeURIComponent(doctorName)}&specialty=${encodeURIComponent(specialty)}`;
    } else {
        window.location.href = '/appointment';
    }
}

// ============================================
// CLOSE MODAL ON OUTSIDE CLICK OR ESCAPE
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('customDrModal');
    
    if (modal) {
        // Close on overlay click
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDoctorModal();
            }
        });
        
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDoctorModal();
            }
        });
    }
=======
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
>>>>>>> 2eb4cefef4397039eb351be26021243836677662
});